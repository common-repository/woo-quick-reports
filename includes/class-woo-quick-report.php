<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://www.multidots.com
 * @since      1.0.0
 *
 * @package    Woo_Quick_Report
 * @subpackage Woo_Quick_Report/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
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

class Woo_Quick_Report {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Woo_Quick_Report_Loader $loader Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $plugin_name The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $version The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->plugin_name = 'woo-quick-report';
		$this->version     = '1.0.7';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Woo_Quick_Report_Loader. Orchestrates the hooks of the plugin.
	 * - Woo_Quick_Report_i18n. Defines internationalization functionality.
	 * - Woo_Quick_Report_Admin. Defines all hooks for the admin area.
	 * - Woo_Quick_Report_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once __DIR__ . '/class-woo-quick-report-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once __DIR__ . '/class-woo-quick-report-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once __DIR__ . '/../admin/class-woo-quick-report-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once __DIR__ . '/../public/class-woo-quick-report-public.php';
		
		/**
		 * User feedback admin notice
		 */
		require_once __DIR__ . '/../includes/class-woo-quick-report-user-feedback.php';

		$this->loader = new Woo_Quick_Report_Loader();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Woo_Quick_Report_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Woo_Quick_Report_i18n();
		$this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Woo_Quick_Report_Admin($this->get_plugin_name(), $this->get_version());
		$this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'wqr_enqueue_admin_scripts_styles');
		$this->loader->add_filter('manage_edit-shop_order_columns', $plugin_admin, 'woo_quick_report_order_column');
		$this->loader->add_filter('manage_shop_order_posts_custom_column', $plugin_admin, 'woo_quick_report_order_manage_column');
		$this->loader->add_action('admin_menu', $plugin_admin, 'woo_quick_report_order_admin_menu');
		$this->loader->add_action('admin_init', $plugin_admin, 'welcome_woocommerce_quick_report_screen_do_activation_redirect');
		$this->loader->add_action('woocommerce_quick_reports_about', $plugin_admin, 'woocommerce_quick_reports_about');
		$this->loader->add_action('plugin_row_meta', $plugin_admin, 'wqr_plugin_row_meta', 10, 2);

	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @return    string    The name of the plugin.
	 * @since     1.0.0
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @return    string    The version number of the plugin.
	 * @since     1.0.0
	 */
	public function get_version() {
		return $this->version;
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Woo_Quick_Report_Public($this->get_plugin_name(), $this->get_version());
		$this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');
		$this->loader->add_action('woocommerce_thankyou', $plugin_public, 'woo_quick_report_woocommerce_payment_order');
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @return    Woo_Quick_Report_Loader    Orchestrates the hooks of the plugin.
	 * @since     1.0.0
	 */
	public function get_loader() {
		return $this->loader;
	}
}