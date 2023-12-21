<?php 

    function create_coupon_odoo($post_id) { 
            
        if($_GET['post_type'] == 'shop_coupon' && $_POST['publish']) {  echo 123456789; exit;

            // $coupon_name    = get_the_excerpt($post_id);
            // $coupon_code    = get_the_title($post_id);
            // $discount_type  = get_field('discount_type' ,$post_id);
            // $amount         = get_field('coupon_amount' ,$post_id);
            // $usage_limit    = get_field('usage_limit' ,$post_id);
            // $usage_limit_per_user = get_field('usage_limit_per_user' ,$post_id);
            // $date_expires   = get_field('date_expires' ,$post_id);
            // $free_shipping  = get_field('free_shipping' ,$post_id);
            // $minimum_amount = get_field('minimum_amount' ,$post_id);

            // $curl = curl_init();
            // curl_setopt_array($curl, array(
            // CURLOPT_URL => 'http://124.248.186.25:8068/api/create/sale_coupon',
            // CURLOPT_RETURNTRANSFER => true,
            // CURLOPT_ENCODING => '',
            // CURLOPT_MAXREDIRS => 10,
            // CURLOPT_TIMEOUT => 0,
            // CURLOPT_FOLLOWLOCATION => true,
            // CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            // CURLOPT_CUSTOMREQUEST => 'POST',
            // CURLOPT_POSTFIELDS =>'{
            //     "params":{
            //         "name": "'.$coupon_name.'",
            //         "coupon_code": "'.$coupon_code.'",
            //         "discount_type": "percentage",
            //         "amount": 10,
            //         "usage_limit": 6,
            //         "usage_limit_per_user": 2,
            //         "date_expires": "2022-09-23",
            //         "minimum_amount":10,
            //         "db":"5q6ONleOaO1TypkFWDncCNM0ouw61tiwlfkPgrIAubPYVeKNPIrfpFFYMLMs25llqhhkekcnafdPvvqtK93lfwMPDYoa1HmMCrD4rvasqI18YaadH23WE9EzldWUJC8TdJFkAQrRzSz"
            //     }
            // }',
            // CURLOPT_HTTPHEADER => array(
            //     'Authorization: Bearer 8IWeKTo2wNX3CxPB0H2SWC6tYdqhdevDa9Pah87Hrnlmo10NTzCNs0CaIFcO7IiJPeVdHeA2JpY0kV9Ecddkw4KiQS2ji7cwmIyVd4B1TrD2xZJAP6tSUWUjC2Okp7O3',
            //     'Content-Type: application/json',
            //     'Cookie: session_id=a9d786dc33c9e5074fc5431caef57055a658e76b'
            // ),
            // ));

            // $response = curl_exec($curl);

            // curl_close($curl);
            // echo $response;
        }

    }
    add_action('save_post', 'create_coupon_odoo' ,10 ,4);