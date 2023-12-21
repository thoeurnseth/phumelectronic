<?php
if ( !class_exists('WBU')) {
class WBU {
    /**
     * @var WBU_Cart
     */
    public $cart;

    /**
     * @var WBU_Checkout
     */
    public $checkout;

    /**
     * @var WBU_Shop
     */
    public $shop;
    
    /**
     * @var WBU_Product
     */
    public $product;
    
    function init_classes() {
        $this->cart = new WBU_Cart();
        $this->checkout = new WBU_Checkout();
        $this->shop = new WBU_Shop();
        $this->product = new WBU_Product();
    }
    
    function init_hooks() {
        // general hooks
        add_action('init', array($this, 'plugin_init'));
        add_action('plugins_loaded', array($this, 'plugins_loaded'), 10 );
        add_action('wp_ajax_wbu_dismissed_notice_handler', array($this, 'ajax_notice_handler') );
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts') );
        add_filter('wbu_shop_quantity_area_args', array($this, 'shop_quantity_area_args'));
        add_filter('wc_add_to_cart_message_html', array($this, 'filter_add_cart_message'));
        add_filter('option_woocommerce_enable_ajax_add_to_cart', array($this, 'check_ajax_enabled'), 10, 1);
        add_filter('wc_get_template', array($this, 'wc_get_template'), 10, 2 );

        add_filter('mh_wbu_settings', array($this, 'wbu_settings'));
        add_filter('mh_wbu_premium_url', array($this, 'wbu_premium_url'));
        
        add_action('woocommerce_before_quantity_input_field', array($this, 'before_quantity_input_field'));
        add_action('woocommerce_after_quantity_input_field', array($this, 'after_quantity_input_field'));

        // initialize subclasses hooks
        $this->shop->init_hooks();
        $this->cart->init_hooks();
        $this->checkout->init_hooks();
        $this->product->init_hooks();
    }

    function wbu_premium_url() {
        return 'https://gumroad.com/l/rLol';
    }

    function plugins_loaded() {
        load_plugin_textdomain( 'woo-better-usability', false, plugin_basename( dirname( __FILE__ ) ) . '/i18n/languages' );
    }
    
    function wbu_settings($arr) {

        // General tab
        $arr['general_show_quantity_buttons'] = array(
            'label' => __('Display quantity buttons everywhere (except minicart)', 'woo-better-usability'),
            'tab' => __('General', 'woo-better-usability'),
            'type' => 'checkbox',
            'default' => 'no',
        );
        $arr['product_ajax_add_to_cart'] = array(
            'label' => __('Convert simple products `Add to Cart` buttons into AJAX', 'woo-better-usability'),
            'tab' => __('General', 'woo-better-usability'),
            'type' => 'checkbox',
            'default' => 'yes',
        );
        $arr['hide_addedtocart_msg'] = array(
            'label' => __('Hide `Added to cart` message after add product', 'woo-better-usability'),
            'tab' => __('General', 'woo-better-usability'),
            'type' => 'checkbox',
            'default' => 'no',
        );
        $arr['hide_viewcart_link'] = array(
            'label' => __('Hide `View cart` link after add product', 'woo-better-usability'),
            'tab' => __('General', 'woo-better-usability'),
            'type' => 'checkbox',
            'default' => 'no',
        );
        $arr['product_max_qty'] = array(
            'label' => __('Max product quantity allowed (blank = disabled)', 'woo-better-usability'),
            'tab' => __('General', 'woo-better-usability'),
            'type' => 'number',
            'default' => '',
            'min' => 1,
            'max' => 100,
        );
        $arr['ajax_timeout'] = array(
            'label' => __('AJAX timeout in milliseconds for the quantity change refresh', 'woo-better-usability'),
            'tab' => __('General', 'woo-better-usability'),
            'type' => 'number',
            'default' => 800,
            'min' => 1,
            'max' => 5000,
        );
        $arr['checkout_allow_delete'] = array(
            'label' => __('Allow to delete products on checkout page', 'woo-better-usability'),
            'tab' => __('General', 'woo-better-usability'),
            'type' => 'checkbox',
            'default' => 'yes',
        );
        $arr['checkout_allow_change_qty'] = array(
            'label' => __('Allow to change quantity on checkout page', 'woo-better-usability'),
            'tab' => __('General', 'woo-better-usability'),
            'type' => 'checkbox',
            'default' => 'yes',
        );
        $arr['show_checkout_quantity_buttons'] = array(
            'label' => __('Show -/+ buttons around item quantity', 'woo-better-usability'),
            'tab' => __('General', 'woo-better-usability'),
            'type' => 'checkbox',
            'default' => 'no',
            'depends_on' => 'checkout_allow_change_qty',
        );
        $arr['checkout_display_unit_price'] = array(
            'label' => __('Display unit price on product name', 'woo-better-usability'),
            'tab' => __('General', 'woo-better-usability'),
            'type' => 'checkbox',
            'default' => 'yes',
            'depends_on' => 'checkout_allow_change_qty',
        );

        // Shop tab
        $arr['enable_quantity_on_shop'] = array(
            'label' => __('Allow to change product quantity on shop page', 'woo-better-usability'),
            'tab' => __('Shop', 'woo-better-usability'),
            'type' => 'checkbox',
            'default' => 'yes',
        );
        $arr['qty_as_select_shop'] = array(
            'label' => __('Show quantity as select instead of numeric field', 'woo-better-usability'),
            'tab' => __('Shop', 'woo-better-usability'),
            'type' => 'checkbox',
            'default' => 'no',
            'depends_on' => 'enable_quantity_on_shop',
        );
        $arr['show_show_quantity_buttons'] = array(
            'label' => __('Show -/+ buttons around item quantity', 'woo-better-usability'),
            'tab' => __('Shop', 'woo-better-usability'),
            'type' => 'checkbox',
            'default' => 'yes',
            'depends_on' => 'enable_quantity_on_shop',
        );
        $arr['enable_direct_checkout'] = array(
            'label' => __('Go to checkout directly instead of cart', 'woo-better-usability'),
            'tab' => __('Shop', 'woo-better-usability'),
            'type' => 'checkbox',
            'default' => 'no',
        );
        $arr['direct_checkout_add_cart_text'] = array(
            'label' => __('Text to replace the `Add to cart` button', 'woo-better-usability'),
            'tab' => __('Shop', 'woo-better-usability'),
            'type' => 'text',
            'default' => __('Buy now', 'woo-better-usability'),
            'depends_on' => 'enable_direct_checkout',
        );
        $arr['replace_view_cart_text'] = array(
            'label' => __('Text to replace the `View cart` link', 'woo-better-usability'),
            'tab' => __('Shop', 'woo-better-usability'),
            'type' => 'text',
            'default' => __('View checkout', 'woo-better-usability'),
            'depends_on' => 'enable_direct_checkout',
        );
        $arr['shop_change_products_per_show'] = array(
            'label' => __('Override the default number of products per row', 'woo-better-usability'),
            'tab' => __('Shop', 'woo-better-usability'),
            'type' => 'checkbox',
            'default' => 'no',
        );
        $arr['shop_products_per_row'] = array(
            'label' => __('Number of products to display per row', 'woo-better-usability'),
            'tab' => __('Shop', 'woo-better-usability'),
            'type' => 'number',
            'default' => 4,
            'min' => 1,
            'max' => 15,
            'depends_on' => 'shop_change_products_per_show',
        );
        $arr['hide_shop_paginator'] = array(
            'label' => __('Hide product pagination', 'woo-better-usability'),
            'tab' => __('Shop', 'woo-better-usability'),
            'type' => 'checkbox',
            'default' => 'no',
        );
        $arr['hide_shop_sorting'] = array(
            'label' => __('Hide `Default sorting` select box', 'woo-better-usability'),
            'tab' => __('Shop', 'woo-better-usability'),
            'type' => 'checkbox',
            'default' => 'no',
        );
        $arr['hide_addtocart_button'] = array(
            'label' => __('Hide `Add to cart` button', 'woo-better-usability'),
            'tab' => __('Shop', 'woo-better-usability'),
            'type' => 'checkbox',
            'default' => 'no',
        );

        // Product tab
        $arr['qty_as_select_product'] = array(
            'label' => __('Show item quantity as select instead of numeric field', 'woo-better-usability'),
            'tab' => __('Product', 'woo-better-usability'),
            'type' => 'checkbox',
            'default' => 'no',
        );
        $arr['product_hide_price_variable'] = array(
            'label' => __('Hide price range for variable products', 'woo-better-usability'),
            'tab' => __('Product', 'woo-better-usability'),
            'type' => 'checkbox',
            'default' => 'no',
        );
        $arr['product_hide_price_grouped'] = array(
            'label' => __('Hide price range for grouped products', 'woo-better-usability'),
            'tab' => __('Product', 'woo-better-usability'),
            'type' => 'checkbox',
            'default' => 'no',
        );
        $arr['product_hide_quantity'] = array(
            'label' => __('Hide quantity input on product page', 'woo-better-usability'),
            'tab' => __('Product', 'woo-better-usability'),
            'type' => 'checkbox',
            'default' => 'no',
        );
        $arr['show_product_quantity_buttons'] = array(
            'label' => __('Show -/+ buttons around item quantity', 'woo-better-usability'),
            'tab' => __('Product', 'woo-better-usability'),
            'type' => 'checkbox',
            'default' => 'yes',
        );

        // Cart tab
        $arr['enable_auto_update_cart'] = array(
            'label' => __('Update cart/prices automatically when change quantity', 'woo-better-usability'),
            'tab' => __('Cart', 'woo-better-usability'),
            'type' => 'checkbox',
            'default' => 'yes',
        );
        $arr['cart_ajax_method'] = array(
            'label' => __('Technical method to update cart prices', 'woo-better-usability'),
            'tab' => __('Cart', 'woo-better-usability'),
            'type' => 'select',
            'options' => array(
                'simulate_update_button' => __('Simulate click on "Update cart" button', 'woo-better-usability'),
                'make_specific_ajax' => __('Run custom AJAX', 'woo-better-usability'),
            ),
            'default' => 'make_specific_ajax',
            'depends_on' => 'enable_auto_update_cart',
        );
        $arr['cart_updating_display'] = array(
            'label' => __('Display text while updating cart automatically', 'woo-better-usability'),
            'tab' => __('Cart', 'woo-better-usability'),
            'type' => 'checkbox',
            'default' => 'yes',
        );
        $arr['cart_updating_location'] = array(
            'label' => __('Where to display the `Updating` text', 'woo-better-usability'),
            'tab' => __('Cart', 'woo-better-usability'),
            'type' => 'select',
            'options' => array(
                'checkout_btn' => __('Proceed to checkout button', 'woo-better-usability'),
                'update_cart_btn' => __('Update cart button', 'woo-better-usability'),
            ),
            'default' => 'checkout_btn',
            'depends_on' => 'cart_updating_display',
        );
        $arr['cart_updating_text'] = array(
            'label' => __('Text to display in Update cart button while loading', 'woo-better-usability'),
            'tab' => __('Cart', 'woo-better-usability'),
            'type' => 'text',
            'default' => __('Updating...', 'woo-better-usability'),
            'depends_on' => 'cart_updating_display',
        );
        $arr['show_qty_buttons'] = array(
            'label' => __('Show -/+ buttons around item quantity', 'woo-better-usability'),
            'tab' => __('Cart', 'woo-better-usability'),
            'type' => 'checkbox',
            'default' => 'no',
        );
        $arr['qty_buttons_lock_input'] = array(
            'label' => __('Lock number input forcing the use of +/- buttons', 'woo-better-usability'),
            'tab' => __('Cart', 'woo-better-usability'),
            'type' => 'checkbox',
            'default' => 'no',
            'depends_on' => 'show_qty_buttons',
        );
        $arr['qty_as_select_cart'] = array(
            'label' => __('Show item quantity as select instead of numeric field', 'woo-better-usability'),
            'tab' => __('Cart', 'woo-better-usability'),
            'type' => 'checkbox',
            'default' => 'no',
        );
        $arr['qty_select_items'] = array(
            'label' => __('Items to show on select', 'woo-better-usability'),
            'tab' => __('Cart', 'woo-better-usability'),
            'type' => 'number',
            'default' => 5,
            'depends_on' => 'qty_as_select_cart',
            'min' => 1,
            'max' => 50,
        );
        $arr['confirmation_zero_qty'] = array(
            'label' => __('Show user confirmation when change item quantity to zero', 'woo-better-usability'),
            'tab' => __('Cart', 'woo-better-usability'),
            'type' => 'checkbox',
            'default' => 'yes',
        );
        $arr['zero_qty_confirmation_text'] = array(
            'label' => __('Confirmation text', 'woo-better-usability'),
            'tab' => __('Cart', 'woo-better-usability'),
            'type' => 'text',
            'default' => __('Are you sure you want to remove this item from cart?', 'woo-better-usability'),
            'depends_on' => 'confirmation_zero_qty',
            'size' => 50,
        );
        $arr['cart_hide_quantity'] = array(
            'label' => __('Hide quantity input on cart page', 'woo-better-usability'),
            'tab' => __('Cart', 'woo-better-usability'),
            'type' => 'checkbox',
            'default' => 'no',
        );
        $arr['cart_hide_update'] = array(
            'label' => __('Hide the `Update cart` button', 'woo-better-usability'),
            'tab' => __('Cart', 'woo-better-usability'),
            'type' => 'checkbox',
            'default' => 'no',
        );

        return $arr;
    }

    function plugin_init() {
        // initialize Common library
        include_once( dirname(__FILE__) . '/../common/MHCommon.php' );
        MHCommon::initializeV2(
            'woo-better-usability',
            'wbu',
            WBU_BASE_FILE,
            __('WooCommerce Better Usability', 'woo-better-usability')
        );
    }

    function shop_quantity_area_args($args) {
        if ( wbu()->option('hide_addtocart_button') === 'yes' ) {
            $args['class'] .= ' wbu-hide hide';
        }

        return $args;
    }

    // this is custom code to cart page ajax work in pages like "Woocommerce Shop page", using the common WC shortcode [cart]
    function optimize_cart_on_shop() {
        wbu()->enqueue_cart_js();
    }

    function enqueue_cart_js() {
        $path = 'assets/js/frontend/cart.js';
        $src = str_replace( array( 'http:', 'https:' ), '', plugins_url( $path, WC_PLUGIN_FILE ) );

        $deps = array( 'jquery' );
        wp_enqueue_script( 'wc-cart', $src, $deps, WC_VERSION, true );
    }

    function js_uri() {
        return apply_filters('wbu_js_uri', plugins_url('assets/wbulite.js', WBU_ROOT_FILE));
    }

    function css_uri() {
        return apply_filters('wbu_css_uri', plugins_url('assets/wbulite.css', WBU_ROOT_FILE));
    }

    function enqueue_assets() {
        // enqueue assets
        wp_enqueue_script('wbulite', wbu()->js_uri(), array('jquery'));
        wp_enqueue_style('wbulite', wbu()->css_uri());

        wp_localize_script('wbulite', 'wbuSettings', $this->get_all_options());
        wp_localize_script('wbulite', 'wbuInfo', array(
            'isCart' => is_cart(),
            'isShop' => wbu()->is_shop_loop(),
            'isSingleProduct' => is_product(),
            'isCheckout' => is_checkout(),
            'ajaxUrl' => get_admin_url() . 'admin-ajax.php',
            'quantityLabel' => __('Quantity', 'woo-better-usability'),
        ));
    }

    function get_all_options() {
        $all_options = apply_filters('mh_wbu_all_options', array());

        $vars = array();
        $vars['cart_ajax_method'] = $all_options['cart_ajax_method'];
        $vars['cart_updating_display'] = $all_options['cart_updating_display'];
        $vars['cart_updating_location'] = $all_options['cart_updating_location'];
        $vars['cart_updating_text'] = $all_options['cart_updating_text'];
        $vars['cart_hide_update'] = $all_options['cart_hide_update'];
        $vars['cart_hide_quantity'] = $all_options['cart_hide_quantity'];
        $vars['cart_fix_enter_key'] = apply_filters('wbu_fix_cart_enter_key', false);
        $vars['ajax_timeout'] = $all_options['ajax_timeout'];
        $vars['confirmation_zero_qty'] = $all_options['confirmation_zero_qty'];
        $vars['zero_qty_confirmation_text'] = $all_options['zero_qty_confirmation_text'];
        $vars['enable_auto_update_cart'] = $all_options['enable_auto_update_cart'];
        $vars['qty_buttons_lock_input'] = $all_options['qty_buttons_lock_input'];
        $vars['enable_quantity_on_shop'] = $all_options['enable_quantity_on_shop'];
        $vars['checkout_allow_change_qty'] = $all_options['checkout_allow_change_qty'];
        $vars['hide_addtocart_button'] = $all_options['hide_addtocart_button'];
        $vars['hide_viewcart_link'] = $all_options['hide_viewcart_link'];

        return apply_filters('wbu_frontend_vars', $vars, $all_options);
    }

    function is_shop_loop() {
        return ( is_shop() || is_product_category() || apply_filters('wbu_is_shop_loop', false) );
    }

    function enqueue_scripts() {
        if ( !class_exists('WooCommerce') ) {
            return;
        }

        wbu()->enqueue_assets();
    }

    function template_path($name) {
        // custom theme template override, similar of woocommerce
        $path = get_template_directory() . '/templates/woo-better-usability/' . $name;
        
        if ( !file_exists($path) ) {
            $path = WBU_ROOT_DIR . '/templates/' . $name;            
        }

        return apply_filters('wbu_get_template', $path);
    }
    
    function get_template($name, $args = array()) {
        if ( !empty($args) ) {
            extract($args);
        }

        ob_start();
        include $this->template_path($name);
        return ob_get_clean();
    }

    function option($name) {
        return apply_filters('mh_wbu_setting_value', $name);
    }
    
    function select_max_quantity($product = null) {
        $productMaxQty = wbu()->option('product_max_qty');
        $maxQty = ( $productMaxQty > 0 ? $productMaxQty : wbu()->option('qty_select_items') );

        if ( !empty($product) && !$product->backorders_allowed() ) {
            $stockQty = $product->get_stock_quantity();

            if ( $stockQty > 0 && $stockQty < $maxQty ) {
                return $stockQty;
            }
        }

        return $maxQty;
    }

    function filter_add_cart_message($message) {
        if ( wbu()->option('hide_viewcart_link') === 'yes' ) {
            $msgDelete = sprintf( '<a href="%s" class="button wc-forward">%s</a>', esc_url( wc_get_page_permalink( 'cart' ) ), esc_html__( 'View cart', 'woocommerce' ));

            $message = str_replace($msgDelete, '', $message);
        }

        // when configured to hide added messages, then erase message to woocommerce not display
        if ( wbu()->option('hide_addedtocart_msg') === 'yes' ) {
            $message = null;
        }

        return $message;
    }

    function enable_ajax_add_to_cart() {
        return ( wbu()->option('product_ajax_add_to_cart') == 'yes' ) && apply_filters('wbu_enable_ajax_add_to_cart', true);
    }

    /**
     * Force inclusion of the frontend script add-to-cart.js
     * 
     * When woocommerce has configured to not enable AJAX on archives, force to return positive in case of:
     *  - wbu plugin enabled the option "Enable AJAX add to cart on product page"
     *  - is product page
     */
    function check_ajax_enabled($value) {
        if ( $value !== 'yes' && $this->enable_ajax_add_to_cart() ) {
            return 'yes';
        }
        
        return $value;
    }

    /**
     * Check if was using legacy mode to AJAX Add to cart buttons.
     * 
     * When in legacy mode, the plugin will convert Add to cart buttons and add ajax_add_to_cart class,
     *  that is not the best way to keep compatibility with other plugins and themes.
     * 
     * When in NOT legacy mode: the plugin will try to serialize the product cart form data and submit
     *  using AJAX, keeping better compatibility with other plugins, themes and the way that WooCommerce frontend works.
     * 
     * The legacy mode will be removed in future.
     * 
     * @return bool
     */
    function is_ajax_cart_add_legacy() {
        return apply_filters('wbu_ajax_add_to_cart_legacy', true);
    }

    function wc_get_template( $located, $template_name ) {
        if ( ( $template_name == 'single-product/add-to-cart/simple.php' ) && $this->is_ajax_cart_add_legacy() && $this->enable_ajax_add_to_cart() ) {
            $located = wbu()->template_path('single-add-cart-ajax.php');
        }

        return $located;
    }

    function get_min_value($product) {
        $minValue = apply_filters('wbu_product_min_value', $product->get_min_purchase_quantity(), $product);

        if ( class_exists('BeRocket_MM_Quantity') ) {
            $brMinValue = get_post_meta( $product->get_id(), 'min_quantity', true );
            if ( !empty($brMinValue) ) {
                $minValue = $brMinValue;
            }
        }

        return apply_filters( 'woocommerce_quantity_input_min', $minValue, $product );
    }

    function get_max_value($product) {
        $maxValue = $product->get_max_purchase_quantity();

        if ( class_exists('BeRocket_MM_Quantity') ) {
            $brMaxValue = get_post_meta( $product->get_id(), 'max_quantity', true );
            if ( !empty($brMaxValue) ) {
                $maxValue = $brMaxValue;
            }
        }

        return apply_filters( 'woocommerce_quantity_input_max', $maxValue, $product );
    }

    function before_quantity_input_field() {
        if ( $this->quantity_buttons_enabled() ) {
            echo apply_filters('wbu_qtybtn_minus', '<a href="" class="wbu-qty-button wbu-btn-sub">-</a>');
        }
    }

    function after_quantity_input_field() {
        if ( $this->quantity_buttons_enabled() ) {
            echo apply_filters('wbu_qtybtn_plus', '<a href="" class="wbu-qty-button wbu-btn-inc">+</a>');
        }
    }

    function quantity_buttons_enabled() {
        // check if generic has enabled (override all other configs)
        $enabled = ( wbu()->option('general_show_quantity_buttons') == 'yes' ) && apply_filters('wbu_enable_qtybuttons', true);

        // check if needs to enable for Cart page
        if ( !$enabled ) {
            $enabled = is_cart() && ( wbu()->option('show_qty_buttons') == 'yes' ) && apply_filters('wbu_cart_enable_qtybuttons', true);
        }

        // check if needs to enable for Shop page
        if ( !$enabled ) {
            $enabled = $this->is_shop_loop() && ( wbu()->option('show_show_quantity_buttons') == 'yes' ) && apply_filters('wbu_shop_enable_qtybuttons', true);
        }

        // check if needs to enable for single Product page
        if ( is_product() ) {
            global $product;

            if ( !$enabled ) {
                $enabled = ( wbu()->option('show_product_quantity_buttons') == 'yes' );
            }

            // when product is sold individually, then disable the buttons
            if ( $enabled && !empty($product) && apply_filters('wbu_qty_when_sold_individually', true) && $product->is_sold_individually() ) {
                $enabled = false;
            }
        }

        // check if needs to enable for Checkout page
        if ( !$enabled ) {
            $enabled = is_checkout() && ( wbu()->option('show_checkout_quantity_buttons') === 'yes' );
        }

        // check if needs to enable for Minicart widget
        // currently only available in premium version because of its complexity
        
        return $enabled;
    }
    
    static function boot() {
        define('WBU_ROOT_DIR', plugin_dir_path( dirname(__FILE__)) );
        define('WBU_ROOT_FILE', dirname(dirname(__FILE__)) . '/' . basename(WBU_PLUGIN) );

        include_once( WBU_ROOT_DIR . 'includes/class-wbu-cart.php' );
        include_once( WBU_ROOT_DIR . 'includes/class-wbu-checkout.php' );
        include_once( WBU_ROOT_DIR . 'includes/class-wbu-shop.php' );
        include_once( WBU_ROOT_DIR . 'includes/class-wbu-product.php' );
        
        if ( !function_exists('wbu') ) {
            /**
             * @return WBU
             */
            function wbu() {
                static $instance = null;

                if ( !$instance ) {
                    $instance = new WBU();
                }

                return $instance;
            }
        }
        
        wbu()->init_classes();
        wbu()->init_hooks();
    }
}
}

