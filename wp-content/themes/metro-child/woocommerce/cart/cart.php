<?php
/**
 * Cart Page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.8.0
 */

use radiustheme\Metro\WC_Functions;

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_cart' ); ?>


<div class="row ">
	<div class="col-lg-12">
		<div class="row  woocom-coupon-cart-71">
			<div class="col-lg-7">
				<div class="row">
					<div class="col-lg-12">
						<form class="woocommerce-cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
							<?php do_action( 'woocommerce_before_cart_table' ); ?>

								<table class="table-cart-select-all-price1 shop_table shop_table_responsive cart woocommerce-cart-form__contents" cellspacing="0">
									<thead>
										<tr>
											<th colspan="6"  class="title"><h2> My Cart</h2></th>
										</tr>
									</thead>
									<form action="" method="post">
										<thead>
											<tr>
												<!-- <th class="product-check"><input type="checkbox" id="checkall"></th> -->
												<th class="product-name"><?php esc_html_e( 'Select all', 'metro' ); ?></th>
												<th class="product-price"><?php esc_html_e( 'Price', 'metro' ); ?></th>
												<th class="product-quantity"><?php esc_html_e( 'Quantity', 'metro' ); ?></th>
												<th class="heart-icon">&nbsp;</th>
												<th class="product-remove">&nbsp;</th>
											</tr>
										</thead>
										<tbody>
											<?php do_action( 'woocommerce_before_cart_contents' ); ?>

											<?php
											foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
												$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
												$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

												if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
													$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
													?>
													<tr class="woocommerce-cart-form__cart-item <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">

														<!-- <td class="product-check"><input type="checkbox"  class='checkbox' name="languages" value=""></td> -->

														<td class="product-name" data-title="<?php esc_attr_e( 'Product', 'metro' ); ?>">
														<?php

														$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );

														if ( ! $product_permalink ) {
															echo WC_Functions::kses_img( $thumbnail ); // PHPCS: XSS ok.
														} else {
															printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $thumbnail ); // PHPCS: XSS ok.
														}

														if ( ! $product_permalink ) {
															echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) . '&nbsp;' );
														} else {
															echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', sprintf( '<h3 class="product-title"><a href="%s">%s</a></h3>', esc_url( $product_permalink ), $_product->get_name() ), $cart_item, $cart_item_key ) );
														}

														do_action( 'woocommerce_after_cart_item_name', $cart_item, $cart_item_key );

														// Meta data.
														echo wc_get_formatted_cart_item_data( $cart_item ); // PHPCS: XSS ok.

														// Backorder notification.
														if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) ) {
															echo wp_kses_post( apply_filters( 'woocommerce_cart_item_backorder_notification', '<p class="backorder_notification">' . esc_html__( 'Available on backorder', 'metro' ) . '</p>', $product_id ) );
														}
														?>
														</td>

														<td class="product-price" data-title="<?php esc_attr_e( 'Price', 'metro' ); ?>">
															<?php
																echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key ); // PHPCS: XSS ok.
															?>
														</td>

														<td class="product-quantity" data-title="<?php esc_attr_e( 'Quantity', 'metro' ); ?>">
														<?php
														if ( $_product->is_sold_individually() ) {
															$product_quantity = sprintf( '1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key );
														} else {
															$product_quantity = woocommerce_quantity_input(
																array(
																	'input_name'   => "cart[{$cart_item_key}][qty]",
																	'input_value'  => $cart_item['quantity'],
																	'max_value'    => $_product->get_max_purchase_quantity(),
																	'min_value'    => '0',
																	'product_name' => $_product->get_name(),
																),
																$_product,
																false
															);
														}

														echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key); // PHPCS: XSS ok.
														?>
														</td>
														<td id="heart-icon"> 
														<a href="http://staging.phumelectronic.com/wishlist/" title="Add to Wishlist" rel="nofollow" data-product-id="3790" data-title-after="Aleady exists in Wishlist! Click here to view Wishlist" class="rdtheme-wishlist-icon rdtheme-add-to-wishlist" data-nonce="97850a66c7">
																<i class="wishlist-icon fa fa-heart-o"></i>
																<img class="ajax-loading" alt="spinner" src="http://staging.phumelectronic.com/wp-content/themes/metro/assets/img/spinner2.gif">
															</a>
														</td>
														<td class="product-remove">
															<?php
																echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
																	'woocommerce_cart_item_remove_link',
																	sprintf(
																		'<a href="%s" class="remove" aria-label="%s" data-product_id="%s" data-product_sku="%s">&times;</a>',
																		esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
																		esc_html__( 'Remove this item', 'metro' ),
																		esc_attr( $product_id ),
																		esc_attr( $_product->get_sku() )
																	),
																	$cart_item_key
																);
															?>
														</td>
													</tr>
													<?php
												}
											}
											?>

											<?php do_action( 'woocommerce_cart_contents' ); ?>

											<tr class="cart-action">
												<td colspan="6" class="actions">

													<?php if ( wc_coupons_enabled() ) { ?>
														<div class="coupon">
															<label for="coupon_code"><?php esc_html_e( 'Get Coupon', 'metro' ); ?></label> <input type="text" name="coupon_code" class="input-text" id="coupon_code" value="" placeholder="<?php esc_attr_e( 'Coupon code', 'metro' ); ?>" /> <button type="submit" class="button" name="apply_coupon" value="<?php esc_attr_e( 'Apply coupon', 'metro' ); ?>"><?php esc_attr_e( 'Apply coupon', 'metro' ); ?></button>
															<?php do_action( 'woocommerce_cart_coupon' ); ?>
														</div>
													<?php } ?>

													<?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
												</td>
											</tr>

											<?php do_action( 'woocommerce_after_cart_contents' ); ?>
										</tbody>
									</form>
								</table>
							<?php do_action( 'woocommerce_after_cart_table' ); ?>
						</form>
					</div>
					<!--list coupon for user apply-->
					<div class="woccomerce-list-coupon-code-card">
						<div class="col-lg-12">
							<div class="coupon-title coupon-title-cart">
								<h2><i class="fa-regular fa-money-check-dollar"></i> Available Coupons:</h2>
								<p class="note-apply-coupon" ><i class="fa fa-info-circle"></i> The applied coupon won't be applied on discount products</p>
							</div>
							<div class="row">
								<!-- <div class="list-coupon-checkout list-coupon-cart"> -->
									<!-- <div class="coupon-title coupon-title-cart">
										<h2><i class="fa-regular fa-money-check-dollar"></i> Available Coupons:</h2>
									</div> -->

									<?php
										global $wpdb;
										$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
										$user_id = get_current_user_id();
										// @get coupon info
										// $arg = array(
										// 	'post_type'      => 'shop_coupon',
										// 	'posts_status'   => 'publish',
										// 	'posts_per_page' => 4,
										// 	'paged' 		 => $paged,
										// );
										$arg = array(
											'post_type'      => 'shop_coupon',
											'posts_status'   => 'publish',
											'posts_per_page' => -1,
											'meta_query' => array(
												'relation' => 'AND',
												array(
													'relation' => 'OR',
													array(
														'key'     => 'inviter_id',
														'value'   => $user_id,
														'compare' => '='
													),
													array(
														'key'     => 'inviter_id',
														'value'   => '',
														'compare' => '='
													),
													array(
														'key'     => 'inviter_id',
														'compare' => 'NOT EXISTS'
													)
												),
												array(
													'key'     => 'date_expires',
													'value'   => date("Y-m-d"),
													'compare' => '>'
												)
											)
										);

										$n=1;
										$data_coupon = new WP_Query($arg);
										if( $data_coupon ->have_posts() ) {
											while($data_coupon ->have_posts()) {
												$data_coupon->the_post();
												$code        	= get_the_title();  
												$description 	= get_the_excerpt();
												$coupon_id   	= get_the_ID();
												$usage_limit 	= get_field('usage_limit');
												$usage_count 	= get_field('usage_count');
												$discount_type 	= get_field('discount_type');
												$usage_limit_per_user 	= get_field('usage_limit_per_user');
												$expire_date		 	= get_field('date_expires');
												$minimum_amount 	  	= get_field('minimum_amount');
												$maximum_amount  	 	= get_field('maximum_amount');
												$discount_amount 	  	= get_field('coupon_amount'); 
												if($discount_type=='percent'){
													$percenttage = '%';
												}else{
													$percenttage = '$';
												}
									
												$cart_sub_total = WC()->cart->subtotal;
												if($cart_sub_total >= $minimum_amount) {
													$user_used_count = $wpdb->get_row(" SELECT COUNT(meta_value) as user_total_used FROM ph0m31e_postmeta WHERE post_id = $coupon_id AND meta_key = '_used_by' AND meta_value = $user_id");
													$total_usage_by_user = $user_used_count->user_total_used;
													// if($expire_date > date('Y-m-d')) {
														if($usage_limit > $usage_count) {
															if($usage_limit_per_user > $total_usage_by_user) {
																if($n <= 4) {
																	?>
																		<div class="col-lg-12" style="padding: 2px 0px;">
																			<div class="wocomerce-coupon-code-cart">
																				<div class="wocomerce-description-title">
																					<input style="cursor: pointer;" type="radio" class="tick_radio" id="<?php echo $code ?>" name="coupon_code_cart" value="<?php echo $code ?>">
																					<div class="description-title-coupon-code">
																						<label class="description-coupon-code-card" for="html"><?php echo $description ?></label> 
																						<!-- <label class="title-coupon-code-card" for=""><?php echo $code ?></label>	 -->
																						<p class="title-coupon-code-card"><?php echo $code ?></p>
																					</div>
																				</div>
																				<div class="wocomerce-image-cart"style="background-image: url('<?php echo site_url() ?>/wp-content/themes/metro-child/assets/images/discount.png');">
																					<div class="discount-coupon"><?php echo $discount_amount.$percenttage ?></div>
																				</div>
																			</div>
																		</div>
																	<?php

																	$n++;
																} else {
																	break;
																}
																// $result = array(
									
																// 	'code' => 200,
																// 	'status' => 'success'
																// );
															}
															else {
																// $result = array(
																// 	'code' => 500,
																// 	'status' => 'Coupon have been reached by user.'
																// );
															}
														}
														else {
															// $result = array(
															// 	'code' => 500,
															// 	'status' => 'Coupon have been reached.'
															// );
														}
													// }
													// else {
														// $result = array(
														// 	'code' => 500,
														// 	'status' => 'Coupon have been expired.'
														// );
													// }
												}
												else {
													// $result = array(
													// 	'code' => 500,
													// 	'message' => 'The minimum spend for this coupon is $'.$minimum_amount.'.'
													// );
												}
											}
										}
									?>
								
								<!-- </div> -->
							</div>	
						</div>
					</div>
				</div>
			</div>
			<div class="col-lg-4">
				<?php do_action( 'woocommerce_before_cart_collaterals' ); ?>
				<div class="cart-collaterals-wrapper">
					<div class="cart-collaterals">
						<?php
							/**
							 * Cart collaterals hook.
							 *
							 * @hooked woocommerce_cross_sell_display
							 * @hooked woocommerce_cart_totals - 10
							 */
							do_action( 'woocommerce_cart_collaterals' );
						?>
					</div>
				</div>
				<?php do_action( 'woocommerce_after_cart' ); ?>
			</div>
		</div>

	</div>

</div>

<script>
	function copyToClipboard(element) {
		var $temp = jQuery("<input>");
		jQuery("body").append($temp);
		$temp.val(jQuery(element).text()).select();
		document.execCommand("copy");
		$temp.remove();

		jQuery(element).addClass('active');
		setTimeout(function(){
			jQuery( element ).removeClass('active');
		},2500);
	}


	// apply coupon
	jQuery(document).ready(function(){
		jQuery(".tick_radio").click(function(){
			var code = jQuery(this).val();

			jQuery('.input-text').val(code);
			jQuery('button[name=apply_coupon]').trigger('click');
		})
    });
</script>
