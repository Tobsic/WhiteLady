var app = angular.module('starter.services', [])
app.service('sharedProperties', function (serverSettings, $http) {
    var Locations = JSON.parse(window.localStorage['Locations'] || '{}');
    var Pois = JSON.parse(window.localStorage['Pois'] || '{}');
    var GpsPositions = JSON.parse(window.localStorage['GpsPositions'] || '{}');
    var Media = JSON.parse(window.localStorage['Media'] || '{}');

    var transform = function (data, name) {
        data = data.data[name];
        var res = {};
        if (typeof data == 'undefined')
            return;
        var columns = data.columns;
        for (values in data.records) {
            var entry = { };
            for(index in columns)
                entry[columns[index]] = data.records[values][index];
            res[entry[(name + '_id').toLowerCase()]] = entry;
        }
        
        return res;
    };


    //Location
    var updateLocations = function () {
        return downloadLocations();
    };

    var getLocations = function () {
        return Locations;
    };

    var downloadLocations = function () {
        return $http({
            method: 'GET',
            url: serverSettings.url + 'Location'
        }).then(function successCallback(response) {
            Locations = transform(response, 'Location');
            window.localStorage['Locations'] = JSON.stringify(Locations);
            return Locations;
        }, function errorCallback(response) {
            console.log("Error:  " + response);
        });
    }

    //Poi

    var getPoisForLocation = function (locationId) {

        var poiArr = {}
        angular.forEach(Pois, function (value, key) {
            if (value.location_id == locationId) {
                poiArr[key] = value;
            }
                
        })
        //console.log(poiArr)

        return poiArr;
    };

    var updatePois = function () {
        return downloadPois();
    };

    var downloadPois = function () {
        return $http({
            method: 'GET',
            url: serverSettings.url + 'Poi'
        }).then(function successCallback(response) {
            // this callback will be called asynchronously
            // when the response is available

            Pois = transform(response, 'Poi');
            window.localStorage['Pois'] = JSON.stringify(Pois);
            return Pois;
        }, function errorCallback(response) {
            console.log("Error:  " + response);
        });
    }

    //GPS
    var updateGpss = function () {
        return downloadGpss();
    };

    var downloadGpss = function () {
        return $http({
            method: 'GET',
            url: serverSettings.url + 'Gps'
        }).then(function successCallback(response) {
            // this callback will be called asynchronously
            // when the response is available
            GpsPositions = transform(response, 'Gps');
            window.localStorage['GpsPositions'] = JSON.stringify(GpsPositions);
            return GpsPositions;
        }, function errorCallback(response) {
            console.log("Error:  " + response);
        });
    }
    
    var getGpssForGpsId = function (gpsId) {
        return GpsPositions[gpsId];
    };

    var getGpssForLocation = function (locationId) {
        pois = getPoisForLocation(locationId);
        gpsArr = {}
        angular.forEach(pois, function (value, key) {
            gpsArr[value.gps_id] = GpsPositions[value.gps_id];
        })
        return gpsArr;
    };
    
    //Media
    var updateMedia = function () {
        return downloadMedia();
    };

    var downloadMedia = function () {
        return $http({
            method: 'GET',
            url: serverSettings.url + 'Media'
        }).then(function successCallback(response) {
            // this callback will be called asynchronously
            // when the response is available
            Media = transform(response, 'Media');
            window.localStorage['Media'] = JSON.stringify(Media);
            return Media;
        }, function errorCallback(response) {
            console.log("Error:  " + response);
        });
    }

    var getMediaForPoi = function (poiId) {
        var mediaArr = {}
        angular.forEach(Media, function (value, key) {
            
            if (value.poi_id == poiId) {
                mediaArr[key] = value
            }
        })
        return mediaArr;
    };

    var getMediaForLocation = function (locationId) {
        pois = getPoisForLocation(locationId);
        var mediaArr = {}
        angular.forEach(pois, function (value, key) {
            medias = getMediaForPoi(key)
            angular.forEach(medias, function (value, key) {
                mediaArr[key] = value
            })
        })
        
        return mediaArr;
    };


    var downloadImage = function (url) {
        ionic.Platform.ready(function () {
            var fileTransfer = new FileTransfer();
            var uri = encodeURI(url);
            fileTransfer.download(
    uri,
    fileURL,
    function (entry) {
        console.log("download complete: " + entry.toURL());
    },
    function (error) {
        console.log("download error source " + error.source);
        console.log("download error target " + error.target);
        console.log("upload error code" + error.code);
    },
    false,
    {
        headers: {
            "Authorization": "Basic dGVzdHVzZXJuYW1lOnRlc3RwYXNzd29yZA=="
        }
    }
);
        });
       
    }
    //return
    return {
        //updates->downloads
        updateLocations: updateLocations,
        updatePois: updatePois,
        updateGpss: updateGpss,
        updateMedia: updateMedia,
        downloadImage: downloadImage,

        //Getter
        getLocations: getLocations,
        getPoisForLocation: getPoisForLocation,
        getGpssForGpsId: getGpssForGpsId,
        getGpssForLocation: getGpssForLocation,
        getMediaForPoi: getMediaForPoi,
        getMediaForLocation: getMediaForLocation,
    };
})
