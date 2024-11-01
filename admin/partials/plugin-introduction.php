<?php
if(!defined('ABSPATH')) exit;

$current_tab     = (isset($tab)) ? $tab : 'general';
$setting_tabs_wc = apply_filters(
	'woocommerce_quick_reports_intro_tabs',
	array(
		"about" => "Overview",
	)
);
?>
<div class="wrap about-wrap">
    <h1 class="wqr-intro-plugin-heading"><?php esc_html_e('Welcome to Quick Reports for WooCommerce', 'woo-quick-report'); ?></h1>
    <div class="about-text woocommerce-about-text">
		<?php esc_html_e('This shows you a quick reports of your order based on Payment Method, Shipping Method, Order Status, Browser and Devices.', 'woo-quick-report'); ?>
        <img class="version_logo_img"
             src="<?php echo esc_url(plugin_dir_url(dirname(__FILE__)) . 'images/woo-quick-reports.png'); ?>">
    </div>
    <h2 id="woo-extra-cost-tab-wrapper" class="nav-tab-wrapper">
		<?php
		if(!empty($setting_tabs_wc)) {
			foreach($setting_tabs_wc as $name => $label) {
				echo '<a  href="' . esc_url(site_url('wp-admin/index.php?page=woocommerce-quick-reports&tab=' . esc_attr($name))) . '" class="nav-tab ' . ($current_tab === $name ? 'nav-tab-active' : '') . '">' . wp_kses_post($label) . '</a>';
			}
		}
		?>
    </h2>
	<?php
	foreach($setting_tabs_wc as $setting_tabkey_wc => $setting_tabvalue) {
		switch($setting_tabkey_wc) {
			case $current_tab:
				do_action('woocommerce_quick_reports_' . $current_tab);
				break;
		}
	}
	?>
    <hr/>
</div>