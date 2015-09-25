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
  $scope.locations = sharedProperties.getLocation();
  //console.log($scope.locations);
})

.controller('LocationCtrl', function($scope, sharedProperties, $stateParams) {
  $scope.locations = sharedProperties.getLocation();
  $scope.locID = $stateParams.locationId;
  $scope.map = 'img/locations/1/map.png';

  calculatePoiMarker(14.612571, -21.075324, 14.673923, -21.155676, 14.66803056, -21.10257222);
  //calculatePoiMarker(14.612571, -21.075324, 14.673923, -21.155676, 14.612571, -21.075324);

  function calculatePoiMarker(upperLeftCornerLo, upperLeftCornerLa, bottomRightCornerLo, bottomRightCornerLa, PoiLo, PoiLa) {
    $scope.mappedX = ((PoiLo - upperLeftCornerLo) / (bottomRightCornerLo - upperLeftCornerLo)) * 100;
    $scope.mappedY = ((PoiLa - upperLeftCornerLa) / (bottomRightCornerLa - upperLeftCornerLa)) * 100;
  }

})

// PoI: Bushphone Controller.
.controller('POIBusphoneCtrl', function($scope, $stateParams){

})


/* SERVICES */
.service('sharedProperties', function() {
  var locations = [
    { title: 'Rabbitwhole', id: 1, backpic: 'img/brandberg640x175.jpg'},
    { title: 'Spitzkoppe', id: 2, backpic: 'img/brandberg640x175.jpg'},
    { title: 'Brandberg', id: 3, backpic: 'img/brandberg640x175.jpg'},
    { title: 'Download new content', id: 4, backpic: 'img/brandberg640x175.jpg'}
  ];

  return {
    getLocation: function() {
      return locations;
    },
    setLocation: function(locs) {
      locations = locs;
    }
  };
});
