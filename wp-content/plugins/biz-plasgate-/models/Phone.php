<?php
/**
 * Includes the composer Autoloader used for packages and classes in the inc/ directory.
 *
 * @package BizSolution/BizPlasGate/Model/Phone
 */

namespace BizSolution\BizPlasGate\Model;
use BizSolution\BizPlasGate\Core\Model as Model;

defined( 'ABSPATH' ) || exit;

/**
 * Phone class.
 *
 * @since 1.0.0
 */
class Phone extends Model
{
    protected $table_name = 'biz_plasgate_phonenumber';

    public function __construct()
    {
        parent::__construct();
    }
    
    public function __destruct()
    {
        parent::__destruct();
    }
}