<?php
/**
* Template Name: Order Invoice
*/
$order_id = $_GET['id'];

?>

<!DOCTYPE html>
<html>
<head>
<title>Invoice</title>
<link rel="shortcut icon" type="image/x-icon" href="<?= site_url() ?>/wp-content/themes/metro-child/assets/images/cropped-Phum-Electronics_logo-1.png" />

    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }
        *{
            font-size: 12px;
        }
        body.A4{

        background: white;
        box-sizing: border-box;
        box-shadow: 0 0 0.5cm rgba(0,0,0,0.5);
        display: block;
        height: 29.7cm;
        width: 21cm;
        margin: 0 auto;
        margin-bottom: 0.5cm; 
        padding: 10px 25px;
        overflow-y: scroll;     
        }
        h2, p {
            clear: both;
        }
        p{
            font-size: 14px;
        }
        .text_right{
            text-align: right;
        }
        .table-invoice,
        .table-product,
        .table-total {
            clear: both;
            margin-bottom: 40px;
        }

        .table-invoice tr td,
        .table-product tr td,
        .table-tota tr td {
            padding: 5px 0;
        }

        /* Table Invioce */
        .table-invoice tr th {
            text-align: left;
        }

        .table-invoice tr td:not(:last-child) {
            padding-right: 25px;
        }
        

        /* Table Product */
        .table-product {
            border-collapse: collapse;
            width: 100%;
            
        }

        .table-product thead {
            border-top: 1px solid #ccc;
            border-bottom: 1px solid #ccc;
        }

        .table-product thead th {
            padding: 10px 0;
            text-align: left;
        }

        /* Table Total */
        .table-total {
            border-collapse:separate; 
            border-spacing: unset;
        }

        .table-total tr td {
            padding: 10px 0;
        }

        .table-total tr:nth-child(1) td,
        .table-total tr:nth-child(3) td,
        .table-total tr:nth-child(4) td,
        .table-total tr:nth-child(6) td {
            border-top: 1px solid #000;
        }

        .table-total tr:nth-child(2) td,
        .table-total tr:nth-child(5) td {
            border-top: 1px solid #ccc;
        }

        .table-total tr td:first-child {
            padding-right: 80px;
        }
        
        .company-address b{
            line-height: 1.5;
        }
        
        @media print {
        .page-break {
            display: block;
            page-break-inside:avoid;
        }
        @media print {
            body {
                margin: 0;
                padding: 0;
            }

            body.A4{
                box-shadow: 0 0 0.5cm rgba(0,0,0,0);
            }
            
            .A4 {
                box-shadow: none;
                margin: 0;
                width: auto;
                height: auto;
            }
        }
    </style>
</head>
<?php 
    $webview = $_GET['webview'];
    if($webview == 'true') {
        echo '<style type="text/css">
            body.A4 {
                width: 100% !important; 
                height: auto !important;
            }
        </style>';
    }


    $order = wc_get_order( $order_id );

    /*
    $billing_name       = $order->get_billing_first_name(). " ".$order->get_billing_last_name();
    $billing_address1   = $order->get_billing_address_1();
    $billing_address2   = $order->get_billing_address_2();
    $billing_city       = $order->get_billing_city();
    $billing_state      = $order->get_billing_state();
    $billing_postcode   = $order->get_billing_postcode();

    $shipping_name      = $order->get_shipping_first_name(). " ".$order->get_shipping_last_name();
    $shipping_address1  = $order->get_shipping_address_1();
    $shipping_address2  = $order->get_shipping_address_2();
    $shipping_city      = $order->get_shipping_city();
    $shipping_state     = $order->get_shipping_state();
    $shipping_postcode  = $order->get_shipping_postcode();
    */
?>
<body class="A4">
    <table align="right" class="company-address">
        <tbody>
            <tr>
                <td>
                    <b>Building 135,</b> <br>
                    <b>Preah Monivong Blvd.,</b> <br>
                    <b>Sangkat Monorom, Khan 7 Makara,</b> <br>
                    <b>Phnom Penh,</b> <br>
                    <b>Cambodia</b> <br>
                </td>
            </tr>
        </tbody>
    </table>
    
    <h2>Invoice <?php echo $order_id; ?></h2>
    <table class="table-invoice">
        <thead>
            <tr>
                <th>Invoice Date :</th>
                <th>Branch :</th>
                <th>Payment Type :</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <?php 
                        //$order = wc_get_order( $order_id );
                        $date = $order->get_date_created();
                        echo date_format($date,'F/d/Y')
                    ?>
                </td>
                <td>Ecommerce</td>
                <td><?php echo $order->get_payment_method(); ?></td>
            </tr>
        </tbody>
    </table>

    <table class="table-product">
        <thead>
            <tr>
                <th>Item Code</th>
                <th>Product Name</th>
                <th>Qty</th>
                <th>Unit Price</th>
                <th>Disc.</th>
                <th>Total Price</th>
            </tr>
        </thead>
        <tbody>
                <?php
                     $order_items = $order->get_items();
                     $item_subtotal     = 0;
                     $item_totalprice   = 0;
                     $total_vat         = 0;
                     $subtotal_vat      = 0;
                
                     foreach ($order_items as $item_id => $item)
                     {
                        $date_paid = $order->get_date_paid();
                        $date_paid_format = date_format($date_paid,'F/d/Y');

                        $discount  = $order->get_discount_to_display();
                        $item_code = $item->get_product()->get_sku(); 
                        $inc_tax                = true;
                        $round                  = false;
                        $product_name           = $item['name']; // Get the item name (product name)
                        $item_quantity          = $item->get_quantity(); // Get the item quantity
                        $item_cost_excl_disc    = $order->get_item_subtotal( $item, $inc_tax, $round ); // Price per item before discount
                        $item_subtotal_tax      = $item->get_subtotal_tax();  // Get the item line total tax non discounted
                        // $item_total             = $order->get_item_meta($item_id, '_line_total', true); 
                        $item_total  = $item->get_subtotal();
                        $item_discount = $order->get_total_discount();

                        $item_subtotal          += ($item_total);
                        $item_totalprice         =  ($item_subtotal + $total_vat - $item_discount);

                        $subtotal_vat           = $item_subtotal * 10 / 100;
                        $total_vat              +=$subtotal_vat;

                         echo '
                             <tr>
                                 <td>'.$item_code.'</td>
                                 <td>'.$product_name.'</td>
                                 <td>'.$item_quantity.' Unit(s)</td>
                                 <td>$ '.$item_cost_excl_disc.'.00</td>
                                 <td>'.$discount.'</td>
                                 <td>$ '.$item_total.'.00</td>
                            </tr>
                         ';
                     }
                ?>
        </tbody>
    </table>

    <table class="table-total" align="right">
        <tbody>
            <?php
                echo'
                    <tr>
                        <td><b>Subtotal</b></td>
                        <td class="text_right">$ '.$item_subtotal.'.00</td>
                    </tr>
                    <tr>
                        <td><b>Total Discount</b></td>
                        <td class="text_right">$'.$order->get_total_discount().'</td>
                    </tr>
                    <tr>
                        <td><b>Total</b></td>
                        <td class="text_right">$ '.$item_totalprice.'.00</td>
                    </tr>   
                '
            ?>
        </tbody>
    </table>

</body>
</html>