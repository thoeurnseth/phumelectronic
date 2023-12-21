<?php
/**
 * Includes the composer Autoloader used for packages and classes in the inc/ directory.
 *
 * @package BizSolution/BizPlasGate/Model/SMS
 */

namespace BizSolution\BizPlasGate\Model;
use BizSolution\BizPlasGate\Core\Model as Model;

defined( 'ABSPATH' ) || exit;

/**
 * Model class.
 *
 * @since 1.0.0
 */
class SMS extends Model
{
    protected $table_name = 'biz_plasgate_sms';

    public function __construct()
    {
        parent::__construct();
    }
    
    public function __destruct()
    {
        parent::__destruct();
    }
}