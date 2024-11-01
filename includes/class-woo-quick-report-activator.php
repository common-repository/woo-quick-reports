<?php

/**
 * Fired during plugin activation
 *
 * @link       http://www.multidots.com
 * @since      1.0.0
 *
 * @package    Woo_Quick_Report
 * @subpackage Woo_Quick_Report/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
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

class Woo_Quick_Report_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		set_transient('_woocommerce_quick_report_welcome_screen', true, 30);
	}
}