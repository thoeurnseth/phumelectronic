<?php
/**
 * Includes the composer Autoloader used for packages and classes in the inc/ directory.
 *
 * @package BizSolution/BizPlasGate/Core/Route
 */

namespace BizSolution\BizPlasGate\Core;

defined( 'ABSPATH' ) || exit;

use BizSolution\BizPlasGate\Controller\SMSController as SMSController;
/**
 * Route class.
 *
 * @since 1.0.0
 */
class Route
{
    private function __construct(){}

    public static function get( $route_name, $controller_method )
    {
        add_action( 'rest_api_init', function() use($route_name, $controller_method){
            self::callable_method('GET', $route_name, $controller_method);
        });
    }

    public static function post( $route_name, $controller_method )
    {
        add_action( 'rest_api_init', function() use($route_name, $controller_method){
            self::callable_method('POST', $route_name, $controller_method);
        });
    }

    public static function put( $route_name, $controller_method )
    {
    }

    public static function delete( $route_name, $controller_method )
    {
    }

    private static function callable_method($method, $route_name, $controller_method)
    {
        register_rest_route( BIZ_PLASGATE_REST_URL, $route_name, [
            'methods'       =>      $method,
            'callback'      =>      function() use($controller_method){

                $controller_method = explode('@', $controller_method);
                $controller = $controller_method[0];
                $method = $controller_method[1];

                $__class__ = '\\BizSolution\\BizPlasGate\\Controller\\' . $controller;
                $object = new $__class__;

                return $object->$method();
            },
            'permission_callback' => '__return_true'
        ]);
    }

}