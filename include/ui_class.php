<?php

/***********************************************************************
* Class: EGM_UserInterface
*
* User Interface hooks and helpers.
*
* The shortcode and widget rendering.
*
************************************************************************/

if (! class_exists('EGM_UserInterface')) {
    class EGM_UserInterface {
        
        /******************************
         * PUBLIC PROPERTIES & METHODS
         ******************************/
        
        /*************************************
         * The Constructor
         */
        function __construct($params) {
        } 
        
        /*************************************
         * method: render_shortcode
         */
        function render_shortcode() {
            global $egm_plugin;
            $egm_plugin->shortcode_was_rendered = true;
        }     
    }
}        
     

