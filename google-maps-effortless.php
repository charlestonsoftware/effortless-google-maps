<?php
/*
Plugin Name: Effortless Google Maps
Plugin URI: http://www.cybersprocket.com/products/effortless-google-maps/
Description: Put Google Maps on any page or post with a simple shortcode or widget.   100% free premium plugin. 
Version: 0.65
Author: Cyber Sprocket Labs
Author URI: http://www.cybersprocket.com
License: GPL3

Copyright 2012  Cyber Sprocket Labs (info@cybersprocket.com)

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
    // Main wpcsl object
    var $wpcsl;

    // Defines
    var $prefix;
    var $base_name;
    var $plugin_dir;
    var $icon_dir;
    var $plugin_url;
    var $icon_url;
    var $admin_page;

    // Objects
    var $Actions;
    var $Admin_actions;
    var $UI;

    // Children globals
    var $Attributes;

    // Constructor to create the default plugin
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
        $this->_create_objects();
        $this->_actions();
    }

    // Include our needed files
    //
    function _includes() {
        require_once($this->plugin_dir . 'include/actions_class.php');
        require_once($this->plugin_dir . 'include/admin_actions_class.php');
        require_once($this->plugin_dir . 'include/ui_class.php');
        require_once($this->plugin_dir . 'include/egm_widget_class.php');
    }

    // Configre wpcsl
    //
    function _configure() {
        $this->wpcsl = new wpCSL_plugin__egm(
            array(
                'prefix'                => $this->prefix,
                'name'                  => 'Effortless Google Maps',
                'sku'                   => 'EGMS',
            
                'url'                   => 'http://www.cybersprocket.com/products/effortless-google-maps/',            
                'support_url'           => 'http://www.cybersprocket.com/products/effortless-google-maps/',

                // Nag menu
                //
                'rate_url'              => 'http://wordpress.org/extend/plugins/google-maps-effortless/',
                'forum_url'             => 'http://redmine.cybersprocket.com/projects/commercial-products/boards/41',
                'version'               => '0.65',
            
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

    // Set up actions and filters
    //
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
        add_shortcode('effortless-gm'   ,array(&$this->UI,'render_shortcode')  );
        add_shortcode('EFFORTLESS-GM'   ,array(&$this->UI,'render_shortcode')  );
        add_shortcode('Effortless-GM'   ,array(&$this->UI,'render_shortcode')  );

        // Text Domains
        //
        load_plugin_textdomain($this->prefix, false, $this->base_name . '/languages/');
    }

    // Create objects
    //
    function _create_objects() {
        $this->Actions = new EGM_Actions();
        $this->Admin_actions = new EGM_Admin_Actions();
        $this->UI = new EGM_UserInterface();
    }
}

$GLOBALS['EffortlessGoogleMaps'] = new EffortlessGoogleMaps();

}


