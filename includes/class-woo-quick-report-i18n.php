<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       http://www.multidots.com
 * @since      1.0.0
 *
 * @package    Woo_Quick_Report
 * @subpackage Woo_Quick_Report/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Woo_Quick_Report
 * @subpackage Woo_Quick_Report/includes
 * @author     Multidots <inquiry@multidots.in>
 */
// Exit if accessed directly
if(!defined('ABSPATH')) {
	exit;
}

class Woo_Quick_Report_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'woo-quick-report',
			false,
			dirname(dirname(plugin_basename(__FILE__))) . '/languages/'
		);

	}


}
