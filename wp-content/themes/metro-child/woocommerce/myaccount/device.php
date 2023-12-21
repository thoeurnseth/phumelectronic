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

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_account_orders', $has_orders ); ?>

<div class="ewarentty-container-desktop">
<table class="woocommerce-orders-table woocommerce-MyAccount-orders shop_table shop_table_responsive my_account_orders account-orders-table">
    <thead>
        <tr>
            <th colspan="6" class="title">
                <h2>E-Warranty</h2>
            </th>
        </tr>
    </thead>
    <thead>
        <tr>
            <th id="tbodyrowstyle">Username</th>
            <th id="tbodyrowstyle">Phone Number</th>
            <th id="tbodyrowstyle">Invoice Number</th>
            <th id="tbodyrowstyle">Order Date</th>
            <th id="tbodyrowstyle">Attachment</th>
        </tr>
    </thead>
    <tbody>
        <?php

            $e_warentty = array(
                'post_type'     => 'e-warranty',
                'posts_status'  => 'publish',
                'author'        => get_current_user_id(),
                'posts_per_page'=> -1 ,
            );

            $query = new WP_Query($e_warentty);
            if($query->have_posts())
            {
                while($query->have_posts())
                {
                    $query->the_post();
                    $name           = get_field('username');
                    $phone          = get_field('phone_number');
                    $invoice_number = get_field('invoice_number');
                    $order_date     = get_field('order_date');
                    $attachment     = get_field('attachment');

                    echo '
                        <tr>
                            <td id="tbodyrowstyle">'.$name.'</td>
                            <td id="tbodyrowstyle">'.$phone.'</td>
                            <td id="tbodyrowstyle">'.$invoice_number.'</td>
                            <td id="tbodyrowstyle">'.$order_date.'</td>
                            <td id="tbodyrowstyle"><a href="'.wp_get_attachment_url( $attachment ) .'" target="_blank">Click to view</td>
                        </tr>
                    ';
                }
            } else {
                echo '
                    <tr>
                        <td colspan="5">
                            <div class="alert alert-warning" role="alert">
                                No E-Warranty upload.
                            </div>
                        </td>
                    </tr>
                ';
            }

        ?>

    </tbody>
</table>  
</div>
<div class="ewarentty-container-mobile">
        <h1>E-Warranty</h1>
    <?php

        $e_warentty = array(
            'post_type'     => 'e-warranty',
            'posts_status'  => 'publish',
            'author'        => get_current_user_id(),
            'posts_per_page'=> -1 ,
        );

        $query = new WP_Query($e_warentty);
        if($query->have_posts())
        {
            while($query->have_posts())
            {
                $query->the_post();
                $name           = get_field('username');
                $phone          = get_field('phone_number');
                $invoice_number = get_field('invoice_number');
                $order_date     = get_field('order_date');
                $attachment     = get_field('attachment');

                echo '
                    <table>
                        <hr>
                        <tr>
                            <td>USERNAME :</td> 
                            <td>'.$name.'</td>
                        </tr>
                        <tr>
                            <td>PHONE NUMBER :</td> 
                            <td>'.$phone.'</td>
                        </tr>
                        <tr>
                            <td>INVOICE NUMBER :</td> 
                            <td>'.$invoice_number.'</td>
                        </tr>
                        <tr>
                            <td>ORDER DATE :</td> 
                            <td>'.$order_date.'</td>
                        </tr>
                        <tr>
                            <td>ATTACHMENT :</td> 
                            <td id="tbodyrowstyle"><a href="'.wp_get_attachment_url( $attachment ) .'" target="_blank">Click to view</td>
                        </tr>                        
                    </table>
                ';
            }
        } else {
            echo '
                <tr>
                    <td colspan="5">
                        <div class="alert alert-warning" role="alert">
                            No E-Warranty upload.
                        </div>
                    </td>
                </tr>
            ';
        }
    ?>
</div>
  
<div class="btn-popup">
    <button class="btn-upload-warentty" data-toggle="modal" data-target="#warranty-modal" class="btn-warranty btn-upload-warentty">UPLOAD</button>
</div>

<!-- Modal -->
<div class="modal fade" id="warranty-modal" tabindex="-1" role="dialog" aria-labelledby="warranty-modal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form id="form_warranty" method="post" enctype="multipart/form-data" accept-charset="utf-8">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Warranty Attachment</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="form-group mb-3">
                        <div class="label">Username</div>
                        <input type="text" class="form-control" name="username" required>
                    </div>
                    <div class="form-group mb-3">
                        <div class="label">Phone Number</div>
                        <input type="number" class="form-control" name="phone_number" minlength="9" maxlength="10" required>
                    </div>
                    <div class="form-group mb-3">
                        <div class="label">Invoice Number</div>
                        <input type="text" class="form-control" name="invoice_number" required>
                    </div>
                    <div class="form-group mb-3">
                        <div class="label">Order Date</div>
                        <input type="date" class="form-control" name="order_date" required>
                    </div>
                    <div class="form-group mb-3">
                        <div class="label">Attachment( image / pdf )</div>
                        <input type="file" class="form-control" name="attachment" required>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary btn-save-warranty" name="btn_save_warentty">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">

    var nonce = '',
        product_id = '';

    // Bottom Warrantry
    jQuery(' .btn-warranty ').on( 'click', function() {
        product_id = $(this).attr( 'data-pro' );
    });

    // Save Warranty
    jQuery(' .btn-save-warranty ').click( function() {

        var file_data = $('#file_warranty').prop('files')[0];
        var form_data = new FormData();
            form_data.append('action', 'file_upload');
            form_data.append('product_id', product_id);
            form_data.append('file', file_data);
            form_data.append('nonce', nonce);

        $.ajax({
            url: "/wp-admin/admin-ajax.php",
            type: 'POST',
            contentType: false,
            processData: false,
            data: form_data,
            success: function (response) {
                console.log(response);
            }
        });
    });

</script>