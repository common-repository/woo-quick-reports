<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://www.multidots.com
 * @since      1.0.0
 *
 * @package    Woo_Quick_Report
 * @subpackage Woo_Quick_Report/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Woo_Quick_Report
 * @subpackage Woo_Quick_Report/public
 * @author     Multidots <inquiry@multidots.in>
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Woo_Quick_Report_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $plugin_name The name of the plugin.
	 * @param string $version The version of this plugin.
	 *
	 * @since    1.0.0
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/woo-quick-report-public.js', array( 'jquery' ), $this->version, true );

	}

	/**
	 * Get Browser Detail When Place order And ADD in Post meta
	 */
	function woo_quick_report_woocommerce_payment_order( $order_id ) {

		/*********** BROWSER CHECK **********/
		$u_agent = filter_input( INPUT_SERVER, 'HTTP_USER_AGENT', FILTER_SANITIZE_STRING );
		$bname   = '';
		if ( preg_match( '/MSIE/i', $u_agent ) &&
		     ( ! preg_match( '/Opera/i', $u_agent ) || ! preg_match( '/OPT/i', $u_agent ) ) ||
		     preg_match( '~Trident/7.0; rv:11.0~', $u_agent ) ) {
			$bname = 'Internet Explorer';
		} elseif ( preg_match( '/Firefox/i', $u_agent ) || preg_match( '/FxiOS/i', $u_agent ) ) {
			$bname = 'Mozilla Firefox';
		} elseif ( preg_match( '/Edge/i', $u_agent ) || preg_match( '/EdgiOS/i', $u_agent ) ) {
			$bname = 'Microsoft Edge';
		} elseif ( preg_match( '/Chrome/i', $u_agent ) || preg_match( '/CriOS/i', $u_agent ) ) {
			$bname = 'Google Chrome';
		} elseif ( preg_match( '/Safari/i', $u_agent ) ) {
			$bname = 'Safari';
		} elseif ( preg_match( '/Opera/i', $u_agent ) || preg_match( '/OPT/i', $u_agent ) ) {
			$bname = 'Opera';
		} elseif ( preg_match( '/Netscape/i', $u_agent ) ) {
			$bname = 'Netscape';
		}

		if ( '' !== $bname ) {
			update_post_meta( $order_id, '_order_browser', $bname );
		}

		/*********** ORIGIN CHECK **********/
		$get_http_referer = filter_input( INPUT_SERVER, 'HTTP_REFERER', FILTER_SANITIZE_STRING );
		$get_server_name  = filter_input( INPUT_SERVER, 'SERVER_NAME', FILTER_SANITIZE_STRING );
		if ( isset( $get_http_referer ) && ! empty( $get_http_referer ) ) {
			$origin_site = $get_http_referer;
			$site_url    = $get_server_name;
			if ( preg_match( '/' . $site_url . '/', $origin_site ) ) {
				$origin = 'Direct';
			} else {
				$origin = $origin_site;
			}
			if ( '' !== $origin ) {
				update_post_meta( $order_id, '_order_origin', $origin );
			}
		}

		/*********** DEVICE CHECK **********/
		$is_proxy      = '';
		$proxy_headers = array(
			'HTTP_VIA',
			'VIA',
			'Proxy-Connection',
			'HTTP_X_FORWARDED_FOR',
			'HTTP_FORWARDED_FOR',
			'HTTP_X_FORWARDED',
			'HTTP_FORWARDED',
			'HTTP_CLIENT_IP',
			'HTTP_FORWARDED_FOR_IP',
			'X-PROXY-ID',
			'MT-PROXY-ID',
			'X-TINYPROXY',
			'X_FORWARDED_FOR',
			'FORWARDED_FOR',
			'X_FORWARDED',
			'FORWARDED',
			'CLIENT-IP',
			'CLIENT_IP',
			'PROXY-AGENT',
			'HTTP_X_CLUSTER_CLIENT_IP',
			'FORWARDED_FOR_IP',
			'HTTP_PROXY_CONNECTION'
		);

		if ( ! empty( $proxy_headers ) && is_array( $proxy_headers ) ) {
			foreach ( $proxy_headers as $header ) {
				if ( isset( $_SERVER[ $header ] ) && ! empty( $_SERVER[ $header ] ) ) {
					$is_proxy = 'yes';
					break;
				}
			}
		}

		if ( '' === $is_proxy ) {
			$device = $this->wqr_get_system_info( $u_agent );
			$device = $device['os'];
		} else {
			$device = 'Proxy';
		}

		if ( '' !== $device ) {
			update_post_meta( $order_id, '_order_device', $device );
		}

		/**
		 * Freshen up the cache key whenever a new order is placed.
		 */
		wp_cache_delete( "wqr_get_order_ids" );

	}

	/**
	 * Function to detect current device.
	 *
	 * @param $u_agent
	 *
	 * @return array
	 */
	function wqr_get_system_info( $user_agent ) {

		$os_platform = "Unknown OS Platform";
		$os_array = array(
			'/windows phone 8/i'    => 'Windows Phone 8',
			'/windows phone os 7/i' => 'Windows Phone 7',
			'/windows nt 10.0/i'    => 'Windows 10',
			'/windows nt 6.3/i'     => 'Windows 8.1',
			'/windows nt 6.2/i'     => 'Windows 8',
			'/windows nt 6.1/i'     => 'Windows 7',
			'/windows nt 6.0/i'     => 'Windows Vista',
			'/windows nt 5.2/i'     => 'Windows Server 2003/XP x64',
			'/windows nt 5.1/i'     => 'Windows XP',
			'/windows xp/i'         => 'Windows XP',
			'/windows nt 5.0/i'     => 'Windows 2000',
			'/windows me/i'         => 'Windows ME',
			'/win98/i'              => 'Windows 98',
			'/win95/i'              => 'Windows 95',
			'/win16/i'              => 'Windows 3.11',
			'/macintosh|mac os x/i' => 'Mac OS X',
			'/mac_powerpc/i'        => 'Mac OS 9',
			'/linux/i'              => 'Linux',
			'/ubuntu/i'             => 'Ubuntu',
			'/iphone/i'             => 'iPhone',
			'/ipod/i'               => 'iPod',
			'/ipad/i'               => 'iPad',
			'/android/i'            => 'Android',
			'/blackberry/i'         => 'BlackBerry',
			'/webos/i'              => 'Mobile'
		);

		$found       = false;
		$device      = '';
		foreach ( $os_array as $regex => $value ) {
			if ( $found ) {
				break;
			} else if ( preg_match( $regex, $user_agent ) ) {
				$os_platform = $value;
				$device      = ! preg_match( '/(windows|mac|linux|ubuntu)/i', $os_platform )
					? 'MOBILE' : ( preg_match( '/phone/i', $os_platform ) ? 'MOBILE' : 'SYSTEM' );
			}
		}
		$device = ! $device ? 'SYSTEM' : $device;

		return array( 'os' => $os_platform, 'device' => $device );

	}
}
