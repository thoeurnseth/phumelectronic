<?php
/**
 * Description: This file for PHP functionality.
 * Version: 0.0.2
 * Author: Biz Solution Co., Ltd.
 * Author URI: https://bizsolution.com.kh
 * @package Biz Solution
 */

/**
 * Name: Function Testing
 * Date: 20-02-2023
 */
// function check_plugin_activation__() {
//    send_otp_plusgate('855972013150');
//     $aa = verify_otp_plusgate('855972013150', '293496');
//     echo json_encode($aa);
//     exit;
// }
// add_action('init', 'check_plugin_activation__');
 
/**
 * Name: Send OTP
 * Date: 13-02-2023
 */
function send_otp_plusgate($phone) {
    global $wpdb;

    try {
        if(!empty($phone)) {
            // Check Attempt Failed | if failed return errors on that fucntion
            $checkAtt = check_attempt_failed_plusgate($phone);

            if($checkAtt['code'] == 202) {
                $digits = (int) get_field('sms_number_of_digits', 'option');
                $otp = str_pad(rand(1, pow(10, $digits)-1), $digits, '1', STR_PAD_LEFT);
                $content = (string) get_field('sms_content', 'option');
                $content = str_replace('[otp_number]', $otp, $content);

                $insert_otp = $wpdb->insert(
                    $wpdb->prefix.'plusgate_otp',
                    array(
                        'phone' => $phone,
                        'otp' => $otp,
                        'duration' => date('G:i:s'),
                        'created_at' => date('Y-m-d G:i:s')
                    )
                );

                if($insert_otp) {
                    $postfields = array(
                        "sender" => get_field('sms_sender', 'option'),
                        "to" => $phone,
                        "content" => $content
                    );


                    // Send OTP
                    $response = curl(LINK_SEND, 'POST', $postfields);
                    if($response->message_count == 1 && !empty($response->queue_id)) {
                        $message = array(
                            'code' => 200,
                            'message' => 'OTP has been sent to you on your mobile phone.',
                            'error' => '',
                            'otp'=> $otp
                        );
                    }
                    else {
                        $message = array(
                            'code' => 500,
                            'data' => array(
                                'message' => 'Somthing unstable, please try again..!',
                                'error' => 'Error with +Gate can\'t sent OTP  to mobile phone.'
                            )
                        );
                    }
                }
                else {
                    $message = array(
                        'code' => 500,
                        'message' => 'Connection lost, please try again..!',
                        'error' => 'Error database connection, OTP can\'t insert to database.'
                    );
                }

                sms_error_log($postfields);
                sms_error_log($message);
                return $message;
            }
            else {
                return $checkAtt;
            }
        }
        else {
            $message = array(
                'code' => 406,
                'message' => 'Somthing unstable, please try again..!',
                'error' => 'Missing parameter, please check connection or coding.'
            );

            sms_error_log($message);
            return $message;
        }
    }
    catch (Exception $e) {
        sms_error_log($e->getMessage());
    }
}

/**
 * Name: Verify OTP
 * Date: 15-02-2023
 */
function verify_otp_plusgate($phone, $otp) {
    global $wpdb;

    try {
        if(!empty($phone) && !empty($otp)) {

            // Check Suspension | if failed return errors on that fucntion
            $checkSUS = check_suspension_faild_plusgate($phone);
            
            // Not Suspension
            if($checkSUS['code'] == 202) {
                // Check OTP Exist and Expire
                $checkOTP = check_otp_plusgate($phone, $otp);

                if($checkOTP['code'] == 202) {
                    $update_otp = $wpdb->update( $wpdb->prefix.'plusgate_otp',
                        array("status" => 1, 'updated_at' => date('Y-m-d G:i:s')),
                        array("phone" => $phone, "otp" => $otp)
                    );

                    if($update_otp) {
                        $message = array(
                            'code' => 200,
                            'message' => 'OTP verfied successfully.',
                            'error' => ''
                        );
                    }
                    else {
                        $message = array(
                            'code' => 404,
                            'message' => 'Connection unstable, please try again..!',
                            'error' => 'OTP not update status to used in database.'
                        );
                    }  

                    sms_error_log($message);
                    return $message;
                }
                else {
                    return $checkOTP;
                }
            }
            else {
                return $checkSUS;
            }
        }
        else {
            $message = array(
                'code' => 406,
                'message' => 'Somthing unstable, please try again..!',
                'error' => 'Missing parameter, please check connection or coding.'
            );

            sms_error_log($message);
            return $message;
        }
    }
    catch (Exception $e) {
        sms_error_log($e->getMessage());
    }
}

/**
 * Name: Check OTP
 * Date: 15-02-2023
 */
