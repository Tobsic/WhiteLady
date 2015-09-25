angular.module('starter.controllers', [])

.controller('AppCtrl', function($scope, $ionicModal, $timeout) {

  // With the new view caching in Ionic, Controllers are only called
  // when they are recreated or on app start, instead of every page change.
  // To listen for when this page is active (for example, to refresh data),
  // listen for the $ionicView.enter event:
  //$scope.$on('$ionicView.enter', function(e) {
  //});
})

.controller('LocationsCtrl', function($scope, sharedProperties) {
  sharedProperties.updateLocations()
    .then(data => console.log(data))
    .then(() => sharedProperties.downloadLocation(3));
  $scope.locations = sharedProperties.getDownloadedLocations();
  window.asdf = $scope.locations;
})

.controller('LocationCtrl', function($scope, pois, sharedProperties) {
  $scope.locations = sharedProperties.getDownloadedLocations();
})

.service('sharedProperties', function(serverSettings, $http) {
  var availableLocations = JSON.parse(window.localStorage['availableLocations'] || '{}');
  var downloadedLocations = JSON.parse(window.localStorage['downloadedLocations'] || '{}');
  //   { id: 1, pic: 'img/desert.png', title: 'Rabbitwhole'},
  //   { id: 2, pic: 'img/desert.png', title: 'Spitzkoppe'},
  //   { id: 3, pic: 'img/desert.png', title: 'Brandberg'},
  //   { id: 4, pic: 'img/desert.png', title: 'Download new content'}
  // ];
  var transform = function(data, name) {
    data = data.data[name];
    var res = { };
    var columns = data.columns;
    for(values in data.records) {
      var entry = { };
      for(index in columns)
        entry[columns[index]] = data.records[values][index];
      res[entry[name + '_id']] = entry;
    }
    return res;
  };
  var getDownloadedLocations = function() { return downloadedLocations };
  var getAvailableLocations = function() { return availableLocations; };
  var updateLocations = function() {
    return $http.get(serverSettings.url + 'Location').then(function(resp) {
      availableLocations = transform(resp, 'location');
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
    return $http
      .get(serverSettings.url + 'Poi?filter=location_id,eq,' + locationId)
      .then(function(resp) {
        downloadedLocations[locationId] = availableLocations[locationId];
        var pois = transform(resp, 'poi');
        downloadedLocations[locationId].pois = pois;
        var promises = [];
        for(poiId in pois) if(pois.hasOwnProperty(poiId))
          promises.push(downloadAdditionlaPoiInfos(pois[poiId]));
        return Promise
          .all(promises)
          .then(() => downloadedLocations[locationId]);
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
