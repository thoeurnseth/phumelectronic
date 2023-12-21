<?php
/**
 * Checkout billing information form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-billing.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 * @global WC_Checkout $checkout
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="woocommerce-billing-fields">
	<?php if ( wc_ship_to_billing_address_only() && WC()->cart->needs_shipping() ) : ?>

		<h3><?php esc_html_e( 'Billing &amp; Shipping', 'woocommerce' ); ?></h3>

	<?php else : ?>
		<h3><?php esc_html_e( 'Billing details', 'woocommerce' ); ?></h3>
	<?php endif; ?>

	<?php do_action( 'woocommerce_before_checkout_billing_form', $checkout ); ?>
	
	<!-- Custom Billing Address -->
	<div class="my-address-wrapper">
		<table id="myaddress" class="tbl-shipping">
			<thead>
				<tr>
					<th>Check</th>
					<th>Street</th>
					<th>Address</th>
					<th>City/Province</th>
					<th>District</th>
				</tr>
			</thead>
			<tbody>
				<?php

				$customer_id = get_current_user_id();
				$addresses   = new WP_Query( array('post_type' => 'user-address', 'author' => $customer_id) );

				if($addresses->have_posts()):

					$province = '';
					$district = '';
					$checked  = '';

					while( $addresses->have_posts() ): $addresses->the_post();
						$post_id =  get_the_ID();
						$street = get_field( 'street', $post_id);
						$address = get_field( 'address',$post_id );
						$province_id = get_field( 'province_2',$post_id );
						$district_id = get_field( 'district_2',$post_id );
						$map 		= get_field('map',$post_id);
						$lat 		= is_array($map) ? $map['lat'] : "";
						$lng 		= is_array($map) ? $map['lng'] : "";


						$province_obj = get_term_by('ID', $province_id, 'location');
						$district_obj = get_term_by('ID', $district_id, 'location');

						$province = $province_obj != null ? $province_obj->name : '---';
						$district = $district_obj != null ? $district_obj->name : '---';

						// default address
						$default_address = get_field( 'default_address',$post_id);
						$checked_ = $default_address == 1 ? 'checked' : '';
						?>

						<tr>
							<td>
								<input type="radio" name="default_address" <?php echo $checked_; ?> data="<?php echo $post_id; ?>" class="set_address" nonce="<?php echo rand(0, 999999); ?>" data-lat="<?=$lat?>" data-lng="<?=$lng?>">
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
			</tbody>
		</table>
		
		<!-- <input type="button" value="CHANGE" name="change-add" class="change-add">
		<input type="button" value="ADD NEW" name="new-add" class="new-add"> -->
		
		<?php  
			$webview = $_GET['webview'] ;
			if($webview == 'true') {
				echo '
					<small>Create new billing address.</small>
					<a href="'.site_url().'/my-account/edit-address/?action&checkout=true&webview=true"><small class="text-red">Click Here.</small></a>
				';
			}
			else {
				echo '
					<small>Create new billing address.</small>
					<a href="'.site_url().'/my-account/edit-address/?action&checkout=true"><small class="text-red">Click Here.</small></a>
				';
			}
		?>
	</div>

	<div class="woocommerce-billing-fields__field-wrapper d-none">
		<?php 
		$fields = $checkout->get_checkout_fields( 'billing' );

		foreach ( $fields as $key => $field ) {
			woocommerce_form_field( $key, $field, $checkout->get_value( $key ) );
		}
		?>
	</div>

	<?php do_action( 'woocommerce_after_checkout_billing_form', $checkout ); ?>
</div>

<?php if ( ! is_user_logged_in() && $checkout->is_registration_enabled() ) : ?>
	<div class="woocommerce-account-fields">
		<?php if ( ! $checkout->is_registration_required() ) : ?>
		
			<p class="form-row form-row-wide create-account">
				<label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox">
					<input class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox" id="createaccount" <?php checked( ( true === $checkout->get_value( 'createaccount' ) || ( true === apply_filters( 'woocommerce_create_account_default_checked', false ) ) ), true ); ?> type="checkbox" name="createaccount" value="1" /> <span><?php esc_html_e( 'Create an account?', 'woocommerce' ); ?></span>
				</label>
			</p>

		<?php endif; ?>

		<?php do_action( 'woocommerce_before_checkout_registration_form', $checkout ); ?>

		<?php if ( $checkout->get_checkout_fields( 'account' ) ) : ?>

			<div class="create-account">
				<?php foreach ( $checkout->get_checkout_fields( 'account' ) as $key => $field ) : ?>
					<?php woocommerce_form_field( $key, $field, $checkout->get_value( $key ) ); ?>
				<?php endforeach; ?>
				<div class="clear"></div>
			</div>

		<?php endif; ?>

		<?php do_action( 'woocommerce_after_checkout_registration_form', $checkout ); ?>
	</div>
<?php endif; ?>


<script type="text/javascript">
	function initMap() {
		// initialize services
		 const service = new google.maps.DistanceMatrixService();

		// default district 
		 var lat = jQuery('.set_address:checked').data('lat');
		 var lng = jQuery('.set_address:checked').data('lng');

		 if(lat == ''){
			var wrap = jQuery('.distance-deliverr-wrap');
			wrap.find('.distance .value').text('N/A');
			wrap.find('.time .value').text('N/A');
			return false;
		 }

		//console.log('lat', lat);
		//console.log('lng', lng);

		// build request
		//const origin = "Phnom Penh";
		//const destination = "Tuol Kouk";

		// using lat lng
		var start = new google.maps.LatLng(11.552924, 104.883689); // Street 2004 Head Office Phumelectronic
		var end = [new google.maps.LatLng(lat, lng)];


		const request = {
			origins: [start],
			destinations: end,
			travelMode: google.maps.TravelMode.DRIVING,
			unitSystem: google.maps.UnitSystem.METRIC,
			avoidHighways: false,
			avoidTolls: false,
		};

		// get distance matrix response
		service.getDistanceMatrix(request).then((response) => {
			// Put Response
			var distance = response.rows[0].elements[0].distance.text;
			var duration = response.rows[0].elements[0].duration.text;

			var wrap = jQuery('.distance-deliverr-wrap');
			wrap.find('.distance .value').text(distance);
			wrap.find('.time .value').text(duration);
		});
	}
	window.initMap = initMap;

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
				
				jQuery('#billing_postcode').val( '12000' );
				jQuery('#billing_phone').val( delivery.phone );
				jQuery('#billing_email').val( delivery.email );
				
				jQuery('#billing_country').val( 'KH' );
				jQuery('#billing_country').trigger('change');
				jQuery('#billing_state').val( delivery.postal_code);
				jQuery('#billing_state').trigger('change');
				
				//window.initMap = initMap;
				initMap();
			},
			error: function(xhr, status, error){
				console.log('error',error);
			}
		});
	}
	
</script>