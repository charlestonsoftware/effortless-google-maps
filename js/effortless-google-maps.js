/*****************************************************************
 * file: effortless-google-maps.js
 *
 * Prep our map interface.
 *
 *****************************************************************/

 /***************************
  * function InitializeTheMap()
  *
  * Setup the map settings and get id rendered.
  *
  */
 function InitializeTheMap() {
      var myOptions = {
          center: new google.maps.LatLng(32.843014, -79.873036),
          zoom: 10,
          mapTypeId: google.maps.MapTypeId.ROADMAP
        };        
        var map = new google.maps.Map(document.getElementById("map_canvas"),myOptions);     
 }
 
 
/* 
 * When the document has been loaded...
 *
 */
jQuery(document).ready(function(){
        InitializeTheMap();
});

