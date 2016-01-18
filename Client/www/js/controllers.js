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

.controller('LocationCtrl', function ($scope, sharedProperties, $stateParams, $ionicLoading, $state, $cordovaGeolocation, $rootScope) {
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
        mapTypeId: google.maps.MapTypeId.HYBRID,
        disableDefaultUI: true,
    });

    angular.forEach($scope.gps, function (gps, gps_id) {
        angular.forEach($scope.pois, function (poi, poi_id) {
            if (poi.gps_id == gps_id) {
                addMarker(gps, poi, map);
            }
        });
    });

    $scope.funcCenterMap = function () {
        console.log("centerMap");
        map.setCenter({ lat: Number(location.location_lat), lng: Number(location.location_long) });
    }

    $scope.funcCenterMe = function () {
        console.log("centerMap", $scope.myPos);
        map.setCenter($scope.myPos);
    }

    console.log("GPS-Count:" + Object.keys($scope.gps).length, $scope.gps);
   // $scope.map = 'img/locations/1/map.png';


    var posOptions = { timeout: 10000, enableHighAccuracy: false };
    $cordovaGeolocation
      .getCurrentPosition(posOptions)
      .then(function (position) {
          gotNewPosition(position);
      }, function (err) {
          // error
      });


    var watchOptions = {
        timeout: 3000,
        enableHighAccuracy: false // may cause errors if true
    };

    var watch = $cordovaGeolocation.watchPosition(watchOptions);
    watch.then(
      null,
      function (err) {
          // error
      },
      function (position) {
          gotNewPosition(position);
      });

    $rootScope.clearWatch = function () {
        watch.clearWatch();
    }
    

    var positionMarker;
    function gotNewPosition(position) {
        $scope.myPos = {
                            lat: position.coords.latitude,
                            lng: position.coords.longitude
                        };

        if (positionMarker != undefined) {
            positionMarker.setMap(null);
            positionMarker = null;
        }
        positionMarker = new google.maps.Marker({
            map: map,
            icon: 'img/marker.png',
            position: $scope.myPos
        });
    };
    function addMarker(gps, poi, map) {
        var pos = { lat: Number(gps.gps_lat), lng: Number(gps.gps_long) }
        var label = poi.poi_name;

        var marker = new google.maps.Marker({
            position: pos,
            title: label,
            label: label,
            map: map,

            gps: gps,
            poi, poi,

        });
        marker.addListener('click', function () {
            var poi = marker.poi;
  
            $state.go('app.position', { locationId: $scope.locID, posId: poi.poi_id });
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
    $scope.location = $scope.locations[$scope.locID];

    //downloading pois
    $scope.pois = sharedProperties.getPoisForLocation($scope.locID);
    $scope.gps = sharedProperties.getGpssForLocation($scope.locID);
    $scope.media = sharedProperties.getMediaForLocation($scope.locID);

    $scope.local = {};
    $scope.local.poi = $scope.pois[$scope.posID]

    $scope.local.media = []
    angular.forEach($scope.media, function (media, media_id) {
        if (media.poi_id == $scope.posID) {
            $scope.local.media[$scope.local.media.length] = media;
        }
    });
    $scope.local.media.sort(function (m1, m2) { return m1.media_pagenumber - m2.media_pagenumber });
    console.log($scope.media);

    $scope.activeMedia = 0;
    $scope.getActiveMedia = function () {
        return $scope.local.media[$scope.activeMedia]
    }

    $scope.setActiveMedia = function (number) {
        console.log(number)
        $scope.activeMedia = number;
    }

    $scope.isMediaType = function (type) {

        switch(type){
            case "image":
                return /image/.test($scope.getActiveMedia().media_type);
                break;
            case "video":
                return /video/.test($scope.getActiveMedia().media_type);
                break;
            case "application":
                return /application/.test($scope.getActiveMedia().media_type);
                break;
            case "text":
                return /text/.test($scope.getActiveMedia().media_type);
                break;

        }
    }

})

// PoI: Bushphone Controller.
.controller('POIBusphoneCtrl', function($scope, $stateParams){

})