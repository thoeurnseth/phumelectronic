<?php
/*
 *	Template Name: Notification
 */
?>


<div class="notofication-content">
    <ul class="nav nav-tabs" id="myTab" role="tablist">
    <li class="nav-item">
        <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Notification</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Order Notification</a>
    </li>
    </ul>
    <div class="tab-content" id="myTabContent" >
        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
            <?php 
                // @get notifications
                $all_notifications = $wpdb->get_results("SELECT * FROM revo_mobile_variable WHERE  is_deleted = 0 ORDER BY id DESC");
                
                foreach($all_notifications as $value) {
                echo  '
                    <div class="noti-detail public_notifi" data-toggle="modal" data-target="#notification-modal">
                        <div class="post_id d-none">'.$value->id.'</div>
                        <div class="noti-img"><img src="'.$value->image.'" alt=""></div>    
                        <div class="noti-message">
                            <h2 class="noto-title">'.$value->title.'</h2>
                            <p class="noti-description">'.$value->description.'</p>
                            <div class="noti-date">
                                <p>'.$value->created_at.'</p>
                            </div>
                        </div>   
                    </div>
                ';
                }
            ?>
        </div>
        <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
            <?php
                // @get order notifications
                  $user_id = get_current_user_id();
                  $all_order_notifications = $wpdb->get_results("SELECT * FROM revo_notification WHERE user_id = $user_id AND type = 'order' ORDER BY id DESC");
                  
                  foreach($all_order_notifications as $value) {
                    echo  '
                        <div class="noti-detail order_notifi" data-toggle="modal" data-target="#order-notification-modal">
                            <div class="order_notification_id d-none">'.$value->id.'</div>    
                            <div class="noti-message">
                                <h2 class="noto-title">'.$value->message.'</h2>
                                <p class="noti-description">Order with number '.$value->target_id.' has '.$value->message.'.</p>
                                <div class="noti-date">
                                    <p>'.$value->created_at.'</p>
                                </div>
                            </div>   
                        </div>
                    ';
                }
                add_action('init' ,'get_order_notification');
            ?>
        </div>
    </div>
</div>


<!--Notification Modal -->
<div class="modal fade" id="notification-modal" tabindex="-1" role="dialog" aria-labelledby="warranty-modal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form id="form_warranty" method="post" enctype="multipart/form-data" accept-charset="utf-8">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Notification</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body" id="popup-public-noti">
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="order-notification-modal" tabindex="-1" role="dialog" aria-labelledby="warranty-modal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form id="form_warranty" method="post" enctype="multipart/form-data" accept-charset="utf-8">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Order Notification</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body" id="popup-order-noti">
                </div> 
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
    // Save Warranty
    // jQuery(' .btn-save-warranty ').click( function() {

    //     var file_data = $('#file_warranty').prop('files')[0];
    //     var form_data = new FormData();
    //         form_data.append('action', 'file_upload');
    //         form_data.append('product_id', product_id);
    //         form_data.append('file', file_data);
    //         form_data.append('nonce', nonce);

    //     $.ajax({
    //         url: "/wp-admin/admin-ajax.php",
    //         type: 'POST',
    //         contentType: false,
    //         processData: false,
    //         data: form_data,
    //         success: function (response) {
    //             console.log(response);
    //         }
    //     });
    // });

    jQuery(document).ready(function(){
        jQuery('.public_notifi').click(function(){
            var noti_id = jQuery(this).find('.post_id').text(); 
            jQuery.ajax({
                type: "POST",
                url: "/wp-admin/admin-ajax.php",
                dataType: "html",    
                async: true,
                cache: true,
                data: {
                    action: 'get_public_notification',
                    id: noti_id,
                },
                dataType: 'JSON',
                success: function ( response ) {
                    var data = response.data;
                    var image = data.image;
                    var title = data.title;
                    var desc = data.desc;
                    var created_at = data.created_at;
                    jQuery("#popup-public-noti").html('<div class="modal-img"><img src="'+image+'"></div><div class="modal-detail"><h3 class="modal-title">'+title+'</h3><p class="modal-desc">'+desc+'</p><div class="modal-date">'+
                               '<p>'+created_at+'</p></div></div>');
                },
                error: function(errorThrown){
                    console.log(errorThrown);
                }

            });
        });
    });

    jQuery(document).ready(function(){
        jQuery('.order_notifi').click(function(){
            var order_noti_id = jQuery(this).find('.order_notification_id').text(); 
            jQuery.ajax({
                type: "POST",
                url: "/wp-admin/admin-ajax.php",
                dataType: "html",    
                async: true,
                cache: true,
                data: {
                    action: 'get_order_notification',
                    id: order_noti_id,
                },
                dataType: 'JSON',
                success: function ( response ) {
                    var data = response.data;
                    var firstname = data.firstname;
                    var lastname = data.lastname;
                    var phone = data.phone;
                    var address = data.address;
                    var city = data.city;
                    var country = data.country;
                    var payment_method = data.payment_method;
                    var order_receipt = data.order_receipt;
                    var total = data.total;
                    var order_subtotal = data.order_subtotal;
                    var shipping_total = data.shipping_total;
                    var item_total = data.item_total;
                    var payment_title = data.payment_title;
                    var product_name = data.product_name;
                    var display_shipping = data.display_shipping;
                    var image = data.image;
                    jQuery("#popup-order-noti").html('<div class="order-noti_detail"><div class="shipping-info"><div class="info-left"><img width="30px" src="<?php echo get_stylesheet_directory_uri() ?>/assets/icons/car.png">'+
                    '</div><div class="info-right"><h4 class="order-title">Shipping Information</h4><span class="noti-delivery">'+display_shipping+'</span></div></div>'+
                    '<div class="address-info"><div class="info-left"><img width="30px" src="<?php echo get_stylesheet_directory_uri() ?>/assets/icons/order-location.png"></div>'+
                    '<div class="info-right"><h4 class="order-title">Shipping Address</h4><div class="user-name">'+firstname+' '+lastname+'</div><div class="user-tel">'+phone+'</div><div class="user-location">'+address+' '+city+', '+country+'</div></div>'+
                    '</div><div class="payment-info"><div class="info-left"><img width="30px" src="<?php echo get_stylesheet_directory_uri() ?>/assets/icons/credit-card.png"></div><div class="info-right">'+
                    '<h4>Payment Info</h4><div class="payment-method"><h5>Payment Method</h5><span>'+payment_method+'</span></div><div class="payment-description"><h5 class="payment-title">Payment Description</h5><span>'+payment_title+'</span>'+
                    '</div></div></div><div class="product-order-info"><div class="product-info-left"><img width="120px" src="'+image+'" alt=""></div><div class="info-right">'+
                    '<h5 class="noti-product-name">'+product_name+'<br></h5><div class="noti-product-item">'+item_total+' items</div><div class="noti-product-price">$'+order_subtotal+'</div></div>'+
                    '</div><div class="total-order-info"><div class="row"> <div class="col-6"><div>Subtotal Product</div><div>Shipping Costs</div><div>Total Orders</div></div><div class="col-6">'+
                    '<div class="subtotal-product">$'+order_subtotal+'</div><div class="shipping-costs">$'+shipping_total+'</div><div class="total-order">$'+total+'</div></div></div></div>'+
                    '<div class="link-recipt"><a href="'+order_receipt+'">Review Receipt</a></div></div>');
                },
                error: function(errorThrown){
                    console.log(errorThrown);
                }
            });
        });
    });

</script>
