var app = angular.module('starter.routing', [])

app.config(function($stateProvider, $urlRouterProvider) {
    $stateProvider

      .state('app', {
          url: '/app',
          abstract: true,
          templateUrl: 'templates/menu.html',
          controller: 'AppCtrl'
      })

    .state('app.search', {
        url: '/search',
        views: {
            'menuContent': {
                templateUrl: 'templates/search.html'
            }
        }
    })

    .state('app.browse', {
        url: '/browse',
        views: {
            'menuContent': {
                templateUrl: 'templates/browse.html'
            }
        }
    })

    .state('app.locations', {
        url: '/locations',
        views: {
            'menuContent': {
                templateUrl: 'templates/locations.html',
                controller: 'LocationsCtrl'
            }
        }
    })

    .state('app.location', {
        url: '/locations/:locationId',
        views: {
            'menuContent': {
                templateUrl: 'templates/location.html',
                controller: 'LocationCtrl'
            }
        }
    })
    .state('app.position', {
        url: '/position/:locationId/:posId',
        views: {
            'menuContent': {
                templateUrl: 'templates/position.html',
                controller: 'PositionCtrl'
            }
        }
    })

    // POI View Routes
    .state('app.poi_bushphone', {
        url: 'templates/poi_bushphone.html',
        controller: 'POIBusphoneCtrl'
    });

    // if none of the above states are matched, use this as the fallback
    $urlRouterProvider.otherwise('/app/locations');
});