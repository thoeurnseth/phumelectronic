<?php
/**
 * Includes the composer Autoloader used for packages and classes in the inc/ directory.
 *
 * @package BizSolution/BizPlasGate/Library/PlasGate
 */

namespace BizSolution\BizPlasGate\Library;

defined( 'ABSPATH' ) || exit;

use BizSolution\BizPlasGate\Core\APIResponse as APIResponse;
use BizSolution\BizPlasGate\Library\PlasGateResponse as PlasGateResponse;

/**
 * SMS class.
 *
 * @since 1.0.0
 */
class PlasGateLegacy
{
    
    private $gw_username, $gw_password, $gw_endpoint;
    public $otp_number  = '';
    public $prefixes    = [];



	/**
	 * Static-only class.
	 */
    public function __construct($gw_username, $gw_password, $gw_endpoint)
    {
        $this->gw_username = $gw_username;
        $this->gw_password = $gw_password;
        $this->gw_endpoint = $gw_endpoint;
    }



    public function random_otp( $digits )
    {
        $this->otp_number = '';

        for( $i = 0; $i < $digits; $i++ )
        {
            $this->otp_number .= rand(0,9);
        }

        return $this->otp_number;
    }



    
	/**
	 * Send SMS with using PlasGate curl_init()
	 *
	 * If the params are not invalid, return boolean response.
	 *
	 * @return boolean
	 */
	public function block_prefix( $prefix_string )
	{
        $this->prefixes = explode(',', $prefix_string);
    }



    
	/**
	 * Send SMS with using PlasGate curl_init()
	 *
	 * If the params are not invalid, return boolean response.
	 *
	 * @return boolean
	 */
	public function send_sms($send_id = "BizSolution", $recipient = "", $content = "Testing SMS text.", $prefix = '')
	{
        if( !empty($this->prefixes) AND !empty($prefix) )
        {
            foreach( $this->prefixes as $value)
            {
                if($value == $prefix)
                    return new APIResponse(405, "prefix_blocked", 'This prefix ('.$prefix.') has been blocked.', 'prefix_blocked');
            }
        }
        return $this->curl($send_id, $recipient, $content);
    }



	/**
	 * curl 
	 *
	 * If the params are not invalid, return boolean response.
	 *
	 * @return string
	 */
    protected function curl($send_id = '', $recipient = '', $content = '')
    {
        $curl_init = curl_init();
        $param = array (
            'gw-username' => $this->gw_username,
            'gw-password' => $this->gw_password,
            'gw-from' => $send_id,
            'gw-to' => $recipient,
            'gw-text' => $content,
            'gw-coding' => 1,
        );
        $content = http_build_query ( $param );
        curl_setopt_array ( $curl_init,
            array (
                CURLOPT_URL             => $this->gw_endpoint,
                CURLOPT_FAILONERROR     => true,
                CURLOPT_RETURNTRANSFER  => true,
                CURLOPT_TIMEOUT         => 30,
                CURLOPT_SSL_VERIFYPEER  => FALSE,
                CURLOPT_SSL_VERIFYHOST  => FALSE,
                CURLOPT_HTTP_VERSION    => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST   => "POST",
                CURLOPT_POST            => count($param),
                CURLOPT_POSTFIELDS      => $content,
                CURLOPT_HTTPHEADER      => array (
                    "Content-Type:application/x-www-form-urlencoded"
                )
            )
        );
        
        $res_str = curl_exec ( $curl_init );
        if (curl_errno($curl_init))
        {
            $error_msg = curl_error($ch);
        }
        curl_close ( $curl_init );
        if (isset($error_msg))
        {
            return new APIResponse(404, "page_not_found", $error_msg);
        }

        $status = '';
        $response = explode('&', $res_str);
        foreach( $response as $params )
        {
            $param = explode('=', $params);
            if($param[0] == 'status')
                $status = $param[1];
        }

        $status = (int)$status;
        if($status == 0)
        {
            return new APIResponse(200, "success", 'SMS successfully sent.', $res_str);
        }
        else
        {
            return new APIResponse($status, "plasgate_error", PlasGateResponse::get_response_status($status), $res_str);
        }
    }
}



/**
 * Plasgate - SMS Gateway - HTTP Interface - v1.5
 *
 * @package BizSolution/BizPlasGate/Library/PlasGateResponse
 */
class PlasGateResponse
{
    private static $response = [
        500      =>      "OK. No error encountered.",
        501      =>      "Authorization failed. NOTE: When this error is encountered, NO SMS is sent to any of the receivers.",
        504      =>      "At least one of the destination numbers is not white listed.",
        505      =>      "At least one of the destination numbers is black listed.",
        506      =>      "No destination number specified.",
        508      =>      "Sender ID not found.",
        510      =>      "Invalid mclass field.",
        517      =>      "Invalid validity field.",
        519      =>      "Invalid character set or message body.",
        520      =>      "Insufficient headers for sending SMS.",
        523      =>      "Empty gw-text.",
        524      =>      "Unknown error.",
        527      =>      "Too many receivers in gw-to field.",
        528      =>      "Invalid receiver.",
        529      =>      "Message body is too long.",
        532      =>      "Message throttled.",
        534      =>      "Invalid request received.",
        537      =>      "Invalid sender id length used.",
        540      =>      "System down for maintenance.",
        543      =>      "SMS flooding detected.",
        544      =>      "Invalid Sender ID.",
        545      =>      "System error code, please retry later.",
        548      =>      "At least one of the senders is black listed.",
        549      =>      "At least one of the senders is not white listed.",
        550      =>      "Inappropriate content detected.",
    ];

    public static function get_response_status( $code )
    {
        if( array_key_exists($code, self::$response) )
        {
            return self::$response[$code];
        }
        return 'Unknown error.';
    }
}