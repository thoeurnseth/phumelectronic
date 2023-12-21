<?php
/**
 * Cart totals
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart-totals.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 2.3.6
 */

defined( 'ABSPATH' ) || exit;

?>
<div class="cart_totals <?php echo ( WC()->customer->has_calculated_shipping() ) ? 'calculated_shipping' : ''; ?>">

	<?php do_action( 'woocommerce_before_cart_totals' ); ?>

	<h2><?php esc_html_e( 'Order Summary', 'woocommerce' ); ?></h2>

	<table cellspacing="0" class="shop_table shop_table_responsive">

		<tr class="cart-subtotal">
			<th><?php esc_html_e( 'Subtotal', 'woocommerce' ); ?></th>
			<td data-title="<?php esc_attr_e( 'Subtotal', 'woocommerce' ); ?>"><?php wc_cart_totals_subtotal_html(); ?></td>
		</tr>

		<?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
			<tr class="cart-discount coupon-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
				<th><?php wc_cart_totals_coupon_label( $coupon ); ?></th>
				<td data-title="<?php echo esc_attr( wc_cart_totals_coupon_label( $coupon, false ) ); ?>"><?php wc_cart_totals_coupon_html( $coupon ); ?></td>
			</tr>
		<?php endforeach; ?>

		<?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>

			<?php do_action( 'woocommerce_cart_totals_before_shipping' ); ?>
			<?php

				$customer_id = get_current_user_id();
				$addresses   = new WP_Query( array('post_type' => 'user-address', 'author' => $customer_id) );

				if($addresses->have_posts()):

					$province = '';
					$district = '';
					$checked  = '';

					while( $addresses->have_posts() ): $addresses->the_post();
						$post_id = get_the_ID();
						$street = get_field( 'street',$post_id );
						$address = get_field( 'address',$post_id );
						$province_id = get_field( 'province_2',$post_id );
						$district_id = get_field( 'district_2',$post_id );

						$province_obj = get_term_by('ID', $province_id, 'location');
						$district_obj = get_term_by('ID', $district_id, 'location');

						$province = $province_obj != null ? $province_obj->name : '---';
						$district = $district_obj != null ? $district_obj->name : '---';

						// default address
						$default_address = get_field( 'default_address',$post_id );
						$checked_ = $default_address == 1 ? 'checked' : '';
						?>

						<tr>
							<td>
								<input type="radio" name="default_address" <?php echo $checked_; ?> data="<?php echo get_the_id(); ?>" class="set_address" nonce="<?php echo rand(0, 999999); ?>">
							</td>
							<td><?=$street?></td>
							<td><?=$address?></td>
							<td><?=$province?></td>
							<td><?=$district?></td>
						</tr>

						<?php
					endwhile;
				else: ?>
					
					<tr>
						<td colspan="5" align="center">No Address</td>
					</tr>
					
				<?php endif; ?>

			<?php do_action( 'woocommerce_cart_totals_after_shipping' ); ?>

		<?php elseif ( WC()->cart->needs_shipping() && 'yes' === get_option( 'woocommerce_enable_shipping_calc' ) ) : ?>

			<tr class="shipping">
				<th><?php esc_html_e( 'Shipping', 'woocommerce' ); ?></th>
				<td data-title="<?php esc_attr_e( 'Shipping', 'woocommerce' ); ?>"><?php woocommerce_shipping_calculator(); ?></td>
			</tr>

		<?php endif; ?>

		<?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
			<tr class="fee">
				<th><?php echo esc_html( $fee->name ); ?></th>
				<td data-title="<?php echo esc_attr( $fee->name ); ?>"><?php wc_cart_totals_fee_html( $fee ); ?></td>
			</tr>
		<?php endforeach; ?>

		<?php
		if ( wc_tax_enabled() && ! WC()->cart->display_prices_including_tax() ) {
			$taxable_address = WC()->customer->get_taxable_address();
			$estimated_text  = '';

			if ( WC()->customer->is_customer_outside_base() && ! WC()->customer->has_calculated_shipping() ) {
				/* translators: %s location. */
				$estimated_text = sprintf( ' <small>' . esc_html__( '(estimated for %s)', 'woocommerce' ) . '</small>', WC()->countries->estimated_for_prefix( $taxable_address[0] ) . WC()->countries->countries[ $taxable_address[0] ] );
			}

			if ( 'itemized' === get_option( 'woocommerce_tax_total_display' ) ) {
				foreach ( WC()->cart->get_tax_totals() as $code => $tax ) { // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
					?>
					<tr class="tax-rate tax-rate-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
						<th><?php echo esc_html( $tax->label ) . $estimated_text; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></th>
						<td data-title="<?php echo esc_attr( $tax->label ); ?>"><?php echo wp_kses_post( $tax->formatted_amount ); ?></td>
					</tr>
					<?php
				}
			} else {
				?>
				<tr class="tax-total">
					<th><?php echo esc_html( WC()->countries->tax_or_vat() ) . $estimated_text; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></th>
					<td data-title="<?php echo esc_attr( WC()->countries->tax_or_vat() ); ?>"><?php wc_cart_totals_taxes_total_html(); ?></td>
				</tr>
				<?php
			}
		}
		?>

		<?php do_action( 'woocommerce_cart_totals_before_order_total' ); ?>

		<tr class="order-total">
			<th><?php esc_html_e( 'Total', 'woocommerce' ); ?></th>
			<td data-title="<?php esc_attr_e( 'Total', 'woocommerce' ); ?>"><?php wc_cart_totals_order_total_html(); ?></td>
		</tr>

		<?php do_action( 'woocommerce_cart_totals_after_order_total' ); ?>

	</table>

	<div class="wc-proceed-to-checkout">
		<?php do_action( 'woocommerce_proceed_to_checkout' ); ?>
	</div>

	<?php do_action( 'woocommerce_after_cart_totals' ); ?>

