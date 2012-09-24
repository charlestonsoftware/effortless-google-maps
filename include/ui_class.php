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
        var $egm;
        var $maps;
        var $idCounter;

        /*************************************
         * The Constructor
         */
        function __construct() {
            $this->egm = $GLOBALS['EffortlessGoogleMaps'];
        } 
        
        /*************************************
         * method: render_shortcode
         */
        function render_shortcode($params=null) {
            $this->egm->wpcsl->shortcode_was_rendered = true;
            
            // stop at max maps
            //
            //$egmMaxMaps = get_option( "MaxMapsPerPage", 2);
            //if ($egmMaxMaps <= $egmIdCounter) return;
			
			$defView = $this->egm->wpcsl->settings->get_item('view');

            // Set the attributes, default or passed in shortcode
            //
            $defSize = $this->egm->wpcsl->settings->get_item('size','100%x400');
            $this->egm->Attributes = shortcode_atts(
                array(
                    'address'   => $this->egm->wpcsl->settings->get_item('address','Charleston, SC, USA'),
                    'size'      => ((trim($defSize)=='')?'100%x400':$defSize),
                    'theme'     => $this->egm->wpcsl->settings->get_item('theme'),
                    'zoom'      => '12',
                    'view'	=> ((trim($defView)=='')?'roadmap':$defView),
                    'disableUI' => $this->egm->wpcsl->settings->get_item('disableUI'),
                    'useSensor' => $this->egm->wpcsl->settings->get_item('useSensor'),
                    'name' => $this->egm->wpcsl->settings->get_item('address'),
                    ), 
                $params
                );

            if($this->egm->Attributes['address'] == '') {
                $this->egm->Attributes['address'] = 'Charleston SC USA';
            }

            // Size is the width x height, split it...
            //
            list($egmWidth,$egmHeight) = (split('x',$this->egm->Attributes['size']));
            $egmWidth  = EGM_UserInterface::CheckDimensions($egmWidth);
            $egmHeight = EGM_UserInterface::CheckDimensions($egmHeight);
            
            //set the egmID
            //
            if (!isSet($this->idCounter)) $this->idCounter = 0;
            $egmID = $this->idCounter++;
                   
            // Keep stuff in range
            //
            $egmZoom = apply_filters($this->egm->prefix."ManageZoom", $this->egm->Attributes['zoom']);

            // Prep our new stuff for passing to the script            
            $this->egm->Attributes = array_merge($this->egm->Attributes,
                array(
                    'width'     => $egmWidth,
                    'height'    => $egmHeight,
                    'zoom'      => $egmZoom,
                    'id'	=> $egmID,
                    )
                );
            
            //adds the map to the map list
            $this->maps[] = $this->egm->Attributes;
            
            // Lets get some variables into our script
            //
            wp_localize_script('effortless-gm','egmMaps',$this->maps);
            
            // Render the map div
            //
            $content ='';
            $content .= '<div class="'.$this->egm->wpcsl->settings->get_item('theme').'" id="canvas'.$egmID.'" style="width:'.$egmWidth.'; height:'.$egmHeight.'"></div>';
            return apply_filters($this->egm->prefix.'Render', $content);
        }
        
        /*************************************
         * private method: manageZoom
         *
         * Allow for certain keywords to be used on zoom:
         *      world = zoom way out
         *      street = moderate zoom
         *      house = tight zoom
         *
         */
        function manageZoom($value) {
            $newNumber = $value;
            switch ($value) {
                case 'world':
                    $newNumber = '1';                    
                    break;
                case 'country':
                    $newNumber='3';
                    break;                                        
                case 'region':
                    $newNumber='5';
                    break;                                        
                case 'state':
                    $newNumber='7';
                    break;                                        
                case 'county':
                    $newNumber='9';
                    break;                                        
                case 'street':
                    $newNumber='15';
                    break;                    
                case 'house':
                    $newNumber='18';
                    break;                    
            }

            return apply_filters($this->egm->prefix."CleanNumber", $newNumber, 0, 20);
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
        
        /*************************************
         * private method: cleanNumber
         *
         * Make sure we strip out all non-digits from the string.
         *
         * Then we check bounds as noted.
         *
         */
        function cleanNumber($value,$min=0,$max=100) {
            $value = preg_replace('/\D/','',$value);
            return max(min($value,$max),$min);
        }        
    }
}        
     

