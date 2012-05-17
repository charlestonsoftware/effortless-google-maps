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
  * Cyber Sprocket Labs Namespace
  *
  * For stuff to do awesome stuff
  *
  */
  var csl = {
  	  
  	  /***************************
  	  	  * Map Object
  	  	  * usage:
  	  	  * create a google maps object linked to a map/canvas id
  	  	  * parameters:
  	  	  * 	aMapNumber: the id/canvas of the map object to load from php side
  	  	  */
  	  Map: function(aMapNumber) {
  	  	  //private: map number to look up at init
  	  	  this.__mapNumber = aMapNumber;
  	  	  
  	  	  //php passed vars set in init
  	  	  this.address = null;
  	  	  this.zoom = null;
  	  	  this.view = null;
  	  	  this.canvasID = null;
  	  	  this.title = null;
  	  	  
  	  	  //gmap set variables
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
  	  	  	  	  	  MapTypeId: this.view
  	  	  	  	  };
  	  	  	  	  this.gmap = new google.maps.Map(document.getElementById("canvas" + this.canvasID), this.options);
  	  	  	  	  this.addMarkerAtCenter();
  	  	  	  } else {
  	  	  	  	  alert("Address could not be processed: " + status);
  	  	  	  }
  	  	  }
  	  	  
  	  	  /***************************
  	  	  * function: addMarkerAtCenter
  	  	  * usage:
  	  	  * Puts a pretty marker right smack in the middle
  	  	  * parameters:
  	  	  * 	none
  	  	  * returns: none
  	  	  */
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
  	  	  	  	  function (result, status) {							// This is a little complicated, 
  	  	  	  	  _this.__geocodeResult.call(_this, result, status); }	// but it forces the callback to keep its scope
  	  	  	  );
  	  	  }
  	  	  
  	  	  //dumb browser quirk trick ... wasted two hours on that one
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

