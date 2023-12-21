<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\Metro;
ob_start();
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<?php
        $fb_image = wp_get_attachment_image_src(get_post_thumbnail_id( get_the_ID() ), 'thumnail');
        $fb_description = preg_replace('#<[^>]+>#', '', get_the_content( get_the_ID() ));
    ?>
    <meta property="fb:pages" content="">
    <meta property="og:image" content="<?php echo $fb_image[0]; ?>">
    <meta property="og:description" content="<?php echo $fb_description; ?>">
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="profile" href="https://gmpg.org/xfn/11" />

	<?php
		wp_head();
		acf_form_head();
	?>
</head>

<body <?php body_class(); ?>>

	<?php wp_body_open(); ?>
	<div id="page" class="site">
		<a class="skip-link screen-reader-text" href="#main-content"><?php esc_html_e( 'Skip to content', 'metro' ); ?></a>
		<header id="site-header" class="site-header">
			<?php get_template_part( 'template-parts/header/header-top-desktop' ); ?>
			<?php get_template_part( 'template-parts/header/header', RDTheme::$header_style );?>
		</header>
		<div id="meanmenu"></div>
		<div id="content" class="site-content">
			<?php get_template_part('template-parts/content', 'banner');?>