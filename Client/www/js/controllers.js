angular.module('starter.controllers', [])



/* CONTROLLERS */

.controller('AppCtrl', function ($scope, $ionicModal, $rootScope, downloader) {
    $scope.locations = {}
  // With the new view caching in Ionic, Controllers are only called
  // when they are recreated or on app start, instead of every page change.
  // To listen for when this page is active (for example, to refresh data),
  // listen for the $ionicView.enter event:
  //$scope.$on('$ionicView.enter', function(e) {
    //});
    $rootScope.downloadedImage = {};
    $rootScope.functions = {}
    $rootScope.functions.downloadImage = function (url) {
        downloader.getImage(url).then(function (path) {
            console.log('Success: ' + path);
            $rootScope.downloadedImage[url] = path;
            return path;
        }, function (error) {
            console.log('Failed: ',error);
        }, function (update) {
            console.log('Got notification: ',update);
        });
    }
    $rootScope.functions.downloadMedia = function (MediaObject) {
        switch(MediaObject.media_type) {
            case "image/png":
            case "image/jpg":
                $rootScope.functions.downloadImage(MediaObject.media_content)
                break;
            //TODO: Other Media-Types
            default:
                break;
        }
    }
})

.controller('LocationsCtrl', function ($scope, $rootScope, sharedProperties) {
    sharedProperties.updateLocations()
        .then(function () {
            $scope.locations = sharedProperties.getLocations()
            //Download banner & map for locations!
            angular.forEach($scope.locations, function (value, key) {
                $rootScope.functions.downloadImage(value.location_banner_url)
                $rootScope.functions.downloadImage(value.location_map_url)
            });
        });
    sharedProperties.updatePois()
    sharedProperties.updateGpss()

    sharedProperties.updateMedia().then(function () {
        $scope.media = sharedProperties.getMedia()
        console.log($scope.media)
        //TODO: Download media for all locations
        angular.forEach($scope.media, function (value, key) {
            $rootScope.functions.downloadMedia(value);
        });
    });
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