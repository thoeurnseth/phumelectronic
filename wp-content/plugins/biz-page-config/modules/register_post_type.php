<?php

add_action( 'init', 'register_post_type_page_confg' );
function register_post_type_page_confg() {
	
	$args = array(
		"labels" => array(
			"name" => __( 'Page Setting' ),
			"menu_name" => __( 'Page Setting' ),
			"all_items" => __( 'Page Setting' ),
		),
		"public" => true,
		"show_in_menu" => true,
		"menu_position" => 100,
		"menu_icon" => "dashicons-groups",
		"supports" => array( "title", "editor", "author" ),
		"capability_type" => "post",
		"description" => "Page Setting",
		"page_url" => "page-setting"			
	);
	register_post_type( "page-setting", $args );
}

add_action( 'init', 'register_post_type_page_confg_login' );
function register_post_type_page_confg_login() {
	
	$args = array(
		"labels" => array(
			"name" => __( 'Page Setting Login' ),
			"menu_name" => __( 'Page Setting Login' ),
			"all_items" => __( 'Page Setting Login List' ),
		),
		"public" => true,
		"show_in_menu" => true,
        "show_in_menu" => "edit.php?post_type=page-setting",
		"menu_icon" => "dashicons-groups",
		"supports" => array( "title", "editor", "author" ),
		"capability_type" => "post",
		"description" => "Page Setting Login",
		"page_url" => "page-setting-login"			
	);
	register_post_type( "page-setting-login", $args );
}

// E-Warranty
add_action( 'init', 'register_post_type_e_warranty' );
function register_post_type_e_warranty() {
	
	$args = array(
		"labels" => array(
			"name" => __( 'E-Warranty' ),
			"menu_name" => __( 'E-Warranty' ),
			"all_items" => __( 'E-Warranty' ),
		),
		"public" => true,
		"show_in_menu" => true,
		"menu_position" => 100,
		"menu_icon" => "dashicons-groups",
		"supports" => array( "title", "editor", "author" ),
		"capability_type" => "post",
		"description" => "E-Warranty",
		"page_url" => "E-Warranty"			
	);
	register_post_type( "e-warranty", $args );
}