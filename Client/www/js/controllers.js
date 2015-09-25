angular.module('starter.controllers', [])

.controller('AppCtrl', function($scope, $ionicModal, $timeout) {

  // With the new view caching in Ionic, Controllers are only called
  // when they are recreated or on app start, instead of every page change.
  // To listen for when this page is active (for example, to refresh data),
  // listen for the $ionicView.enter event:
  //$scope.$on('$ionicView.enter', function(e) {
  //});
})

.controller('LocationsCtrl', function($scope, sharedProperties) {
  $scope.locations = sharedProperties.get();
  //console.log($scope.locations);
})

.controller('LocationCtrl', function($scope, pois, sharedProperties) {
  $scope.locations = sharedProperties.get();
})

.service('sharedProperties', function(serverSettings) {
  var availableLocations = window.localStorage['availableLocations'];
  var downloadedLocations = window.localStorage['downloadedLocations'];
  //   { id: 1, pic: 'img/desert.png', title: 'Rabbitwhole'},
  //   { id: 2, pic: 'img/desert.png', title: 'Spitzkoppe'},
  //   { id: 3, pic: 'img/desert.png', title: 'Brandberg'},
  //   { id: 4, pic: 'img/desert.png', title: 'Download new content'}
  // ];
  var getDownloadedLocations = function() { return downloadedLocations };
  var getAvailableLocations = function() { return availableLocations; };
  var updateLocations = function() {
      return $http.updateLocations(serverSettings.url + 'Location').then(function(data) {
          return availableLocations
            = window.localStorage['availableLocations']
            = data;
    });
  };

  return {
    getDownloadedLocations: getDownloadedLocations,
    getAvailableLocations : getAvailableLocations,
    updateLocations: updateLocations
  };
})

.service('pois', function() {
  var pois = [];

  return {
    get: function() {
      return pois;
    }
  };
});
