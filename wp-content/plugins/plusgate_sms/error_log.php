<?php

/**
 * Request File
 */

if (!function_exists('sms_error_log'))
{
	function sms_error_log($log, $type = 'debug')
    {
		ini_set( 'error_log',  dirname(__FILE__) .'/'. $type .'.log' );

		if (is_array($log) || is_object($log)) {
			error_log(print_r($log, true));
		}
		else {
			error_log($log);
		}
	}
}