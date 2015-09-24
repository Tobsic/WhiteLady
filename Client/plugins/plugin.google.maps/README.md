phonegap-googlemaps-plugin
==========================
This plugin helps you leverage [Google Maps Android SDK v2](https://developers.google.com/maps/documentation/android/) and [Google Maps SDK for iOS](https://developers.google.com/maps/documentation/ios/) with your JavaScript.
Both [PhoneGap](http://phonegap.com/) and [Apache Cordova](http://cordova.apache.org/) are supported.


![ScreenShot](https://googledrive.com/host/0B1ECfqTCcLE8ZVQ1djlWNThISEE/phonegap-googlemaps-plugin_small.png)


###Example
You can see an example here. [phonegap-googlemaps-plugin-v1.2.5.apk](https://googledrive.com/host/0B1ECfqTCcLE8TXlUQUJXMmJpNGs/phonegap-googlemaps-plugin-v1.2.5.apk)
```bash
$> adb install phonegap-googlemaps-plugin-v1.2.5.apk
```

![image](https://googledrive.com/host/0B1ECfqTCcLE8ZVQ1djlWNThISEE/example-v1.2.5.gif)

### Join the official community
New versions will be announced through the official community. Stay tuned!

<a href="https://plus.google.com/u/0/communities/117427728522929652853"><img src="https://googledrive.com/host/0B1ECfqTCcLE8Yng5OUZIY3djUzg/Red-signin_Google_base_44dp.png" height="40"></a>

###What's up?

v1.2.5
- Add : The `opacity` and `tileSize` properties has been added to `map.addTileOverlay()`
- Add : Add marker animations: `DROP` and `BOUNCE`
- Add : Add INDOOR_BUILDING_FOCUSED & INDOOR_LEVEL_ACTIVATED events for indoor map events.
- Add plugin.google.maps.geometry.encoding.decodePath() & plugin.google.maps.geometry.encoding.encodePath()
- Update : v1.2.5 uses Google Play Services version 23.
- Added settings for watchdogtimer / complete rewrite of remote marker downloads
- The `icon` property of the `map.addMarker()` accepts `file://` & `cdvfile://` protocol, and also the absolute file path.
- Loading KML files from `file://` & `cdvfile://` protocol.
- And many bugs are fixed.

Check out the [release notes](https://github.com/wf9a5m75/phonegap-googlemaps-plugin/wiki/Release-Notes).

###Documentation

* Introduction
  * <a href="https://github.com/wf9a5m75/phonegap-googlemaps-plugin/wiki/How-different-are-the-JavaScript-APIs-%28web%29-and-the-mobile-SDKs-%28native%29">How different are the JavaScript APIs (web) and the mobile SDKs (native)</a>
  * [Why use this plugin?](https://github.com/wf9a5m75/phonegap-googlemaps-plugin/wiki/Why-use-this-plugin%3F)
  * [Java Objective C or JavaScript. Which one do you like?](https://github.com/wf9a5m75/phonegap-googlemaps-plugin/wiki/Java-Objective-C-or-JavaScript.-Which-one-do-you-like%3F)


* [Installation](https://github.com/wf9a5m75/phonegap-googlemaps-plugin/wiki/Installation)
  * Automatic Installation
  * Tutorials
    * [Tutorial for Windows](https://github.com/wf9a5m75/phonegap-googlemaps-plugin/wiki/Tutorial-for-Windows)
    * [Tutorial for Mac/Linux](https://github.com/wf9a5m75/phonegap-googlemaps-plugin/wiki/Tutorial-for-Mac)
    * [PhoneGap Usage](https://github.com/wf9a5m75/phonegap-googlemaps-plugin/wiki/Phonegap-Usage)
    * [Tutorial for Monaca (Cloud building service)](https://github.com/wf9a5m75/phonegap-googlemaps-plugin/wiki/Tutorial-for-Monaca)
  * Upgrade
    * [How to upgrade the Google Maps SDK for iOS to the latest version?](https://github.com/wf9a5m75/phonegap-googlemaps-plugin/wiki/How-to-upgrade-the-Google-Maps-SDK-for-iOS-to-the-latest-version%3F)
* [Terms of Services](https://github.com/wf9a5m75/phonegap-googlemaps-plugin/wiki/Terms-of-Services)
* [Map](https://github.com/wf9a5m75/phonegap-googlemaps-plugin/wiki/Map)
  * ![img](https://raw.github.com/wf9a5m75/phonegap-googlemaps-plugin/Images/screencapture/animateCamera.gif)
  * Create a map
  * Create a map with initialize options
  * Change the map type
  * Move the camera
  * Move the camera within the specified duration time
  * Get the camera position
  * Get my location
  * Map Class Reference
* [Marker](https://github.com/wf9a5m75/phonegap-googlemaps-plugin/wiki/Marker)
  * ![img](https://googledrive.com/host/0B1ECfqTCcLE8LUxUWmhsQmgxVVU/marker5.gif)
  * Add a Marker
  * Show InfoWindow
  * Add a marker with multiple line
  * callback
  * Simple Icon
  * Scaled Icon
  * Text Styling
  * Base64 Encoded Icon
  * Remove the marker
  * Click a marker
  * Click an infoWindow
  * Create a marker draggable
  * Drag Events
  * Create a flat marker
  * Marker Class Reference
* [Circle](https://github.com/wf9a5m75/phonegap-googlemaps-plugin/wiki/Circle)
  * ![img](https://googledrive.com/host/0B1ECfqTCcLE8ZVQ1djlWNThISEE/circle.png)
  * Add a circle
  * callback
  * Remove the circle
  * Circle Class Reference
* [Polyline](https://github.com/wf9a5m75/phonegap-googlemaps-plugin/wiki/Polyline)
  * ![img](https://googledrive.com/host/0B1ECfqTCcLE8ZVQ1djlWNThISEE/polyline.png)
  * Add a polyline
  * callback
  * Remove the polyline
  * Polyline Class Reference
* [Polygon](https://github.com/wf9a5m75/phonegap-googlemaps-plugin/wiki/Polygon)
  * ![img](https://googledrive.com/host/0B1ECfqTCcLE8ZVQ1djlWNThISEE/polygon.png)
  * Add a polygon
  * Click a polygon
  * callback
  * Remove the polygon
  * Polygon Class Reference
* [Tile Overlay](https://github.com/wf9a5m75/phonegap-googlemaps-plugin/wiki/TileOverlay)
  * <img src="https://googledrive.com/host/0B1ECfqTCcLE8MU1CbUtNVUs3TEE/tileOverlay.gif" height="250">
  * Add a tile overlay
  * TileOverlay Class Reference
* [Ground Overlay](https://github.com/wf9a5m75/phonegap-googlemaps-plugin/wiki/GroundOverlay)
  * <img src="https://googledrive.com/host/0B1ECfqTCcLE8ZVQ1djlWNThISEE/ground_overlay.gif" height="250">
  * Add a ground overlay
  * GroundOverlay Class Reference
* [Kml Overlay](https://github.com/wf9a5m75/phonegap-googlemaps-plugin/wiki/KmlOverlay)
  * <img src="https://googledrive.com/host/0B1ECfqTCcLE8MU1CbUtNVUs3TEE/kml-polygon.gif" height="250">
  * Add a kml overlay
  * KmlOverlay Class Reference
* [LatLng](https://github.com/wf9a5m75/phonegap-googlemaps-plugin/wiki/LatLng)
  * Create a LatLng object
  * LatLng Class Reference
* [LatLngBounds](https://github.com/wf9a5m75/phonegap-googlemaps-plugin/wiki/LatLngBounds)
  * Create a LatLngBounds object
  * LatLngBounds Class Reference
* [CameraPosition](https://github.com/wf9a5m75/phonegap-googlemaps-plugin/wiki/CameraPosition)
  * CameraPosition Class Reference
* [Location](https://github.com/wf9a5m75/phonegap-googlemaps-plugin/wiki/Location)
  * Location Class Reference
* [Geocoder](https://github.com/wf9a5m75/phonegap-googlemaps-plugin/wiki/Geocoder)
  * <img src="https://googledrive.com/host/0B1ECfqTCcLE8MU1CbUtNVUs3TEE/geocoding.gif" height="250">
  * Geocoding
  * Reverse geocoding
  * Geocoder Class Reference
* [BaseClass](https://github.com/wf9a5m75/phonegap-googlemaps-plugin/wiki/BaseClass)
  * BaseClass Reference
* [External Service](https://github.com/wf9a5m75/phonegap-googlemaps-plugin/wiki/External-Service)
  * <img src="https://googledrive.com/host/0B1ECfqTCcLE8MU1CbUtNVUs3TEE/direction.gif" height="250">
  * Launch the navigation application


-----

##Do you want to use marker cluster?

Marker clustering is the technique for grouping some markers based on bounds. This reduces the marker on the map, and reduce the memory usage.

<img src="https://googledrive.com/host/0B1ECfqTCcLE8SHVUX25xcmNIUTQ/marker_cluster.png" width="250">

[![Bountysource](https://www.bountysource.com/badge/team?team_id=942&style=raised)](https://www.bountysource.com/teams/phonegap-googlemaps-plugin?utm_source=PhoneGap%20GoogleMaps%20Plugin&utm_medium=shield&utm_campaign=raised) / $500
