angular.module('starter.controllers', [])



/* CONTROLLERS */

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

.controller('LocationCtrl', function($scope, sharedProperties, $stateParams) {
  $scope.locations = sharedProperties.getProperty();
  $scope.locID = $stateParams.locationId;
  //console.log($stateParams.locationId);
  //console.log($scope.locations);
})

// PoI: Bushphone Controller.
.controller('POIBusphoneCtrl', function($scope, $stateParams){

})


/* SERVICES */
.service('sharedProperties', function() {
  var locations = [
    { title: 'Rabbitwhole', id: 1, pic: '/img/brandberg640x175.jpg'},
    { title: 'Spitzkoppe', id: 2, pic: '/img/brandberg640x175.jpg'},
    { title: 'Brandberg', id: 3, pic: '/img/brandberg640x175.jpg'},
    { title: 'Download new content', id: 4, pic: '/img/brandberg640x175.jpg'}
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
