<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\Metro;

$id  = $product->get_id();
$cat = $block_data['cat_display'] ? WC_Functions::get_top_category_name() : false;
?>
<div class="rt-product-block rt-product-block-3">

	<div class="rtin-thumb-wrapper">
		<div class="rtin-thumb">
			<?php
			if ( $block_data['gallery'] ) {
				echo WC_Functions::get_product_thumbnail_gallery( $product, $block_data['thumb_size'] );
			}
			else {
				echo WC_Functions::get_product_thumbnail_link( $product, $block_data['thumb_size'] );
			}
			?>
		</div>
		<?php woocommerce_show_product_loop_sale_flash();?>
	</div>

	<?php if ( $block_data['v_swatch'] ) WC_Functions::run_variation_swatch();?>

	<?php if ( WC_Functions::is_product_archive() ) do_action( 'woocommerce_before_shop_loop_item_title' );?>
	
	<div class="product-item-code rtin-title">
		<?php 
			$variant_ids = $product->get_children();
            if(!empty($variant_ids)){
                $variant_id = json_encode($variant_ids[0]);
                echo wc_get_product($variant_id)->get_sku();
            }else{
                echo "";
            }
		?>
	</div>

	<h3 class="rtin-title"><a href="<?php the_permalink();?>"><?php the_title();?></a></h3>

	<?php if ( WC_Functions::is_product_archive() ) do_action( 'woocommerce_after_shop_loop_item_title' );?>

	<?php if ( $cat ): ?>
		<div class="rtin-cat"><?php // echo esc_html( $cat );?></div>
	<?php endif; ?>

	<?php
	if ( $block_data['rating_display'] ) {
		wc_get_template( 'loop/rating.php' );
	}
	?>

<!--
	<div class="rtin-bottom-area">

		<?php if ( $price_html = $product->get_price_html() ) : ?>
			<div class="rtin-price price"><?php echo wp_kses_post( $price_html ); ?></div>
		<?php endif; ?>

		<div class="rtin-buttons">
			<div class="rtin-left">
				<?php WC_Functions::print_add_to_cart_icon( false ); ?>
			</div>
			<div class="rtin-right">
				<?php
				if ( $block_data['quickview'] ) WC_Functions::print_quickview_icon();
				if ( $block_data['compare'] ) WC_Functions::print_compare_icon();
				if ( $block_data['wishlist'] ) WC_Functions::print_add_to_wishlist_icon();
				?>
			</div>
		</div>

	</div>

-->
	<?php 
		$variant_ids = $product->get_children();
		if(!empty($variant_ids)){
			$on_sale = wc_get_product($variant_id)->is_on_sale();
			if($on_sale == '1'){
				$price =  wc_get_product($variant_id)->get_price();
				$price = number_format($price, 2, '.', '');
				$sale_price = wc_get_product($variant_id)->get_sale_price();
				$sale_price = number_format($sale_price, 2, '.', '');
				$regular_price = wc_get_product($variant_id)->get_regular_price();
				$date_on_sale_from = strtotime(wc_get_product($variant_id)->get_date_on_sale_from());

				if($regular_price !== $sale_price && $sale_price === $price){
					$regular_prices = '<del style="color: #458200">'.'$'.number_format($regular_price, 2, '.', '').'</del>';
					$sale_prices = number_format($sale_price, 2, '.', ''); 
				}
			}else{
				$regular_prices = wc_get_product($variant_id)->get_regular_price();
				$regular_prices = number_format($regular_prices, 2, '.', ''); 
			}
		}else{
			echo "";
		}
	?>

	<div class="rtin-bottom-area-custom">
		<div class="rtin-bottom-area-custom-left">
			<?php if ( $price_html = $product->get_price_html() ) : ?>
				<!-- <div class="rtin-price price"><?php echo wp_kses_post( $price_html ); ?></div> -->
				<div class="rtin-price price" style="display: flex; justify-content: space-between;">
					<div class="regular-price"><?php echo $regular_prices ?></div>
					<div class="sale-price" style="padding-left: 12px;"><?php echo $sale_prices ?></div>
				</div>
			<?php endif; ?>
		</div>
		<div class="rtin-bottom-area-custom-right">
			<?php
			//if ( $block_data['quickview'] ) WC_Functions::print_quickview_icon();
			//if ( $block_data['compare'] ) WC_Functions::print_compare_icon();
			if ( $block_data['wishlist'] ) WC_Functions::print_add_to_wishlist_icon();
			?>

			<span class="custom-cart-container"><?php WC_Functions::print_add_to_cart_icon( false ); ?></span>
		</div>
	</div>

</div>