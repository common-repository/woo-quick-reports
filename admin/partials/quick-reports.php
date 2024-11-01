<?php
if(!defined('ABSPATH')) exit;

$orders = wp_cache_get('wqr_get_order_ids');
if(false === $orders) {
	$query  = new WC_Order_Query(array(
		'limit' => -1,
		'return' => 'ids',
	));
	$orders = $query->get_orders();
	wp_cache_set('wqr_get_order_ids', $orders);
}
$orders_charts = array();

$orders_by_payment = $orders_by_statuses = $orders_by_shipping = $orders_by_browser = $orders_by_device = $orders_by_line_items = $orders_by_coupons = $_orders_by_payment = $_orders_by_statuses = $_orders_by_shipping = $_orders_by_browser = $_orders_by_device = $_orders_by_line_items = $_orders_by_coupons = array();
if(!empty($orders) && is_array($orders)) {
	$order_status_labels = wc_get_order_statuses();
	foreach($orders as $oid) {
		$_order = wc_get_order($oid);

		/**
		 * Skipping the order that is having some parent ID.
         * Refunded orders have parent ID, which do not have other details, so shall be skipped.
		 */
		if( 0 !== $_order->get_parent_id() ) continue;

		/*----------------------------------------------PAYMENT METHODS------------------------------------------------------*/
        $payment_method                       = $_order->get_payment_method_title();
		$orders_by_payment[$payment_method][] = $oid;

		/*----------------------------------------------BROWSER------------------------------------------------------*/
		$browser = get_post_meta($oid, '_order_browser', true);
		if(!empty($browser)) {
			$orders_by_browser[$browser][] = $oid;
		}


		/*----------------------------------------------DEVICE------------------------------------------------------*/
		$device = get_post_meta($oid, '_order_device', true);
		if(!empty($device)) {
			$orders_by_device[$device][] = $oid;
		}

		/*----------------------------------------------SHIPPING------------------------------------------------------*/
		$shipping                        = $_order->get_shipping_method();
		$shipping                        = !empty($shipping) ? $shipping : esc_html__('Without Shipping', 'woo-quick-cart');
		$orders_by_shipping[$shipping][] = $oid;

		/*----------------------------------------------STATUS------------------------------------------------------*/
		$o_status                                              = get_post_status($oid);
		$orders_by_statuses[$order_status_labels[$o_status]][] = $oid;

		/*----------------------------------------------LINE ITEMS------------------------------------------------------*/
		$line_items = $_order->get_items();
		if(!empty($line_items) && is_array($line_items)) {
			foreach($line_items as $line_item) {
				$orders_by_line_items[$line_item['name']][] = $oid;
			}
		}

		/*----------------------------------------------COUPONS------------------------------------------------------*/
		$coupons = $_order->get_coupons();
		if(!empty($coupons) && is_array($coupons)) {
			foreach($coupons as $coupon) {
				$orders_by_coupons[$coupon->get_code()][] = $oid;
			}
		}
	}

	if(!empty($orders_by_payment) && is_array($orders_by_payment)) {
		foreach($orders_by_payment as $index => $o) {
			$_orders_by_payment[] = array(
				'category' => $index,
				'value' => count($o)
			);
		}
	}

	if(!empty($orders_by_browser) && is_array($orders_by_browser)) {
		foreach($orders_by_browser as $index => $o) {
			$_orders_by_browser[] = array(
				'category' => $index,
				'value' => count($o)
			);
		}
	}

	if(!empty($orders_by_shipping) && is_array($orders_by_shipping)) {
		foreach($orders_by_shipping as $index => $o) {
			$_orders_by_shipping[] = array(
				'category' => $index,
				'value' => count($o)
			);
		}
	}

	if(!empty($orders_by_device) && is_array($orders_by_device)) {
		foreach($orders_by_device as $index => $o) {
			$_orders_by_device[] = array(
				'category' => $index,
				'value' => count($o)
			);
		}
	}

	if(!empty($orders_by_statuses) && is_array($orders_by_statuses)) {
		foreach($orders_by_statuses as $index => $o) {
			$_orders_by_statuses[] = array(
				'category' => $index,
				'value' => count($o)
			);
		}
	}

	if(!empty($orders_by_line_items) && is_array($orders_by_line_items)) {
		foreach($orders_by_line_items as $index => $o) {
			$_orders_by_line_items[] = array(
				'category' => $index,
				'value' => count($o)
			);
		}
	}

	if(!empty($orders_by_coupons) && is_array($orders_by_coupons)) {
		foreach($orders_by_coupons as $index => $o) {
			$_orders_by_coupons[] = array(
				'category' => $index,
				'value' => count($o)
			);
		}
	}

	$orders_charts = compact('_orders_by_statuses','_orders_by_payment','_orders_by_shipping','_orders_by_browser','_orders_by_device','_orders_by_line_items','_orders_by_coupons');
}
?>
<div class="wrap">
    <h2 class="wqr-main-heading"><?php esc_html_e('Quick Reports', 'woo-quick-report'); ?></h2>

    <div id="woo-chart-container">
        <h3 class="woo-description"><?php esc_html_e('Quick Reports shows you order information in one dashboard in very intuitive and easy to understand format. You will see quick order reports like device wise, browser wise, order status wise and payment method wise.', 'woo-quick-report'); ?></h3>
        <?php if(!empty($orders) && is_array($orders)) {?>
        <section class="wqr-section-tab">
            <input type="hidden" name="orders_charts" value="<?php echo esc_attr(htmlspecialchars(wp_json_encode($orders_charts))); ?>">
            <div class="wqr-row wqr-charts">
				<?php
				if(!empty($orders_by_statuses)) if(file_exists(__DIR__ . '/reports/status.php')) include __DIR__ . '/reports/status.php';
				if(!empty($orders_by_payment)) if(file_exists(__DIR__ . '/reports/payment.php')) include __DIR__ . '/reports/payment.php';
				if(!empty($orders_by_shipping)) if(file_exists(__DIR__ . '/reports/shipping.php')) include __DIR__ . '/reports/shipping.php';
				if(!empty($orders_by_browser)) if(file_exists(__DIR__ . '/reports/browser.php')) include __DIR__ . '/reports/browser.php';
				if(!empty($orders_by_device)) if(file_exists(__DIR__ . '/reports/device.php')) include __DIR__ . '/reports/device.php';
				if(!empty($orders_by_line_items)) if(file_exists(__DIR__ . '/reports/line-items.php')) include __DIR__ . '/reports/line-items.php';
				if(!empty($orders_by_coupons)) if(file_exists(__DIR__ . '/reports/coupon.php')) include __DIR__ . '/reports/coupon.php';
				?>
            </div>
        </section>
        <?php } else {?>
            <h4 class="wqr-no-charts-available"><?php esc_html_e('No charts available as there isn\'t any order placed yet!', 'woo-quick-report');?></h4>
        <?php }?>
    </div>
</div>