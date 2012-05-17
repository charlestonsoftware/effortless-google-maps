/*****************************************************************
 * file: effortless-google-maps.js
 *
 * Prep our map interface.
 *
 *****************************************************************/
var map;
var opts;
var markers;

/***************************
  * class MapBuilder
  *
  * Setup the map settings and get id rendered.
  *
  */
  var csl = {
  	  Map: function(aMapNumber) {
  	  	  this.__mapNumber = aMapNumber;
  	  	  this.address = null;
  	  	  this.zoom = null;
  	  	  this.view = null;
  	  	  this.canvasID = null;
  	  	  this.title = null;
  	  	  
  	  	  this.options = null;
  	  	  this.gmap = null;
  	  	  this.centerMarker = null;
  	  	  
  	  	  /***************************
  	  	  * function: __geocodeResult
  	  	  * usage:
  	  	  * Callback from google so we can update our map
  	  	  * parameters:
  	  	  * 	results: some usable results (see google api reference)
  	  	  *		status:  the status of the geocode (ok means g2g)
  	  	  * returns: none
  	  	  */
  	  	  this.__geocodeResult = function(results, status) {
  	  	  	  if (status == 'OK' && results.length > 0)
  	  	  	  {
  	  	  	  	  this.options = {
  	  	  	  	  	  center: results[0].geometry.viewport.getCenter(),
  	  	  	  	  	  zoom: parseInt(this.zoom),
  	  	  	  	  	  MapTypeId: eval(this.view)
  	  	  	  	  };
  	  	  	  	  this.gmap = new google.maps.Map(document.getElementById("canvas" + this.canvasID), this.options);
  	  	  	  	  this.addMarkerAtCenter();
  	  	  	  } else {
  	  	  	  	  alert("Address could not be processed: " + status);
  	  	  	  }
  	  	  }
  	  	  this.addMarkerAtCenter = function() {
  	  	  	  this.centerMarker = new google.maps.Marker(
  	  	  	  	  {
  	  	  	  	  	  position: this.gmap.getCenter(),
  	  	  	  	  	  map: this.gmap
  	  	  	  	  }
  	  	  	   );
  	  	  }
  	  	  
  	  	  /***************************
  	  	  * function: __init()
  	  	  * usage:
  	  	  * Called at the end of the 'class' due to some browser's quirks
  	  	  * parameters: none
  	  	  * returns: none
  	  	  */
  	  	  this.__init = function() {
  	  	  	  this.address = egmMaps[this.__mapNumber].address;
  	  	  	  this.zoom = egmMaps[this.__mapNumber].zoom;
  	  	  	  this.view = egmMaps[this.__mapNumber].view;
  	  	  	  this.canvasID = egmMaps[this.__mapNumber].id;
  	  	  }
  	  	  
  	  	  /***************************
  	  	  * function doGeocode()
  	  	  * usage:
  	  	  * Call to start the geocode of the address and display it on the map if possible
  	  	  * make sure to call init first
  	  	  * parameters: none
  	  	  * returns: none
  	  	  */
  	  	  this.doGeocode = function() {
  	  	  	  var geocoder = new google.maps.Geocoder();
  	  	  	  console.log("Geocoding: " + this.address);
  	  	  	  var _this = this;
  	  	  	  geocoder.geocode(
  	  	  	  	  {
  	  	  	  	  	  'address': this.address
  	  	  	  	  },
  	  	  	  	  function (result, status) {
  	  	  	  	  _this.__geocodeResult.call(_this, result, status); }
  	  	  	  );
  	  	  }
  	  	  
  	  	  //dumb browser quirk trick ...
  	  	  this.__init();
  	  }
  }
 
 /***************************
  * function InitializeTheMap()
  *
  * Setup the map settings and get id rendered.
  *
  */
 function InitializeTheMap() {
    for (egmMap in egmMaps)
    {
    	    var cslmap = new csl.Map(egmMap);
    	    cslmap.doGeocode();
    }
 }

/* 
 * When the document has been loaded...
 *
 */
jQuery(document).ready(function(){
        InitializeTheMap();
});

