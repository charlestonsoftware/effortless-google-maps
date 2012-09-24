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

        /** The egm main class
        */
        var $egm;

        /**
         * The Constructor
         */
        function __construct() {
            $this->egm = $GLOBALS['EffortlessGoogleMaps'];
        } 
        
        /** Initialize the admin page
        */
        function admin_init() {
            if ($this->egm->wpcsl->isOurAdminPage) {
                    // Then add our sections
                    //
                    $this->egm->wpcsl->settings->add_section(
                        array(
                            'name'              => __('Info', $this->egm->prefix),
                            'description'       => __(
                                $this->egm->wpcsl->helper->get_string_from_phpexec($this->egm->plugin_dir.'how_to_use.txt'),$this->egm->prefix),
                            'start_collapsed'   => false,
                        )
                    );
            
                    // Then add our sections
                    //
                    $this->egm->wpcsl->settings->add_section(
                        array(
                            'name'              => __('General Settings', $this->egm->prefix),
                            'description'       => __(
                                $this->egm->wpcsl->helper->get_string_from_phpexec($this->egm->plugin_dir.'general_settings.txt'),$this->egm->prefix),
                            'start_collapsed'   => false,
                        )
                    );        
            
                    $this->egm->wpcsl->settings->add_item(
                            __('General Settings', $this->egm->prefix), 
                            __('Google API Key', $this->egm->prefix), 
                            'api_key', 
                            'text', 
                            false,
                            __('Your Google API Key. This is optional.', $this->egm->prefix)
                   );
                   $this->egm->wpcsl->settings->add_item(
                            __('General Settings', $this->egm->prefix), 
                            __('Map Size', $this->egm->prefix), 
                            'size', 
                            'text', 
                            false,
                            __('The default size of the map(s).  If not set it will be 100%x400.', $this->egm->prefix)
                   );
	           $this->egm->wpcsl->settings->add_item(
	   	           __('General Settings', $this->egm->prefix),
	   	           __('Default View', $this->egm->prefix),
	   	           'view',
	   	           'list',
	   	           false,
	   	           __('The type of map to display.', $this->egm->prefix),
	   	           array(
	   	   	           'Terrain View' => 'terrain',
	   	   	           'Road View' => 'roadmap',
	   	   	           'Satellite View' => 'satellite',
	   	   	           'Hybrid View' => 'hybrid'
	   	           )
	           );
	           $this->egm->wpcsl->settings->add_item(
	   	           __('General Settings', $this->egm->prefix),
	   	           __('Default Address', $this->egm->prefix),
	   	           'address',
	   	           'text',
	   	           false,
	   	           __('The default address.', $this->egm->prefix),
	   	           '359 Wando Place Drive, Suite D, Mount Pleasant, SC 29464'
	           );
	   	        $this->egm->wpcsl->settings->add_item(
                    __('General Settings', $this->egm->prefix),
                    __('Use Location Sensor', $this->egm->prefix),
                    'useSensor',
                    'checkbox',
                    false,
                    __("Use the user's Location Sensor (if available) and offer directions to your location")
                );
            }
        }
        
        /**
         * method: admin_print_styles
         */
        function admin_print_styles() {
            if ( file_exists($this->egm->plugin_dir.'css/admin.css')) {
                wp_enqueue_style('csl_egm_admin_css', $this->egm->plugin_url .'/css/admin.css'); 
            }               
        } 
    }
}        
     

