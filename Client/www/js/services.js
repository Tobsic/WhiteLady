var app = angular.module('starter.services', [])
app.service('sharedProperties', function(serverSettings, $http) {
    var availableLocations = JSON.parse(window.localStorage['availableLocations'] || '{}');
    var downloadedLocations = JSON.parse(window.localStorage['downloadedLocations'] || '{}');
    //   { id: 1, pic: 'img/desert.png', title: 'Rabbitwhole'},
    //   { id: 2, pic: 'img/desert.png', title: 'Spitzkoppe'},
    //   { id: 3, pic: 'img/desert.png', title: 'Brandberg'},
    //   { id: 4, pic: 'img/desert.png', title: 'Download new content'}
    // ];
    var transform = function (data, name) {
        
        data = data.data[name];
        var res = {};
        console.log(name + " - " + data);
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
    var getDownloadedLocations = function() { return downloadedLocations };
    var getAvailableLocations = function() { return availableLocations; };
    var updateLocations = function() {
        return $http.get(serverSettings.url + 'location').then(function (resp) {
            availableLocations = transform(resp, 'Location');
            window.localStorage['availableLocations'] = JSON.stringify(availableLocations);
            return availableLocations;
        });
    };
    var downloadFile = function(url) {
        console.log('should download file: ', url);
    };
    var downloadMedia = function(poiId) {
        return $http.get(serverSettings.url + 'Media?filter=poi_id,eq,' + poiId).then(function(resp) {
            var pages = []
            var data = transform(resp, 'media');
            for(i in data) {
                var media = data[i];
                var type = media.media_type.split('/')[0];
                if(!pages[media.media_pagenumber])
                    pages[media.media_pagenumber] = {
                        image: [],
                        audio: [],
                        video: []
                    };
                if(type == 'text')
                    pages[media.media_pagenumber].description = media.media_content;
                else if(type == 'image' || type == 'audio' || type == 'video') {
                    var info = {
                        url: media.media_content,
                        loading: true
                    };
                    downloadFile(info);
                    pages[media.media_pagenumber][type].push(info)
                }
            }
            return pages;
        });
    };
    var downloadGps = function(gpsId) {
        return $http
          .get(serverSettings.url + 'Gps?filter=gps_id,eq,' + gpsId)
          .then(resp => transform(resp, 'gps'));
    };
    var downloadAdditionlaPoiInfos = function(poi) {
        Promise.all([
          downloadMedia(poi.poi_id).then(pages => poi.pages = pages),
          downloadGps(poi.gps_id).then(gps => poi.gps = gps)
        ]);
    };
    var downloadLocation = function(locationId) {
       return $http({
            method: 'GET',
            url: serverSettings.url + 'Poi?filter=location_id,eq,' + locationId
        }).then(function successCallback(response) {
            // this callback will be called asynchronously
            // when the response is available
            downloadedLocations[locationId] = availableLocations[locationId];
            var pois = transform(response, 'Poi');

            downloadedLocations[locationId].pois = pois;
            var promises = [];
            for(poiId in pois) if(pois.hasOwnProperty(poiId))
                promises.push(downloadAdditionlaPoiInfos(pois[poiId]));
            return Promise
              .all(promises)
              .then(() => downloadedLocations[locationId]);
        }, function errorCallback(response) {
            console.log("Error:  "+response);
        });
    };
    var getPois = function(locationId) {

    };

    return {
        getDownloadedLocations: getDownloadedLocations,
        getAvailableLocations : getAvailableLocations,
        updateLocations: updateLocations,
        downloadLocation: downloadLocation,
        getPois: getPois
    };
})

.service('pois', function() {
    var pois = [];

    return {
        get: function() {
            return pois;
        }
    };
});