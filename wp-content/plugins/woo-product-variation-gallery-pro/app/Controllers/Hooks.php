<?php

namespace Rtwpvg\Controllers;

use Rtwpvg\Helpers\Functions;

class Hooks
{

    public function __construct() {

        add_action('init', array($this, 'remove_wc_default_template'), 200);

        add_filter('body_class', array($this, 'body_class'));
        add_filter('post_class', array($this, 'product_loop_post_class'), 25, 3);

        add_action('after_setup_theme', array($this, 'enable_theme_support'), 200); // Enable theme support

        add_action('woocommerce_save_product_variation', array($this, 'save_variation_gallery'), 10, 2);
        add_action('woocommerce_product_after_variable_attributes', array($this, 'gallery_admin_html'), 10, 3);

        add_filter('woocommerce_available_variation', array($this, 'available_variation_gallery'), 90, 3);
        add_action('woocommerce_before_single_product_summary', array(
            $this,
            'woocommerce_show_product_images'
        ), 22);
        add_filter('wc_get_template', array($this, 'gallery_template_override'), 30, 2);

        add_action('wp_ajax_rtwpvg_get_default_gallery_images', array($this, 'get_default_gallery_images'));
        add_action('wp_ajax_nopriv_rtwpvg_get_default_gallery_images', array($this, 'get_default_gallery_images'));

        add_action('wp_ajax_rtwpvg_get_variation_images', array($this, 'get_variation_images'));
        add_action('wp_ajax_nopriv_rtwpvg_get_variation_images', array($this, 'get_variation_images'));

        add_filter('rtwpvg_inline_style', array($this, 'rtwpvg_add_inline_style'), 9);

        add_filter('attachment_fields_to_edit', array($this, 'add_media_video_url'), null, 2);
        add_filter('wp_prepare_attachment_for_js', array($this, 'update_attachment_for_js'), 10, 3);

        add_action('wp_ajax_save-attachment-compat', array($this, 'save_compat_video_field_via_ajax'), 0, 1);
        add_action('edit_attachment', array($this, 'save_compat_video_field'), 1);
    }

    function save_compat_video_field_via_ajax() {
        $post_id = $_POST['id'];
        if (isset($_POST['attachments'][$post_id]['rtwpvg_video_link'])) {
            $link = $_POST['attachments'][$post_id]['rtwpvg_video_link'];
            update_post_meta($post_id, 'rtwpvg_video_link', $link);

            clean_post_cache($post_id);
        }
    }

    /**
     * Update media custom field from edit media page (non ajax).
     *
     * @param $post_id
     */

    function save_compat_video_field($post_id) {
        if (isset($_POST['attachments'][$post_id]['rtwpvg_video_link'])) {
            $video = $_POST['attachments'][$post_id]['rtwpvg_video_link'];
            update_post_meta($post_id, 'rtwpvg_video_link', $video);
        }

        return;
    }

    function update_attachment_for_js($response, $attachment, $meta) {
        $response['rtwpvg_video_link'] = get_post_meta($attachment->ID, 'rtwpvg_video_link', true);

        return $response;
    }

    function add_media_video_url($form_fields, $post) {
        $video = get_post_meta($post->ID, 'rtwpvg_video_link', true);

        $form_fields['rtwpvg_video_link_label'] = array(
            'tr' => "<tr>
                            <td colspan='2'>
                                " . __('<h2>VARIATION GALLERY VIDEO</h2>', "woo-product-variation-gallery") . "
                            </td>
                        </tr>",
        );
        $form_fields['rtwpvg_video_link'] = array(
            'value' => $video ? $video : '',
            'label' => __('Video URL', "woo-product-variation-gallery"),
            'helps' => __('Youtube or vimeo video url<br> <a href="#" class="rtwpvg-media-video-popup">Upload your video <span class="dashicons dashicons-video-alt3"></span></a>', "woo-product-variation-gallery"),
            'input' => 'url'
        );

        return $form_fields;
    }

    public function body_class($classes) {
        array_push($classes, 'rtwpvg');

        return array_unique($classes);
    }

    public function product_loop_post_class($classes, $class, $product_id) {

        if ('product' === get_post_type($product_id)) {
            $product = wc_get_product($product_id);
            if ($product->is_type('variable')) {
                $classes[] = 'rtwpvg-product';
            }
        }

        return $classes;
    }

    function rtwpvg_add_inline_style($styles) {
        $gallery_width = absint(apply_filters('rtwpvg_default_width', 30));
        if ($gallery_width > 99) {
            $styles['float'] = 'none';
            $styles['display'] = 'block';
        }

        return $styles;
    }

