<?php if(!defined('ABSPATH')) exit; ?>
<div class="wqr-col-half">
    <div class="wqr-blocks">
        <div class="wqr-awr-box">
            <div class="wqr-awr-title">
                <h3>
                    <i class="fa fa-laptop"></i>
					<?php esc_html_e('Device', 'woo-quick-report'); ?>
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
                            <th><?php esc_html_e('Device', 'woo-quick-report'); ?></th>
                            <th><?php esc_html_e('Count', 'woo-quick-report'); ?></th>
                        </tr>
                        </thead>
                        <tbody>
						<?php
						if(!empty($orders_by_device) && is_array($orders_by_device)) {
							foreach($orders_by_device as $device => $order_ids) {
								?>
                                <tr>
                                    <td><?php echo esc_html($device); ?></td>
                                    <td><?php echo count($order_ids); ?></td>
                                </tr>
								<?php
							}
						}
						?>
                        </tbody>
                    </table>
                </div>
                <div class="wqr-contents-block" id="wqr-awr-grid-chart">
                    <div id="wqr-wc-device-chart" class="wqr-pie-chart"></div>
                </div>
                <div class="wqr-contents-block" id="wqr-awr-bar-chart">
                    <div id="wqr-wc-device-bar-chart" class="wqr-bar-chart"></div>
                </div>
            </div>
        </div>
    </div>
</div>