<?php
/*
Plugin Name: Effortless Google Maps
Plugin URI: http://www.charlestonsw.com/products/effortless-google-maps/
Description: Put Google Maps on any page or post with a simple shortcode or widget.   100% free premium plugin. 
Version: 0.66
Author: Charleston Software Associates
Author URI: http://www.charlestonsw.com
License: GPL3

Copyright 2012  Charleston Software Associates (info@charlestonsw.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// If we haven't been loaded yet
//
if ( ! class_exists( 'EffortlessGoogleMaps' ) ) {

// Call in wpcsl if we need it
if (class_exists('wpCSL_plugin__egm') === false) {
        require_once('WPCSL-generic/classes/CSL-plugin.php');
}

class EffortlessGoogleMaps {
    /** The main wpcsl object for this plugin */
    var $wpcsl;

    /***********/
    /* Defines */
    /***********/

    /** Plugin prefix */
    var $prefix;

    /** The Plugin Base name */
    var $base_name;

    /** The plugin directory */
    var $plugin_dir;

    /** The directory to icons */
    var $icon_dir;

    /** The url to the plugin */
    var $plugin_url;

    /** The url to the icons */
    var $icon_url;

    /** The admin page */
    var $admin_page;

    /***********/
    /* Objects */
    /***********/

    /** Actions class */
    var $Actions;

    /** Admin page actions */
    var $Admin_actions;

    /** UI Stuff */
    var $UI;

    /** Global maps attributes */
    var $Attributes;

    /***********/
    /* Methods */
    /***********/

    /** Constructor to create the default plugin
    */
    function __construct() {
        $this->plugin_dir = plugin_dir_path(__FILE__);
        $this->icon_dir = $this->plugin_dir . 'images/icons/';

        $this->plugin_url = plugins_url('',__FILE__);
        $this->icon_url = $this->plugin_url . 'images/icons/';
        $this->admin_page = admin_url() . 'admin.php?page=' . $this->plugin_dir;

        $this->base_name = plugin_basename(__FILE__);

        $this->prefix = 'csl-egm';

        $this->_configure();
        $this->_includes();
        $this->_actions();
    }

    /** Include our needed files
    */
    function _includes() {
        require_once($this->plugin_dir . 'include/actions_class.php');
        require_once($this->plugin_dir . 'include/admin_actions_class.php');
        require_once($this->plugin_dir . 'include/ui_class.php');
        require_once($this->plugin_dir . 'include/egm_widget_class.php');
    }

    /** Configre wpcsl
    */
    function _configure() {
        $this->wpcsl = new wpCSL_plugin__egm(
            array(
                'prefix'                => $this->prefix,
                'name'                  => 'Effortless Google Maps',
                'sku'                   => 'EGMS',
            
                'url'                   => 'http://www.charlestonsw.com/product/effortless-google-maps/',
                'support_url'           => 'http://www.charlestonsw.com/product/effortless-google-maps/',

                // Nag menu
                //
                'rate_url'              => 'http://wordpress.org/extend/plugins/google-maps-effortless/',
                'forum_url'             => 'http://wordpress.org/support/plugin/google-maps-effortless/',
                'version'               => '0.66',
            
                'basefile'              => $this->base_name,
                'plugin_path'           => $this->plugin_dir,
                'plugin_url'            => $this->plugin_url,
                'cache_path'            => $this->plugin_dir . 'cache',
            
                // We don't want default wpCSL objects, let's set our own
                //
                'use_obj_defaults'      => false,
            
                'cache_obj_name'        => 'none',
                'license_obj_name'      => 'none',            
                'products_obj_name'     => 'none',
            
                'helper_obj_name'       => 'default',
                'notifications_obj_name'=> 'default',
                'settings_obj_name'     => 'default',
            
                // Themes and CSS
                //
                'themes_obj_name'       => 'default',
                'themes_enabled'        => 'true',
                'css_prefix'            => 'csl_themes',
                'css_dir'               => $this->plugin_dir . 'css/',
                'no_default_css'        => true,
            
                // Custom Config Settings
                //
                'display_settings_collapsed'=> false,
                'show_locale'               => false,            
                'uses_money'                => false,            
                'has_packages'              => false,            
            
                'driver_type'           => 'none',
                'driver_args'           => array(
                        'api_key'   => get_option($this->prefix.'-api_key'),
                ),
            )
        );
    }

    /** Set up actions and filters
    */
    function _actions() {
        // Regular Actions
        //
        add_action('wp_enqueue_scripts' ,array(&$this->Actions,'wp_enqueue_scripts')      );
        add_action( 'widgets_init', create_function( '', 'register_widget( "egmWidget" );' ) );
        add_action('shutdown'           ,array(&$this->Actions,'shutdown')                );

        // Admin Actions
        //
        add_action('admin_init'         ,array(&$this->Admin_actions,'admin_init')        );
        add_action('admin_print_styles' ,array(&$this->Admin_actions,'admin_print_styles'));

        // Short Codes
        //
        add_shortcode('effortless-gm'   ,array(&$this,'render_shortcode')  );
        add_shortcode('EFFORTLESS-GM'   ,array(&$this,'render_shortcode')  );
        add_shortcode('Effortless-GM'   ,array(&$this,'render_shortcode')  );

        // Text Domains
        //
        load_plugin_textdomain($this->prefix, false, $this->base_name . '/languages/');

        // EGM Specific filters
        //
        add_filter($this->prefix."CleanNumber"  , array(&$this->UI, 'cleanNumber'), 1, 3);
        add_filter($this->prefix."ManageZoom"   , array(&$this->UI, 'manageZoom'), 1, 1);
        add_filter($this->prefix."RenderWidget" , array(&$this->UI, 'render_shortcode'), 1, 1);

        // EGM Specific actions
        //
    }

    /**
     * Render the shortcode
     * 
     * @return string
     *
     */
    function render_shortcode($params=null) {
            return $this->UI->render_shortcode($params);
    }

    /** Create objects
    */
    function _create_objects() {
        $this->Actions = new EGM_Actions();
        $this->Admin_actions = new EGM_Admin_Actions();
        $this->UI = new EGM_UserInterface();
    }
}

$GLOBALS['EffortlessGoogleMaps'] = new EffortlessGoogleMaps();
$GLOBALS['EffortlessGoogleMaps']->_create_objects();
}


