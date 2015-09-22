angular.module('starter.controllers', [])

.controller('AppCtrl', function($scope, $ionicModal, $timeout) {

  // With the new view caching in Ionic, Controllers are only called
  // when they are recreated or on app start, instead of every page change.
  // To listen for when this page is active (for example, to refresh data),
  // listen for the $ionicView.enter event:
  //$scope.$on('$ionicView.enter', function(e) {
  //});

  
})

.controller('LocationsCtrl', function($scope) {
  $scope.locations = [
    { title: 'Rabbitwhole', id: 1 },
    { title: 'Spitzkoppe', id: 2 },
    { title: 'Brandberg', id: 3 },
    { title: 'Download new content', id: 4 }
  ];
})

.controller('LocationCtrl', function($scope, $stateParams) {
});
