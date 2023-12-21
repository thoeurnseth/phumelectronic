<?php
/**
 * Description: This file for write function API.
 * Version: 0.0.1
 * Author: Biz Solution Co., Ltd.
 * Author URI: https://bizsolution.com.kh
 * @package Biz Solution
 */

if ( ! class_exists( 'PlusGateSMS_Controller' ) ) {
    class PlusGateSMS_Controller {

        /**
         * Name: Init Action
         * Date: 15-02-2023
         */
        public function __construct() {
            try {
                // Validate plugin activation
                $plugin_status = get_field('sms_status', 'option');
                if($plugin_status == 0) {
                    $message = array(
                        'code' => 410,
                        'data' => array(
                            'message' => 'The plugin send OTP unable to access, please contact ot admin.',
                            'error' => 'The plugin +Gate has not yet activate to use.'
                        )
                    );

                    sms_error_log($message);
                    wp_send_json_success( $message );
                    wp_die();
                }
            } catch (Exception $e) {
                sms_error_log($e->getMessage());
            }
        }

        /**
         * Name: Send OTP
         * Date: 14-02-2023
         */
        public function send_otp() {
            global $wpdb;

            try {
                $phone = '';
                $phones = '';
                if(isset($_POST['phone'])) {
                    $phones = $_POST['phone'];
                    $phone = "855".substr($phones,1);
                } else {
                    $request = file_get_contents('php://input');
                    $request = json_decode( $request );
                    $phones = $request->phone;
                    $phone = "855".substr($phones,1);
                }
                
                if(!empty($phones)) {

                    // Check Attempt Failed | if failed return errors on that fucntion
                    $checkAtt = $this::check_attempt_failed($phone);

                    if($checkAtt == true) {
                        $digits = get_field('sms_number_of_digits', 'option');
                        $otp = str_pad(rand(1, pow(10, $digits)-1), $digits, '1', STR_PAD_LEFT);
                        $content = get_field('sms_content', 'option');
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
                            $response = $this::curl(LINK_SEND, 'POST', $postfields);
                            if($response->message_count == 1 && !empty($response->queue_id)) {
                                // $message = array(
                                //     'code' => 200,
                                //     'message' => 'OTP has been sent to you on your mobile phone.',
                                //     'error' => ''
                                // );
                                return [
                                    "code"      =>  "success",
                                    "message"   =>  "SMS successfully sent.",
                                    "data"      =>  [
                                        "status"   =>  200, 
                                        "phone"    =>  $phone,
                                        "otp"      =>  $otp
                                    ],
                                ];
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
                    }
                }
                else {
                    // $message = array(
                    //     'code' => 406,
                    //     'message' => 'Somthing unstable, please try again..!',
                    //     'error' => 'Missing parameter, please check connection or coding.'
                    // );
                    return [
                        "code"      =>  "invalid_phone_format",
                        "message"   =>  "Invalid phone number format.",
                        "data"      =>  [
                            "status"    =>  411
                        ],
                    ];
                }

                sms_error_log($message);
                wp_send_json_success( $message );
                wp_die();
                
            } catch (Exception $e) {
                sms_error_log($e->getMessage());
            }
        }

        /**
         * Name: Verify OTP
         * Date: 15-02-2023
         */
        public function verify_otp() {
            global $wpdb;

            try {

                $phone  = '';
                $phones = '';
                $otp_number = '';
                if(isset($_POST['phone'])) {
                    $otp_number = $_POST['otp_number'];
                    $phones = $_POST['phone'];
                    $phone  = "855".substr($phones,1);
                } else {
                    $request = file_get_contents('php://input');
                    $request = json_decode( $request );
                    $otp_number = $request->otp_number;
                    $phones = $request->phone;
                    $phone  = "855".substr($phones,1);
                }

                if(!empty($phones) && !empty($otp_number)) {

                    // Check Suspension | if failed return errors on that fucntion
                    $checkSUS = $this::check_suspension_faild($phone);

                    // Not Suspension
                    if($checkSUS == true) {
                        // Check OTP Exist and Expire
                        $checkOTP = $this::check_otp($phone, $otp_number);

                        if($checkOTP == true) {
                            $update_otp = $wpdb->update( $wpdb->prefix.'plusgate_otp',
                                array("status" => 1, 'updated_at' => date('Y-m-d G:i:s')),
                                array("phone" => $phone, "otp" => $otp_number)
                            );

                            if($update_otp) {
                                return [
                                    "code"      =>  "success",
                                    "message"   =>  "User has been verified successfully.",
                                    "data"      =>  [
                                        "status"    =>  200,
                                        "phone"    =>  $phone
                                    ],
                                ];
                            }
                            else {
                                $message = array(
                                    'code' => 404,
                                    'message' => 'Connection unstable, please try again..!',
                                    'error' => 'OTP not update status to used in database.'
                                );
                            }  
                        }
                    }
                }
                else {
                    if(empty($phones) && !empty($otp_number)){
                        return [
                            "code"      =>  "invalid_phone_format",
                            "message"   =>  "Invalid phone number format.",
                            "data"      =>  [
                                "status"    =>  411
                            ],
                        ];
                    }
    
                    if(empty($otp_number) && !empty($phones)){
                        return [
                            "code"      =>  "invalid_otp",
                            "message"   =>  "OTP is invalid.",
                            "data"      =>  [
                                "status"    =>  403
                            ],
                        ];
                    }  
                    return [
                        "code"      =>  "invalid_phone_format",
                        "message"   =>  "Invalid phone number format.",
                        "data"      =>  [
                            "status"    =>  411
                        ],
                    ];
                }

                sms_error_log($message);
                wp_send_json_success( $message );
                wp_die();

            } catch (Exception $e) {
                sms_error_log($e->getMessage());
            }
        }

        /**
         * Name: Check OTP
         * Date: 15-02-2023
         */
        public function check_otp($phone, $otp_number) {
            global $wpdb;
            
            try {
                // Check Attemp Failed
                $checkOTP = $wpdb->get_row("SELECT duration, COUNT(id) AS found, created_at
                                            FROM ".$wpdb->prefix."plusgate_otp 
                                            WHERE phone = '".$phone."' AND otp = '".$otp_number."' AND status = 0");
                                        
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

                            sms_error_log($message);
                            wp_send_json_success( $message );
                            wp_die();
                        }
                    }

                    return true;
                }
                else {
                    $insert_otp = $wpdb->insert(
                        $wpdb->prefix.'plusgate_otp_failed',
                        array(
                            'phone' => $phone,
                            'otp' => $otp_number,
                            'created_at' => date('Y-m-d G:i:s')
                        )
                    );
                    
                    $message = array(
                        'code' => 404,
                        'message' => 'The OTP number is invalid, please check your OTP number and try again.',
                        'error' => 'OTP number is not found in database.'
                    );
                }

                sms_error_log($message);
                wp_send_json_success( $message );
                wp_die();

            } catch (Exception $e) {
                sms_error_log($e->getMessage());
            }
        }

        /**
         * Name: Check Attempt Failed
         * Date: 15-02-2023
         */
        public function check_attempt_failed($phone) {
            global $wpdb;

            try {
                $current_date = date('Y-m-d');
                $failed_attempt = get_field('sms_failed_attempt_limit', 'option');
                
                // Check Attemp Failed
                if($failed_attempt > 0) {
                    $checkAtt = $wpdb->get_row("SELECT COUNT(id) AS attempt
                                                FROM ".$wpdb->prefix."plusgate_otp 
                                                WHERE phone = '".$phone."' AND DATE(created_at) = '".$current_date."' AND status = 0");
                    
                    // User Attempt is Blocked
                    if($checkAtt->attempt >= $failed_attempt)  {
                        $message = array(
                            'code' => 406,
                            'message' => 'Opps! You have reached OTP limit, please try for the next day or contact admin.',
                            'error' => 'User have reached limit send OTP.'
                        );

                        sms_error_log($message);
                        wp_send_json_success( $message );
                        wp_die();
                    }
                }

                return true;

            } catch (Exception $e) {
                sms_error_log($e->getMessage());
            }
        }

        /**
         * Name: Check Attemp Suspension Failed
         * Date: 15-02-2023
         */
        public function check_suspension_faild($phone) {
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

                        sms_error_log($message);
                        wp_send_json_success( $message );
                        wp_die();
                    }
                }

                return true;

            } catch (Exception $e) {
                sms_error_log($e->getMessage());
            }
        }

        /**
         * Name: Request CURL
         * Date: 15-02-2023
         */
        public function curl($url, $method, $postfields) {
            try {
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

                return json_decode($response);

            } catch (Exception $e) {
                sms_error_log($e->getMessage());
            }
        }
    }
}