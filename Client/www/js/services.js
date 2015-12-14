var app = angular.module('starter.services', ['ngCordova'])
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

    var getMedia = function () {
        return Media;
    };

    //return
    return {
        //updates->downloads
        updateLocations: updateLocations,
        updatePois: updatePois,
        updateGpss: updateGpss,
        updateMedia: updateMedia,

        //Getter
        getLocations: getLocations,
        getPoisForLocation: getPoisForLocation,
        getGpssForGpsId: getGpssForGpsId,
        getGpssForLocation: getGpssForLocation,
        getMediaForPoi: getMediaForPoi,
        getMediaForLocation: getMediaForLocation,
        getMedia: getMedia,
    };
})

app.service('downloader', function (serverSettings, $http, $ionicPlatform, $cordovaFile, $cordovaFileTransfer, $timeout, $rootScope, $q) {
    var downloadedMedia = JSON.parse(window.localStorage['downloadedMedia'] || '{}');

    var getHashedFileName = function (url) {
        hashCode = function (s) {
            return s.split("").reduce(function (a, b) { a = ((a << 5) - a) + b.charCodeAt(0); return a & a }, 0);
        }
        extension = function (s){
            return (/[.]/.exec(s)) ? /[^.]+$/.exec(s) : undefined;
        }

        extension = "."+extension(url);

        var hashedUrl = hashCode(url)+extension;
        return hashedUrl;
    }

    var getPath = function () {
        /*if (ionic.Platform.isAndroid()) {
           // console.log('cordova.file.externalDataDirectory: ' + cordova.file.externalDataDirectory);
            myFsRootDirectory1 = 'file:///storage/emulated/0/'; // path for tablet
            myFsRootDirectory2 = 'file:///storage/sdcard0/'; // path for phone
            fileTransferDir = cordova.file.externalDataDirectory;
            if (fileTransferDir.indexOf(myFsRootDirectory1) === 0) {
                fileDir = fileTransferDir.replace(myFsRootDirectory1, '');
            }
            if (fileTransferDir.indexOf(myFsRootDirectory2) === 0) {
                fileDir = fileTransferDir.replace(myFsRootDirectory2, '');
            }
            //console.log('Android FILETRANSFERDIR: ' + fileTransferDir);
            //console.log('Android FILEDIR: ' + fileDir);
        }
        if (ionic.Platform.isIOS()) {
            //console.log('cordova.file.documentsDirectory: ' + cordova.file.documentsDirectory);
            fileTransferDir = cordova.file.documentsDirectory;
            fileDir = '';
            //console.log('IOS FILETRANSFERDIR: ' + fileTransferDir);
            //console.log('IOS FILEDIR: ' + fileDir);
        }
        return fileTransferDir;
        */

        //https://github.com/apache/cordova-plugin-file
        return cordova.file.dataDirectory;
    }

    var addMedia = function(url, path, type, filename){
        var entry = {
            type: type,
            url: url,
            path: path,
        };

        downloadedMedia[filename] = entry;
        window.localStorage['downloadedMedia'] = JSON.stringify(downloadedMedia);

        return path;
    }
      
    var downloadFile = function (url, filename, type) {
        var deferred = $q.defer();

        var path = getPath() + type + "/" + filename;
        var trustHosts = true
        var options = {};

        $cordovaFileTransfer.download(url, path, options, trustHosts)
            .then(function (result) {
                //success
                //console.log("Success");
                var newpath = addMedia(url, path, type, filename)
                return deferred.resolve(newpath);
            }, function (err) {
                // Error
                return deferred.reject(err);
            }, function (progress) {
                $timeout(function () {
                    deferred.notify(((progress.loaded / progress.total) * 100));
                })
            });
        return deferred.promise;
    }

    //public methods:

    var getImage = function (url) {
        var deferred = $q.defer();

        if (url == null || url == undefined) {
            deferred.reject("No url / invalid url is given");
        } else {
            var fileName = getHashedFileName(url);
            if (fileName in downloadedMedia) {
                console.log('already found', url);
                deferred.resolve(downloadedMedia[fileName].path);
            } else {
                console.log('not found -> start download', url);
                downloadFile(url, fileName, 'images').then(function (path) {
                    deferred.resolve(path);
                }, function (error) {
                    deferred.reject(error);
                }, function (update) {
                    deferred.notify(update);
                });
            }
        }
        return deferred.promise;
    }

    return {
        getImage: getImage,
    }
})
