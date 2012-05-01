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
        
        
        function admin_init() {
            global $egm_plugin;
    
            // Then add our sections
            //
            $egm_plugin->settings->add_section(
                array(
                    'name'              => __('Info', EGM_PREFIX),
                    'description'       => __(
                        $egm_plugin->helper->get_string_from_phpexec(EGM_PLUGINDIR.'how_to_use.txt'),EGM_PREFIX),
                    'start_collapsed'   => false,
                )
            );
            
            // Then add our sections
            //
            $egm_plugin->settings->add_section(
                array(
                    'name'              => __('General Settings', EGM_PREFIX),
                    'description'       => __(
                        $egm_plugin->helper->get_string_from_phpexec(EGM_PLUGINDIR.'general_settings.txt'),EGM_PREFIX),
                    'start_collapsed'   => false,
                )
            );        
            
            $egm_plugin->settings->add_item(
                    __('General Settings', EGM_PREFIX), 
                    __('Google API Key', EGM_PREFIX), 
                    'api_key', 
                    'text', 
                    false,
                    __('Your Google API Key. This is optional.', EGM_PREFIX)
           );
            $egm_plugin->settings->add_item(
                    __('General Settings', EGM_PREFIX), 
                    __('Map Size', EGM_PREFIX), 
                    'size', 
                    'text', 
                    false,
                    __('The default size of the map(s).  If not set it will be 100%x400.', EGM_PREFIX)
           );            
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
     

