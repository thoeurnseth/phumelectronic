<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php if ( $useSelectField && $product->is_type('simple') ): ?>
    <div class="quantity">
        <?php do_action( 'woocommerce_before_quantity_input_field' ); ?>
        <select class="input-text qty text">
            <?php for ( $i=0; $i <= wbu()->select_max_quantity($product); $i++ ): ?>
                <option <?php if ( $i == $inputValue ): ?>selected="selected"<?php endif; ?>
                        value="<?php echo $i; ?>">
                    <?php echo $i; ?>
                </option>
            <?php endfor; ?>
        </select>
        <?php do_action( 'woocommerce_after_quantity_input_field' ); ?>
    </div>
<?php else: ?>
    <?php echo woocommerce_quantity_input(array(
            'min_value' => wbu()->get_min_value($product),
            'max_value' => wbu()->get_max_value($product),
            'input_value' => $inputValue,
        ), $product, false ); ?>
<?php endif; ?>
