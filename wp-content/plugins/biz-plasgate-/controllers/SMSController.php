<?php
/**
 *
 * @package BizSolution/BizPlasGate/Controller/SMSController
 */

namespace BizSolution\BizPlasGate\Controller;

use BizSolution\BizPlasGate\Library\PlasGate as PlasGate;
use BizSolution\BizPlasGate\Library\BasicAuth as BasicAuth;
use BizSolution\BizPlasGate\Library\CambodiaNetworkOperator as CambodiaNetworkOperator;

use BizSolution\BizPlasGate\Model\SMS as SMS;
use BizSolution\BizPlasGate\Model\Phone as Phone;

defined( 'ABSPATH' ) || exit;


/**
 * Controller class.
 *
 * @since 1.0.0
 */
class SMSController
{
    public function __contruct(){}

    public function send_otp()
    {
        
        if( !isset($_POST['phone']) ):

            // Return Invalid params.
            return [
                "code"      =>  "invalid_param",
                "message"   =>  "Invalid params.",
                "data"      =>  [
                    "status"    =>  410
                ],
            ];

        endif;

        $phone = new CambodiaNetworkOperator($_POST['phone']);

        if( is_null($phone->international_line) ):
            // Return Invalid params.
            return [
                "code"      =>  "invalid_phone_format",
                "message"   =>  "Invalid phone number format.",
                "data"      =>  [
                    "status"    =>  411
                ],
            ];
        endif;

        $biz_plasgate_disable_plasgate      = get_option('biz_plasgate_disable_plasgate');
        $biz_plasgate_auth_plugin           = get_option('biz_plasgate_auth_plugin');

        // Load BasicAuth credential from wordpress setting
        $biz_plasgate_php_auth_user         = get_option('biz_plasgate_php_auth_user');
        $biz_plasgate_php_auth_pw           = get_option('biz_plasgate_php_auth_pw');

        // Load PlasGate credential from wordpress setting
        $biz_plasgate_username              = get_option('biz_plasgate_username');
        $biz_plasgate_password              = get_option('biz_plasgate_password');
        $biz_plasgate_endpoint              = get_option('biz_plasgate_endpoint');

        // Load OTP Setting from wordpress setting
        $biz_plasgate_pin_code_digit_number                     = get_option('biz_plasgate_pin_code_digit_number');
        $biz_plasgate_failed_attempt_limit                      = get_option('biz_plasgate_failed_attempt_limit');
        $biz_plasgate_failed_attempt_period_of_suspension       = get_option('biz_plasgate_failed_attempt_period_of_suspension');
        $biz_plasgate_blocked_prefixes                          = get_option('biz_plasgate_blocked_prefixes');

        // Load OTP SMS Template from wordpress setting
        $biz_plasgate_template_sender_id     = get_option('biz_plasgate_template_sender_id');
        $biz_plasgate_template_content       = get_option('biz_plasgate_template_content');
		
        // Check authentication mechanism whether BasicAuth or No Auth
        // Check if not authenticated using BasicAuth
        if ( 
            $biz_plasgate_auth_plugin == 'basic-auth' AND
            !BasicAuth::authenticate($biz_plasgate_php_auth_user, $biz_plasgate_php_auth_pw)
        ):

            // Return Unauthenticated
            return [
                "code"      =>  "unauthenticated",
                "message"   =>  "You are not authenticated.",
                "data"      =>  [
                    "status"    =>  401
                ],
            ];

        endif;


        // Check if PlasGate enable or disable
        if (  $biz_plasgate_disable_plasgate != 'enable' ):

            // Return Disabled
            return [
                "code"      =>  "disabled",
                "message"   =>  "PlasGate has been disabled from this server.",
                "data"      =>  [
                    "status"    =>  402
                ],
            ];

        endif;


        $plasgate       =   new PlasGate( $biz_plasgate_username, $biz_plasgate_password, $biz_plasgate_endpoint);
        $otp            =   $plasgate->random_otp($biz_plasgate_pin_code_digit_number);
        if (preg_match_all("~\{\{\s*(.*?)\s*\}\}~", $biz_plasgate_template_content, $dynamic_values)):
            $biz_plasgate_template_content = str_replace("{{OTP}}", $otp, $biz_plasgate_template_content);
        endif;
        $send_sms       =   $plasgate->block_prefix( $biz_plasgate_blocked_prefixes );
        $send_sms       =   $plasgate->send_sms(
                                            $biz_plasgate_template_sender_id,
                                            $phone->international_line,
                                            $biz_plasgate_template_content,
                                            $phone->prefix
                                        );

        $phone_number = new Phone;
        $phone_number = $phone_number->where('phone', $phone->international_line)->first();
        biz_write_log($phone_number,'06-01-2022 Phone OTP'); 
        if( !is_null($phone_number->instance) ):
            $phone_number->update([
                "otp_number"    =>  $otp,
                "updated_at"    =>  date("Y-m-d H:i:s")
            ]);
        else:
            $phone_number->create([
                "prefix"        =>  $phone->prefix,
                "phone"         =>  $phone->international_line,
                "otp_number"    =>  $otp,
                "created_at"    =>  date("Y-m-d H:i:s"),
                "updated_at"    =>  date("Y-m-d H:i:s")
            ]);
        endif;


        $sms = new SMS;
        $sms = $sms->create([
            "sender_id"         =>  $biz_plasgate_template_sender_id,
            "prefix"            =>  $phone->prefix,
            "phone"             =>  $phone->international_line,
            "content"           =>  $biz_plasgate_template_content,
            "plasgate_response" =>  $send_sms->data,
            "updated_at"        =>  date("Y-m-d H:i:s")
        ]);

        return [
            "code"      =>  $send_sms->status,
            "message"   =>  $send_sms->description,
            "data"      =>  [
                "status"    =>  $send_sms->code,
                "phone"     =>  $phone->international_line
            ],
        ];


    }

    public function verify_otp()
    {
        if( !isset($_POST['phone']) && isset($_POST['otp_number']) ):

            // Return Invalid params.
            return [
                "code"      =>  "invalid_param",
                "message"   =>  "Invalid params.",
                "data"      =>  [
                    "status"    =>  410
                ],
            ];

        endif;

        $phone_number      = new CambodiaNetworkOperator($_POST['phone']);
        $otp_number = $_POST['otp_number'];

        if( is_null($phone_number->international_line) ):
            // Return Invalid params.
            return [
                "code"      =>  "invalid_phone_format",
                "message"   =>  "Invalid phone number format.",
                "data"      =>  [
                    "status"    =>  411
                ],
            ];
        endif;


        $phone  = new Phone;
        $phone  = $phone
                ->where('phone', $phone_number->international_line)
                ->where('otp_number', $otp_number)
                ->get();

        if( count($phone) > 0 ):

            return [
                "code"      =>  "success",
                "message"   =>  "User has been verified successfully.",
                "data"      =>  [
                    "status"    =>  200,
                    "phone"     =>  $phone_number->international_line,
                ],
            ];


        else:

            return [
                "code"      =>  "invalid_otp",
                "message"   =>  "OTP is invalid.",
                "data"      =>  [
                    "status"    =>  403
                ],
            ];

        endif;

    }
}