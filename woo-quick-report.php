<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.thedotstore.com/
 * @since             1.0.0
 * @package           Woo_Quick_Report
 *
 * @wordpress-plugin
 * Plugin Name:       Quick Reports for WooCommerce
 * Plugin URI:        https://www.thedotstore.com/woocommerce-quick-reports/
 * Description:       Quick Reports shows you order information in one dashboard in very intuitive, easy to understand format which gives a quick information.You will see quick order reports like Devise wise, Browser wise, order status wise, shipping method wise and payment method wise.
 * Version:           2.7.1
 * Author:            theDotstore
 * Author URI:        https://www.thedotstore.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       woo-quick-report
 * Domain Path:       /languages
 */
// If this file is called directly, abort.
if ( !defined( 'WPINC' ) ) {
    die;
}
add_action( 'plugins_loaded', 'wqr_initialize_plugin' );
$wc_active = in_array( 'woocommerce/woocommerce.php', get_option( 'active_plugins' ), true );

if ( true === $wc_active ) {
    
    if ( !function_exists( 'wqr_fs' ) ) {
        // Create a helper function for easy SDK access.
        function wqr_fs()
        {
            global  $wqr_fs ;
            
            if ( !isset( $wqr_fs ) ) {
                // Include Freemius SDK.
                require_once dirname( __FILE__ ) . '/freemius/start.php';
                $wqr_fs = fs_dynamic_init( array(
                    'id'             => '4959',
                    'slug'           => 'woo-quick-reports',
                    'premium_slug'   => 'advanced-flat-rate-shipping-for-woocommerce-premium',
                    'type'           => 'plugin',
                    'public_key'     => 'pk_c8edc3b9b67199d1f42ebe5379b11',
                    'is_premium'     => false,
                    'premium_suffix' => 'Pro',
                    'has_addons'     => false,
                    'has_paid_plans' => false,
                    'menu'           => array(
                    'slug'       => 'wc-quick-reports',
                    'first-path' => 'admin.php?page=wc-quick-reports',
                    'contact'    => false,
                    'support'    => false,
                    'network'    => true,
                    'parent'     => array(
                    'slug' => 'woocommerce',
                ),
                ),
                    'is_live'        => true,
                ) );
            }
            
            return $wqr_fs;
        }
        
        // Init Freemius.
        wqr_fs();
        // Signal that SDK was initiated.
        do_action( 'wqr_fs_loaded' );
        wqr_fs()->add_action( 'after_uninstall', 'wqr_fs_uninstall_cleanup' );
    }
    
    /**
     * The code that runs during plugin activation.
     * This action is documented in includes/class-woo-quick-report-activator.php
     */
    function activate_woo_quick_report()
    {
        require_once __DIR__ . '/includes/class-woo-quick-report-activator.php';
        Woo_Quick_Report_Activator::activate();
    }
    
    /**
     * The code that runs during plugin deactivation.
     * This action is documented in includes/class-woo-quick-report-deactivator.php
     */
    function deactivate_woo_quick_report()
    {
        require_once __DIR__ . '/includes/class-woo-quick-report-deactivator.php';
        Woo_Quick_Report_Deactivator::deactivate();
    }
    
    register_activation_hook( __FILE__, 'activate_woo_quick_report' );
    register_deactivation_hook( __FILE__, 'deactivate_woo_quick_report' );
    /**
     * Begins execution of the plugin.
     *
     * Since everything within the plugin is registered via hooks,
     * then kicking off the plugin from this point in the file does
     * not affect the page life cycle.
     *
     * @since    1.0.0
     */
    function run_woo_quick_report()
    {
        /**
         * The core plugin class that is used to define internationalization,
         * admin-specific hooks, and public-facing site hooks.
         */
        require __DIR__ . '/includes/class-woo-quick-report.php';
        $plugin = new Woo_Quick_Report();
        $plugin->run();
    }

}

/**
 * Check plugin requirement on plugins loaded, this plugin requires WooCommerce to be installed and active.
 *
 * @since    1.0.0
 */
function wqr_initialize_plugin()
{
    $wc_active = in_array( 'woocommerce/woocommerce.php', get_option( 'active_plugins' ), true );
    
    if ( current_user_can( 'activate_plugins' ) && true !== $wc_active ) {
        add_action( 'admin_notices', 'wqr_plugin_admin_notice' );
    } else {
        run_woo_quick_report();
        add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'wqr_plugin_links' );
    }

}

/**
 * Settings link on plugin listing page.
 *
 * @since    1.0.0
 */
function wqr_plugin_links( $links )
{
    $this_plugin_links = array( '<a title="' . esc_html__( 'WC Quick Reports Settings', 'woo-quick-report' ) . '" href="' . admin_url( 'admin.php?page=wc-quick-reports' ) . '">' . esc_html__( 'Settings', 'woo-quick-report' ) . '</a>' );
    return array_merge( $links, $this_plugin_links );
}

/**
 * Show admin notice in case of WooCommerce plugin is missing.
 *
 * @since    1.0.0
 */
function wqr_plugin_admin_notice()
{
    $vpe_plugin = esc_html__( 'Quick Reports for WooCommerce', 'woo-quick-report' );
    $wc_plugin = esc_html__( 'WooCommerce', 'woo-quick-report' );
    ?>
    <div class="error">
        <p>
            <?php 
    echo  sprintf( esc_html__( '%1$s is ineffective as it requires %2$s to be installed and active.', 'woo-quick-report' ), '<strong>' . esc_html( $vpe_plugin ) . '</strong>', '<strong>' . esc_html( $wc_plugin ) . '</strong>' ) ;
    ?>
        </p>
    </div>
    <?php 
}
