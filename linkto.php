<?php
    $menu = $_GET['menu'];
    $id = $_GET['id'];
    if (strpos($_SERVER['HTTP_USER_AGENT'], 'iPhone OS') !== FALSE) {
        // redirect
        $url = 'phum-electronic:/'.$menu.'/'.$id;
        //echo $url;
        header("location: ".$url."");
        exit();
    }
?>