<?php
/**
 * Includes the composer Autoloader used for packages and classes in the inc/ directory.
 *
 * @package BizSolution/BizPlasGate/Core/APIResponse
 */

namespace BizSolution\BizPlasGate\Core;

defined( 'ABSPATH' ) || exit;

/**
 * Response class.
 *
 * @since 1.0.0
 */
class APIResponse
{
    private $array;

    public $code;
    public $status;
    public $description;
    public $data; 

    public function __construct($code, $status, $description = '', $data = '')
    {
        $this->code             = $code;
        $this->status           = $status;
        $this->description      = $description;
        $this->data             = $data;

        $this->array  = [
            "code"          =>  $this->code,
            "status"        =>  $this->status,
            "description"   =>  $this->description,
            "data"          =>  $this->data,
        ];

        return $this->array;
    }

    public function json()
    {
        return json_encode( $this->array );
    }

    public function array()
    {
        return $this->array;
    }
}