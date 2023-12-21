<?php
/**
 * Orders
 *
 * Shows orders on the account page.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/orders.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.7.0
 */
use radiustheme\Metro\WC_Functions;
defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_account_orders', $has_orders ); 
?>

<?php if ( $has_orders ) : ?>
	<nav>
		<div class="nav nav-tabs nav-fill" id="nav-tab" role="tablist">
			<a class="nav-item nav-link active" id="nav-pending-tab" data-toggle="tab" href="#nav-pending" role="tab" aria-controls="nav-pending" aria-selected="true"><img width="50px" src="<?php echo get_stylesheet_directory_uri() ?>/assets/icons/pending.png"><p>Pending Payment</p></a>
			<a class="nav-item nav-link" id="nav-hold-tab" data-toggle="tab" href="#nav-hold" role="tab" aria-controls="nav-hold" aria-selected="false"><img width="50px" src="<?php echo get_stylesheet_directory_uri() ?>/assets/icons/hold.png"><p>On Hold</p></a>
			<a class="nav-item nav-link" id="nav-processing-tab" data-toggle="tab" href="#nav-processing" role="tab" aria-controls="nav-processing" aria-selected="false"><img width="50px" src="<?php echo get_stylesheet_directory_uri() ?>/assets/icons/processing.png"><p>Processing</p></a>
			<a class="nav-item nav-link" id="nav-completed-tab" data-toggle="tab" href="#nav-completed" role="tab" aria-controls="nav-completed" aria-selected="false"><img width="50px" src="<?php echo get_stylesheet_directory_uri() ?>/assets/icons/completed.png"><p>Completed</p></a>
			<a class="nav-item nav-link" id="nav-cancel-tab" data-toggle="tab" href="#nav-cancel" role="tab" aria-controls="nav-cancel" aria-selected="false"><img width="50px" src="<?php echo get_stylesheet_directory_uri() ?>/assets/icons/cancel.png"><p>Canceled</p></a>
			<a class="nav-item nav-link" id="nav-refund-tab" data-toggle="tab" href="#nav-refund" role="tab" aria-controls="nav-refund" aria-selected="false"><img width="50px" src="<?php echo get_stylesheet_directory_uri() ?>/assets/icons/refund.png"><p>Refunded</p></a>
			<a class="nav-item nav-link" id="nav-fail-tab" data-toggle="tab" href="#nav-fail" role="tab" aria-controls="nav-fail" aria-selected="false"><img width="50px" src="<?php echo get_stylesheet_directory_uri() ?>/assets/icons/failed.png"><p>Failed</p></a>
		</div>
	</nav>
	<div class="tab-content" id="nav-tabContent">
		<div class="tab-pane fade show active" id="nav-pending" role="tabpanel" aria-labelledby="nav-pending-tab">
				<table class="woocommerce-orders-table woocommerce-MyAccount-orders shop_table shop_table_responsive my_account_orders account-orders-table">
					<!-- <thead>
						<tr>
							<th colspan="6" class="title">
							<h2> My Order</h2>
							</th>
						</tr>
					</thead> -->
					<thead>
						<tr>
							<?php foreach ( wc_get_account_orders_columns() as $column_id => $column_name ) : ?>
								<th class="woocommerce-orders-table__header woocommerce-orders-table__header-<?php echo esc_attr( $column_id ); ?>" id="theadrowstyle"><span class="nobr"><?php echo esc_html( $column_name ); ?></span></th>					
							<?php endforeach; ?>
						</tr>
					</thead>
					<tbody>
						<?php
						
						foreach ( $customer_orders->orders as $customer_order ) {
							$order = wc_get_order( $customer_order);
							$items = $order->get_items();
							$item_count = $order->get_item_count() - $order->get_item_count_refunded();
							$order_status = $order->status;
							if($order_status == 'pending'){
							?>
							<tr class="woocommerce-orders-table__row woocommerce-orders-table__row--status-<?php echo esc_attr( $order->get_status() ); ?> order">
								<?php foreach ( wc_get_account_orders_columns() as $column_id => $column_name ) : ?>
									<td class="woocommerce-orders-table__cell woocommerce-orders-table__cell-<?php echo esc_attr( $column_id ); ?>" data-title="<?php echo esc_attr( $column_name ); ?>" id="tbodyrowstyle">
										<?php if ( has_action( 'woocommerce_my_account_my_orders_column_' . $column_id ) ) : ?>
											<?php do_action( 'woocommerce_my_account_my_orders_column_' . $column_id, $order ); ?>
										<?php elseif ('order-image' == $column_id) : ?>
											<?php 
												foreach($items as $item) {  
													$product_id = $item['product_id'];
												}
												$image = wp_get_attachment_image_src( get_post_thumbnail_id($product_id), 'single-post-thumbnail' );
												echo '<img src="'.$image[0].'" alt="" width="100px">';
											?>

										<?php elseif ( 'order-number' === $column_id ) : ?>
											<a href="<?php echo esc_url( $order->get_view_order_url() ); ?>">
												<?php echo esc_html( _x( '#', 'hash before order number', 'woocommerce' ) . $order->get_order_number() ); ?>
											</a>

										<?php elseif ( 'order-quantity' === $column_id ) : ?>
											<?php
											echo wp_kses_post( sprintf( _n( '%2$s', '%2$s', $item_count, 'woocommerce' ), $order->get_formatted_order_total(), $item_count ) );
											?>
									
										<?php elseif ( 'order-total' === $column_id ) : ?>
											<?php
											/* translators: 1: formatted order total 2: total order items */
											echo wp_kses_post( sprintf( _n( '%1$s', '%1$s', 'woocommerce' ), $order->get_formatted_order_total()) );
											?>

										<?php elseif ( 'order-date' === $column_id ) : ?>
											<time datetime="<?php echo esc_attr( $order->get_date_created()->date( 'c' ) ); ?>"><?php echo esc_html( wc_format_datetime( $order->get_date_created() ) ); ?></time>

										<?php elseif ( 'order-status' === $column_id ) : ?>
											<?php echo esc_html( wc_get_order_status_name( $order->get_status()) ); ?>

										<?php elseif ('order-download' === $column_id ) : ?>

											<?php
												// if( $order->get_status() == 'completed')
												// {
													echo '<a href="'.site_url().'/order-invoice/?id='.$order->get_order_number().'" target="_blank">Download</a>';
												// } else
												// {
												// 	echo '---';
												// }
											?>

										<?php elseif ( 'order-actions' === $column_id ) : ?>
											<?php
											$actions = wc_get_account_orders_actions( $order );

											if ( ! empty( $actions ) ) {
												foreach ( $actions as $key => $action ) { // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
													echo '<a href="' . esc_url( $action['url'] ) . '" class="woocommerce-button button ' . sanitize_html_class( $key ) . '">' . esc_html( $action['name'] ) . '</a>';
												}
											}
											?>
										<?php endif; ?>
									</td>
								<?php endforeach; ?>
							</tr>
							<?php 
							}
						}
					  ?>
					</tbody>
			</table>
		</div>
		<div class="tab-pane fade" id="nav-hold" role="tabpanel" aria-labelledby="nav-hold-tab">
				<table class="woocommerce-orders-table woocommerce-MyAccount-orders shop_table shop_table_responsive my_account_orders account-orders-table">
					<thead>
						<tr>
							<?php foreach ( wc_get_account_orders_columns() as $column_id => $column_name ) : ?>
								<th class="woocommerce-orders-table__header woocommerce-orders-table__header-<?php echo esc_attr( $column_id ); ?>" id="theadrowstyle"><span class="nobr"><?php echo esc_html( $column_name ); ?></span></th>					
							<?php endforeach; ?>
						</tr>
					</thead>
					<tbody>
						<?php
						
						foreach ( $customer_orders->orders as $customer_order ) {
							$order = wc_get_order( $customer_order);
							$items = $order->get_items();
							$item_count = $order->get_item_count() - $order->get_item_count_refunded();
							$order_status = $order->status;
							if($order_status == 'on-hold'){
							?>
							<tr class="woocommerce-orders-table__row woocommerce-orders-table__row--status-<?php echo esc_attr( $order->get_status() ); ?> order">
								<?php foreach ( wc_get_account_orders_columns() as $column_id => $column_name ) : ?>
									<td class="woocommerce-orders-table__cell woocommerce-orders-table__cell-<?php echo esc_attr( $column_id ); ?>" data-title="<?php echo esc_attr( $column_name ); ?>" id="tbodyrowstyle">
										<?php if ( has_action( 'woocommerce_my_account_my_orders_column_' . $column_id ) ) : ?>
											<?php do_action( 'woocommerce_my_account_my_orders_column_' . $column_id, $order ); ?>

										<?php elseif ('order-image' == $column_id) : ?>
											<?php 
												foreach($items as $item) {  
													$product_id = $item['product_id'];
												}
												$image = wp_get_attachment_image_src( get_post_thumbnail_id($product_id), 'single-post-thumbnail' );
												echo '<img src="'.$image[0].'" alt="" width="100px">';
											?>

										<?php elseif ( 'order-number' === $column_id ) : ?>
											<a href="<?php echo esc_url( $order->get_view_order_url() ); ?>">
												<?php echo esc_html( _x( '#', 'hash before order number', 'woocommerce' ) . $order->get_order_number() ); ?>
											</a>

										<?php elseif ( 'order-quantity' === $column_id ) : ?>
											<?php
											echo wp_kses_post( sprintf( _n( '%2$s', '%2$s', $item_count, 'woocommerce' ), $order->get_formatted_order_total(), $item_count ) );
											?>
									
										<?php elseif ( 'order-total' === $column_id ) : ?>
											<?php
											/* translators: 1: formatted order total 2: total order items */
											echo wp_kses_post( sprintf( _n( '%1$s', '%1$s', 'woocommerce' ), $order->get_formatted_order_total()) );
											?>

										<?php elseif ( 'order-date' === $column_id ) : ?>
											<time datetime="<?php echo esc_attr( $order->get_date_created()->date( 'c' ) ); ?>"><?php echo esc_html( wc_format_datetime( $order->get_date_created() ) ); ?></time>

										<?php elseif ( 'order-status' === $column_id ) : ?>
											<?php echo esc_html( wc_get_order_status_name( $order->get_status()) ); ?>

										<?php elseif ('order-download' === $column_id ) : ?>

											<?php
												// if( $order->get_status() == 'completed')
												// {
													echo '<a href="'.site_url().'/order-invoice/?id='.$order->get_order_number().'" target="_blank">Download</a>';
												// } else
												// {
												// 	echo '---';
												// }
											?>

										<?php elseif ( 'order-actions' === $column_id ) : ?>
											<?php
											$actions = wc_get_account_orders_actions( $order );

											if ( ! empty( $actions ) ) {
												foreach ( $actions as $key => $action ) { // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
													echo '<a href="' . esc_url( $action['url'] ) . '" class="woocommerce-button button ' . sanitize_html_class( $key ) . '">' . esc_html( $action['name'] ) . '</a>';
												}
											}
											?>
										<?php endif; ?>
									</td>
								<?php endforeach; ?>
							</tr>
							<?php 
							}
						}
					  ?>
					</tbody>
			</table>
		</div>
		<div class="tab-pane fade" id="nav-processing" role="tabpanel" aria-labelledby="nav-processing-tab">
				<table class="woocommerce-orders-table woocommerce-MyAccount-orders shop_table shop_table_responsive my_account_orders account-orders-table">
					<thead>
						<tr>
							<?php foreach ( wc_get_account_orders_columns() as $column_id => $column_name ) : ?>
								<th class="woocommerce-orders-table__header woocommerce-orders-table__header-<?php echo esc_attr( $column_id ); ?>" id="theadrowstyle"><span class="nobr"><?php echo esc_html( $column_name ); ?></span></th>					
							<?php endforeach; ?>
						</tr>
					</thead>
					<tbody>
						<?php
						
						foreach ( $customer_orders->orders as $customer_order ) {
							$order = wc_get_order( $customer_order);
							$items = $order->get_items();
							$item_count = $order->get_item_count() - $order->get_item_count_refunded();
							$order_status = $order->status;
							if($order_status == 'processing'){
							?>
							<tr class="woocommerce-orders-table__row woocommerce-orders-table__row--status-<?php echo esc_attr( $order->get_status() ); ?> order">
								<?php foreach ( wc_get_account_orders_columns() as $column_id => $column_name ) : ?>
									<td class="woocommerce-orders-table__cell woocommerce-orders-table__cell-<?php echo esc_attr( $column_id ); ?>" data-title="<?php echo esc_attr( $column_name ); ?>" id="tbodyrowstyle">
										<?php if ( has_action( 'woocommerce_my_account_my_orders_column_' . $column_id ) ) : ?>
											<?php do_action( 'woocommerce_my_account_my_orders_column_' . $column_id, $order ); ?>

										<?php elseif ('order-image' == $column_id) : ?>
											<?php 
												foreach($items as $item) {  
													$product_id = $item['product_id'];
												}
												$image = wp_get_attachment_image_src( get_post_thumbnail_id($product_id), 'single-post-thumbnail' );
												echo '<img src="'.$image[0].'" alt="" width="100px">';
											?>

										<?php elseif ( 'order-number' === $column_id ) : ?>
											<a href="<?php echo esc_url( $order->get_view_order_url() ); ?>">
												<?php echo esc_html( _x( '#', 'hash before order number', 'woocommerce' ) . $order->get_order_number() ); ?>
											</a>

										<?php elseif ( 'order-quantity' === $column_id ) : ?>
											<?php
											echo wp_kses_post( sprintf( _n( '%2$s', '%2$s', $item_count, 'woocommerce' ), $order->get_formatted_order_total(), $item_count ) );
											?>
									
										<?php elseif ( 'order-total' === $column_id ) : ?>
											<?php
											/* translators: 1: formatted order total 2: total order items */
											echo wp_kses_post( sprintf( _n( '%1$s', '%1$s', 'woocommerce' ), $order->get_formatted_order_total()) );
											?>

										<?php elseif ( 'order-date' === $column_id ) : ?>
											<time datetime="<?php echo esc_attr( $order->get_date_created()->date( 'c' ) ); ?>"><?php echo esc_html( wc_format_datetime( $order->get_date_created() ) ); ?></time>

										<?php elseif ( 'order-status' === $column_id ) : ?>
											<?php echo esc_html( wc_get_order_status_name( $order->get_status()) ); ?>

										<?php elseif ('order-download' === $column_id ) : ?>

											<?php
												// if( $order->get_status() == 'completed')
												// {
													echo '<a href="'.site_url().'/order-invoice/?id='.$order->get_order_number().'" target="_blank">Download</a>';
												// } else
												// {
												// 	echo '---';
												// }
											?>

										<?php elseif ( 'order-actions' === $column_id ) : ?>
											<?php
											$actions = wc_get_account_orders_actions( $order );

											if ( ! empty( $actions ) ) {
												foreach ( $actions as $key => $action ) { // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
													echo '<a href="' . esc_url( $action['url'] ) . '" class="woocommerce-button button ' . sanitize_html_class( $key ) . '">' . esc_html( $action['name'] ) . '</a>';
												}
											}
											?>
										<?php endif; ?>
									</td>
								<?php endforeach; ?>
							</tr>
							<?php 
							}
						}
					  ?>
					</tbody>
			</table>
		</div>
		<div class="tab-pane fade" id="nav-completed" role="tabpanel" aria-labelledby="nav-completed-tab">
				<table class="woocommerce-orders-table woocommerce-MyAccount-orders shop_table shop_table_responsive my_account_orders account-orders-table">
					<thead>
						<tr>
							<?php foreach ( wc_get_account_orders_columns() as $column_id => $column_name ) : ?>
								<th class="woocommerce-orders-table__header woocommerce-orders-table__header-<?php echo esc_attr( $column_id ); ?>" id="theadrowstyle"><span class="nobr"><?php echo esc_html( $column_name ); ?></span></th>					
							<?php endforeach; ?>
						</tr>
					</thead>
					<tbody>
						<?php
						
						foreach ( $customer_orders->orders as $customer_order ) {
							$order = wc_get_order( $customer_order);
							$items = $order->get_items();
							$item_count = $order->get_item_count() - $order->get_item_count_refunded();
							$order_status = $order->status;
							if($order_status == 'completed'){
							?>
							<tr class="woocommerce-orders-table__row woocommerce-orders-table__row--status-<?php echo esc_attr( $order->get_status() ); ?> order">
								<?php foreach ( wc_get_account_orders_columns() as $column_id => $column_name ) : ?>
									<td class="woocommerce-orders-table__cell woocommerce-orders-table__cell-<?php echo esc_attr( $column_id ); ?>" data-title="<?php echo esc_attr( $column_name ); ?>" id="tbodyrowstyle">
										<?php if ( has_action( 'woocommerce_my_account_my_orders_column_' . $column_id ) ) : ?>
											<?php do_action( 'woocommerce_my_account_my_orders_column_' . $column_id, $order ); ?>

										<?php elseif ('order-image' == $column_id) : ?>
											<?php 
												foreach($items as $item) {  
													$product_id = $item['product_id'];
												}
												$image = wp_get_attachment_image_src( get_post_thumbnail_id($product_id), 'single-post-thumbnail' );
												echo '<img src="'.$image[0].'" alt="" width="100px">';
											?>

										<?php elseif ( 'order-number' === $column_id ) : ?>
											<a href="<?php echo esc_url( $order->get_view_order_url() ); ?>">
												<?php echo esc_html( _x( '#', 'hash before order number', 'woocommerce' ) . $order->get_order_number() ); ?>
											</a>

										<?php elseif ( 'order-quantity' === $column_id ) : ?>
											<?php
											echo wp_kses_post( sprintf( _n( '%2$s', '%2$s', $item_count, 'woocommerce' ), $order->get_formatted_order_total(), $item_count ) );
											?>
									
										<?php elseif ( 'order-total' === $column_id ) : ?>
											<?php
											/* translators: 1: formatted order total 2: total order items */
											echo wp_kses_post( sprintf( _n( '%1$s', '%1$s', 'woocommerce' ), $order->get_formatted_order_total()) );
											?>

										<?php elseif ( 'order-date' === $column_id ) : ?>
											<time datetime="<?php echo esc_attr( $order->get_date_created()->date( 'c' ) ); ?>"><?php echo esc_html( wc_format_datetime( $order->get_date_created() ) ); ?></time>

										<?php elseif ( 'order-status' === $column_id ) : ?>
											<?php echo esc_html( wc_get_order_status_name( $order->get_status()) ); ?>

										<?php elseif ('order-download' === $column_id ) : ?>

											<?php
												// if( $order->get_status() == 'completed')
												// {
													echo '<a href="'.site_url().'/order-invoice/?id='.$order->get_order_number().'" target="_blank">Download</a>';
												// } else
												// {
												// 	echo '---';
												// }
											?>

										<?php elseif ( 'order-actions' === $column_id ) : ?>
											<?php
											$actions = wc_get_account_orders_actions( $order );

											if ( ! empty( $actions ) ) {
												foreach ( $actions as $key => $action ) { // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
													echo '<a href="' . esc_url( $action['url'] ) . '" class="woocommerce-button button ' . sanitize_html_class( $key ) . '">' . esc_html( $action['name'] ) . '</a>';
												}
											}
											?>
										<?php endif; ?>
									</td>
								<?php endforeach; ?>
							</tr>
							<?php 
							}
						}
					  ?>
					</tbody>
			</table>
		</div>
		<div class="tab-pane fade" id="nav-cancel" role="tabpanel" aria-labelledby="nav-cancel-tab">
				<table class="woocommerce-orders-table woocommerce-MyAccount-orders shop_table shop_table_responsive my_account_orders account-orders-table">
					<thead>
						<tr>
							<?php foreach ( wc_get_account_orders_columns() as $column_id => $column_name ) : ?>
								<th class="woocommerce-orders-table__header woocommerce-orders-table__header-<?php echo esc_attr( $column_id ); ?>" id="theadrowstyle"><span class="nobr"><?php echo esc_html( $column_name ); ?></span></th>					
							<?php endforeach; ?>
						</tr>
					</thead>
					<tbody>
						<?php
						
						foreach ( $customer_orders->orders as $customer_order ) {
							$order = wc_get_order( $customer_order);
							$items = $order->get_items();
							$item_count = $order->get_item_count() - $order->get_item_count_refunded();
							$order_status = $order->status;
							if($order_status == 'cancelled'){
							?>
							<tr class="woocommerce-orders-table__row woocommerce-orders-table__row--status-<?php echo esc_attr( $order->get_status() ); ?> order">
								<?php foreach ( wc_get_account_orders_columns() as $column_id => $column_name ) : ?>
									<td class="woocommerce-orders-table__cell woocommerce-orders-table__cell-<?php echo esc_attr( $column_id ); ?>" data-title="<?php echo esc_attr( $column_name ); ?>" id="tbodyrowstyle">
										<?php if ( has_action( 'woocommerce_my_account_my_orders_column_' . $column_id ) ) : ?>
											<?php do_action( 'woocommerce_my_account_my_orders_column_' . $column_id, $order ); ?>
										
										<?php elseif ('order-image' == $column_id) : ?>
											<?php 
												foreach($items as $item) {  
													$product_id = $item['product_id'];
												}
												$image = wp_get_attachment_image_src( get_post_thumbnail_id($product_id), 'single-post-thumbnail' );
												echo '<img src="'.$image[0].'" alt="" width="100px">';
											?>

										<?php elseif ( 'order-number' === $column_id ) : ?>
											<a href="<?php echo esc_url( $order->get_view_order_url() ); ?>">
												<?php echo esc_html( _x( '#', 'hash before order number', 'woocommerce' ) . $order->get_order_number() ); ?>
											</a>

										<?php elseif ( 'order-quantity' === $column_id ) : ?>
											<?php
											echo wp_kses_post( sprintf( _n( '%2$s', '%2$s', $item_count, 'woocommerce' ), $order->get_formatted_order_total(), $item_count ) );
											?>
									
										<?php elseif ( 'order-total' === $column_id ) : ?>
											<?php
											/* translators: 1: formatted order total 2: total order items */
											echo wp_kses_post( sprintf( _n( '%1$s', '%1$s', 'woocommerce' ), $order->get_formatted_order_total()) );
											?>

										<?php elseif ( 'order-date' === $column_id ) : ?>
											<time datetime="<?php echo esc_attr( $order->get_date_created()->date( 'c' ) ); ?>"><?php echo esc_html( wc_format_datetime( $order->get_date_created() ) ); ?></time>

										<?php elseif ( 'order-status' === $column_id ) : ?>
											<?php echo esc_html( wc_get_order_status_name( $order->get_status()) ); ?>

										<?php elseif ('order-download' === $column_id ) : ?>

											<?php
												// if( $order->get_status() == 'completed')
												// {
													echo '<a href="'.site_url().'/order-invoice/?id='.$order->get_order_number().'" target="_blank">Download</a>';
												// } else
												// {
												// 	echo '---';
												// }
											?>

										<?php elseif ( 'order-actions' === $column_id ) : ?>
											<?php
											$actions = wc_get_account_orders_actions( $order );

											if ( ! empty( $actions ) ) {
												foreach ( $actions as $key => $action ) { // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
													echo '<a href="' . esc_url( $action['url'] ) . '" class="woocommerce-button button ' . sanitize_html_class( $key ) . '">' . esc_html( $action['name'] ) . '</a>';
												}
											}
											?>
										<?php endif; ?>
									</td>
								<?php endforeach; ?>
							</tr>
							<?php 
							}
						}
					  ?>
					</tbody>
			</table>
		</div>
		<div class="tab-pane fade" id="nav-refund" role="tabpanel" aria-labelledby="nav-refund-tab">
				<table class="woocommerce-orders-table woocommerce-MyAccount-orders shop_table shop_table_responsive my_account_orders account-orders-table">
					<thead>
						<tr>
							<?php foreach ( wc_get_account_orders_columns() as $column_id => $column_name ) : ?>
								<th class="woocommerce-orders-table__header woocommerce-orders-table__header-<?php echo esc_attr( $column_id ); ?>" id="theadrowstyle"><span class="nobr"><?php echo esc_html( $column_name ); ?></span></th>					
							<?php endforeach; ?>
						</tr>
					</thead>
					<tbody>
						<?php
						
						foreach ( $customer_orders->orders as $customer_order ) {
							$order = wc_get_order( $customer_order); 
							$item_count = $order->get_item_count() - $order->get_item_count_refunded();
							$order_status = $order->status;
							if($order_status == 'refunded'){
							?>
							<tr class="woocommerce-orders-table__row woocommerce-orders-table__row--status-<?php echo esc_attr( $order->get_status() ); ?> order">
								<?php foreach ( wc_get_account_orders_columns() as $column_id => $column_name ) : ?>
									<td class="woocommerce-orders-table__cell woocommerce-orders-table__cell-<?php echo esc_attr( $column_id ); ?>" data-title="<?php echo esc_attr( $column_name ); ?>" id="tbodyrowstyle">
										<?php if ( has_action( 'woocommerce_my_account_my_orders_column_' . $column_id ) ) : ?>
											<?php do_action( 'woocommerce_my_account_my_orders_column_' . $column_id, $order ); ?>

										<?php elseif ('order-image' == $column_id) : ?>
											<?php 
												foreach($items as $item) {  
													$product_id = $item['product_id'];
												}
												$image = wp_get_attachment_image_src( get_post_thumbnail_id($product_id), 'single-post-thumbnail' );
												echo '<img src="'.$image[0].'" alt="" width="100px">';
											?>

										<?php elseif ( 'order-number' === $column_id ) : ?>
											<a href="<?php echo esc_url( $order->get_view_order_url() ); ?>">
												<?php echo esc_html( _x( '#', 'hash before order number', 'woocommerce' ) . $order->get_order_number() ); ?>
											</a>

										<?php elseif ( 'order-quantity' === $column_id ) : ?>
											<?php
											echo wp_kses_post( sprintf( _n( '%2$s', '%2$s', $item_count, 'woocommerce' ), $order->get_formatted_order_total(), $item_count ) );
											?>
									
										<?php elseif ( 'order-total' === $column_id ) : ?>
											<?php
											/* translators: 1: formatted order total 2: total order items */
											echo wp_kses_post( sprintf( _n( '%1$s', '%1$s', 'woocommerce' ), $order->get_formatted_order_total()) );
											?>

										<?php elseif ( 'order-date' === $column_id ) : ?>
											<time datetime="<?php echo esc_attr( $order->get_date_created()->date( 'c' ) ); ?>"><?php echo esc_html( wc_format_datetime( $order->get_date_created() ) ); ?></time>

										<?php elseif ( 'order-status' === $column_id ) : ?>
											<?php echo esc_html( wc_get_order_status_name( $order->get_status()) ); ?>

										<?php elseif ('order-download' === $column_id ) : ?>

											<?php
												// if( $order->get_status() == 'completed')
												// {
													echo '<a href="'.site_url().'/order-invoice/?id='.$order->get_order_number().'" target="_blank">Download</a>';
												// } else
												// {
												// 	echo '---';
												// }
											?>

										<?php elseif ( 'order-actions' === $column_id ) : ?>
											<?php
											$actions = wc_get_account_orders_actions( $order );

											if ( ! empty( $actions ) ) {
												foreach ( $actions as $key => $action ) { // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
													echo '<a href="' . esc_url( $action['url'] ) . '" class="woocommerce-button button ' . sanitize_html_class( $key ) . '">' . esc_html( $action['name'] ) . '</a>';
												}
											}
											?>
										<?php endif; ?>
									</td>
								<?php endforeach; ?>
							</tr>
							<?php 
							}
						}
					  ?>
					</tbody>
			</table>
		</div>
		<div class="tab-pane fade" id="nav-fail" role="tabpanel" aria-labelledby="nav-fail-tab">
				<table class="woocommerce-orders-table woocommerce-MyAccount-orders shop_table shop_table_responsive my_account_orders account-orders-table">
					<thead>
						<tr>
							<?php foreach ( wc_get_account_orders_columns() as $column_id => $column_name ) : ?>
								<th class="woocommerce-orders-table__header woocommerce-orders-table__header-<?php echo esc_attr( $column_id ); ?>" id="theadrowstyle"><span class="nobr"><?php echo esc_html( $column_name ); ?></span></th>					
							<?php endforeach; ?>
						</tr>
					</thead>
					<tbody>
						<?php
						
						foreach ( $customer_orders->orders as $customer_order ) {
							$order = wc_get_order( $customer_order);
							$items = $order->get_items();
							$item_count = $order->get_item_count() - $order->get_item_count_refunded();
							$order_status = $order->status;
							if($order_status == 'failed'){
							?>
							<tr class="woocommerce-orders-table__row woocommerce-orders-table__row--status-<?php echo esc_attr( $order->get_status() ); ?> order">
								<?php foreach ( wc_get_account_orders_columns() as $column_id => $column_name ) : ?>
									<td class="woocommerce-orders-table__cell woocommerce-orders-table__cell-<?php echo esc_attr( $column_id ); ?>" data-title="<?php echo esc_attr( $column_name ); ?>" id="tbodyrowstyle">
										<?php if ( has_action( 'woocommerce_my_account_my_orders_column_' . $column_id ) ) : ?>
											<?php do_action( 'woocommerce_my_account_my_orders_column_' . $column_id, $order ); ?>

										<?php elseif ('order-image' == $column_id) : ?>
											<?php 
												foreach($items as $item) {  
													$product_id = $item['product_id'];
												}
												$image = wp_get_attachment_image_src( get_post_thumbnail_id($product_id), 'single-post-thumbnail' );
												echo '<img src="'.$image[0].'" alt="" width="100px">';
											?>

										<?php elseif ( 'order-number' === $column_id ) : ?>
											<a href="<?php echo esc_url( $order->get_view_order_url() ); ?>">
												<?php echo esc_html( _x( '#', 'hash before order number', 'woocommerce' ) . $order->get_order_number() ); ?>
											</a>

										<?php elseif ( 'order-quantity' === $column_id ) : ?>
											<?php
											echo wp_kses_post( sprintf( _n( '%2$s', '%2$s', $item_count, 'woocommerce' ), $order->get_formatted_order_total(), $item_count ) );
											?>
									
										<?php elseif ( 'order-total' === $column_id ) : ?>
											<?php
											/* translators: 1: formatted order total 2: total order items */
											echo wp_kses_post( sprintf( _n( '%1$s', '%1$s', 'woocommerce' ), $order->get_formatted_order_total()) );
											?>

										<?php elseif ( 'order-date' === $column_id ) : ?>
											<time datetime="<?php echo esc_attr( $order->get_date_created()->date( 'c' ) ); ?>"><?php echo esc_html( wc_format_datetime( $order->get_date_created() ) ); ?></time>

										<?php elseif ( 'order-status' === $column_id ) : ?>
											<?php echo esc_html( wc_get_order_status_name( $order->get_status()) ); ?>

										<?php elseif ('order-download' === $column_id ) : ?>

											<?php
												// if( $order->get_status() == 'completed')
												// {
													echo '<a href="'.site_url().'/order-invoice/?id='.$order->get_order_number().'" target="_blank">Download</a>';
												// } else
												// {
												// 	echo '---';
												// }
											?>

										<?php elseif ( 'order-actions' === $column_id ) : ?>
											<?php
											$actions = wc_get_account_orders_actions( $order );

											if ( ! empty( $actions ) ) {
												foreach ( $actions as $key => $action ) { // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
													echo '<a href="' . esc_url( $action['url'] ) . '" class="woocommerce-button button ' . sanitize_html_class( $key ) . '">' . esc_html( $action['name'] ) . '</a>';
												}
											}
											?>
										<?php endif; ?>
									</td>
								<?php endforeach; ?>
							</tr>
							<?php 
							}
						}
					  ?>
					</tbody>
			</table>
		</div>
	</div>


	<?php do_action( 'woocommerce_before_account_orders_pagination' ); ?>
	
	<?php if ( 1 < $customer_orders->max_num_pages ) : ?>
		 <div class="woocommerce-Pagination">
			 
		    <?php
				$args = array(
					'base'          => esc_url( wc_get_endpoint_url( 'orders') ) . '%_%',
					'format'        => '%#%',
					'total'         => $customer_orders->max_num_pages,
					'current'       => $current_page,
					'show_all'      => false,
					'end_size'      => 1,
					'mid_size'      => 2,
					'prev_next'     => true,
					'prev_text'    => __('<'),
            		'next_text'    => __('>'),
					'type'          => 'list',
					'add_args'      => false,
					'add_fragment'  => ''
				);
				echo paginate_links( $args );
			?> 
		</div>
	<?php endif; ?>

<?php else : ?>
	<div class="woocommerce-message woocommerce-message--info woocommerce-Message woocommerce-Message--info woocommerce-info">
		<a class="woocommerce-Button button" href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>"><?php esc_html_e( 'Browse products', 'woocommerce' ); ?></a>
		<?php esc_html_e( 'No order has been made yet.', 'woocommerce' ); ?>
	</div>
<?php endif; ?>

<?php do_action( 'woocommerce_after_account_orders', $has_orders ); ?>
