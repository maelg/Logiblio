<?php

header('Content-type: application/json; charset=utf-8');

include('../../logiblio.config.php');

class Amazon {
 
	public static $secretKey;
	public static $publicKey = 'AKIAJBXCBAOFFX4B6DCQ';
	public static $associateTag = 'logiblio-20';
	private static $_key;
	private static $_hashAlgorithm;

	public function __construct($config)
	{
		self::$secretKey = $config['amazonAPI']->secretKey;
	}
 
	/**
	 * Permet de creer une url pour les services amazon
	 * @param string $operation le paramètre opération de la requete
	 * @param array $options les autres paramètres.
	 * @return string l'url
	 */
    public static function createRequest($operation, array $options)
    {
        $options['AWSAccessKeyId'] = self::$publicKey;
        $options['Service']        = 'AWSECommerceService';
        $options['Operation']      = (string) $operation;
        $options['AssociateTag']   = (string) self::$associateTag;
 
        $baseUri = 'http://webservices.amazon.fr';
 
        if(self::$secretKey !== null) {
            $options['Timestamp'] = gmdate("Y-m-d\TH:i:s\Z");;
            ksort($options);
            $options['Signature'] = self::computeSignature($baseUri, self::$secretKey, $options);
        }
 
        return 'http://webservices.amazon.fr/onca/xml?'.http_build_query($options, null, '&');
    }
 
    static public function computeSignature($baseUri, $secretKey, array $options)
    {
        $signature = self::buildRawSignature($baseUri, $options);
        return base64_encode(
            self::compute($secretKey, 'sha256', $signature, 'binary')
        );
    }
 
   static public function buildRawSignature($baseUri, $options)
    {
        ksort($options);
        $params = array();
        foreach($options AS $k => $v) {
            $params[] = $k."=".rawurlencode($v);
        }
 
        return sprintf("GET\n%s\n/onca/xml\n%s",
            str_replace('http://', '', $baseUri),
            implode("&", $params)
        );
    }
 
    public static function compute($key, $hash, $data, $output)
    {
        // set the key
        if (!isset($key) || empty($key)) {
            throw new Exception('provided key is null or empty');
        }
        self::$_key = $key;
 
        // set the hash
        self::_setHashAlgorithm($hash);
 
        // perform hashing and return
        return self::_hash($data, $output);
    }
 
   protected static function _setHashAlgorithm($hash)
    {
        if (!isset($hash) || empty($hash)) {
            throw new Exception('provided hash string is null or empty');
        }
 
        $hash = strtolower($hash);
        $hashSupported = false;
 
        if (function_exists('hash_algos') && in_array($hash, hash_algos())) {
            $hashSupported = true;
        }
 
        if ($hashSupported === false) {
            throw new Exception('hash algorithm provided is not supported on this PHP installation; please enable the hash or mhash extensions');
        }
        self::$_hashAlgorithm = $hash;
    }
 
    protected static function _hash($data, $output = 'string', $internal = false)
    {
        if (function_exists('hash_hmac')) {
            if ($output == 'binary') {
                return hash_hmac(self::$_hashAlgorithm, $data, self::$_key, 1);
            }
            return hash_hmac(self::$_hashAlgorithm, $data, self::$_key);
        }
 
        if (function_exists('mhash')) {
            if ($output == 'binary') {
                return mhash(self::_getMhashDefinition(self::$_hashAlgorithm), $data, self::$_key);
            }
            $bin = mhash(self::_getMhashDefinition(self::$_hashAlgorithm), $data, self::$_key);
            return bin2hex($bin);
        }
    }
 
    protected static function _getMhashDefinition($hashAlgorithm)
    {
        for ($i = 0; $i <= mhash_count(); $i++)
        {
            $types[mhash_get_hash_name($i)] = $i;
        }
        return $types[strtoupper($hashAlgorithm)];
    }
}

if(isset($_GET['keywords'])) {
	$api = new Amazon($config);
	$url = Amazon::createRequest('ItemSearch', array(
				'ResponseGroup' => 'ItemAttributes,Images,EditorialReview',
				'Keywords' => $_GET['keywords'],
				'SearchIndex' => 'Books',
				'ItemPage' => 1
			));

	$results = file_get_contents($url);

	$json = json_encode(simplexml_load_string($results));
	echo $json;

	/*$object = json_decode($json);
	print_r(json_decode($json));
	echo $object->Items->Item[0]->ItemAttributes->Title.' - '.$object->Items->Item[0]->ItemAttributes->Author;*/
}
?>
