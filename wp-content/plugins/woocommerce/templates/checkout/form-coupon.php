<?php
/**
 * Checkout coupon form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-coupon.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.4.4
 */

defined( 'ABSPATH' ) || exit;

if ( ! wc_coupons_enabled() ) { // @codingStandardsIgnoreLine.
	return;
}

?>
<div class="woocommerce-form-coupon-toggle">
	<?php wc_print_notice( apply_filters( 'woocommerce_checkout_coupon_message', esc_html__( 'Have a coupon?', 'woocommerce' ) . ' <a href="#" class="showcoupon">' . esc_html__( 'Click here to apply', 'woocommerce' ) . '</a>' ), 'notice' ); ?>
</div>

<form class="checkout_coupon woocommerce-form-coupon" method="post" style="display:none">

	<p><?php esc_html_e( 'If you have a coupon code, please apply it below.', 'woocommerce' ); ?></p>

	<p class="form-row form-row-first">
		<input type="text" name="coupon_code" class="input-text" placeholder="<?php esc_attr_e( 'Coupon code', 'woocommerce' ); ?>" id="coupon_code" value="" />
	</p>

	<p class="form-row form-row-last">
		<button type="submit" class="button" name="apply_coupon" value="<?php esc_attr_e( 'Apply coupon', 'woocommerce' ); ?>"><?php esc_html_e( 'Apply coupon', 'woocommerce' ); ?></button>

	<!-- list coupon for user apply  -->
		<div class="list-coupon-icon-discount">
			<div class="list-coupon-checkout">
				<!-- <div class="coupon-title">
					<h2><i class="fa-regular fa-money-check-dollar"></i> Available Coupons:</h2>
				</div> -->

		

			</div>
			<div class="icon-coupon-discount">
				<div class="image">
				<i class="fa fa-tags"></i>
				</div>
			</div>
		</div>

	</p>
	<div class="clear"></div>
</form>


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
		},500);
	}

	// apply coupon
	jQuery(document).ready(function(){
		jQuery(".tick_radio").click(function(){
			var code = jQuery(this).val();

			jQuery('.input-text').val(code);
			jQuery('button[name=apply_coupon]').trigger('click');

			// jQuery('.distance-deliverr-wrap .discount .dsds').html(jQuery('.cart-discount .woocommerce-Price-amount.amount').html());
		})
    });

</script>
