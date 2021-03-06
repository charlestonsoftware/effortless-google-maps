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

    /**
     * Class: EGM_Actions
     *
     * The action hooks and helpers.
     *
     * The methods in here are normally called from an action hook that is
     * called via the WordPress action stack.  
     * 
     * @See http://codex.wordpress.org/Plugin_API/Action_Reference
     */
    class EGM_Actions {
        
        /******************************
         * PUBLIC PROPERTIES & METHODS
         ******************************/

        /** @var the egm plugin class
        */
        var $egm;
        
        /**
         * The Constructor
         */
        function __construct() {
            $this->egm = $GLOBALS['EffortlessGoogleMaps'];
        }
        
        /**
         * @method wp_enqueue_scripts
         * @return none
         * @param none
         */
        function wp_enqueue_scripts() {
            
            // If Google API Key Is Set, Pass It
            //
            $egmAPIKey = 'key=' . $this->egm->wpcsl->settings->get_item('api_key');
            if ($egmAPIKey == 'key=') {
                $egmAPIKey = '';
            }
            
            wp_register_script('google_maps',"http://maps.googleapis.com/maps/api/js?$egmAPIKey&sensor=true");
            wp_register_script('effortless-gm',$this->egm->plugin_url . '/js/effortless-google-maps.js',array('jquery'));
        } 
        
        /**
         * @method shutdown
         */
        function shutdown() {
            
            // If we rendered a shortcode...
            //
            if ($this->egm->wpcsl->shortcode_was_rendered) {
                
                // Render Scripts
                //
                wp_enqueue_script('google_maps');
                wp_enqueue_script('effortless-gm');
                
                // Render Styles
                //
                $this->egm->wpcsl->themes->assign_user_stylesheet($this->egm->Attributes['theme']);
                           
                // Force our scripts to load for badly behaved themes
                //
                wp_print_footer_scripts();                     
            }             
        } 
    }
}        
     