    public function woocommerce_show_product_images() {
        Functions::get_template('product-images');
    }

    public function gallery_template_override($located, $template_name) {
        if ($template_name == 'single-product/product-image.php') {
            $located = rtwpvg()->locate_template('product-images');
        }

        if ($template_name == 'single-product/product-thumbnails.php') {
            $located = rtwpvg()->locate_template('product-thumbnails');
        }

        return $located;
    }

    public function enable_theme_support() {
        add_theme_support('wc-product-gallery-zoom');
        add_theme_support('wc-product-gallery-lightbox');
    }

    public function remove_wc_default_template() {
        if (apply_filters('rtwpvg_remove_wc_default_template', true)) {
            remove_action('woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 10);
            remove_action('woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20);
            remove_action('woocommerce_product_thumbnails', 'woocommerce_show_product_thumbnails', 20);
            remove_action('woocommerce_before_single_product_summary_product_images', 'woocommerce_show_product_thumbnails', 20);
        }
    }

    public function save_variation_gallery($variation_id, $loop) {
        if (isset($_POST['rtwpvg'])) {
            if (isset($_POST['rtwpvg'][$variation_id])) {
                $rtwpvg_ids = (array) array_map( 'absint', $_POST[ 'rtwpvg' ][ $variation_id ] );
                update_post_meta($variation_id, 'rtwpvg_images', $rtwpvg_ids);
            } else {
                delete_post_meta($variation_id, 'rtwpvg_images');
            }
        } else {
            delete_post_meta($variation_id, 'rtwpvg_images');
        }
    }

    public function gallery_admin_html($loop, $variation_data, $variation) {
        $variation_id = absint($variation->ID);
        $gallery_images = get_post_meta($variation_id, 'rtwpvg_images', true);
        ?>
        <div class="form-row form-row-full rtwpvg-gallery-wrapper">
            <h4><?php esc_html_e('Variation Image Gallery', 'woo-product-variation-gallery') ?></h4>
            <div class="rtwpvg-image-container">
                <ul class="rtwpvg-images">
                    <?php
                    if (is_array($gallery_images) && !empty($gallery_images)) {
                        foreach ($gallery_images as $image_id):
                            $image = wp_get_attachment_image_src($image_id);
                            $video = get_post_meta($image_id, 'rtwpvg_video_link', true);
                            ?>
                            <li class="image<?php echo $video ? ' video' : null; ?>">
                                <input type="hidden" name="rtwpvg[<?php echo esc_attr($variation_id) ?>][]"
                                       value="<?php echo $image_id ?>">
                                <img src="<?php echo esc_url($image[0]) ?>">
                                <a href="#" class="delete rtwpvg-remove-image"><span
                                            class="dashicons dashicons-dismiss"></span></a>
                            </li>
                        <?php endforeach;
                    } ?>
                </ul>
            </div>
            <p class="rtwpvg-add-image-wrapper hide-if-no-js">
                <a href="#" data-product_variation_loop="<?php echo absint($loop) ?>"
                   data-product_variation_id="<?php echo esc_attr($variation_id) ?>"
                   class="button rtwpvg-add-image"><?php esc_html_e('Add Gallery Images', 'woo-product-variation-gallery') ?></a>
            </p>
        </div>
        <?php
    }


    /**
     * @param $available_variation
     * @param $variationProductObject
     * @param $variation
     *
     * @return string
     */
    public function available_variation_gallery($available_variation, $variationProductObject, $variation) {

        $product_id = absint($variation->get_parent_id());
        $variation_id = absint($variation->get_id());
        $variation_image_id = absint($variation->get_image_id());
        $has_variation_gallery_images = (bool)get_post_meta($variation_id, 'rtwpvg_images', true);
        $product = wc_get_product($product_id);

        if ($has_variation_gallery_images) {
            $gallery_images = (array)get_post_meta($variation_id, 'rtwpvg_images', true);
        } else {
            $gallery_images = $product->get_gallery_image_ids();
        }


        if ($variation_image_id) {
            array_unshift($gallery_images, $variation->get_image_id());
        } else {
            if (has_post_thumbnail($product_id)) {
                array_unshift($gallery_images, get_post_thumbnail_id($product_id));
            }
        }

        $available_variation['variation_gallery_images'] = array();

        $image_size = apply_filters('woocommerce_gallery_image_size', 'woocommerce_single');

        foreach ($gallery_images as $i => $variation_gallery_image_id) {

            $default_image = wp_get_attachment_image_src($variation_gallery_image_id, $image_size);

            $available_variation['variation_gallery_images'][$i] = wc_get_product_attachment_props($variation_gallery_image_id);
            $available_variation['variation_gallery_images'][$i]['image_id'] = absint($variation_gallery_image_id);
            $available_variation['variation_gallery_images'][$i]['css_class'] = ($i < 1) ? 'wp-post-image' : '';
            $available_variation['variation_gallery_images'][$i]['default_src'] = $default_image[0];

            // $available_variation[ 'variation_gallery_images' ][ $i ][ 'wrapper_css_class' ] = ( $i < 1 ) ? 'current-thumbnail' : '';


            $has_video = trim(get_post_meta($variation_gallery_image_id, 'rtwpvg_video_link', true));
            $type = wp_check_filetype($has_video);

            $video_width = trim(get_post_meta($variation_gallery_image_id, 'rtwpvg_video_width', true));
            $video_height = trim(get_post_meta($variation_gallery_image_id, 'rtwpvg_video_height', true));


            $available_variation['variation_gallery_images'][$i]['rtwpvg_video_link'] = $has_video;
            $available_variation['variation_gallery_images'][$i]['rtwpvg_video_thumbnail_src'] = rtwpvg()->get_images_uri('play-button.svg');


            if (!empty($has_video)) {

                if (!empty($type['type'])) {
                    $available_variation['variation_gallery_images'][$i]['rtwpvg_video_embed_type'] = 'video';
                } else {
                    $available_variation['variation_gallery_images'][$i]['rtwpvg_video_embed_type'] = 'iframe';
                    $available_variation['variation_gallery_images'][$i]['rtwpvg_video_embed_url'] = Functions::get_simple_embed_url($has_video);
                }

                $available_variation['variation_gallery_images'][$i]['rtwpvg_video_width'] = $video_width ? $video_width : '100%';
                $available_variation['variation_gallery_images'][$i]['rtwpvg_video_height'] = trim($video_height);

            }
        }

        return apply_filters('rtwpvg_available_variation_gallery', $available_variation, $variation, $product);
    }


    public function get_default_gallery_images() {
        $product_id = absint($_POST['product_id']);

        $product = wc_get_product($product_id);
        $gallery_images = $product->get_gallery_image_ids();
        $images = array();
        if (has_post_thumbnail($product_id)) {
            array_unshift($gallery_images, get_post_thumbnail_id($product_id));
        }
        if (is_array($gallery_images) && !empty($gallery_images)) {
            foreach ($gallery_images as $i => $image_id) {
                $images[$i] = wc_get_product_attachment_props($image_id);
                $images[$i]['image_id'] = $image_id;
                $has_video = trim(get_post_meta($image_id, 'rtwpvg_video_link', true));
                $images[$i]['rtwpvg_video_link'] = $has_video;
                $images[$i]['rtwpvg_video_thumbnail_src'] = rtwpvg()->get_images_uri('play-button.svg');
                if (!empty($has_video)) {
                    $type = wp_check_filetype($has_video);

                    $video_width = trim(get_post_meta($image_id, 'rtwpvg_video_width', true));
                    $video_height = trim(get_post_meta($image_id, 'rtwpvg_video_height', true));

                    if (!empty($type['type'])) {
                        $images[$i]['rtwpvg_video_embed_type'] = 'video';
                    } else {
                        $images[$i]['rtwpvg_video_embed_type'] = 'iframe';
                        $images[$i]['rtwpvg_video_embed_url'] = Functions::get_simple_embed_url($has_video);
                    }

                    $images[$i]['rtwpvg_video_width'] = $video_width ? $video_width : '100%';
                    $images[$i]['rtwpvg_video_height'] = trim($video_height);

                }
            }
        }

        wp_send_json_success(apply_filters('rtwpvg_get_default_gallery_images', $images, $product_id));
    }

    public function get_variation_images() {
        $product_id = absint($_POST['product_id']);

        $product = wc_get_product($product_id);

        $images = array();
        $available_variations = array_values($product->get_available_variations());

        foreach ($available_variations as $i => $variation) {
            array_push($variation['variation_gallery_images'], $variation['image']);

            foreach ($variation['variation_gallery_images'] as $image) {
                array_push($images, $image);
            }
        }

        wp_send_json_success(apply_filters('rtwpvg_get_variation_images', $images, $product_id));
    }


}