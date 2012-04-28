<?php

/***********************************************************************
* Class: EGM_Admin_Actions
*
* The action hooks and helpers.
*
* The methods in here are normally called from an action hook that is
* called via the WordPress action stack.  
* 
* See http://codex.wordpress.org/Plugin_API/Action_Reference
*
************************************************************************/

if (! class_exists('EGM_Admin_Actions')) {
    class EGM_Admin_Actions {
        
        /******************************
         * PUBLIC PROPERTIES & METHODS
         ******************************/
        
        /*************************************
         * The Constructor
         */
        function __construct($params) {
        } 
        
        /*************************************
         * method: admin_print_styles
         */
        function admin_print_styles() {
            if ( file_exists(EGM_PLUGINDIR.'css/admin.css')) {
                wp_enqueue_style('csl_egm_admin_css', EGM_PLUGINURL .'/css/admin.css'); 
            }               
        } 
    }
}        
     

