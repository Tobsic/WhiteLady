angular.module('starter.controllers', [])



/* CONTROLLERS */

.controller('AppCtrl', function($scope, $ionicModal, $timeout) {
    $scope.locations = {}
  // With the new view caching in Ionic, Controllers are only called
  // when they are recreated or on app start, instead of every page change.
  // To listen for when this page is active (for example, to refresh data),
  // listen for the $ionicView.enter event:
  //$scope.$on('$ionicView.enter', function(e) {
  //});
})

.controller('LocationsCtrl', function ($scope, sharedProperties) {
    sharedProperties.updateLocations()
        .then(() => $scope.locations = sharedProperties.getLocations());
    sharedProperties.updatePois()
    sharedProperties.updateGpss()
    sharedProperties.updateMedia()
    sharedProperties.downloadImage("https://upload.wikimedia.org/wikipedia/commons/b/bb/Wolf_on_alert.jpg");
})

.controller('LocationCtrl', function($scope, sharedProperties, $stateParams) {
    $scope.locations = sharedProperties.getLocations();

    $scope.locID = $stateParams.locationId;
    console.log($scope.locID)
    //downloading pois
    $scope.pois = sharedProperties.getPoisForLocation($scope.locID);
    $scope.gps = sharedProperties.getGpssForLocation($scope.locID);
    $scope.media = sharedProperties.getMediaForLocation($scope.locID);

    console.log("Poi-Count:" + Object.keys($scope.gps).length);
    console.log("GPS-Count:" + Object.keys($scope.gps).length);
    console.log("Media-Count:" + Object.keys($scope.media).length);



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