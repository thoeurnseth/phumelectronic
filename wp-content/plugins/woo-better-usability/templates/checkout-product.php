<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>

<?php if ( $allowChangeQty == 'yes' && $displayUnitPrice == 'yes' ): ?>
    <span class="product_price">
        <?php echo wc_price($cart_item['data']->get_price()); ?>
    </span>
<?php endif; ?>

<?php if ( $allowChangeQty == 'yes' ): ?>
    <?php if ( $product->is_sold_individually() ): ?>
      1 <input type="hidden" name="cart[<?php echo $cart_item_key; ?>][qty]" value="1" />
    <?php else: ?>
        <?php
            echo wbu()->get_template('checkout-product-qty.php', array(
                'product' => $product,
                'cart_item_key' => $cart_item_key,
                'cart_item' => $cart_item,
            ));
        ?>
    <?php endif; ?>
<?php endif; ?>
