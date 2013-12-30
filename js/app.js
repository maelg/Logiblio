

function LogiblioAdd($scope, $http) {
	$scope.results = [];

	$scope.search = function(){

		$http({method: 'GET', url: 'lib/amazon/search.php?keywords='+$scope.isbn}).
			success(function(data, status, headers, config) {
				console.log(data);
				var name, author, isbn
				if(data.Items.TotalResults == 0) {
					alert('pas de resultats');
					isbn = $scope.isbn;
				}
				else if(data.Items.TotalResults == 1) {
					name = data.Items.Item.ItemAttributes.Title;
					author = data.Items.Item.ItemAttributes.Author;
					isbn = data.Items.Item.ItemAttributes.ISBN;
				}
				else if(data.Items.TotalResults > 1) {
					name = data.Items.Item[0].ItemAttributes.Title;
					author = data.Items.Item[0].ItemAttributes.Author;
					isbn = data.Items.Item[0].ItemAttributes.ISBN;
				}
				$scope.results.push({
					name : name,
					author : author,
					isbn : isbn
				});
			}).
			error(function(data, status, headers, config) {
				alert('error');
			});
	}
}