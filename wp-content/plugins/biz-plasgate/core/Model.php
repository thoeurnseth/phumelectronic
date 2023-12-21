<?php
/**
 * Includes the composer Autoloader used for packages and classes in the inc/ directory.
 *
 * @package BizSolution/BizPlasGate
 */

namespace BizSolution\BizPlasGate\Core;

defined( 'ABSPATH' ) || exit;

/**
 * Model class.
 *
 * @since 1.0.0
 */
class Model
{
    protected $table_name;
    protected $primary_key = "id";
    protected $sql_command;
    private $where_clause = 'WHERE';

    public $instance;
    public $wpdb;

    protected function __construct()
    {
        global $wpdb;
        $this->wpdb                                 =   $wpdb;
        $this->table_name 		                    =   $this->wpdb->prefix . $this->table_name;
    }

    public function create($params)
    {
        $esc_sql_params = [];
        foreach( $params as $key => $val )
        {
            $esc_sql_params[$key] = esc_sql($val);
        }
        return $this->wpdb->insert($this->table_name, $esc_sql_params);
    }

    public function update($params, $row = '' )
    {
        if( empty($row) )
        {
            $row = ["id" => $this->instance->id];
        }
        $esc_sql_params = [];
        foreach( $params as $key => $val )
        {
            $esc_sql_params[$key] = esc_sql($val);
        }
        return $this->wpdb->update($this->table_name, $params, $row);
    }

    public function find( $value )
    {
        $esc_sql_value = esc_sql($value);
        $esc_sql_value = (int) $esc_sql_value;
        $this->sql_command = " WHERE $this->primary_key = $esc_sql_value";
        return $this->get();
    }

    public function where( $field_name, $value )
    {
        $esc_sql_value = esc_sql($value);
        $esc_sql_field_name = esc_sql($field_name);
        $this->sql_command .= " $this->where_clause $esc_sql_field_name = '$esc_sql_value'";
        $this->where_clause = "AND";
        return $this;
    }

    public function orWhere( $field_name, $value )
    {
        $esc_sql_value = esc_sql($value);
        $esc_sql_field_name = esc_sql($field_name);
        $this->sql_command .= " OR $esc_sql_field_name = '$esc_sql_value'";
        return $this;
    }

    public function limit( $value )
    {
        $esc_sql_value = esc_sql($value);
        $value = (int) $value;
        $this->sql_command .= " LIMIT $esc_sql_value";
        return $this;
    }

    public function skip( $value )
    {
        $esc_sql_value = esc_sql($value);
        $value = (int) $value;
        $this->sql_command .= " OFFSET $esc_sql_value";
        return $this;
    }

    public function get()
    {
        $results = $this->wpdb->get_results( "SELECT * FROM  " . $this->table_name . $this->sql_command );
        $this->instance = $results;
        return $results;
    }

    public function first()
    {
        $results = $this->wpdb->get_results( "SELECT * FROM  " . $this->table_name . $this->sql_command . " ORDER BY id DESC");
        foreach($results as $item)
        {
            $this->instance = $item;
        }
        return $this;
    }


    protected function __destruct()
    {
        $this->wpdb->close();
    }

    public function delete()
    {
    }

    public function orderBy()
    {
    }

    public function groupBy()
    {
    }

}