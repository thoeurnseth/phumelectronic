<?php 

  if(isset($_GET['user_id'])) {
    $customer_id = $_GET['user_id'];
    $webview_status = '&webview=true';
  } else {
    $customer_id = get_current_user_id();
    $webview_status = '';
  }
  $args = array('post_type' => 'user-address', 'author'=> $customer_id);
  $addresses = new WP_Query($args);

  $province_id = '';
  $district_id = '';

  if($_GET['webview'] == 'true') {
    $return_webview = 'true';
  }
  else {
    $return_webview = '';
  }
  
  // if action equal update, show update acf_form
  if( isset($_GET['action']) ):
    $action = $_GET['action'];
    $args = array(
      'post_id'       => 'new_post',
      'new_post'      => array(
        'post_type'     => 'user-address',
        'post_status'   => 'publish'
      ),
      'submit_value'  => 'Submit',
      'return' => add_query_arg( ['created'=>'true','webview'=>$return_webview], site_url() . '/my-account/edit-address')
    );
    if( $action == 'update' ):
      $my_address = get_post( $_GET['id'] );
      // Check if the Address ID belong to logged-in users, otherwise, we won't allow editting address.
      if( $my_address->post_author == get_current_user_id() ):

        $args = array(
          'post_id'       => $_GET['id'],
          'new_post'      => array(
            'post_type'     => 'user-address',
            'post_status'   => 'publish'
          ),
          'submit_value'  => 'Update',
          // 'return' => add_query_arg( 'updated', 'true', site_url( $_SERVER['REQUEST_URI'] ))
        );

        $province_id = get_post_meta($my_address->ID, 'province_2', true);
        $district_id = get_post_meta($my_address->ID, 'district_2', true);

      endif;
        $odoo_user_id = get_user_meta( get_current_user_id(), 'odoo_id', true );
        if(!empty($odoo_user_id)) {
          acf_form($args);
        }
        else {
          echo "We found an error in your account. You can't create an address now. Please contact our team 016888029 / 012444051. We are sorry really for the inconvenience.";
        }
    else:
      $odoo_user_id = get_user_meta( get_current_user_id(), 'odoo_id', true );
      if(!empty($odoo_user_id)) {
        acf_form($args);
      }
      else {
        echo "We found an error in your account. You can't create an address now. Please contact our team 016888029 / 012444051. We are sorry really for the inconvenience.";
      }
      
      $webview = '';
      $webview = $_GET['webview'];
      if(isset($_GET['checkout']))
      {
        echo '
          <div class="mt-2">
            <small>Back to checkout.</small>
            <a href="'. site_url().'/checkout-2/?webview='.$webview.'"><small class="text-red">Click Here.</small></a>
          </div>
        ';
      }  
        
    endif;

  // if action not set, show "User Address" table
  else: ?>
    <div class="my-address-wrapper table-responsive">
      <table id="myaddress" class="tbl-shipping">
        <thead>
          <tr>
            <th colspan="6" class="title"><h2>Shipping Address</h2></th>
          </tr>
          <tr>
            <th>Check</th>
            <th>Street</th>
            <th>Address</th>
            <th>City/Province</th>
            <th>District</th>
            <th>Commune</th>
            <th>Commune Code</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $province = '';
          $district = '';
          $checked  = '';
          $args = array('post_type' => 'user-address', 'author'=> $customer_id);
          $addresses = new WP_Query($args);
            if($addresses->have_posts()):
              while( $addresses->have_posts() ): $addresses->the_post();
                $post_id = get_the_ID();
                $address = get_field('address', $post_id);
                $street = get_field( 'street', $post_id );
                $district_id = get_field( 'district_2', $post_id );
                $province_id = get_field( 'province_2', $post_id );
                $province_obj = get_term_by('ID', $province_id, 'location');
                $district_obj = get_term_by('ID', $district_id, 'location');
                $province = $province_obj != null ? $province_obj->name : '---';
                $district = $district_obj != null ? $district_obj->name : '---';

                // default address
                $default_address = get_field( 'default_address',$post_id );
                $checked_ = $default_address == 1 ? 'checked' : '';

                // get commune
                $commune_id   = get_field( 'commune_2', $post_id );
                $commune_obj  = get_term_by('ID', $commune_id, 'location');
                $commune      = $commune_obj != null ? $commune_obj->name : '---';
                $code         = get_field('commune_code', $post_id);

                ?>
                  <tr>
                    <td>
                      <input type="radio" name="default_address" <?php echo $checked_; ?> data="<?php echo $post_id ?>" class="set_address" nonce="<?php echo rand(0, 999999); ?>">
                    </td>
                    <td><?=$street?></td>
                    <td><?=$address?></td>
                    <td><?=$province?></td>
                    <td><?=$district?></td>
                    <td><?=$commune?></td>
                    <td><?=$code?></td>
                    <td><a href="?action=update&id=<?=$post_id.$webview_status?>"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a></td>
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
      
      <?php
        if($_GET['webview'] == 'true') {
          $webview_address = '&webview=true';
        }
        else {
          $webview_address = '';
        }
      ?>

      <a class="woocommerce-button button woocommerce-form-login__submit" href="?action=new<?php echo $webview_address; ?>">Add New</a>&ensp; 

      <?php
        if(isset($_GET['created']) == 'true') {
          echo '
            <span class="back_to_checkout">
              <a href="'.site_url().'/checkout-2">Go to checkout</a>
            </span>
          ';
        }
      ?>
     
    </div>
  <?php endif; ?>
  <?php 
      if(isset($_GET['type']) == 'view_product')
      {
        echo '
          <div class="mt-2">
            <small>Back to view product.</small>
            <a href="'.wp_get_referer().'"><small class="text-red">Click Here.</small></a>
          </div>
        ';
      }
  ?>

  <input type="hidden" value="<?=$province_id;?>" id="province_id">
  <input type="hidden" value="<?=$district_id;?>" id="district_id">
