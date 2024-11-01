<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://www.multidots.com
 * @since      1.0.0
 *
 * @package    Woo_Quick_Report
 * @subpackage Woo_Quick_Report/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Woo_Quick_Report
 * @subpackage Woo_Quick_Report/admin
 * @author     Multidots <inquiry@multidots.in>
 */

// Exit if accessed directly
if(!defined('ABSPATH')) exit;

class Woo_Quick_Report_Admin {

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
	 * @param string $plugin_name The name of this plugin.
	 * @param string $version The version of this plugin.
	 * @since    1.0.0
	 *
	 */
	public function __construct($plugin_name, $version) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	function wqr_enqueue_admin_scripts_styles($hook) {

		$suffix = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';
		wp_enqueue_style($this->plugin_name . '-font-awesome', plugin_dir_url(__FILE__) . 'css/font-awesome.min.css');
		wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . "css/woo-quick-report-admin{$suffix}.css");
		if(isset($hook) && 'woocommerce_page_wc-quick-reports' === $hook) {
			wp_enqueue_style('wp-pointer');
			wp_enqueue_script('wp-pointer');
			wp_enqueue_script('jquery-loader-js', plugin_dir_url(__FILE__) . 'js/loader.js', array( 'jquery' ));
			wp_enqueue_script('amcharts-core', plugin_dir_url(__FILE__) . 'js/amcharts-core.min.js');
			wp_enqueue_script('amcharts', plugin_dir_url(__FILE__) . 'js/amcharts.min.js');
			wp_enqueue_script('amcharts-animation', plugin_dir_url(__FILE__) . 'js/amcharts-animation.min.js');
			wp_enqueue_script($this->plugin_name . '-css-polyfills', plugin_dir_url(__FILE__) . 'js/css-polyfills.min.js', array( 'jquery' ), $this->version, true);
			wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . "js/woo-quick-report-admin{$suffix}.js", array( 'jquery' ), $this->version, true);
		}

	}

	/**
	 * Filter hooked to add custom column to WC Orders table in admin section.
	 *
	 * @param $columns
	 * @return array
	 */
	function woo_quick_report_order_column($columns) {

		$columns['wqr'] = esc_html__('Quick Report', 'woo-quick-report');

		return $columns;

	}

	/**
	 * Manage Browser Column
	 */
	public function woo_quick_report_order_manage_column($column) {
		global $post;

		if('wqr' === $column) {
			$gateways = WC()->payment_gateways->get_available_payment_gateways();
			$browser  = get_post_meta($post->ID, '_order_browser', true);
			$origin   = get_post_meta($post->ID, '_order_origin', true);
			$payment  = get_post_meta($post->ID, '_payment_method', true);
			$device   = get_post_meta($post->ID, '_order_device', true);


			if('Mozilla Firefox' === $browser) $browser = '<i class="fa fa-firefox"></i>';
            elseif('Google Chrome' === $browser) $browser = '<i class=" fa fa-chrome"></i>';
            elseif('Safari' === $browser) $browser = '<i class="fa fa-safari"></i>';
            elseif('Internet Explorer' === $browser) $browser = '<i class="fa fa-internet-explorer"></i>';
            elseif('Opera' === $browser) $browser = '<i class="fa fa-opera"></i>';
            elseif('Netscape' === $browser) $browser = '<i class="fa fa-netscape"></i>';
            elseif('Microsoft Edge' === $browser) $browser = '<i class="fa fa-edge"></i>';
			?>
            <ul>
				<?php if(!empty($browser)) { ?>
                    <li><?php echo sprintf(esc_html__('%1$sBrowser:%2$s %3$s', 'woo-quick-report'), '<span class="wqr-detail-heading">', '</span>', wp_kses_post($browser)); ?></li>
				<?php } ?>

				<?php if(!empty($origin)) { ?>
                    <li><?php echo sprintf(esc_html__('%1$sOrigin:%2$s %3$s', 'woo-quick-report'), '<span class="wqr-detail-heading">', '</span>', esc_html($origin)); ?></li>
				<?php } ?>

				<?php if(!empty($payment)) {
					$payment_title = (isset($gateways[$payment]->title) && !empty($gateways[$payment]->title)) ? $gateways[$payment]->title : $payment; ?>
                    <li><?php echo sprintf(esc_html__('%1$sPayment:%2$s %3$s', 'woo-quick-report'), '<span class="wqr-detail-heading">', '</span>', esc_html($payment_title)) ?></li>
				<?php } ?>

				<?php if(!empty($device)) { ?>
                    <li><?php echo sprintf(esc_html__('%1$sDevice:%2$s %3$s', 'woo-quick-report'), '<span class="wqr-detail-heading">', '</span>', esc_html($device)); ?></li>
				<?php } ?>
            </ul>
			<?php
		}

	}

	/**
	 * Add Woo Quick Reports Menu in woocommerce
	 *
	 */
	function woo_quick_report_order_admin_menu() {

		/**
		 * Plugin settings page.
		 */
		add_submenu_page(
			'woocommerce',
			esc_html__('Quick Reports for WooCommerce', 'woo-quick-report'),
			esc_html__('Quick Reports', 'woo-quick-report'),
			'manage_options',
			'wc-quick-reports',
			array(
				$this,
				'woo_quick_reports_page',
			)
		);

	}

	/**
	 * Quick reports page for this plugin.
	 */
	function woo_quick_reports_page() {

		include __DIR__ . '/partials/quick-reports.php';
	}

	/**
	 * Redirect the user to the welcome screen on plugin activation.
	 *
	 * @since    1.0.0
	 */
	public function welcome_woocommerce_quick_report_screen_do_activation_redirect() {
		if(!get_transient('_woocommerce_quick_report_welcome_screen')) {
			return;
		}

		// Delete the redirect transient
		delete_transient('_woocommerce_quick_report_welcome_screen');

		// if activating from network, or bulk
		$get_activate_multi = filter_input(INPUT_GET, 'activate-multi', FILTER_SANITIZE_STRING);
		if(is_network_admin() || isset($get_activate_multi)) {
			return;
		}
		// Redirect to quick reports welcome  page
		wp_safe_redirect(
				admin_url('admin.php?page=wc-quick-reports')
		);
		exit();

	}

	/**
	 * The template to render as soon as the plugin is activated.
	 * This is the plugin welcome screen.
	 *
	 * @since    1.0.0
	 */
	public function woocommerce_quick_reports_about() {
		?>
        <div class="changelog">
            <div class="changelog about-integrations">
                <div class="wc-feature feature-section col three-col">
                    <div>
                        <p class="woocommerce_quick_reports_overview"><?php esc_html_e('Quick Reports shows you order information in one dashboard in very intuitive, easy to understand format which gives a quick information. You will see quick order reports like device wise, browser wise, order status wise, shipping method wise and payment method wise.', 'woo-quick-report'); ?></p>
                        <p class="woocommerce_quick_reports_overview">
                            <strong><?php esc_html_e('Plugin Functionality :', 'woo-quick-report'); ?> </strong></p>
                        <div class="woocommerce_quick_reports_content_ul">
                            <ul>
                                <li><?php esc_html_e('Quick Order Reports', 'woo-quick-report'); ?></li>
                                <li><?php esc_html_e('Browser based order reports', 'woo-quick-report'); ?></li>
                                <li><?php esc_html_e('Order status based reports', 'woo-quick-report'); ?></li>
                                <li><?php esc_html_e('Device based reports', 'woo-quick-report') ?></li>
                                <li><?php esc_html_e('Payment method based reports', 'woo-quick-report'); ?></li>
                                <li><?php esc_html_e('Shipping method based reports', 'woo-quick-report'); ?></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
		<?php
	}

	/**
	 * Add custom link to the plugin row meta for dotstore support.
	 *
	 * @param $links
	 * @param $file
	 * @return array
	 */
	function wqr_plugin_row_meta($links, $file) {

		if(false !== strpos($file, 'woo-quick-report.php')) {
			$new_links = array(
				'support' => '<a href="https://www.thedotstore.com/support/" target="_blank">' . esc_html__('Support', 'woo-quick-report') . '</a>',
			);
			$links     = array_merge($links, $new_links);
		}

		return $links;
	}
}