/*****************************************************************
 * file: effortless-google-maps.js
 *
 * Prep our map interface.
 *
 *****************************************************************/
var map;
 
 /***************************
  * function InitializeTheMap()
  *
  * Setup the map settings and get id rendered.
  *
  */
 function InitializeTheMap() {
    var geocoder = new google.maps.Geocoder();
    geocoder.geocode(
        {
            'address': egm.address,
            'partialmatch': true
        }, 
        geocodeResult
        );
 }

 
 /***************************
  * function geocodeResult()
  *
  * process a lat/long result
  *
  */
function geocodeResult(results, status) {
    if (status == 'OK' && results.length > 0) {
      var myOptions = {
          center: results[0].geometry.viewport.getCenter(),
          zoom: parseInt(egm.zoom),
          mapTypeId: google.maps.MapTypeId.ROADMAP
        };        
      map = new google.maps.Map(document.getElementById("map_canvas"),myOptions);
      addMarkerAtCenter(map);
    } else {
        alert("Address could not be processed: " + status);
    }
} 

 /***************************
  * function addMarkerAtCenter()
  *
  * process a lat/long result
  *
  */
function addMarkerAtCenter() {
    var marker = new google.maps.Marker(
        {
            position: map.getCenter(),
            map: map
        }
        ); 
}

/* 
 * When the document has been loaded...
 *
 */
jQuery(document).ready(function(){
        InitializeTheMap();
});

