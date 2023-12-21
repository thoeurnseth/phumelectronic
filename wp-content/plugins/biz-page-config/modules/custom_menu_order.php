<?php


add_filter( 'custom_menu_order', 'custom_menu_order_page_config');
function custom_menu_order_page_config( $menu_ord )
{
    global $submenu;

    $arr_page_config = array();
    $arr_page_config[] = $submenu['edit.php?post_type=page-config'][11]; //
    $submenu['edit.php?post_type=page-config'] = $arr_page_config;
}



add_action( 'admin_init', 'post_redirect' );
function post_redirect()
{
    if(isset($_REQUEST['post_type']))
    {
        if($_REQUEST['post_type']=="page-config-login")
        {
            echo '<script>window.location.replace("'.admin_url("post.php?post=1695&action=edit").'")</script>';

        }
    }
}