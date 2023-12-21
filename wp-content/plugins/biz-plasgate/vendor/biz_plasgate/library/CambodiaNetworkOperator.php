<?php
/**
 * Includes the composer Autoloader used for packages and classes in the inc/ directory.
 *
 * @package BizSolution/BizPlasGate
 */

namespace BizSolution\BizPlasGate\Library;

defined( 'ABSPATH' ) || exit;



class CambodiaNetworkOperator
{
    public $input_phone;
    public $prefix;
    public $network;
    public $local_line;
    public $international_line;
    public $international_line_p;
    public $country_code;
    public $iso_code;

    public function __construct( $input_phone )
    {
        $formatted_phone = $this->format_phone( $input_phone );
        if( $formatted_phone )
        {
            
            $this->input_phone              =   $input_phone;
            $this->prefix                   =   '0' . substr(substr($formatted_phone, 3), 0, 2);;
            $this->network                  =   $input_phone;
            $this->local_line               =   '0' . substr($formatted_phone, 3);
            $this->international_line       =   $formatted_phone;
            $this->international_line_p     =   '+' . $formatted_phone;
            $this->country_code             =   '855';
            $this->iso_code                 =   'KH/KHM';
        }
        else
            return NULL;
    }

    private function format_phone( $phone_number )
    {
        $phone_number = preg_replace("/[^0-9]/", "",$phone_number);
		$first_3l = substr($phone_number, 0, 3);
        if($first_3l == '855')
            $phone_number = substr_replace($phone_number, '', 0, 3);
		$phone_number = ltrim($phone_number,"0");
        $phone_number = '0' . $phone_number;
		if(strlen($phone_number) <= 8)
			return FALSE;
        else if( strlen($phone_number) <= 10 ){
            $phone_number = '855' . substr($phone_number, 1);
        }
        else{
            $first_3l = substr($phone_number, 0, 3);
            if($first_3l != '855')
                return FALSE;

            $without_first_3l = substr($phone_number, 3);
            if( strlen($without_first_3l) > 9)
                return FALSE;

            $first_3l = substr($without_first_3l, 0, 1);
            if($first_3l == '0')
                return FALSE;
			

            $phone_number = $phone_number;
        }

        return $phone_number;
    }

    private function get_prefix($phone)
    {
    }
    
}