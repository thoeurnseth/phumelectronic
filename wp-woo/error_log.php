<?php

/**
 * Request File
 */

if (!function_exists('odoo_write_log'))
{
	function odoo_write_log($log, $type = 'debug')
    {
		ini_set( 'error_log', $type .'.log' );

		if (is_array($log) || is_object($log))
		{
			error_log(print_r($log, true));
		}
		else {
			error_log($log);
		}
	}
}