<script>
  var province_id = jQuery('#province_id').val();
  var district_id = jQuery('#district_id').val();
  var selectable = false;

  jQuery(document).ready(function() {
    acf.add_filter('select2_ajax_data', function( data, args, $input, field, instance ){
                   data["province_id"] = province_id;
                   data["district_id"] = district_id;
      return data;
    });
	    acf.add_filter('select2_ajax_results', function( json, params, instance ){
      selectable = true;
      //alert(JSON.stringify(json));
      return json;
    });

    // The event to prevent from selecting, during ajax loading
    jQuery( '#acf_district select' ).on('select2:selecting', function (e) {
      if(selectable == false)
        e.preventDefault();
    });

    // The event to prevent from selecting, during ajax loading
    jQuery( '#acf_commune select' ).on('select2:selecting', function (e) {
      if(selectable == false)
        e.preventDefault();
    });

    jQuery( '#acf_province select' ).change(function () {

      province_id = jQuery( this ).val();
      //alert(province_id)
      // Get selected value
      // jQuery( '#acf_province select option:selected' ).each(function(key,value) {
      //   province_id = jQuery( this ).val();
      //   //alert(province_id);
      // });

      // Clear select2 data
      jQuery( '#acf_district select' ).html( jQuery('<option></option>').val(0).html('Select') );

      jQuery( '#acf_commune select' ).html( jQuery('<option></option>').val(0).html('Select') );
    });

    jQuery( '#acf_district select' ).change(function () {
     
      // Get selected value
      district_id = '';    
      jQuery( '#acf_district select option:selected' ).each(function() {
        district_id += jQuery( this ).val();
      });
      // Clear select2 data
      jQuery( '#acf_commune select' ).html( jQuery('<option></option>').val(0).html('Select') );
    });

    // get commune code 
    jQuery( '#acf_commune select' ).change(function () {
      var commune_id = jQuery(this).val();
      jQuery.ajax({
          type: "POST",
          url: "/wp-admin/admin-ajax.php",
          data: {
              action: 'get_commune_code',
              commune_id: commune_id
          },
          dataType: 'JSON',
          success: function ( response ) {
            //console.log( response.data );
            jQuery('#acf_commune_code input').val(response.data.code);
          }
        });
    });
   
    // Update the ajax arguments for select2 objects to include the Make value
    // This is done by replacing the default ajax.data function with a wrapper, which calls the old function and then appends "vehicle_make" to the results.
    acf.add_filter('select2_args', function( args ) {
      console.log( args );
      if ( typeof args.ajax.data == 'function' ) {
        var old_data_func = args.ajax.data; // We'll keep this for maximum compatibility, and extend it.
        args.ajax.data = function(term, page) {
          var default_response = old_data_func( term, page ); // Call the old, default function.
          default_response.province_id = function() {
            // Add the province param to the ajax function. This happens for all select 2 objects, even if it's blank.
            return province_id;
          };
          default_response.district_id = function() {
            // Add the province param to the ajax function. This happens for all select 2 objects, even if it's blank.
            return district_id;
          };
          // Return the default args with our vehicle_make function.
          return default_response;
        }
      }
      return args;
    });
  });
  
  // Set Default Address
  jQuery('.set_address').on( 'click', function(){
    var nonce   = '';
    var post_id = '';
    var checked = jQuery(this).is(':checked');

    if( checked )
    {
      post_id = jQuery(this).attr('data');
      nonce = jQuery(this).attr('nonce');

      jQuery.ajax({
        type: "POST",
        url: "/wp-admin/admin-ajax.php",
        data: {
            action: 'set_default_billing_address',
            post_id: post_id,
            nonce: nonce,
        },
        dataType: 'JSON',
        success: function ( response ) {
          //console.log( response.data );
        }
      });
    }
  });

  jQuery('#acf_commune_code input').attr('readonly', true);

</script>