</div>


<script type="text/javascript">

	// Get delivery address after reload page
	jQuery(document).ready( function() {
		jQuery( '#myaddress tr').each( function() {
			var nonce	= '';
			var post_id = '';
			var checked = jQuery(this).find('.set_address').is(':checked');

			if( checked )
			{
				post_id = jQuery(this).find('.set_address').attr('data');
				nonce = jQuery(this).find('.set_address').attr('nonce');

				// call function
				get_delivery_address( post_id, nonce );
			}
		});
	});

	// Set default address
	jQuery('.set_address').on( 'click', function(){
		var post_id, nonce = '';
		var checked = jQuery(this).is(':checked');

		if( checked )
		{
			post_id = jQuery(this).attr('data');
			nonce = jQuery(this).attr('nonce');

			// call function
			get_delivery_address( post_id, nonce );
		}
	});

	// Get delivery address
	function get_delivery_address( post_id, nonce ) {
		jQuery.ajax({
			type: "POST",
			url: "/wp-admin/admin-ajax.php",
			data: {
				action: 'set_default_billing_address',
				post_id: post_id,
				nonce: nonce,
			},
			dataType: 'JSON',
			success: function ( response )
			{
				var delivery = response.data;
				var street   = delivery.address+', '+delivery.street;
				var full_address = delivery.street+', '+delivery.commune+', '+delivery.district+', '+delivery.province;
					
				// Append data to input box
				jQuery('#billing_first_name').val( delivery.firstname );
				jQuery('#billing_last_name').val( delivery.lastname );
				jQuery('#billing_address_1').val( street );
				jQuery('#billing_address_2').val( full_address );
				jQuery('#billing_city').val( delivery.province );
				jQuery('#billing_state').val( 'Cambodia' );
				jQuery('#billing_postcode').val( '12000' );
				jQuery('#billing_phone').val( delivery.phone );
				jQuery('#billing_email').val( delivery.email );
				
				jQuery('#billing_country').val( 'KH' );
				jQuery('#billing_country').trigger('change');
			}
		});
	}

</script>