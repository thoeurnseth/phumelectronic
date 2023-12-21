<?php

use LDAP\Result;

add_action( 'wp_enqueue_scripts', 'metro_child_styles', 18 );
function metro_child_styles() {

  $parent_style = 'parent-style';
  $version_style = '11.0.1';

	wp_enqueue_style( 'metro-child-style', get_stylesheet_uri(), array(), $version_style );
  wp_enqueue_style( 'metro-child-style', '//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css' );
  

  wp_enqueue_style( $parent_style, get_stylesheet_directory_uri() );
  wp_enqueue_style( 'child-company-profile', get_stylesheet_directory_uri() . '/assets/css/company-profile.css',
      array( $parent_style ), 
      $version_style
  );

  wp_enqueue_script( 'slick-slider', '//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js' );
  wp_enqueue_script( 'metro-child-theme-js', get_stylesheet_directory_uri() . '/assets/js/theme.js', array(), '0.0.1', true );
  wp_enqueue_script( 'google-map', 'https://maps.googleapis.com/maps/api/js?key=AIzaSyCeklFOynIMeNsGu8qXNztlUeyDmPmasxc&callback=initMap&v=weekly', array(), '3', true );
  //wp_enqueue_script( 'google-map', 'https://maps.googleapis.com/maps/api/js?key=AIzaSyCeklFOynIMeNsGu8qXNztlUeyDmPmasxc', array(), '3', true );
	wp_enqueue_script( 'google-map-init', get_stylesheet_directory_uri() . '/assets/js/google-maps.js', array('google-map', 'jquery'), '0.1', true );

  wp_localize_script( 'ajax-script', 'my_ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
}

add_action( 'after_setup_theme', 'metro_child_theme_setup' );
function metro_child_theme_setup() {
    load_child_theme_textdomain( 'metro', get_stylesheet_directory() . '/languages' );
}

/**
 * Register style sheet.
 */
function mychildtheme_enqueue_styles() {

  wp_enqueue_style( 'select2', get_stylesheet_directory_uri() . '/assets/js/select2/css/select2.min.css' );
  
  wp_enqueue_script( 'custom-js', get_stylesheet_directory_uri() . '/assets/js/custom.js', array(), '0.0.1', true );
  wp_enqueue_script( 'select2', get_stylesheet_directory_uri() . '/assets/js/select2/js/select2.min.js', array(), '0.0.1', true );
}
add_action( 'init', 'mychildtheme_enqueue_styles' );

add_action( 'wp_print_styles', 'wps_deregister_styles', 100 );

// @ rigister style admin
function wpdocs_enqueue_custom_admin_style() {
  wp_enqueue_style( 'qq', get_stylesheet_directory_uri() . '/assets/js/select2/css/style-admin.css' );
  // wp_enqueue_style( 'select' );
}
add_action( 'admin_enqueue_scripts', 'wpdocs_enqueue_custom_admin_style' );

function wps_deregister_styles() {
    // wp_dequeue_style( 'wp-block-library' );
    // wp_dequeue_style( 'wp-block-library-theme' );
    // wp_dequeue_style( 'wc-block-vendors-style' );
    // wp_dequeue_style( 'wc-block-style' )0;
    // wp_dequeue_style( 'woocommerce-layout' );
    // wp_dequeue_style( 'woocommerce-smallscreen' );
    // wp_dequeue_style( 'woocommerce-general' );
	  //wp_dequeue_style( 'metro-elementor' );
    //wp_dequeue_style( 'metro-wc' );
    //wp_dequeue_style( 'slick' );
    //wp_dequeue_style( 'slick-theme' );
    //wp_dequeue_style( 'metro-style' );
    //wp_dequeue_style( 'metro-wc' );
	wp_deregister_style( 'dashicons' ); 
}

function metro_child_theme_add_woocommerce_support() {
    add_theme_support( 'woocommerce' );
}
add_action( 'after_setup_theme', 'metro_child_theme_add_woocommerce_support' );

add_action( 'woocommerce_before_checkout_form', 'checkout_message' );
function checkout_message() {
echo '<p>Please fill all required fields. Thank you!</p>';
}



add_filter( 'woocommerce_account_menu_items', 'reorder_woocommerce_account_menu_items', 99, 1 );
function reorder_woocommerce_account_menu_items($items) {
    
	unset( $items['dashboard'] ); // Remove Dashboard
	unset( $items['payment-methods'] ); // Remove Payment Methods
	unset( $items['orders'] ); // Remove Orders
	unset( $items['downloads'] ); // Disable Downloads
	unset( $items['edit-account'] ); // Remove Account details tab
	unset( $items['edit-address'] ); // Remove Account details tab
	unset( $items['customer-logout'] ); // Remove Logout link
 
    $items['edit-account']      = __('Edit Profile', 'PE');
    $items['orders']            = __('My Orders', 'PE');
    $items['device']            = __('My Device', 'PE');
    $items['cart']              = __('My Cart', 'PE');
    $items['notification']      = __('Notification', 'PE');
    $items['points']            = __('My Points', 'PE');
    $items['wishlist']          = __('My Favorite', 'PE');
    $items['reviews']          = __('Reviews', 'PE');
    $items['edit-address']      = __('My Address', 'PE');
     $items['coupons']           = __('My Coupon', 'PE');
    $items['customer-logout']   = __('Logout', 'PE');
    return $items;
}


add_action( 'init', function() {
	add_rewrite_endpoint( 'wishlist',  	EP_PAGES );
	add_rewrite_endpoint( 'cart',  		  EP_PAGES );
	add_rewrite_endpoint( 'invoices',  	EP_PAGES );
	add_rewrite_endpoint( 'points',   	EP_PAGES );
	add_rewrite_endpoint( 'coupons',  	EP_PAGES );
	add_rewrite_endpoint( 'device',  	EP_PAGES );
  add_rewrite_endpoint( 'notification',  	EP_PAGES );
  add_rewrite_endpoint( 'reviews',  	EP_PAGES );
});

add_action( 'woocommerce_account_wishlist_endpoint', function(){
	wc_get_template_part('myaccount/wishlist');
});
add_action( 'woocommerce_account_cart_endpoint', function(){
	wc_get_template_part('myaccount/cart');
});
add_action( 'woocommerce_account_invoices_endpoint', function(){
	wc_get_template_part('myaccount/invoices');
});
add_action( 'woocommerce_account_points_endpoint', function(){
	wc_get_template_part('myaccount/points');
});
 add_action( 'woocommerce_account_coupons_endpoint', function(){
 	wc_get_template_part('myaccount/coupons');
 });
add_action( 'woocommerce_account_device_endpoint', function(){
	wc_get_template_part('myaccount/device');
});
add_action( 'woocommerce_account_notification_endpoint', function(){
	wc_get_template_part('myaccount/notification');
});
add_action( 'woocommerce_account_reviews_endpoint', function(){
	wc_get_template_part('myaccount/reviews');
});

add_action( 'woocommerce_register_form', 'misha_add_register_form_field' );
function misha_add_register_form_field(){
	woocommerce_form_field(
		'country_to_visit',
		array(
			'type'        => 'text',
			'required'    => true, // just adds an "*"
			'label'       => 'Country you want to visit the most'
		),
		( isset($_POST['country_to_visit']) ? $_POST['country_to_visit'] : '' )
	);
}




function woocommerce_user_registration_shortcode()
{ 
	
	if ( 'yes' === get_option( 'woocommerce_enable_myaccount_registration' ) ) : ?>

		<div class="u-column2 col-2">

			<h2><?php esc_html_e( 'Register', 'woocommerce' ); ?></h2>

			<form method="post" class="woocommerce-form woocommerce-form-register register" <?php do_action( 'woocommerce_register_form_tag' ); ?> >

				<?php do_action( 'woocommerce_register_form_start' ); ?>

				<?php if ( 'no' === get_option( 'woocommerce_registration_generate_username' ) ) : ?>

					<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
						<label for="reg_username"><?php esc_html_e( 'Username', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
						<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" id="reg_username" autocomplete="username" value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" /><?php // @codingStandardsIgnoreLine ?>
					</p>

				<?php endif; ?>

				<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
					<label for="reg_email"><?php esc_html_e( 'Email address', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
					<input type="email" class="woocommerce-Input woocommerce-Input--text input-text" name="email" id="reg_email" autocomplete="email" value="<?php echo ( ! empty( $_POST['email'] ) ) ? esc_attr( wp_unslash( $_POST['email'] ) ) : ''; ?>" /><?php // @codingStandardsIgnoreLine ?>
				</p>

				<?php if ( 'no' === get_option( 'woocommerce_registration_generate_password' ) ) : ?>

					<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
						<label for="reg_password"><?php esc_html_e( 'Password', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
						<input type="password" class="woocommerce-Input woocommerce-Input--text input-text" name="password" id="reg_password" autocomplete="new-password" />
					</p>

				<?php else : ?>

					<p><?php esc_html_e( 'A password will be sent to your email address.', 'woocommerce' ); ?></p>

				<?php endif; ?>

				<?php do_action( 'woocommerce_register_form' ); ?>

				<p class="woocommerce-form-row form-row">
					<?php wp_nonce_field( 'woocommerce-register', 'woocommerce-register-nonce' ); ?>
					<button type="submit" class="woocommerce-Button woocommerce-button button woocommerce-form-register__submit" name="register" value="<?php esc_attr_e( 'Register', 'woocommerce' ); ?>"><?php esc_html_e( 'Register', 'woocommerce' ); ?></button>
				</p>

				<?php do_action( 'woocommerce_register_form_end' ); ?>

			</form>

		</div>

		<?php 
	endif; 

} 
add_shortcode('woocommerce_user_registration', 'woocommerce_user_registration_shortcode'); 




add_action( 'woocommerce_save_account_details', 'save_additional_fields_account_details', 12, 1 );
function save_additional_fields_account_details( $user_id )
{

    if( isset( $_POST['date_of_birth'] ) )
		    update_user_meta( $user_id,   'date_of_birth', sanitize_text_field( $_POST['date_of_birth'] ) );
		
    if( isset( $_POST['phone_number'] ) )
        update_user_meta( $user_id,   'phone_number', sanitize_text_field( $_POST['phone_number'] ) );

    if( isset( $_POST['account_email'] ) )
        update_user_meta( $user_id,   'billing_email', sanitize_text_field( $_POST['account_email'] ) );

    if( isset( $_POST['province'] ) )
        update_user_meta( $user_id,   'user_province', sanitize_text_field( $_POST['province'] ) );
      	// update_user_meta($user_id,  	'province'     , $_POST['province']);
        // update_user_meta( $user_id, 'user_district', sanitize_text_field( $_POST['province'] ) );
    if( isset( $_POST['district'] ) )
         update_user_meta( $user_id,  'user_district', sanitize_text_field( $_POST['district'] ) );
}

add_filter('woocommerce_save_account_details_required_fields', 'ts_hide_last_name');
function ts_hide_last_name($required_fields)
{
	unset($required_fields["account_display_name"]);
	return $required_fields;
}

/**
 * Register a custom post type called "Addresses".
 *
 * @see get_post_type_labels() for label keys.
 */
function register_address_post_type() {
    $labels = array(
        'name'                  => _x( 'User Addresses', 'Post type general name', 'textdomain' ),
        'singular_name'         => _x( 'User Address', 'Post type singular name', 'textdomain' ),
        'menu_name'             => _x( 'User Addresses', 'Admin Menu text', 'textdomain' ),
        'name_admin_bar'        => _x( 'User Address', 'Add New on Toolbar', 'textdomain' ),
        'add_new'               => __( 'Add New', 'textdomain' ),
        'add_new_item'          => __( 'Add New Address', 'textdomain' ),
        'new_item'              => __( 'New Address', 'textdomain' ),
        'edit_item'             => __( 'Edit Address', 'textdomain' ),
        'view_item'             => __( 'View Address', 'textdomain' ),
        'all_items'             => __( 'User Addresses', 'textdomain' ),
        'search_items'          => __( 'Search Addresses', 'textdomain' ),
        'parent_item_colon'     => __( 'Parent Addresses:', 'textdomain' ),
        'not_found'             => __( 'No Addresses found.', 'textdomain' ),
        'not_found_in_trash'    => __( 'No Addresses found in Trash.', 'textdomain' ),
        'featured_image'        => _x( 'Address Cover Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'textdomain' ),
        'set_featured_image'    => _x( 'Set cover image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'textdomain' ),
        'remove_featured_image' => _x( 'Remove cover image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'textdomain' ),
        'use_featured_image'    => _x( 'Use as cover image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'textdomain' ),
        'archives'              => _x( 'Address archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'textdomain' ),
        'insert_into_item'      => _x( 'Insert into Address', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'textdomain' ),
        'uploaded_to_this_item' => _x( 'Uploaded to this Address', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'textdomain' ),
        'filter_items_list'     => _x( 'Filter Addresses list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'textdomain' ),
        'items_list_navigation' => _x( 'Addresses list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'textdomain' ),
        'items_list'            => _x( 'Addresses list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'textdomain' ),
    );
    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'user-address' ),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        // 'show_in_menu'       => 'users.php',
        'supports'           => array( 'author'),
    );
    register_post_type( 'user-address', $args );
}
add_action( 'init', 'register_address_post_type' );

function register_location_taxonomy() {
    $args = array(
        'label'        => __( 'Location', 'textdomain' ),
        'public'       => true,
        'rewrite'      => false,
        'hierarchical' => true,
        'show_in_nav_menus' => true
    );  
    register_taxonomy( 'location', 'user-address', $args );
}
add_action( 'init', 'register_location_taxonomy', 0 );

function acf_load_select_country( $field ) {
    // Reset choices
    $field['choices'] = array();
    // Get field from options page
    $countries_and_areas = get_field('countries_and_areas', 'options');
    // Get only countries in array
    foreach ($countries_and_areas as $key => $value) {
      $countries[] = $value['country'];
    }
    // Sort countries alphabetically
    natsort( $countries );
    // Populate choices
    foreach( $countries as $choice ) {
      $field['choices'][ $choice ] = $choice;
    }
    // Return choices
    return $field;
  }
  // Populate select field using filter
//   add_filter('acf/load_field/name=district', 'acf_load_select_country');

function acf_admin_enqueue( $hook ) {
 
    // $type = get_post_type(); // Check current post type
    // $types = array( 'user-address' ); // Allowed post types
   
    // if( !in_array( $type, $types ) )
    //     return; // Only applies to post types in array
    wp_enqueue_script( 'populate-district', get_stylesheet_directory_uri() . '/assets/js/district-populates.js' );
    wp_localize_script( 'populate-district', 'pa_vars', array(
          'pa_nonce' => wp_create_nonce( 'pa_nonce' ), // Create nonce which we later will use to verify AJAX request
          'ajaxurl' => admin_url( 'admin-ajax.php' ),
        )
    );

    wp_enqueue_script( 'commune-commune', get_stylesheet_directory_uri() . '/assets/js/commune-populates.js' );
    wp_localize_script( 'populate-commune', 'pa_vars', array(
          'pa_nonce' => wp_create_nonce( 'pa_nonce' ), // Create nonce which we later will use to verify AJAX request
          'ajaxurl' => admin_url( 'admin-ajax.php' ),
        )
    );
}
// add_action( 'admin_enqueue_scripts', 'acf_admin_enqueue' );
// add_action( 'wp_enqueue_scripts', 'acf_admin_enqueue' );


  // Return areas by country
function district_by_province( $selected_country ) {
 
    // Verify nonce
    if( !isset( $_POST['pa_nonce'] ) || !wp_verify_nonce( $_POST['pa_nonce'], 'pa_nonce' ) )
      die('Permission denied');
   
    // Get country var
    $selected_country = $_POST['province'];
   
    // Get field from options page
    $countries_and_areas = get_field('countries_and_areas', 'options');
   
    // Simplify array to look like: country => areas
    foreach ($countries_and_areas as $key => $value) {
      $countries[$value['country']] = $value['area'];
    }
   
    // Returns Area by Country selected if selected country exists in array
    if (array_key_exists( $selected_country, $countries)) {
      // Convert areas to array
      $arr_data = explode( ', ', $countries[$selected_country] );
      return wp_send_json($arr_data);
    } else {
      $arr_data = array();
      return wp_send_json($arr_data);
    } 
    die();
}

   
//   add_action('wp_ajax_pa_add_district', 'district_by_province');
//   add_action('wp_ajax_nopriv_pa_add_district', 'district_by_province');

function acf_filter_province_by_country( $args, $field, $post_id ) {
  
    // Look for the vehicle make in the AJAX request.
    // $country_id = isset($_REQUEST['country_id']) ? (int) $_REQUEST['country_id'] : false;

    // If not found, use the default query args
    // if ( $country_id === false || empty($country_id) || $country_id <= 0 )
    // {
    //     $args['parent'] = -1;
    //     return $args;
    // }

    $country_id = get_term_by('slug', sanitize_title('Cambodia'), 'location');
    $country_id = $country_id->term_id;

    // get all province ids by country id
    $province_ids = new WP_Term_Query( array(
        'fields'        =>  'ids',
        'taxonomy'      =>  'location',
        'meta_key'      =>  'taxonomy_type',
        'meta_value'    =>  'province',
        'hide_empty'    =>  false,
        // 'parent'        =>  $country_id
    ));

    if(empty($province_ids))
    {
      $args['parent'] = -1;
      return $args;
    }
    
    // If no make has been selected, do not show any terms (-1 will never give results)
    // Otherwise, use the make as the parent term. Models are always a child of a make.
    // $args['include'] = $province_ids->terms;
    $args['parent'] = $country_id;

    return $args;
}
add_filter( 'acf/fields/taxonomy/query/name=province_2', 'acf_filter_province_by_country', 20, 3 );



function acf_filter_district_by_province( $args, $field, $post_id ) {
    // Look for the vehicle make in the AJAX request.
    
    $province_id = isset($_REQUEST['province_id']) ? (int) $_REQUEST['province_id'] : false;
    // If not found, use the default query args
    if ( $province_id === false || empty($province_id) || $province_id <= 0 )
    {
        $args['parent'] = -1;
        return $args;
    }

    // get all district ids by province id
    $district_ids = new WP_Term_Query( array(
        'fields'        =>  'ids',
        'taxonomy'      =>  'location',
        'meta_key'      =>  'taxonomy_type',
        'meta_value'    =>  'district',
        'hide_empty'    =>  false,
        'parent'        =>  $province_id
    ));

    if(empty($district_ids))
    {
      $args['parent'] = -1;
      return $args;
    }

    // If no make has been selected, do not show any terms (-1 will never give results)
    // Otherwise, use the make as the parent term. Models are always a child of a make.
    $args['include'] = $district_ids->terms;

    return $args;
}
add_filter( 'acf/fields/taxonomy/query/name=district_2', 'acf_filter_district_by_province', 20, 3 );



function acf_filter_commune_by_district( $args, $field, $post_id ) {
    // Look for the vehicle make in the AJAX request.
    $district_id = isset($_REQUEST['district_id']) ? (int) $_REQUEST['district_id'] : false;

    // If not found, use the default query args
    if ( $district_id === false || empty($district_id) || $district_id <= 0 )
    {
        $args['parent'] = -1;
        return $args;
    }

    // get all commune ids by province id
    $commune_ids = new WP_Term_Query( array(
        'fields'        =>  'ids',
        'taxonomy'      =>  'location',
        'meta_key'      =>  'taxonomy_type',
        'meta_value'    =>  'commune',
        'hide_empty'    =>  false,
        'parent'        =>  $district_id
    ));

    if(empty($commune_ids))
    {
      $args['parent'] = -1;
      return $args;
    }

    // If no make has been selected, do not show any terms (-1 will never give results)
    // Otherwise, use the make as the parent term. Models are always a child of a make.
    $args['include'] = $commune_ids->terms;

    return $args;
}
add_filter( 'acf/fields/taxonomy/query/name=commune_2', 'acf_filter_commune_by_district', 20, 3 );


function asacf_admin_enqueue( $hook ) {
 
  // $type = get_post_type(); // Check current post type
  // $types = array( 'user-address' ); // Allowed post types
 
  // if( !in_array( $type, $types ) )
  //     return; // Only applies to post types in array
 
  wp_enqueue_script( 'populate-district', get_stylesheet_directory_uri() . '/assets/js/district-populates.js' );
  wp_localize_script( 'populate-district', 'pa_vars', array(
        'pa_nonce' => wp_create_nonce( 'pa_nonce' ), // Create nonce which we later will use to verify AJAX request
        'ajaxurl' => admin_url( 'admin-ajax.php' ),
      )
  );
}

function new_orders_columns( $columns = array() ) {

  // Hide the columns
  if( isset($columns['order-total']) ) {
      // Unsets the columns which you want to hide
      unset( $columns['order-number'] );
      unset( $columns['order-date'] );
      unset( $columns['order-status'] );
      unset( $columns['order-total'] );
      unset( $columns['order-actions'] );
  }

  // Add new columns
  $columns['order-image']    = __( 'Image', 'Phumelectronic' );
  $columns['order-number']    = __( 'Order id', 'Phumelectronic' );
  $columns['order-quantity']  = __( 'Quantity', 'Phumelectronic' );
  $columns['order-total']     = __( 'Amount', 'Phumelectronic' );
  $columns['order-date']      = __( 'Date', 'Phumelectronic' );
  $columns['order-status']    = __( 'Status', 'Phumelectronic' );
  $columns['order-download']  = __( 'Download', 'Phumelectronic' );

  return $columns;
}
add_filter( 'woocommerce_account_orders_columns', 'new_orders_columns' );

/**
 * Update user profile
 */
function update_user_profile() {
  global $wpdb;

  if(!empty($_FILES['user_profile']['name']))
  {
    
    $file_name  = $_FILES['user_profile']['name'];
    $dir_upload = WP_CONTENT_DIR.'/uploads/nsl_avatars/';
    

    $extention  = substr($file_name, strripos($file_name, '.')); // get file extention
    $basename   = substr($file_name, 0, strripos($file_name, '.'));  // get file name
    $encryp_basename = md5( $basename ).rand(111,999); // Encrypt file name
    $new_file   = $encryp_basename.$extention; // made new file name
    $uploaded = move_uploaded_file($_FILES['user_profile']['tmp_name'],$dir_upload.$new_file);

    $user_id    = get_current_user_id();
    $avatar_id  = get_user_meta( $user_id, 'ph0m31e_user_avatar', true );
    update_post_meta( $avatar_id, '_wp_attached_file', 'nsl_avatars/'.$new_file.'' );


    update_post_meta($uploaded, 'nsl_avatars/'.$new_file.'', 'file' );
	 

    $upload_uri = wp_get_upload_dir();
    $wpdb->update($wpdb->posts, ['guid' => $upload_uri['baseurl'].'/nsl_avatars/'.$new_file], ['ID' => $avatar_id]);
    //biz_write_log($avatar_id, 'change_profile');
  }
}
add_action('init', 'update_user_profile');

/**
 * Get Province
 */
function get_province() {

	$provinces = get_terms( array(
		'taxonomy' => 'location',
    'parent' => 0,
		'hide_empty' => false,
	) );

	return $provinces;
}

/**
 * Get District
 */
add_action('wp_ajax_get_district', 'get_district');
add_action('wp_ajax_nopriv_get_district', 'get_district');

function get_district() {
  
  $i=0;
  $data = [];
  $province_id = $_POST['province_id'];    

  $provinces = get_terms(
    array(
      'taxonomy' => 'location',
      'parent' => $province_id,
      'hide_empty' => false
    )
  );

  foreach($provinces as $province)
  {
    $data[$i] = array(
      'id'    => $province->term_id,
      'text'  => $province->name,
    );

    $i++;
  }

  wp_send_json_success($data);
}

function filter_woocommerce_loop_add_to_cart_link( $args, $product ) {
  // Shop page & product type = simple
  if ( is_shop() && $product->product_type === 'simple' ) {
      // Get product ID, sku & add to cart url
      $product_id = $product->get_id();
      $product_sku = $product->get_sku();
      $product_url = $product->add_to_cart_url();

      // Quantity & text
      $quantity = isset( $args['quantity'] ) ? $args['quantity'] : 1;
      $text = $product->add_to_cart_text();

      $args = '<a rel="nofollow" href="' . $product_url . '" data-quantity="' . $quantity . '" data-product_id="' . $product_id . '" data-product_sku="' . $product_sku . '" class="button product_type_simple add_to_cart_button ajax_add_to_cart add-to-cart" aria-label="Add to cart"><em>' . $text . '</em></a>';
  }
  
  return $args; 
}
add_filter( 'woocommerce_loop_add_to_cart_link', 'filter_woocommerce_loop_add_to_cart_link', 10, 2 );

 add_filter( 'woocommerce_product_add_to_cart_text' , 'custom_woocommerce_product_add_to_cart_text' );
 /**
  * custom_woocommerce_template_loop_add_to_cart
 */
 function custom_woocommerce_product_add_to_cart_text() {
 	global $product;
	
 	$product_type = $product->get_type();
	
	switch ( $product_type ) {
		case 'external':
 			return __( 'Buy product', 'woocommerce' );
 		break;
 		case 'grouped':
 			return __( 'View products', 'woocommerce' );
 		break;
 		case 'simple':
 			return __( 'Select options', 'woocommerce' );
 		break;
 		case 'variable':
 			return __( 'Add to cart', 'woocommerce' );
 		break;
 		default:
 			return __( 'Read more', 'woocommerce' );
 	}
	
 }

 /**
 * woocommerce_single_product_summary hook
 *
 * @hooked woocommerce_template_single_title - 5
 * @hooked woocommerce_template_single_price - 10
 * @hooked woocommerce_template_single_excerpt - 20
 * @hooked woocommerce_template_single_add_to_cart - 30
 * @hooked woocommerce_template_single_meta - 40
 * @hooked woocommerce_template_single_sharing - 50
 */
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 50 );


/**
 * Set default billing address 
 */
// add_action( 'profile_update', 'set_default_billing_address' );
add_action('wp_ajax_set_default_billing_address', 'set_default_billing_address');
function set_default_billing_address()
{
  $default_address_id = $_POST['post_id'];
  $author_ID = get_current_user_id();

  // Update other default to number 0
  $args = array(
    'post_type'      => 'user-address',
    'posts_per_page' => -1,
    'author'         => $author_ID
  );

  $query = new WP_Query( $args );

  if( $query->have_posts() ):
    while( $query->have_posts() ): $query->the_post();

      $post_id = get_the_id();
      $default_address_status = get_field('default_address');

      if( $default_address_status == 1 )
      {
        update_post_meta( $post_id, 'default_address', 0 );
      }

    endwhile;
  endif;

  // Update new default billing address
  update_post_meta( $default_address_id, 'default_address', 1 );

  // User Information
	$user_id	 = get_current_user_id();
	$user_info = get_userdata( $user_id );
	$firstname = $user_info->first_name;
	$lastname  = $user_info->last_name;
	$email 	   = $user_info->user_email;

	$address = get_field( 'address', $default_address_id );
	$street  = get_field( 'street', $default_address_id );
	$phone   = get_field( 'phone', $default_address_id );
	
	// Province
	$province_id  = get_field( 'province_2', $default_address_id );
	$province_obj = get_term_by( 'id', $province_id, 'location' );
	$province 	  = $province_obj->name;
  $postal_code  = get_term_meta($province_obj->term_id, 'code',true);
	
	// Distric
	$district_id  = get_field( 'district_2', $default_address_id );
	$district_obj = get_term_by( 'id', $district_id, 'location' );
	$district 	  = $district_obj->name;
	
	// Commune
	$commune_id  = get_field( 'commune_2', $default_address_id );
	$commune_obj = get_term_by( 'id', $commune_id, 'location' );
	$commune 	 = $commune_obj->name;

	$delivery_address = array(
		'firstname' => $firstname,
		'lastname'  => $lastname,
		'address' 	=> $address,
		'street' 	  => $street,
		'phone'		  => $phone,
    'postal_code' => $postal_code,
		'province'	=> $province,
		'district' 	=> $district,
		'commune' 	=> $commune,
		'email' 	  => $email,
	);
	
	//echo json_encode($delivery_address);

  update_user_meta( $user_id, 'shipping_address_1', $street );
  update_user_meta( $user_id, 'shipping_address_2', $commune.','.$district );
  update_user_meta( $user_id, 'shipping_city', $province );
  update_user_meta( $user_id, 'shipping_state', 'Cambodia' );
  update_user_meta( $user_id, 'billing_phone', $phone );

  // return result
  wp_send_json_success( $delivery_address );
  die();
}

// Get Shpping Zone
function get_shipping_zone_address() {
  $arr_string= '';
    $args = array(
        'taxonomy'  => 'location',
        'hide_empty' => false,
        'meta_query' => array(
            'relation' => 'OR',
            // array(
            //     'key'       => 'taxonomy_type',
            //     'value'     => 'commune',
            //     'compare'   => '='
            // ),
            array(
                'key'       => 'taxonomy_type',
                'value'     => 'district',
                'compare'   => '='
            ),
            array(
                'key'       => 'taxonomy_type',
                'value'     => 'province',
                'compare'   => '='
            )
        )
    );

    $terms = get_terms( $args );
    foreach($terms as $key => $term) {

        $id   = $term->term_id;
        $parent_id = wp_get_term_taxonomy_parent_id($id, 'location');
        $parent_name = get_term( $parent_id )->name ? ', '.get_term( $parent_id )->name : '';
        $name = $term->name;
        $code = get_term_meta( $id, 'code', true );
        $arr_string .= '"'.$code.'":"'.$name.$parent_name.'",';
    }

    $arr_object = (array) json_decode( '{'.substr_replace($arr_string, '', -1, 1).'}', true );
    $states['KH'] = $arr_object;

    return $states;
}
add_filter( 'woocommerce_states', 'get_shipping_zone_address' );


/**
 * Upload Warranty
 */
add_action( 'wp_ajax_file_upload', 'file_upload_callback' );
add_action( 'wp_ajax_nopriv_file_upload', 'file_upload_callback' );

function file_upload_callback() {

  $arr_img_ext = array('image/png', 'image/jpeg', 'image/jpg', 'application/pdf');
  if ( in_array($_FILES['file']['type'], $arr_img_ext) && isset( $_POST['product_id'] ) ) {
    
    // WordPress environment
    require( '../wp-load.php' );

    /**
     * Upload Warranty
     */
    $wordpress_upload_dir = wp_upload_dir();
    // $wordpress_upload_dir['path'] is the full server path to wp-content/uploads/2017/05, for multisite works good as well
    // $wordpress_upload_dir['url'] the absolute URL to the same folder, actually we do not need it, just to show the link to file
    $i = 1; // number of tries when the file with the same name is already exists
    
    $profilepicture = $_FILES['file'];
    $new_file_path = $wordpress_upload_dir['path'] . '/' . $profilepicture['name'];
    $new_file_mime = mime_content_type( $profilepicture['tmp_name'] );
    
    if( empty( $profilepicture ) )
      die( 'File is not selected.' );
    
    if( $profilepicture['error'] )
      die( $profilepicture['error'] );
    
    if( $profilepicture['size'] > wp_max_upload_size() )
      die( 'It is too large than expected.' );
    
    if( !in_array( $new_file_mime, get_allowed_mime_types() ) )
      die( 'WordPress doesn\'t allow this type of uploads.' );
    
    while( file_exists( $new_file_path ) ) {
      $i++;
      $new_file_path = $wordpress_upload_dir['path'] . '/' . $i . '_' . $profilepicture['name'];
    }
    
    // looks like everything is OK
    if( move_uploaded_file( $profilepicture['tmp_name'], $new_file_path ) ) {

      $upload_id = wp_insert_attachment( array(
        'guid'           => $new_file_path, 
        'post_mime_type' => $new_file_mime,
        'post_title'     => preg_replace( '/\.[^.]+$/', '', $profilepicture['name'] ),
        'post_content'   => '',
        'post_status'    => 'inherit'
      ), $new_file_path );

      // Update post_status attachment
      global $wpdb;
      $wpdb->update($wpdb->posts, ['post_status' => 'closed'], ['ID' => $upload_id]);

      // wp_generate_attachment_metadata() won't work if you do not include this file
      require_once( ABSPATH . 'wp-admin/includes/image.php' );
    
      // Generate and save the attachment metas into the database
      wp_update_attachment_metadata( $upload_id, wp_generate_attachment_metadata( $upload_id, $new_file_path ) );


      /**
       * Chcek old warranty if exist
       */
      $product_id = $_POST['product_id'];
      $args = array(
        'post_type'     => 'e-warranty',
        'post_status'   => 'publish',
        'meta_query' => array(
          array(
            'key' => 'product_id',
            'value' => $product_id,
            'compare' => '='
          )
        )
      );
    
      $query = get_posts( $args );
      $warranty_id = $query[0]->ID;
      $count_post = count( $query );

      if( $count_post <= 0 ) {
        // Insert Post Warrantry
        $customer_id = get_current_user_id();
        $customer_info = get_userdata( $customer_id );
        $customer_name = $customer_info->display_name;

        $post = array(
          'post_type'       => 'e-warranty',
          'post_title'      => $customer_name.' | E - Warranty',
          'post_name'       => $customer_name.' | E - Warranty',
          'post_status'     => 'publish',
          'post_author'     => $user_id,
        );
        $post_id = wp_insert_post( $post );

        // Insert attachment meta
        add_post_meta( $post_id, 'warranty_file', $upload_id, false);

        // Insert Product id
        add_post_meta( $post_id, 'product_id', $product_id, false);

        // Inester Status Pedding
        add_post_meta( $post_id, 'warranty_status', 0, false);

        // Return success message
        wp_send_json_success( array(
          'message' => 'File is valid, and upload was successfully uploaded.',
          'status' => 200
        ) );
      }
      else {
        // Update attachment meta
        update_post_meta( $warranty_id, 'warranty_file', $upload_id, false);

        // Return success message
        wp_send_json_success( array(
          'message' => 'File is valid, and update was successfully uploaded.',
          'status' => 200
        ) );
      }
      
      // Show the uploaded file in browser
      wp_redirect( $wordpress_upload_dir['url'] . '/' . basename( $new_file_path ) );
    }
  }
  else {
    // Return success message
    wp_send_json_success( array(
      'message' => 'File is invalid, please check your file and try again.',
      'status' => 403
    ) );
  }

  wp_die();
}

/**
 * 
 *  API Google Map
 */

function my_acf_init() {
  acf_update_setting('google_api_key', 'AIzaSyCeklFOynIMeNsGu8qXNztlUeyDmPmasxc');
}
add_action('acf/init', 'my_acf_init');

/**
 * Undocumented function
 *
 * @param [string] $subject
 * @param [string] $message
 * @param array $attachments
 * @return void
 */
function send_mail()
{
// /$subject, $message, $attachments = array()
  /**
    * Send email to admin
    */
  $subject = "Warranty";
  $attachments = '';
  $message = '
      Dear Sir or Madam, Mr/Ms has been subscribe Phum Electronic.

      <table>
        <thead>
          <tr align="left">
            <th colspan="2">Subscriber\'s Information</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>Name</td>
          </tr>
          <tr>
            <td>Email</td>
          </tr>
          <tr>
            <td>Phone</td>
          </tr>
        </tbody>
      </table>';
  // send_mail($subject, $message, $attachments);

  // $url = "//".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
  // header("Location: ".$url."");
  // exit;

	$title = 'App';
	$to = 'souengsopheap@gmail.com'; //get_field('send_to_mail','option');
	$headers = array('Content-Type: text/html; charset=UTF-8');
	
	wp_mail( $to, $subject, $message, $headers, $attachments);
}
add_action( 'init', 'send_mail' );

function register_by_social( $user_id ) {

    $user = new WP_User( $user_id );
    $user->remove_role( 'subscriber' );
    $user->set_role('customer');
    
    update_user_meta( $user_id, 'register_status', 1 );

    // Register user to odoo
    do_action( 'biz_customer_successfully_registered', $user_id );
}
  add_action('nsl_register_new_user','register_by_social');

  //@User Information
function upload_warentty() 
{ 
  global $wpdb;
  // WordPress environment
  include( '../wp-load.php' );

	if(isset($_POST['btn_save_warentty'])) { 

	  $name           = $_POST['username'];
		$phone   	      = $_POST['phone_number'];
		$invoice_number = $_POST['invoice_number'];
		$order_date     = $_POST['order_date'];

    $upload_war_id = wp_insert_post(array (
			'post_type'   => 'e-warranty',
			'post_title'  => 'E-Warentty',
			'post_status' => 'publish',
		));
    
    /**
     * Upload Warranty
     */
    $wordpress_upload_dir = wp_upload_dir();
    $i = 1; // number of tries when the file with the same name is already exists
    
    $profilepicture = $_FILES['attachment'];
    $new_file_path = $wordpress_upload_dir['path'] . '/' . $profilepicture['name']; 
    $new_file_mime = mime_content_type( $profilepicture['tmp_name'] );
    
    if( empty( $profilepicture ) )
      die( 'File is not selected.' );
    
    if( $profilepicture['error'] )
      die( $profilepicture['error'] );
    
    if( $profilepicture['size'] > wp_max_upload_size() )
      die( 'It is too large than expected.' );
    
    if( !in_array( $new_file_mime, get_allowed_mime_types() ) )
      die( 'WordPress doesn\'t allow this type of uploads.' );
    
    while( file_exists( $new_file_path ) ) {
      $i++;
      $new_file_path = $wordpress_upload_dir['path'] . '/' . $i . '_' . $profilepicture['name'];
    }
    
    // looks like everything is OK
    if( move_uploaded_file( $profilepicture['tmp_name'], $new_file_path ) ) {
      $upload_id = wp_insert_attachment( array(
        'guid'           => $new_file_path, 
        'post_mime_type' => $new_file_mime,
        'post_title'     => preg_replace( '/\.[^.]+$/', '', $profilepicture['name'] ),
        'post_content'   => '',
        'post_status'    => 'inherit',//'inherit'
      ), $new_file_path );

      // Update post_status attachment
      // global $wpdb;
      $wpdb->update($wpdb->posts, ['post_status' => 'closed'], ['ID' => $upload_id]);

      // wp_generate_attachment_metadata() won't work if you do not include this file
      require_once( ABSPATH . 'wp-admin/includes/image.php' );
    
      // Generate and save the attachment metas into the database
      wp_update_attachment_metadata( $upload_id, wp_generate_attachment_metadata( $upload_id, $new_file_path ) );
    }	
    
    if ($upload_war_id) {
			// insert post meta
			add_post_meta($upload_war_id, 'username', $name);
			add_post_meta($upload_war_id, 'phone_number', $phone);
			add_post_meta($upload_war_id, 'invoice_number', $invoice_number);
			add_post_meta($upload_war_id, 'order_date', $order_date);
      add_post_meta($upload_war_id, 'attachment', $upload_id, false);

			echo '
			<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
			<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
			<script type="text/javascript">
				$(document).ready(function() {
					swal({
						title: "Upload Success",
						text: "Thank for upload your E-Warentty.",
						icon: "success",
						showConfirmButton: false,
						timer: 5000
					});
				});				
			</script>
			';
		}
	} 
}
add_action('init', 'upload_warentty');

// @Set webview prefix
if( isset( $_GET['webview'] ) )
{
    $webview = $_GET['webview'];
    $style = '';
    if($webview == 'true')
    {
?>

      <style>
        .page-id-9308 .mean-container ,
        .page-id-9308 #content ,
        .page-id-9308 footer ,
        .page-id-9308 .banner ,
        .page-id-9308 header {
          display: none !important;
        }
        .fb_dialog_content iframe {
          bottom: 24px !important;
          right: 12px !important;
          display: none !important;
        }
		.checkout .woocommerce-checkout-review-order-table a.remove{
          display: none;
        }
        .checkout table.shop_table tbody tr td {
            padding: 15px 5px 15px 0 !important;
        }  
      </style>

<?php
    }
}


// Add Pickup From Warehouse to order list
// add_action( 'init', 'register_custom_post_status', 10 );
// function register_custom_post_status() {
//     register_post_status( 'wc-pickup-from-warehouse', array(
//         'label'                     => _x( 'Pickup From Warehouse', 'Order status', 'woocommerce' ),
//         'public'                    => false,
//         'exclude_from_search'       => false,
//         'show_in_admin_status_list' => true,
//         'show_in_admin_all_list'    => true,
//         'label_count'               => _n_noop( 'Pickup From Warehouse<span class="count">(%s)</span>', 'Pickup From Warehouse <span class="count">(%s)</span>' )
//     ) );

//     register_post_status( 'wc-deliverying', array(
//         'label'                     => _x( 'Deliverying', 'Order status', 'woocommerce' ),
//         'public'                    => false,
//         'exclude_from_search'       => false,
//         'show_in_admin_status_list' => true,
//         'show_in_admin_all_list'    => true,
//         'label_count'               => _n_noop( 'Deliverying<span class="count">(%s)</span>', 'Deliverying <span class="count">(%s)</span>' )
//     ) );

//     register_post_status( 'wc-shipment-arrival', array(
//         'label'                     => _x( 'Shipment Arrival', 'Order status', 'woocommerce' ),
//         'public'                    => false,
//         'exclude_from_search'       => false,
//         'show_in_admin_status_list' => true,
//         'show_in_admin_all_list'    => true,
//         'label_count'               => _n_noop( 'Shipment Arrival<span class="count">(%s)</span>', 'Shipment Arrival <span class="count">(%s)</span>' )
//     ) );
// }

// add_filter( 'wc_order_statuses', 'custom_wc_order_statuses_deliverying' );
// function custom_wc_order_statuses_deliverying( $order_statuses ) {
//     $order_statuses['wc-deliverying'] = _x( 'Deliverying', 'Order status', 'woocommerce' );
//     return $order_statuses;
// }

// add_filter( 'wc_order_statuses', 'custom_wc_order_statuses_shipment_arrival' );
// function custom_wc_order_statuses_shipment_arrival( $order_statuses ) {
//     $order_statuses['wc-shipment-arrival'] = _x( 'Shipment Arrival', 'Order status', 'woocommerce' );
//     return $order_statuses;
// }





// @custom order status
// add_filter( 'woocommerce_register_shop_order_post_statuses', 'bbloomer_register_custom_order_status' );
// function bbloomer_register_custom_order_status( $order_statuses ){
    
//    // Status must start with "wc-"
//    $order_statuses['wc-custom-status'] = array(                                 
//    'label'                     => _x( 'Pickup From Warehouse', 'Order status', 'woocommerce' ),
//    'public'                    => false,                                 
//    'exclude_from_search'       => false,                                 
//    'show_in_admin_all_list'    => true,                                 
//    'show_in_admin_status_list' => true,                                 
//    'label_count'               => _n_noop( 'Pickup From Warehouse <span class="count">(%s)</span>', 'Pickup From Warehouse <span class="count">(%s)</span>', 'woocommerce' ),                              
//    );      
//    return $order_statuses;
// }
 
// ---------------------
// 2. Show Order Status in the Dropdown @ Single Order and "Bulk Actions" @ Orders
// add_filter( 'wc_order_statuses', 'bbloomer_show_custom_order_status' );
// function bbloomer_show_custom_order_status( $order_statuses ) {      
//    $order_statuses['wc-custom-status'] = _x( 'Pickup From Warehouse', 'Order status', 'woocommerce' );       
//    return $order_statuses;
// }
 
// add_filter( 'bulk_actions-edit-shop_order', 'bbloomer_get_custom_order_status_bulk' );
// function bbloomer_get_custom_order_status_bulk( $bulk_actions ) {
//    // Note: "mark_" must be there instead of "wc"
//    $bulk_actions['mark_custom-status'] = 'Change status to Pickup From Warehouse';
//    return $bulk_actions;
// }
 
// ---------------------
// 3. Set Custom Order Status @ WooCommerce Checkout Process
 
add_action( 'woocommerce_thankyou', 'bbloomer_thankyou_change_order_status' );
function bbloomer_thankyou_change_order_status( $order_id ){
   if( ! $order_id ) return;
   $order = wc_get_order( $order_id );
 
   // Status without the "wc-" prefix
   //$order->update_status( 'custom-status' );
}

function detect_webview() {    
    $webview = $_GET['webview'];
    if($webview == 'true') {
        echo '<style type="text/css">
          .page-id-1658 .site-content .content-area {
              padding: 10px 0;
          }
          .page-template-default {
            background-color: #eceade;
          }
          .mean-bar ,
          .site-footer ,
          .woocommerce-MyAccount-navigation ,
          .banner ,
          .mean-bar {
              display: none !important;
            }
        </style>';
    }
}
add_action('init','detect_webview');

/**
 * @search news
 */
function search_data() {
  if(isset($_POST['btn_search'])) {
      $value = $_POST['search_box'];
      $url = site_url().'/?s='.$value;
      wp_redirect( $url, 301 ); exit;
  }
}
add_action('init','search_data');

// @Create new account hook url
function hook_url_create_new_account() {
  if(isset($_POST['btn_create_new_account'])) {
      $url = site_url().'/my-account?action=register';
      wp_redirect( $url, 301 ); exit;
  }
}
add_action('init','hook_url_create_new_account');


// Declare WooCommerce support.
add_theme_support( 'woocommerce', apply_filters( 'storefront_woocommerce_args', array(
  'single_image_width' => 416,
  'thumbnail_image_width' => 324,
  'product_grid' => array(
    'default_columns' => 3,
    'default_rows' => 4,
    'min_columns' => 1,
    'max_columns' => 6,
    'min_rows' => 1
  )
) ) );
add_filter( 'woocommerce_store_api_disable_nonce_check', '__return_true' );

 //Display all reviews
 if (!function_exists('display_all_reviews')) {
	function display_all_reviews(){
		//Pagination setup
		$reviews_per_page = 10;
		$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
		$offset = ($paged - 1) * $reviews_per_page;
    
		$args = array(
			'status' => 'approve',
			'type' => 'review',
			'number' => $reviews_per_page,
			'offset' => $offset
		);

		// The Query
		$comments_query = new WP_Comment_Query;
		$comments = $comments_query->query( $args );
    // Comment Loop
		if ( $comments ) {
			echo "<ol>";
			foreach ( $comments as $comment ): ?>
			<?php if ( $comment->comment_approved == '0' ) : ?>
				<p class="meta waiting-approval-info">
					<em><?php _e( 'Thanks, your review is awaiting approval', 'woocommerce' ); ?></em>
				</p>
				<?php endif;  ?>
				<li itemprop="reviews" class="list-reviews" itemscope itemtype="http://schema.org/Review" <?php comment_class(); ?> id="li-review-<?php echo $comment->comment_ID; ?>">
					<div id="review-<?php echo $comment->comment_ID; ?>" class="review_container">
            <div class="review-detail">
              <div class="review-avatar">
                  <?php echo get_avatar( $comment->comment_author_email, $size = '50' ); ?>
                </div>
                <div class="review-author">
                  <strong class="review-author-name" itemprop="author"><?php echo $comment->comment_author; ?></strong>								
                  <?php
                    $timestamp = strtotime( $comment->comment_date ); //Changing comment time to timestamp
                    $date = date('F d, Y', $timestamp);
                  ?>
                  <span class="review-date">
                    <time itemprop="datePublished" datetime="<?php echo $comment->comment_date; ?>"><?php echo $date; ?></time>
                  </span>
              </div>
            </div>
            <div class='star-rating-container'>
                <div itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating" class="star-rating" title="<?php echo esc_attr( get_comment_meta( $comment->comment_ID, 'rating', true ) ); ?>">
                  <span style="width:<?php echo get_comment_meta( $comment->comment_ID, 'rating', true )*22; ?>px"><span itemprop="ratingValue"><?php echo get_comment_meta( $comment->comment_ID, 'rating', true ); ?></span> <?php _e('out of 5', 'woocommerce'); ?></span>					
                </div>
            </div>
        </div>
          <div class="review-text">
              <div class="review-image">
                <?php 
                  $product_thumbnail_url = '';
                  $product_id = $comment->comment_post_ID;

                  $product = wc_get_product( $product_id );
                  $permalink = $product->get_permalink();

                  $image = wp_get_attachment_image_src( get_post_thumbnail_id($product_id), 'single-post-thumbnail' );
                  $product_thumbnail = $image[0];
                  if(!empty($product_thumbnail)) {
                    $product_thumbnail_url = $image[0];
                  }
                  else {
                    $product_thumbnail_url = 'https://via.placeholder.com/100';
                  }
                ?> 
              
                 <a href="<?php echo $permalink; ?>"><img width="100px" src="<?php echo $product_thumbnail_url; ?>" alt=""></a>
              </div>
              <div itemprop="description" class="rev-description">
                <?php echo $comment->comment_content; ?>
              </div>
          </div>		
			</li>

			<?php 
			endforeach;
			echo "</ol>";
			//Pagination output
			echo "<div class='navigation'>";
				$all_approved_product_review_count = get_comments(array(
					'status'   => 'approve',
					'type' => 'review',
					'count' => true
				));
				if( is_float($all_approved_product_review_count / $reviews_per_page) ){
					//In case the value is float, we need to make sure there is an additional page for the final results
					$additional = 1;
				}else{
					$additional = 0;
				}
				$max_num_pages = intval( $all_approved_product_review_count / $reviews_per_page ) + $additional;  
				$current_page = max(1, get_query_var('paged'));
		
				echo paginate_links(array(
					'total' => $max_num_pages
				));
			echo "</div>";
			
		} else {
			echo "This product hasn't been rated yet.";
		}
	}
}


//@get order notifications
// function get_order_notification() {
//   global $wpdb;
//   $user_id = get_current_user_id();
//   $all_order_notifications = $wpdb->get_results("SELECT * FROM revo_notification WHERE user_id = $user_id AND type = 'order' ORDER BY id DESC");
  
//   foreach($all_order_notifications as $value) {
//     $order_id = $value->target_id;
//     $message  = $value->message;
//   }
// }
// add_action('init' ,'get_order_notification');

 function get_public_notification() {
  global $wpdb;
  $post_id = $_POST['id'];
  $all_notifications = $wpdb->get_row("SELECT * FROM revo_mobile_variable WHERE is_deleted = 0 AND id = $post_id ");

  $image = $all_notifications->image;
  $title = $all_notifications->title;
  $description = $all_notifications->description;
  $created_at = $all_notifications->created_at;

  wp_send_json_success(['image'=>$image,'title'=>$title,'desc'=>$description,'created_at'=>$created_at]);

 }

add_action('wp_ajax_get_public_notification', 'get_public_notification');
add_action('wp_ajax_nopriv_get_public_notification', 'get_public_notification');


// Order Notification
function get_order_notification() {
  global $wpdb;
  $user_id = get_current_user_id();
  $id = $_POST['id'];
  $all_order_notifications = $wpdb->get_row("SELECT * FROM revo_notification WHERE id = $id AND user_id = $user_id");

  $order_id = $all_order_notifications->target_id;
  $order = wc_get_order( $order_id );
  $shipping_first_name = $order->get_shipping_first_name();
  $shipping_last_name  = $order->get_shipping_last_name();
  $billing_phone        = $order->get_billing_phone();
  $shipping_address_1  = $order->get_shipping_address_1();
  $shipping_city       = $order->get_shipping_city();
  $shipping_country    = $order->get_shipping_country();
  $payment_method = $order->get_payment_method();
  $order_reciept = $order->get_view_order_url();
  $total = $order->get_total();
  $order_subtotal = $order->get_subtotal();
  $shipping_total = $order->get_shipping_total();
  $item_total = $order->get_item_count();
  $payment_title = $order->get_payment_method_title();
  $display_shipping = $order->get_shipping_to_display();
  $items = $order->get_items();
  foreach ( $items as $item ) {
    $product_name = $item->get_name();
    $product_id = $item['product_id'];
  }
  $image = wp_get_attachment_image_src( get_post_thumbnail_id($product_id), 'single-post-thumbnail' );

  wp_send_json_success([
    'firstname' =>  $shipping_first_name,
    'lastname'  =>  $shipping_last_name,
    'phone'     =>  $billing_phone,
    'address'   =>  $shipping_address_1,
    'city'      =>$shipping_city,
    'country'   =>$shipping_country,
    'payment_method' => $payment_method,
    'order_receipt' => $order_reciept,
    'order_subtotal' => $order_subtotal,
    'total' => $total,
    'shipping_total'=> $shipping_total,
    'item_total' => $item_total,
    'payment_title' => $payment_title,
    'product_name' => $product_name,
    'display_shipping' => $display_shipping,
    'image' => $image[0]
  ]);

}

add_action('wp_ajax_get_order_notification', 'get_order_notification');
add_action('wp_ajax_nopriv_get_order_notification', 'get_order_notification');


add_action( 'rest_api_init', function () {
  register_rest_route( 'aba/v1', '/check_tansaction', array(
    'methods' => 'GET',
    'callback' => 'api_aba_check_transaction',
  ) );
} );

function api_aba_check_transaction() {
  $consumer_key     = isset($_GET['consumer_key'])    ? $_GET['consumer_key'] : '';
  $consumer_secret  = isset($_GET['consumer_secret']) ? $_GET['consumer_secret'] : '';

  if(CONSUMER_KEY == $consumer_key && CONSUMER_SECRET == $consumer_secret){
    require_once( ABSPATH . 'wp-content/plugins/aba-payway-woocommerce-payment-gateway/PayWayApiCheckout.php' );
    $tran_id = isset($_GET['tran_id']) ? $_GET['tran_id'] : '';
    $result = aba_PAYWAY_AIM::getTransaction($tran_id); 
    $result['status'] = isset($result['status']) && $result['status'] == 0 ? 'success' : 'fail';
    
    // Clear cart after tran payment success
    // Request from mobile 
    if($result['status'] == 'success'){     
      $cookie  = isset($_GET['cookie']) ? $_GET['cookie'] : '';
      biz_write_log($cookie, "api_aba_check_transaction");
      if(!empty($cookie)){
        clear_cart_by_cookie($cookie);
      }
    }
    return $result;   
  }

  return [
    'status'  => 'fail',
    'error'   => 'Unauthenticated!'
  ];
}

function clear_cart_by_cookie($cookie){
  $curl = curl_init();
  curl_setopt_array($curl, array(
  CURLOPT_URL => site_url().'/wp-json/wc/store/cart/items',
  CURLOPT_SSL_VERIFYHOST => 0,
  CURLOPT_SSL_VERIFYPEER => 0,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'DELETE',
  //CURLOPT_POSTFIELDS =>'',
  CURLOPT_HTTPHEADER => array(
    'Cookie: '.$cookie.'',
    'Content-Type: application/json'
    ),
  ));
  $response = curl_exec($curl);
  biz_write_log($response, "clear_cart_by_cookie_response");
  curl_close($curl);
  return json_decode($response);  
}

// Get Deeplink
add_action('wp_ajax_get_aba_deeplink', 'get_aba_deeplink');
add_action('wp_ajax_nopriv_get_aba_deeplink', 'get_aba_deeplink');
function get_aba_deeplink() {

    require_once( ABSPATH . 'wp-content/plugins/aba-payway-woocommerce-payment-gateway/PayWayApiCheckout.php' );
    $aba_PAYWAY_AIM = new aba_PAYWAY_AIM();

    // Get Params
    $tran_id        = isset($_POST['tran_id']) ? $_POST['tran_id'] : '';
    $amount         = isset($_POST['amount']) ? $_POST['amount'] : '';
    $firstname      = isset($_POST['firstname']) ? $_POST['firstname'] : '';
    $lastname       = isset($_POST['lastname']) ? $_POST['lastname'] : '';
    $email          = isset($_POST['email']) ? $_POST['email'] : '';
    $phone          = isset($_POST['phone']) ? $_POST['phone'] : '';
    $items          = isset($_POST['items']) ? $_POST['items'] : '';

    $api_url = aba_PAYWAY_AIM::getEndpointApiUrl();
    $curl = curl_init();
    $reqTime = date("YmdHis");
    $payment_option = 'abapay_deeplink';
    $merchant_id = aba_PAYWAY_AIM::getMerchantId();
    $return_url = base64_encode($aba_PAYWAY_AIM->getReturnURL());
    $return_params = "json";

    $post_data = array(
        'req_time'          =>      $reqTime,
        'merchant_id'       =>      $merchant_id,
        "tran_id"           =>      $tran_id,
        "amount"            =>      $amount,
        "firstname"         =>      $firstname,
        "lastname"          =>      $lastname,
        "email"             =>      $email,
        "phone"             =>      $phone,
        "payment_option"    =>      $payment_option,
        "return_url"        =>      $return_url,
        "return_params"     =>      $return_params,
        "items"             =>      $items,
    );
    $hash = getHash($post_data);
    $post_data['hash'] = $hash;
    curl_setopt_array($curl, array(
        CURLOPT_URL => $api_url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_REFERER => 'https://staging.phumelectronic.com',
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => $post_data,
        CURLOPT_SSL_VERIFYPEER => false,
    ));

    $response = curl_exec($curl);
    $response = json_decode($response, true);
    curl_close($curl);
    $deeplink = isset($response['abapay_deeplink']) ? $response['abapay_deeplink'] : '';

    wp_send_json_success([
        'deeplink'  =>  $deeplink,
    ]);
}


// woocommerce custom response order object [payment_method_title]
function get_selected_payment_method_aba( $response, $object, $request ) {
 
  if(isset($response->data['meta_data'])){
    $payment_method_title = '';
    foreach($response->data['meta_data'] as $meta){
      if($meta->key == 'aba_choosen_method'){
        if($meta->value == 'aba_payway_aim_card') {
          $payment_method_title = 'Credit/Debit Card';
        }else if($meta->value == 'aba_payway_aim'){
          $payment_method_title = 'ABA PAY';
        }
        break;
      }
    }

    if(!empty($payment_method_title)){
      $response->data['payment_method_title'] = $payment_method_title;
    }
  }

  return $response;
} 
add_filter( "woocommerce_rest_prepare_shop_order_object", "get_selected_payment_method_aba", 10, 3 );


function get_commune_code() {
  $commune_id = isset($_POST['commune_id']) ? $_POST['commune_id'] : '';
  $code = get_term_meta($commune_id, 'code', true);
  wp_send_json_success(['code'=>$code]);
 }

add_action('wp_ajax_get_commune_code', 'get_commune_code');
add_action('wp_ajax_nopriv_get_commune_code', 'get_commune_code');


function demo1() {
  $post_id = 13891;
  $user_id = 1233;

  $user_use = $old_user = get_post_meta( $post_id, 'user_used_coupon', true);
  // $old_user_obj = json_decode($old_user);
  if(empty($old_user_obj)) :
    update_post_meta( $post_id, 'user_used_coupon', '['. $user_id .']' );
  endif;

  $oldID = "";
  if(!empty($old_user_obj)) :
    foreach($old_user_obj as $value):
     $oldID .= $value.',';
    endforeach;

    $new_user_obj = '['.$oldID.$user_id.']';
    update_post_meta( $post_id, 'user_used_coupon', $new_user_obj);
  endif;
}
add_action('init','demo1', 10, 3);

// add_action( 'post_updated', 'check_values', 10, 3 );
function check_values($post_ID, $post_after, $post_before){
	date_default_timezone_set("Asia/Bangkok");
	date_default_timezone_get();
	$date_order  = date("Y-m-d : H:i:s");
	$status      = get_field('packing_order',$post_ID);
	$status_1    = get_field('delivery_order',$post_ID);
	$status_2    = get_field('arrived',$post_ID);
	$status_3    = get_field('confirm_received',$post_ID);

	$date_packing_order    = get_field('date_packing_order',$post_ID);
	$date_delivery_order   = get_field('date_delivery_order',$post_ID);
	$date_arrived          = get_field('date_arrived',$post_ID);
	$date_confirm_received = get_field('date_confirm_received',$post_ID);

  $order = wc_get_order( $post_ID );
  // update_post_meta( $post_ID ,'online_payment' ,'paid');

  if(!empty($status)){
		if(empty($date_packing_order)){
			update_post_meta($post_ID,'date_packing_order',$date_order);
		}else{
			echo '';
		}
	}

	if(!empty($status_1)){
		if(empty($date_delivery_order)){
			update_post_meta($post_ID,'date_delivery_order',$date_order);
		}else{
			echo '';
		}
	}

	if(!empty($status_2)){
		if(empty($date_arrived)){
			update_post_meta($post_ID,'date_arrived',$date_order);
		}else{
			echo '';
		}
	}

	if(!empty($status_3)){
		if(empty($date_confirm_received)){
			update_post_meta($post_ID,'date_confirm_received',$date_order);
      $order->update_status('completed', 'order_note');
		}else{
			echo '';
		}
	}
}
add_action('save_post','check_values',10,3);


//@ validate coupon
add_action('acf/validate_save_post', 'my_acf_validate_save_post');
function my_acf_validate_save_post() {

    // Remove all errors if user is an administrator.
    if( current_user_can('manage_options') ) {
        acf_reset_validation_errors();
    }

    // Remove all errors if user is an administrator.
    if(isset($_POST['post_type']) && $_POST['post_type']=="shop_coupon") {

        // post_title
        if( empty($_POST['post_title']) ) {
          acf_add_validation_error( $_POST['post_title'], 'Required coupon code.');
        }

        // description
        if( empty($_POST['excerpt']) ) {
          acf_add_validation_error( $_POST['excerpt'], 'Required description.');
        }

        // expiry_date
        if( empty($_POST['expiry_date']) ) {
          acf_add_validation_error( $_POST['expiry_date'], 'Required expiry date.');
        }

        //usage_limit
        if( empty($_POST['usage_limit']) ) {
          acf_add_validation_error( $_POST['usage_limit'], 'Required Usage limit per coupon.');
        }

        // usage_limit_per_user
        if( empty($_POST['usage_limit_per_user']) ) {
          acf_add_validation_error( $_POST['usage_limit_per_user'], 'Required usage limit per user.');
        }
    }
}
// add_filter( 'woocommerce_coupon_get_discount_amount', 'filter_woocommerce_coupon_get_discount_amount', 10, 5 );
// function filter_woocommerce_coupon_get_discount_amount( $discount, $discounting_amount, $cart_item, $single, $instance ) {
//   $product = $cart_item['data'];
//   $sale_price = $product->get_sale_price();
//   $date_on_sale_from = $product->get_date_on_sale_from();
//   $date_on_sale_from_format = date_format($date_on_sale_from,"Y/m/d");
//   $date_on_sale_to = $product->get_date_on_sale_to();
//   $date_on_sale_to_format = date_format($date_on_sale_to,"Y/m/d");

//     if(isset($sale_price) && $sale_price != ''){
//       if(is_null($date_on_sale_from) || ( date('Y/m/d') >= date('Y-m-d', strtotime($date_on_sale_from_format. ' + 1 days')))) {
//         if(is_null($date_on_sale_to) || ( date("Y/m/d") <= date('Y-m-d', strtotime($date_on_sale_to_format. ' + 1 days')))) {
//           return 0;
//         }
//       }
//     }
//     return $discount; 
// }


//hide product cambo  when expire date 
add_action( 'pre_get_posts', 'auto_hide_products_from_shop_page' );
function auto_hide_products_from_shop_page( $q ) {
  if ( ! is_admin() ) {
      global $wpdb;
      $data = [];
      $current_date       = date('Y-m-d : H:i:s');
      $productExpired = $wpdb->get_results("SELECT post_id FROM ph0m31e_postmeta WHERE meta_key='cambo_exspire_date' AND meta_value<>'' AND meta_value < '$current_date';");
      foreach($productExpired as $p){
        $data[] = $p->post_id;
      }
      wp_reset_query();
      $q->set( 'post__not_in',$data);
  }
}

add_action( 'woocommerce_after_calculate_totals', 'auto_add_coupons_total_based', 10, 1 );
function auto_add_coupons_total_based( $cart ) {
  foreach($cart->get_coupons() as $cart_key => $cart_value){
    if($cart_key){
      $is_has_discount = 0;
      $normal_pro = 0;
      $sale = 0;
      foreach($cart->get_cart() as $cart_item_key => $cart_item){
        $product = $cart_item['data'];
        $sale_price = $product->get_sale_price(); 
        $date_on_sale_from = $product->get_date_on_sale_from();
        $date_on_sale_from_format = date_format($date_on_sale_from,"Y/m/d");

        $date_on_sale_to = $product->get_date_on_sale_to();
        $date_on_sale_to_format = date_format($date_on_sale_to,"Y/m/d");

        if(isset($sale_price) && $sale_price != ''){
          if(is_null($date_on_sale_from) || ( date('Y/m/d') >= date('Y/m/d', strtotime($date_on_sale_from_format. ' + 1 days')))) {
            if(is_null($date_on_sale_to) || ( date("Y/m/d") <= date('Y/m/d', strtotime($date_on_sale_to_format. ' + 1 days')))) {
              $is_has_discount += 1;
              $sale += $product->get_sale_price();   
            }
          }
        }
        else {
            $coupons     = $cart->get_coupons();
            $normal_pro +=  $cart_item['line_subtotal']; 
            $coupon_discounts = [];
            $total = 0;
            foreach ($coupons as $coupon){
                if($coupon->discount_type == 'percent') {
                    $coupon_discount = ($normal_pro * $coupon->amount) / 100;
                    $coupon_discounts [$coupon->code] = round($coupon_discount,2);
                    $total += round($coupon_discount,2);
                }
                else {
                    $total += $coupon->amount;
                    $coupon_discounts [$coupon->code] = $coupon->amount;
                }
            }

            $order_total = $normal_pro + $sale - $total;

            $cart->set_discount_total($total);
            $cart->set_coupon_discount_totals($coupon_discounts);
            $cart->set_total($order_total);
        }
        
      }
      if(count($cart->get_cart()) == $is_has_discount){
        $cart->remove_coupon($cart_key);
        $cart->set_coupon_discount_totals([]);
        wc_clear_notices();
        wc_add_notice("You cannot apply coupon with product has discount!", "error");
      }
    }
  }
}


// add_action( 'woocommerce_after_calculate_totals', 'auto_add_coupons_total_based', 10, 1 );
// function auto_add_coupons_total_based( $cart ) {

//     $shipping_fee = (float)$cart->get_shipping_total();
//     $coupons      = $cart->get_coupons();
//     $sub_total    = $cart->get_subtotal();

//     $items = $cart->products();


//     $coupon_discounts = [];
//     $total = 0;
//     foreach ($coupons as $coupon){
//         if($coupon->discount_type == 'percent') {
//             $coupon_discount = ($sub_total * $coupon->amount) / 100;
//             $coupon_discounts [$coupon->code] = round($coupon_discount,2);
//             $total += round($coupon_discount,2);
//         }
//         else {
//             $total += $coupon->amount;
//             $coupon_discounts [$coupon->code] = $coupon->amount;
//         }
//     }

//     $order_total = $sub_total + $shipping_fee - $total;

//     $cart->set_discount_total($total);
//     $cart->set_coupon_discount_totals($coupon_discounts);
//     $cart->set_total($order_total);
// }


// @Add Default Payment Method
add_action('woocommerce_review_order_before_payment', function(){
  $current = WC()->session->get( 'chosen_payment_method' );
  $gateways = WC()->payment_gateways()->payment_gateways();
  if(empty($current)){  
      $gateway = $gateways['cod'];
      $gateway->chosen = true;
  }
});
add_filter( 'woocommerce_cart_item_price', 'bbloomer_change_cart_table_price_display', 30, 3 );
  
function bbloomer_change_cart_table_price_display( $price, $values, $cart_item_key ) {
   $slashed_price = $values['data']->get_price_html();
   $is_on_sale = $values['data']->is_on_sale();
   if ( $is_on_sale ) {
      $price = $slashed_price;
   }
   return $price;
}

// add_filter( 'woocommerce_get_price_html', 'modify_woocommerce_get_price_html', 10, 2 );

// function modify_woocommerce_get_price_html( $price, $product ) {
//     if( $product->is_on_sale() && ! is_admin() )
//         return $price . sprintf( __('<p>Save %s</p>', 'woocommerce' ), $product->regular_price - $product->sale_price );
//     else
//         return $price;
// }

add_filter( 'woocommerce_cart_item_subtotal', 'ts_show_product_discount_order_summary', 10, 3 );
 
function ts_show_product_discount_order_summary( $total, $cart_item, $cart_item_key ) {
     
    //Get product object
    $_product = $cart_item['data'];
     
    //Check if sale price is not empty
    if( '' !== $_product->get_sale_price() ) {
         
        //Get regular price of all quantities
        $regular_price = $_product->get_regular_price() * $cart_item['quantity'];
         
        //Prepend the crossed out regular price to actual price
        $total = '<span style="text-decoration: line-through; opacity: 0.5; padding-right: 5px;">' . wc_price( $regular_price ) . '</span>' . $total;
    }
     
    // Return the html
    return $total;
}

// hook diplay sale price and regular price when click detail product
add_filter( 'woocommerce_get_price_html', 'wpa83367_price_html', 100, 2 );
function wpa83367_price_html( $price, $product ){
    $variant_ids = $product->get_children();
    $variant_id = json_encode($variant_ids[0]);
		if(!empty($variant_ids)){
			$on_sale = wc_get_product($variant_id)->is_on_sale();
			if($on_sale == '1'){
				$price =  wc_get_product($variant_id)->get_price();
				$price = number_format($price, 2, '.', '');
				$sale_price = wc_get_product($variant_id)->get_sale_price();
				$sale_price = number_format($sale_price, 2, '.', '');
				$regular_price = wc_get_product($variant_id)->get_regular_price();
				$date_on_sale_from = strtotime(wc_get_product($variant_id)->get_date_on_sale_from());

				if($regular_price !== $sale_price && $sale_price === $price){
					$regular_prices = '<del style="color: #458200;font-size: 14px !important;">'.'$'.number_format($regular_price, 2, '.', '').'</del>';
					$sale_prices = '<span style="color: #458200;font-size: 14px !important;">'.'$'.number_format($sale_price, 2, '.', '').'</span>';
				}
			}else{
				$regular_prices = wc_get_product($variant_id)->get_regular_price();
        $regular_prices = '<span style="color: #458200; font-size: 14px !important;">'.'$'.number_format($regular_prices, 2, '.', '').'</span>';
			}
		}else{
			echo "";
		}
    $price_regular = $regular_prices.'	&nbsp;'.$sale_prices;
    return $price_regular;
}
