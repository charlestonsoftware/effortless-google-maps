<?php

/***********************************************************************
* Class: EGM_Actions
*
* The action hooks and helpers.
*
* The methods in here are normally called from an action hook that is
* called via the WordPress action stack.  
* 
* See http://codex.wordpress.org/Plugin_API/Action_Reference
*
************************************************************************/

if (! class_exists('EGM_Actions')) {
    class EGM_Actions {
        
        /******************************
         * PUBLIC PROPERTIES & METHODS
         ******************************/
        
        /*************************************
         * The Constructor
         */
        function __construct($params) {
        }
        
        /*************************************
         * method: wp_enqueue_scripts
         */
        function wp_enqueue_scripts() {
        } 
        
        /*************************************
         * method: shutdown
         */
        function shutdown() {
        } 
    }
}        
     

