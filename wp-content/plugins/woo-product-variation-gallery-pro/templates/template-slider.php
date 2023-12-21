<?php
/**
 * Slider js Template
 * This template can be overridden by copying it to yourtheme/woo-product-variation-gallery/template-slider.php
 */

defined( 'ABSPATH' ) || exit;
?>
<script type="text/html" id="tmpl-rtwpvg-slider-template">
    <# if(data.rtwpvg_video_link) { #>
    <div class="rtwpvg-gallery-image rtwpvg-gallery-video">
        <# if(data.rtwpvg_video_embed_type == 'video'){ #>
        <video preload="auto" controls="" controlslist="nodownload" poster="{{data.src}}" src="{{data.rtwpvg_video_link}}"
               style="width: 100%; height: 400px; margin: 0;padding: 0; background-color: #000"></video>
        <# } else if(data.rtwpvg_video_embed_type == 'iframe'){ #>
        <iframe class="rtwpvg-lightbox-iframe" src="{{data.rtwpvg_video_embed_url}}"
                style="width: 100%; height: 400px; margin: 0;padding: 0; background-color: #000" frameborder="0"
                webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>
        <# } #>
    </div>
    <# }else{ #>
    <div class="rtwpvg-gallery-image">
        <div>
            <img width="{{data.src_w}}" height="{{data.src_h}}" src="{{data.src}}" alt="{{data.alt}}"
                 title="{{data.title}}" data-caption="{{data.caption}}" data-src="{{data.full_src}}"
                 data-large_image="{{data.full_src}}" data-large_image_width="{{data.full_src_w}}"
                 data-large_image_height="{{data.full_src_h}}"
                <# if(data.srcset) { #>
                     srcset="{{data.srcset}}"
                <# } #>
                 sizes="{{data.sizes}}"/>
        </div>
    </div>
    <# } #>
</script>