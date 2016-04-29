<?php
/*
Plugin Name: Firebase Like Button
Plugin URI: http://fyaconiello.github.com/wp-plugin-template
Description: A like button that shows where clients who like a product are located.
Version: 1.0
Author: Casey Carroll
Author URI: http://www.caseyrcarroll.info
License: GPL2
*/
/*
Copyright 2012  Casey Carroll  (email : casey.r.carroll@icloud.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
if(!class_exists('FB_Like_Bttn'))
{
    class FB_Like_Bttn
    {
        /**
         * Construct the plugin object
         */
        public function __construct()
        {
            // register actions
						add_action('admin_init', array(&$this, 'admin_init'));
						add_action('admin_menu', array(&$this, 'add_menu'));
        } // END public function __construct

				/**
				 * hook into WP's admin_init action hook
				 */
				public function admin_init()
				{
				    // Set up the settings for this plugin
				    $this->init_settings();
				    // Possibly do additional admin_init tasks
				} // END public static function activate

				/**
				 * Initialize some custom settings
				 */
				public function init_settings()
				{
				    // register the settings for this plugin
				    register_setting('fb_like_bttn-group', 'setting_a');
				    register_setting('fb_like_bttn-group', 'setting_b');
				} // END public function init_custom_settings()

				/**
				 * add a menu
				 */
				public function add_menu()
				{
				    add_options_page('Firebase Like Button Settings', 'Firebase Like Button Template', 'manage_options', 'fb_like_bttn', array(&$this, 'plugin_settings_page'));
				} // END public function add_menu()

				/**
				 * Menu Callback
				 */
				public function plugin_settings_page()
				{
				    if(!current_user_can('manage_options'))
				    {
				        wp_die(__('You do not have sufficient permissions to access this page.'));
				    }

				    // Render the settings template
				    include(sprintf("%s/templates/settings.php", dirname(__FILE__)));
				} // END public function plugin_settings_page()

        /**
         * Activate the plugin
         */
        public static function activate()
        {
            // Do nothing
        } // END public static function activate

        /**
         * Deactivate the plugin
         */
        public static function deactivate()
        {
            // Do nothing
        } // END public static function deactivate
    } // END class FB_Like_Bttn
} // END if(!class_exists('FB_Like_Bttn'))

if(class_exists('FB_Like_Bttn'))
{
    // Installation and uninstallation hooks
    register_activation_hook(__FILE__, array('FB_Like_Bttn', 'activate'));
    register_deactivation_hook(__FILE__, array('FB_Like_Bttn', 'deactivate'));

    // instantiate the plugin class
    $fb_like_bttn = new FB_Like_Bttn();
		// Add a link to the settings page onto the plugin page
		if(isset($fb_like_bttn))
		{
		    // Add the settings link to the plugins page
		    function plugin_settings_link($links)
		    {
		        $settings_link = '<a href="options-general.php?page=fb_like_bttn">Stats</a>';
		        array_unshift($links, $settings_link);
		        return $links;
		    }

		    $plugin = plugin_basename(__FILE__);
		    add_filter("plugin_action_links_$plugin", 'plugin_settings_link');

        function load_like_bttn() {
          if(is_product())
            include(sprintf("%s/templates/like_bttn.php", dirname(__FILE__)));
        }
		}

    add_action('woocommerce_after_single_product_summary', 'load_like_bttn');
}
?>
