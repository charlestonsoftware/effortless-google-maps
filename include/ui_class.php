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
        function render_shortcode($params) {
            global $egm_plugin;
            $egm_plugin->shortcode_was_rendered = true;
            
            // Set the attributes, default or passed in shortcode
            //
            $egmAttributes = shortcode_atts(
                array(
                    'size'      => '100%x400',
                    'address'   => '359 Wando Place Drive, Suite D, Mount Pleasant, SC 29464',
                    ), 
                $params
                );
            
            // Size is the width x height, split it...
            //
            list($egmWidth,$egmHeight) = (split('x',$egmAttributes['size']));
            $egmWidth  = EGM_UserInterface::CheckDimensions($egmWidth);
            $egmHeight = EGM_UserInterface::CheckDimensions($egmHeight);
            
            // Render the map div
            //
            print '<div id="map_canvas" style="width:'.$egmWidth.'; height:'.$egmHeight.'"></div>';
        }
        
        /*************************************
         * private method: checkDimensions
         *
         * If the number has a % in it, keep that suffix, otherwise assume 'px'.
         *
         * Then make sure we strip out all non-digits from the string.
         *
         * Then we check bounds, 0 - 100 for % and 0 - 9999 for px.
         *
         */
        function checkDimensions($value) {
            $suffix = 
                (substr_compare($value, '%', -strlen('%'), strlen('%')) === 0) ? 
                    '%' : 
                    'px';
            $value = preg_replace('/\D/','',$value);
            
            if ($suffix == 'px') {
                $value = max(min($value,9999),0);
            } else {
                $value = max(min($value,100),0);
            }            
            return  $value . $suffix; 
        }
    }
}        
     

