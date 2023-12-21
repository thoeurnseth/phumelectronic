<?php
/**
 * Edit account form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-edit-account.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.5.0
 */

	$url 			= get_template_directory_uri();
	$uploads 		= wp_upload_dir();
	$upload_path 	= $uploads['baseurl'];
	$current_user 	= get_current_user_id();
	$district_id	= get_user_meta($current_user, 'user_district', true);

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_edit_account_form' ); ?>
<div class="profile-wraper">


<form class="woocommerce-EditAccountForm edit-account" action="" method="post" <?php do_action( 'woocommerce_edit_account_form_tag' ); ?> enctype="multipart/form-data">

	<?php do_action( 'woocommerce_edit_account_form_start' ); ?>

	<div class="row">
		<div class="col-md-9">
			<div class="profile-title">
				<h2>Edit Profile</h2> 
			</div>
		</div>
		<div class="col-md-3">
			<div class="avatar-upload">
				<div class="avatar-edit">
					<input type="file" name="user_profile" id="imageUpload" accept=".png, .jpg, .jpeg" />
					<label for="imageUpload"></label>
				</div>
				<div class="avatar-preview">
					<?php
						$profile_image   = get_field( 'profile_image', 'user_' . $current_user );
					?>
					<input type="hidden" name="profile_name">
					<div id="imagePreview" style="background-image: url(<?php echo  get_avatar_url( get_current_user_id() )	?>);">
					</div>
				</div>
			</div>
		</div>
	</div>

	<p class="woocommerce-form-row woocommerce-form-row--first form-row form-row-first">
		<label for="account_first_name"><?php esc_html_e( 'First name', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
		<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="account_first_name" id="account_first_name" autocomplete="given-name" value="<?php echo esc_attr( $user->first_name ); ?>" />
	</p>
	<p class="woocommerce-form-row woocommerce-form-row--last form-row form-row-last">
		<label for="account_last_name"><?php esc_html_e( 'Last name', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
		<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="account_last_name" id="account_last_name" autocomplete="family-name" value="<?php echo esc_attr( $user->last_name ); ?>" />
	</p>
	<div class="clear"></div>

	<p class="woocommerce-form-row woocommerce-form-row--first form-row form-row-first">
		<label for="date_of_birth"><?php _e( 'Date of Birth', 'woocommerce' ); ?></label>
		<input type="date" class="woocommerce-Input woocommerce-Input--text input-text" name="date_of_birth" id="date_of_birth" value="<?php echo esc_attr( $user->date_of_birth ); ?>" />
	</p>

	<p class="woocommerce-form-row woocommerce-form-row--last form-row form-row-last">
		<label for="phone_number"><?php _e( 'Phone Number', 'woocommerce' ); ?></label>
		<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="phone_number" id="phone_number" value="<?php echo esc_attr( $user->phone_number ); ?>" />
	</p>
	<div class="clear"></div>

	<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
		<label for="account_email"><?php esc_html_e( 'Email address', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
		<input type="email" class="woocommerce-Input woocommerce-Input--email input-text" name="account_email" id="account_email" autocomplete="email" value="<?php echo esc_attr( $user->user_email ); ?>" />
	</p>
	<!-- user invite code -->
	<p class="woocommerce-form-row woocommerce-form-row--last form-row form-row-last" style="float: left;">
		<label for="user_code"><?php esc_html_e( 'User Invite Code', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
		<input type="text" readonly class="woocommerce-Input woocommerce-Input--text input-text" name="user_code" id="user_code" autocomplete="family-name" value="<?php echo esc_attr( $user->user_code ); ?>" />
	</p>
	<!-- invite code -->
	<p class="woocommerce-form-row woocommerce-form-row--first form-row form-row-first" style="display: none;">
		<label for="invite_code"><?php esc_html_e( 'Invite code', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
		<input type="text" readonly class="woocommerce-Input woocommerce-Input--text input-text" name="invite_code" id="invite_code" autocomplete="given-name" value="<?php echo esc_attr( $user->invite_code ); ?>" />
	</p>
	<div class="clear"></div>

	<!-- <p class="woocommerce-form-row woocommerce-form-row--first form-row form-row-first">
		<label for="province"><?php // _e( 'City / Province', 'woocommerce' ); ?></label>

		<select name="province" id="province" class="select2">
			<option value="" disabled="" selected="selected">Choose...</option>
			<?php
				// $old_province_id = get_user_meta($current_user ,'user_province', true);

				// $provinces = get_province();
				// foreach( $provinces as $province )
				// {
				// 	$selected = $province->term_id == $old_province_id ? 'selected' : ''; 
				// 	echo '
				// 		<option '.$selected.' value="'.$province->term_id.'">'.$province->name.'</option>
				// 	';
				// }
			?>
		</select>
	</p> -->

	<!-- <p class="woocommerce-form-row woocommerce-form-row--last form-row form-row-last">
		<label for="district"><?php // _e( 'District', 'woocommerce' ); ?></label>
		<select name="district" id="district" class="select2">
			<option value="" disabled="" selected="selected">Choose...</option>
	
		</select>
	</p> -->

	<div class="clear"></div>

	<?php do_action( 'woocommerce_edit_account_form' ); ?>

	<p>
		<?php wp_nonce_field( 'save_account_details', 'save-account-details-nonce' ); ?>
		<button type="submit" class="woocommerce-Button button" name="save_account_details" value="<?php esc_attr_e( 'Save', 'woocommerce' ); ?>"><?php esc_html_e( 'Save', 'woocommerce' ); ?></button>
		<input type="hidden" name="action" value="save_account_details" />
	</p>

	<input type="hidden" name="" id="old_district_id" value="<?=$district_id ?>">

	<?php do_action( 'woocommerce_edit_account_form_end' ); ?>
	
</form>

<?php do_action( 'woocommerce_after_edit_account_form' ); ?>

</div>
