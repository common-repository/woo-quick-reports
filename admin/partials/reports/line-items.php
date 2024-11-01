<?php
if(!defined('ABSPATH')) exit;

/**
 * Returns the product's thumbnail src.
 *
 * @param $product_id
 * @return false|string
 * @since    1.0.0
 * @author   Multidots <info@multidots.com>
 */
function wqr_get_product_thumbnail_src($product_id) {

	$product              = get_post($product_id);
	$product_thumbnail_id = get_post_thumbnail_id($product_id);
	$product_thumbnail    = wc_placeholder_img_src();
	if($product_thumbnail_id !== '') {
		$product_thumbnail = wp_get_attachment_url($product_thumbnail_id);
		if(false === $product_thumbnail) {
			$product_thumbnail = wc_placeholder_img_src();
		}
	} else {
		if(0 !== $product->post_parent) {
			$product_thumbnail_id = get_post_thumbnail_id($product->post_parent);
			if($product_thumbnail_id !== '') {
				$product_thumbnail = wp_get_attachment_url($product_thumbnail_id);
				if(false === $product_thumbnail) {
					$product_thumbnail = wc_placeholder_img_src();
				}
			}
		}
	}

	return $product_thumbnail;

}

?>
<div class="wqr-col-half">
    <div class="wqr-blocks">
        <div class="wqr-awr-box">
            <div class="wqr-awr-title">
                <h3>
                    <i class="fa fa-newspaper-o"></i>
					<?php esc_html_e('Selling Item', 'woo-quick-report'); ?>
                </h3>
                <div class="wqr-awr-title-icons">
                    <div class="wqr-awr-title-icon" data-table="wqr-awr-summary-chart">
                        <i class="fa fa-table"> </i>
                    </div>
                    <div class="wqr-awr-title-icon" data-table="wqr-awr-grid-chart">
                        <i class="fa fa-pie-chart"></i>
                    </div>
                    <div class="wqr-awr-title-icon" data-table="wqr-awr-bar-chart">
                        <i class="fa fa-bar-chart"></i>
                    </div>
                </div>
            </div>
            <div class="wqr-contents-block-main">
                <div class="wqr-contents-block" id="wqr-awr-summary-chart">
                    <table class="wqr-table-design wqr-order-table">
                        <thead>
                        <tr>
                            <th><?php esc_html_e('Selling Item', 'woo-quick-report'); ?></th>
                            <th><?php esc_html_e('Count', 'woo-quick-report'); ?></th>
                        </tr>
                        </thead>
                        <tbody>
						<?php
						if(!empty($orders_by_line_items) && is_array($orders_by_line_items)) {
							foreach($orders_by_line_items as $line_item => $order_ids) {
								if(function_exists('wpcom_vip_get_page_by_title')) {
									$item = wpcom_vip_get_page_by_title($line_item, OBJECT, 'product');
									if( empty( $item ) ) {
										$item = wpcom_vip_get_page_by_title($line_item, OBJECT, 'product_variation');
                                    }
								} else {
									$item = get_page_by_title($line_item, OBJECT, 'product');
									if( empty( $item ) ) {
										$item = get_page_by_title($line_item, OBJECT, 'product_variation');
									}
								}

								if( ! empty( $item ) ) {
									?>
                                    <tr>
                                        <td><a href="<?php echo esc_url(get_edit_post_link($item->ID)); ?>"><img
                                                        src="<?php echo esc_url(wqr_get_product_thumbnail_src($item->ID)); ?>"
                                                        class="wqr_profile_image" width="50"
                                                        height="50"/><?php echo esc_html($line_item); ?></a></td>
                                        <td><?php echo count($order_ids); ?></td>
                                    </tr>
									<?php
                                }
							}
						}
						?>
                        </tbody>
                    </table>
                </div>
                <div class="wqr-contents-block" id="wqr-awr-grid-chart">
                    <div id="wqr-wc-line-items-chart" class="wqr-pie-chart"></div>
                </div>
                <div class="wqr-contents-block" id="wqr-awr-bar-chart">
                    <div id="wqr-wc-line-items-bar-chart" class="wqr-bar-chart"></div>
                </div>
            </div>
        </div>
    </div>
</div>