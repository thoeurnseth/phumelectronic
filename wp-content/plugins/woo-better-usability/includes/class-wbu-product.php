<?php
if ( !class_exists('WBU_Product')) {
class WBU_Product {
    function init_hooks() {
        add_filter('wc_get_template', array($this, 'wc_get_template'), 10, 2 );
        add_filter('woocommerce_quantity_input_args', array($this, 'quantity_input_args'), 20, 2 );
        add_filter('woocommerce_available_variation', array($this, 'available_variation'), 10, 3 );
    }
    
    function quantity_input_args($args, $product) {
        $productMaxQty = wbu()->option('product_max_qty');
        
        // if the option "product_max_qty" was defined AND current max_value is not overrided by others,
        // then sets input max value based on this configuration
        if ( ( $productMaxQty > 0 ) && !empty($args['max_value']) && ( $args['max_value'] == '-1' || is_plugin_active('woocommerce-min-max-quantities/woocommerce-min-max-quantities.php') ) ) {
            $args['max_value'] = $productMaxQty;
        }

        return $args;
    }
    
    function available_variation( $args, $product, $variation ) {
        $productMaxQty = wbu()->option('product_max_qty');
        
        if ( ( $productMaxQty > 0 ) && empty($args['max_qty']) ) {
            $args['max_qty'] = $productMaxQty;
        }
        
        return $args;
    }
    
    function wc_get_template( $located, $template_name ) {    

        if ( !is_product() ) {
            return $located;
        }
        
        if ( $template_name == 'global/quantity-input.php' ) {
            if ( wbu()->option('product_hide_quantity') == 'yes' ) {
                $located = wbu()->template_path('empty.php');
            }
            elseif ( wbu()->option('qty_as_select_product') == 'yes' ) {
                $located = wbu()->template_path('qty-select-product.php');
            }
        }
        else if ( $template_name == 'single-product/price.php' ) {
            global $product;
            
            if ( ( $product->is_type('variable') && ( wbu()->option('product_hide_price_variable') === 'yes' ) ) ||
                 ( $product->is_type('grouped') && ( wbu()->option('product_hide_price_grouped') === 'yes' ) ) ) {
                $located = wbu()->template_path('empty.php');
            }
        }
        
        return $located;
    }
}
}
