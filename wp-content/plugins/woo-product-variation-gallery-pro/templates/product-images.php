<?php
/**
 * Single Product Images
 *
 * @see        https://docs.woocommerce.com/document/template-structure/
 * @author     WooThemes
 * @package    WooCommerce/Templates
 * @version    3.6.5
 */


use Rtwpvg\Helpers\Functions;

defined( 'ABSPATH' ) || exit;

global $product;

$product_id   = $product->get_id();
$product_type = $product->get_type();

$columns = absint( apply_filters( 'rtwpvg_thumbnails_columns', rtwpvg()->get_option( 'thumbnails_columns' ) ) );

$post_thumbnail_id = $product->get_image_id();
if($post_thumbnail_id) {
    $default_sizes = wp_get_attachment_image_src($post_thumbnail_id, 'woocommerce_single');
    $default_height = $default_sizes[2];
    $default_width = $default_sizes[1];
}

$attachment_ids = $product->get_gallery_image_ids();

$has_gallery_thumbnail = ( has_post_thumbnail() && ( count( $attachment_ids ) > 0 ) );

$gallery_slider_js_options   = apply_filters( 'rtwpvg_slider_js_options', array(
	'slidesToShow'   => 1,
	'slidesToScroll' => 1,
	'arrows'         => rtwpvg()->get_option( 'slider_arrow' ) ? true : false,
	'adaptiveHeight' => rtwpvg()->get_option( 'slider_adaptive_height' ) ? true : false,
	'rtl'            => is_rtl(),
	'asNavFor'       => '.rtwpvg-thumbnail-slider',
	"prevArrow"      => '<i class="rtwpvg-slider-prev-arrow dashicons dashicons-arrow-left-alt2"></i>',
	"nextArrow"      => '<i class="rtwpvg-slider-next-arrow dashicons dashicons-arrow-right-alt2"></i>',
	// 'lazyLoad'       => 'progressive',
	"rows"           => 0
) );
$thumbnail_position          = rtwpvg()->get_option( 'thumbnail_position' );
$thumbnail_slider_js_options = apply_filters( 'rtwpvg_thumbnail_slider_js_options', array(
	'slidesToShow'   => $columns,
	'slidesToScroll' => $columns,
	'focusOnSelect'  => true,
	'arrows'         => true,
	'vertical'       => in_array( $thumbnail_position, array( 'left', 'right' ) ) ? true : false,
	'asNavFor'       => '.rtwpvg-slider',
	'centerMode'     => ( $columns % 2 !== 0 ? true : false ),
	'infinite'       => true,
	'rtl'            => is_rtl(),
	"prevArrow"      => '<i class="rtwpvg-thumbnail-prev-arrow dashicons dashicons-arrow-left-alt2"></i>',
	"nextArrow"      => '<i class="rtwpvg-thumbnail-next-arrow dashicons dashicons-arrow-right-alt2"></i>',
	"responsive"     => array(
		array(
			"breakpoint" => 768,
			"settings"   => array(
				"vertical" => false
			)
		),
		array(
			"breakpoint" => 480,
			"settings"   => array(
				"vertical" => false
			)
		)
	),
	'centerPadding'  => '0px',
	"rows"           => 0
) );

$gallery_thumbnail_position = rtwpvg()->get_option( 'thumbnail_position' );


$gallery_width = absint( apply_filters( 'rtwpvg_width', rtwpvg()->get_option( 'gallery_width' ) ) );

$inline_style = apply_filters( 'rtwpvg_product_inline_style', array() );

$wrapper_classes = apply_filters( 'rtwpvg_image_classes', array(
	'rtwpvg-images',
	'rtwpvg-images-thumbnail-columns-' . absint( $columns ),
	$has_gallery_thumbnail ? 'rtwpvg-has-product-thumbnail' : ''
) );
?>

<div style="<?php echo esc_attr( Functions::generate_inline_style( $inline_style ) ) ?>"
     class="<?php echo esc_attr( implode( ' ', array_map( 'sanitize_html_class', array_unique( $wrapper_classes ) ) ) ); ?>">
    <div class="loading-rtwpvg rtwpvg-wrapper rtwpvg-thumbnail-position-<?php echo esc_attr( $gallery_thumbnail_position ) ?> rtwpvg-product-type-<?php echo esc_attr( $product_type ) ?>">

        <div class="rtwpvg-container rtwpvg-preload-style-<?php echo trim( rtwpvg()->get_option( 'preload_style' ) ) ?>">

            <div class="rtwpvg-slider-wrapper">

				<?php if ( has_post_thumbnail() && rtwpvg()->get_option( 'lightbox' ) ): ?>
                    <a href="#"
                       class="rtwpvg-trigger rtwpvg-trigger-position-<?php echo rtwpvg()->get_option( 'zoom_position' ); ?>">
                        <span class="dashicons dashicons-search"></span>
                    </a>
				<?php endif; ?>

                <div class="rtwpvg-slider"
                     data-slick='<?php echo htmlspecialchars( wp_json_encode( $gallery_slider_js_options ) ); // WPCS: XSS ok. ?>'>
					<?php
					// Main  Image
					if ( has_post_thumbnail() ) :
						echo Functions::get_gallery_image_html( $post_thumbnail_id, true );
					else:
						echo '<div class="rtwpvg-gallery-image rtwpvg-gallery-image-placeholder">';
						echo sprintf( '<img src="%s" alt="%s" class="wp-post-image" />', esc_url( wc_placeholder_img_src() ), esc_html__( 'Awaiting product image', 'woocommerce' ) );
						echo '</div>';
					endif;

					// Gallery attachment Images
					if ( $has_gallery_thumbnail ) :
						foreach ( $attachment_ids as $attachment_id ) :
							echo Functions::get_gallery_image_html( $attachment_id, true );
						endforeach;
					endif;
					?>
                </div>
            </div> <!-- .Slider-wrapper -->

            <div class="rtwpvg-thumbnail-wrapper">
                <div class="rtwpvg-thumbnail-slider rtwpvg-thumbnail-columns-<?php echo esc_attr( $columns ) ?>"
                     data-slick='<?php echo htmlspecialchars( wp_json_encode( $thumbnail_slider_js_options ) ); // WPCS: XSS ok. ?>'>
					<?php
					if ( $has_gallery_thumbnail ):
						// Main Image
						echo Functions::get_gallery_image_html( $post_thumbnail_id );

						// Gallery attachment Images
						foreach ( $attachment_ids as $key => $attachment_id ) :
							echo Functions::get_gallery_image_html( $attachment_id, false, $key );
						endforeach;
					endif;
					?>
                </div>
            </div> <!-- .Thumb-wrapper -->
        </div> <!-- .container -->
    </div> <!-- .rtwpvg-wrapper -->
</div>


