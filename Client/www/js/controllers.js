angular.module('starter.controllers', [])



/* CONTROLLERS */

.controller('AppCtrl', function ($scope, $ionicModal, $rootScope, downloader) {
  // With the new view caching in Ionic, Controllers are only called
  // when they are recreated or on app start, instead of every page change.
  // To listen for when this page is active (for example, to refresh data),
  // listen for the $ionicView.enter event:
  //$scope.$on('$ionicView.enter', function(e) {
    //});
    $rootScope.downloadedFiles = {};
    $rootScope.functions = {}

    $rootScope.functions.downloadFile = function (url,type) {
        downloader.getMedia(url, type).then(function (path) {
            console.log('Success: ' + path);
            if ($rootScope.downloadedFiles[type] == undefined) {
                $rootScope.downloadedFiles[type] = {};
            }
            $rootScope.downloadedFiles[type][url] = path;
            return path;
        }, function (error) {
            console.log('Failed: ',error);
        }, function (update) {
            console.log('Got notification: ',update);
        });
    }
    $rootScope.functions.downloadMedia = function (MediaObject) {
        switch(true) {
            case /image/.test(MediaObject.media_type):
                $rootScope.functions.downloadFile(MediaObject.media_content, "images")
                break;
            case /video/.test(MediaObject.media_type):
                $rootScope.functions.downloadFile(MediaObject.media_content, "videos")
                break;
            case /application/.test(MediaObject.media_type):
                $rootScope.functions.downloadFile(MediaObject.media_content, "others")
                break;
            case /text/.test(MediaObject.media_type):
                break;
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
                console.log("locations:", value);
                $rootScope.functions.downloadFile(value.location_banner_url, "images")
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

.controller('LocationCtrl', function ($scope, sharedProperties, $stateParams, $ionicLoading, $state) {
    $scope.locations = sharedProperties.getLocations();
    $scope.locID = $stateParams.locationId;

    var location = $scope.locations[$scope.locID];
    console.log(location)
    //downloading pois
    $scope.pois = sharedProperties.getPoisForLocation($scope.locID);
    $scope.gps = sharedProperties.getGpssForLocation($scope.locID);
    $scope.media = sharedProperties.getMediaForLocation($scope.locID);

    console.log("Poi-Count:" + Object.keys($scope.pois).length, $scope.pois);
    console.log("GPS-Count:" + Object.keys($scope.gps).length, $scope.gps);
    console.log("Media-Count:" + Object.keys($scope.media).length, $scope.media);


    var map = new google.maps.Map(document.getElementById('map'), {
        center: { lat: Number(location.location_lat), lng: Number(location.location_long) },
        zoom: Number(location.location_zoom),
        disableDefaultUI: true,
    });


    angular.forEach($scope.gps, function (gps, gps_id) {
        angular.forEach($scope.pois, function (poi, poi_id) {
            if (poi.gps_id == gps_id) {
                addMarker(gps, poi, map);
            }
        });
    });

    console.log("GPS-Count:" + Object.keys($scope.gps).length, $scope.gps);
   // $scope.map = 'img/locations/1/map.png';

    navigator.geolocation.getCurrentPosition(function (pos) {
        console.log("MyPos:", pos)

    });

    function addMarker(gps, poi, map) {
        var pos = { lat: Number(gps.gps_lat), lng: Number(gps.gps_long) }
        var label = poi.poi_name;

        var marker = new google.maps.Marker({
            position: pos,
            label: label,
            map: map,
            gps: gps,
            poi, poi,
        });
        marker.addListener('click', function () {
            var poi = marker.poi;
            $state.go('app.position', { locationId: poi.location_id, posId: poi.poi_id });
        });
    }

  function calculatePoiMarker(upperLeftCornerLo, upperLeftCornerLa, bottomRightCornerLo, bottomRightCornerLa, PoiLo, PoiLa) {
    var x = ((PoiLo - upperLeftCornerLo) / (bottomRightCornerLo - upperLeftCornerLo)) * 100;
    var y = ((PoiLa - upperLeftCornerLa) / (bottomRightCornerLa - upperLeftCornerLa)) * 100;
    return { 'x': x, 'y': y };
  }
})

.controller('PositionCtrl', function($scope, sharedProperties, $stateParams) {
    $scope.locations = sharedProperties.getLocations();

    $scope.locID = $stateParams.locationId;
    $scope.posID = $stateParams.posId;

    //downloading pois
    $scope.pois = sharedProperties.getPoisForLocation($scope.locID);
    $scope.gps = sharedProperties.getGpssForLocation($scope.locID);
    $scope.media = sharedProperties.getMediaForLocation($scope.locID);

    console.log("Poi-Count:" + Object.keys($scope.pois).length, $scope.pois);
    console.log("GPS-Count:" + Object.keys($scope.gps).length, $scope.gps);
    console.log("Media-Count:" + Object.keys($scope.media).length, $scope.media);
})

// PoI: Bushphone Controller.
.controller('POIBusphoneCtrl', function($scope, $stateParams){

})