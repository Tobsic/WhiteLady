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
  $scope.locations = sharedProperties.getProperty();
  //console.log($scope.locations);
})

.controller('LocationCtrl', function($scope, sharedProperties) {
  $scope.locations = sharedProperties.getProperty();
})

.service('sharedProperties', function() {
  var locations = [
    { title: 'Rabbitwhole', id: 1, pic: '/img/desert.png'},
    { title: 'Spitzkoppe', id: 2, pic: '/img/desert.png'},
    { title: 'Brandberg', id: 3, pic: '/img/desert.png'},
    { title: 'Download new content', id: 4, pic: '/img/desert.png'}
  ];

  return {
    getProperty: function() {
      return locations;
    },
    setProperty: function(locs) {
      locations = locs;
    }
  };
});
