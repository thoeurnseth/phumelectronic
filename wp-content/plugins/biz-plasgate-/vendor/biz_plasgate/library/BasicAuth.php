<?php
/**
 * Includes the Basic Auth used for packages and classes in the library/ directory.
 *
 * @package BizSolution/BizPlasGate
 */

namespace BizSolution\BizPlasGate\Library;

defined( 'ABSPATH' ) || exit;

/**
 * BasicAuth class.
 *
 * @since 1.0.0
 */
class BasicAuth
{

	/**
	 * Static-only class.
	 */
    private function __construct(){}
    


	/**
	 * Authenticate Basic Auth by callin gstatic BasicAuth class
	 *
	 * If the params are not invalid, return boolean response.
	 *
	 * @return boolean
	 */
	public static function authenticate($auth_user, $auth_pass)
	{	
        $AUTH_USER = $auth_user;
        $AUTH_PASS = $auth_pass;
        header('Cache-Control: no-cache, must-revalidate, max-age=0');
        $has_supplied_credentials = !(empty($_SERVER['PHP_AUTH_USER']) && empty($_SERVER['PHP_AUTH_PW']));
        $is_not_authenticated = (
            !$has_supplied_credentials ||
            $_SERVER['PHP_AUTH_USER'] != $AUTH_USER ||
            $_SERVER['PHP_AUTH_PW']   != $AUTH_PASS
        );
        if ($is_not_authenticated)
        {
            return FALSE;
        }
        return TRUE;
	}
}