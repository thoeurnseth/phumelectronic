<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>

<a href="<?php echo esc_url( wc_get_cart_remove_url( $cart_item_key ) ); ?>"
    class="remove"
    title="<?php echo __( 'Remove this item', 'woocommerce' ); ?>"
    data-product_id="<?php echo esc_attr( $product_id ); ?>"
    data-product_sku="<?php echo esc_attr( $product->get_sku()); ?>">&times;
</a>
