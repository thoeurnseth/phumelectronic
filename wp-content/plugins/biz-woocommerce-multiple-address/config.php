<?php


	/**
	 * Define plugin directory
	 */
	if ( ! defined( 'BIZ_WMA_PLUGIN_DIR' ) )
	{
		define( 'BIZ_WMA_PLUGIN_DIR', __DIR__ );
	}




	/**
	 * Define plugin file
	 */
	if ( ! defined( 'BIZ_WMA_PLUGIN_FILE' ) )
	{
		define( 'BIZ_WMA_PLUGIN_FILE', __FILE__ );
	}




	/**
	 * Define Rest Route for Biz PlasGate
	 */
	// if ( ! defined( 'BIZ_PLASGATE_REST_URL' ) )
	// {
	// 	define( 'BIZ_PLASGATE_REST_URL', 'biz-plasgate/api/v2' );
	// }


	  /**
 * Validate ACF field address
 */

// Validate fields Province
add_filter('acf/validate_value/name=province_2', 'validated_province_filter', 10, 4);
function validated_province_filter($valid, $value, $field, $input) {

    // Bail early if value is already invalid
    if( !$valid ) {
        return $valid;
    }

    // Get two values
    // You need to change these based on your field keys
    $province = $_POST['acf']['field_5fc60fa2bb209'];

    // Invalid
    if ( empty($province) )  {
        $valid = 'Province value is required.';
    }

    // Return
    return $valid;
}
    

// Validate fields District
add_filter('acf/validate_value/name=district_2', 'validated_district_filter', 10, 4);
function validated_district_filter($valid, $value, $field, $input) {

    // Bail early if value is already invalid
    if( !$valid ) {
        return $valid;
    }

    // Get two values
    // You need to change these based on your field keys
    $district = $_POST['acf']['field_5fc6100dca218'];

    if ( empty($district) )  {
        $valid = 'District value is required';
    }

    // Return
    return $valid;
}


// Validate fields Commune
add_filter('acf/validate_value/name=commune_2', 'validated_district_commune', 10, 4);
function validated_district_commune($valid, $value, $field, $input) {

    // Bail early if value is already invalid
    if( !$valid ) {
        return $valid;
    }

    // Get two values
    // You need to change these based on your field keys
    $commune  = $_POST['acf']['field_5fc6101ad6dd3'];

    // Invalid
    if ( empty($commune) )  {
        $valid = 'Commune value is required.';
    }

    // Return
    return $valid;
}