function check_otp_plusgate($phone, $otp) {
    global $wpdb;
    
    try {
        // Check Attemp Failed
        $checkOTP = $wpdb->get_row("SELECT duration, COUNT(id) AS found, created_at
                                    FROM ".$wpdb->prefix."plusgate_otp 
                                    WHERE phone = '".$phone."' AND otp = '".$otp."' AND status = 0");
                                
        if($checkOTP->found > 0) {
            $interval_second = get_field('sms_interval_second', 'option');

            if($interval_second > 0) {
                $current_time = date('G:i:s');
                $limit_time = date('G:i:s', strtotime($checkOTP->created_at));

                $time1 = new DateTime($current_time);
                $time2 = new DateTime($limit_time);
                $time_diff = $time1->diff($time2);

                $house = 3600 * $time_diff->h;
                $minute = 60 * $time_diff->i;
                $secods = $house + $minute + $time_diff->s;

                if($secods > $interval_second) {
                    $message = array(
                        'code' => 406,
                        'message' => 'Your OTP has expired. Please request for a new OTP via "RESEND OTP" button.',
                        'error' => 'TOP was expired.'
                    );
                }
                else {
                    $message = array(
                        'code' => 202,
                        'message' => 'Users allow verify OTP.',
                        'error' => ''
                    );
                }

                sms_error_log($message);
                return $message;
            }
            else {
                $message = array(
                    'code' => 202,
                    'message' => 'Users allow verify OTP.',
                    'error' => ''
                );
                
                sms_error_log($message);
                return $message;
            }
        }
        else {
            $insert_otp = $wpdb->insert(
                $wpdb->prefix.'plusgate_otp_failed',
                array(
                    'phone' => $phone,
                    'otp' => $otp,
                    'created_at' => date('Y-m-d G:i:s')
                )
            );
            
            $message = array(
                'code' => 404,
                'message' => 'The OTP number is invalid, please check your OTP number and try again.',
                'error' => 'OTP number is not found in database.'
            );

            sms_error_log($message);
            return $message;
        }
    }
    catch (Exception $e) {
        sms_error_log($e->getMessage());
    }
}

/**
 * Name: Check Attempt Failed
 * Date: 15-02-2023
 */
function check_attempt_failed_plusgate($phone) {
    global $wpdb;

    try {
        $current_date = date('Y-m-d');
        $failed_attempt = get_field('sms_failed_attempt_limit', 'option');
        
        // Check Attemp Failed
        if($failed_attempt > 0) {
            $checkAtt = $wpdb->get_row("SELECT COUNT(id) AS attempt
                                        FROM ".$wpdb->prefix."plusgate_otp 
                                        WHERE phone = '".$phone."' AND DATE(created_at) = '".$current_date."' AND status = 0");
                                        sms_error_log(json_encode($checkAtt));
            // User Attempt is Blocked
            if($checkAtt->attempt >= $failed_attempt)  {
                $message = array(
                    'code' => 406,
                    'message' => 'Opps! You have reached OTP limit, please try for the next day or contact admin.',
                    'error' => 'User have reached limit send OTP.'
                );
            }
            else {
                $message = array(
                    'code' => 202,
                    'message' => 'Users allow sending OTP.',
                    'error' => ''
                );
            }

            sms_error_log($message);
            return $message;
        } else {
            $message = array(
                'code' => 202,
                'message' => 'Users allow sending OTP.',
                'error' => ''
            );
            
            sms_error_log($message);
            return $message;
        }
    }
    catch (Exception $e) {
        sms_error_log($e->getMessage());
    }
}

/**
 * Name: Check Attemp Suspension Failed
 * Date: 15-02-2023
 */
function check_suspension_faild_plusgate($phone) {
    global $wpdb;

    try {
        $current_date = date('Y-m-d');
        $failed_suspension = get_field('sms_failed_attempt_period', 'option');

        // Check Attemp Failed
        if($failed_suspension > 0) {
            $checkSUS = $wpdb->get_row("SELECT COUNT(id) AS suspension 
                                        FROM ".$wpdb->prefix."plusgate_otp_failed  
                                        WHERE phone = '".$phone."' AND DATE(created_at) = '".$current_date."'");

            // User Attempt is Blocked
            if($checkSUS->suspension >= $failed_suspension)  {
                $message = array(
                    'code' => 406,
                    'message' => 'You have too many failed to confirm OTP attempts. Please try again the next day.',
                    'error' => 'User too many failed to confirm OTP.'
                );
            }
            else {
                $message = array(
                    'code' => 202,
                    'message' => 'Users allow confirming OTP.',
                    'error' => ''
                );
            }

            sms_error_log($message);
            return $message;
        }
        else {
            $message = array(
                'code' => 202,
                'message' => 'Users allow confirming OTP.',
                'error' => ''
            );
            
            sms_error_log($message);
            return $message;  
        }
    }
    catch (Exception $e) {
        sms_error_log($e->getMessage());
    }
}

/**
 * Name: Test Send OTP
 * Date: 13-02-2023
 */
function test_send_otp_plus_gate() {
    if(isset($_POST['publish']) && isset($_GET['page']) && $_GET['page'] == 'plusgate-sms') {

        $status = get_field('sms_test_status', 'option');
        if($status == 1) {
            $postfields = array(
                "sender" => get_field('sms_sender', 'option'),
                "to" => get_field('sms_test_send_to', 'option'),
                "content" => get_field('sms_test_content', 'option')
            );

            curl(LINK_SEND, 'POST', $postfields);
        }
    }
}
add_action('updated_option', 'test_send_otp_plus_gate', 10, 3);

/**
 * Name: Request URL
 * Date: 13-02-2023
 */
function curl($url, $method, $postfields) {
    $secretKey = get_field('sms_secret_key', 'option');
    $privateKey = get_field('sms_private_key', 'option');

    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $url .'?private_key='. $privateKey,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => $method,
        CURLOPT_POSTFIELDS => json_encode( $postfields ),
        CURLOPT_SSL_VERIFYHOST => 0,
        CURLOPT_SSL_VERIFYPEER => 0,
        CURLOPT_HTTPHEADER => array(
            'X-Secret: '.$secretKey.'',
            'Content-Type: application/json'
        ),
    ));

    $response = curl_exec($curl);
    curl_close($curl);
    sms_error_log($response);

    return json_decode($response);
}