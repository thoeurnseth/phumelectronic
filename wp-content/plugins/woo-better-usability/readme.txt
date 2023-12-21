=== WooCommerce Better Usability ===
Contributors: moiseh
Tags: woocommerce, usability, ux, ajax, checkout, cart, shop
Requires at least: 4.8
Tested up to: 5.8
Stable tag: 1.0.49
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

== Description ==

Provides overall improvements of the user experience when buying products in a WooCommerce based store. The areas of that the improvements was included are: Shop, Product, Cart and Checkout.

Free version features:

* Auto refresh the price totals on cart page when quantity changes using AJAX
* Show "-" and "+" buttons around the quantity field
* Show confirmation before user changes quantity to zero
* Go to checkout directly instead of cart page (simplified buy process)
* Allow to delete or change quantity on checkout page
* Allow to change product quantity direct on shop page
* Allow to add to cart AJAX on product page (like on shop)
* Ability to override various default things of WooCommerce
* Hide quantity fields on Product and Cart pages

[youtube https://www.youtube.com/watch?v=ysF3ZLYO1nQ ]

Premium version features:

* Synchronize products automatically with cart when change quantity [view demo](https://youtu.be/Xqv8rZ-hoOk)
* Update price automatically in Product and Shop pages, like on cart [view demo](https://youtu.be/ZKYJZAUXV_g)
* Change product variations directly in Shop page, meaning less clicks to buy [view demo](https://youtu.be/NhJewWjX-I8)
* Synchronize with MiniCart widget from Shop or Product pages [view demo](https://youtu.be/r7XQtkPV7sg)
* Make AJAX requests to delete product on checkout page, without full page reload [view demo](https://youtu.be/KYkqoqfsi3U)
* Allow to change quantities in Mini-Cart widget [view demo](https://youtu.be/8GDpWQhcfyU)

Get PRO version [clicking here](https://gum.co/wbupro).

== Installation ==

1. Upload `woo-better-usability.zip` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. It's done. Now you can go to the Settings to customize what you want

== Screenshots ==

1. Add to cart on Shop
2. Cart configuration
3. Product configuration
4. Shop configuration

== Changelog ==

= 1.0.49 =
* Added a span with named class product_name to product title at checkout page
* Fixed block and unblock effect functions to avoid edge cases errors
* Tested with WC 5.5.1 and WP 5.8

= 1.0.48 =
* Fixed WooCommerce 5.2 checkout quantity and remove button incompatibilities
* Adding `wbu_ajax_add_to_cart_legacy` filter to use AJAX Add to cart buttons in more compatible way

= 1.0.47 =
* Added single_add_to_cart_button class for Add cart buttons in Shop
* Added BlockUI loading effect on Checkout table when changing quantity
* Allow to reduce quantity with minus button when preloaded exceeding the max limit
* Improved quantity buttons support
* Updated Tested up to tag

= 1.0.46 =
* When product is sold individually, disable quantity buttons in single product page
* Fixed some WooCommerce frontend event listeners

= 1.0.45 =
* Using document instead document.body listeners that was causing issues with some themes
* Fix incorrect translation domain for specific config
* Compatibility with Saasland theme buttons
* Define more global quantity event listener for different cart theme layouts
* Fixed missing hook call to display -/+ buttons in single product page when using selects
* Added option: Display quantity buttons everywhere, simplified buttons display config

= 1.0.44 =
* Cleaning unused source code
* Changed plus and minus buttons display to be more standard (using woo *_quantity_input_field hooks)
* Removing `Fix layout break when Enter key is pressed` config to use `wbu_fix_cart_enter_key` filter instead

= 1.0.43 =
* Fixing bug in lib that was displaying unwanted admin notices
* Updated WooCommerce tested tag

= 1.0.42 =
* Fixed text domain to allow plugin string translation
* Reduced and removed unnecessary frontend variables to reduce page size load
* Removing minified asset wbulite.min.js for better coding standards and debug

= 1.0.41 =
* Fixed flatsome duplicating add to cart section issue in shop page
* Updated WooCommerce and WordPress tested tag

= 1.0.40 =
* Removing deprecated PHP short_open_tag blocks
* Supporting for decimal quantity increment and decrement buttons

= 1.0.39 =
* Optimized Hide View cart link after add product config to not blink after add
* Added per product limit compatibility with BeRocket Min and Max Quantity for WooCommerce plugin

= 1.0.38 =
* Reversed changelog ordering to make it more standard
* Changed plugin notices to respect the guidelines
* Standardization of custom plugin templates overriding in theme
* Added filter wbu_bypass_shop_quantity_override to avoid templae override quantity html area in Shop
* Fixed View cart link after add product option to work with Greenmart theme

= 1.0.37 =
* Added custom theme template overriding similar to WooCommerce
* Removed listener for minus and plus buttons because it causing trouble with many themes

= 1.0.36 =
* Fixed Divi theme child compatibility in increase/decrease buttons
* Fixed Porto theme issues with duplicated quantity buttons
* Prevent page reload in specific cases after change quantity in shop
* Added listener for minus and plus buttons used in most themes
* Compatibility with BeRocket Ajax Products Filter plugin
* Avoid double quantity increment when using Astra theme
* Refresh minicart totals when change quantity in checkout

= 1.0.35 =
* Fixed divi theme child quantity on shop display
* Added wp 5.4 tested up to tag
* The option to transform Add to Cart into AJAX in Product is now generic for all pages

= 1.0.34 =
* Added compatibility support with WooCommerce 4.0.1

= 1.0.33 =
* Respect max stock quantities when using select input for quantities
* Fixed bug that not displaying select input in Cart in some conditions

= 1.0.32 =
* Fixed checkout max input quantity validation

= 1.0.31 =
* Added compatibility support with WooCommerce 3.8.1
* Fixed Undefined Index error in Shop page

= 1.0.30 =
* Added support for modified remove link zero quantity check
* Added support for Woo Gutenberg Products Block plugin

= 1.0.29 =
* Updated `WC tested up to` tag

= 1.0.28 =
* Removing -/+ buttons when product is sold individually

= 1.0.27 =
* Fixed blockUI to hide when finish Custom AJAX callback
* Added AJAX timeout for quantity change refresh
* Updated `WC tested up to` tag

= 1.0.26 =
* Changed the cart overlay behavior in Custom AJAX mode to use the default of WooCommerce

= 1.0.25 =
* Fix and optimize some mess in JS code and removing loops
* Fix issue in Related products not showing quantity input for first product

= 1.0.24 =
* Enqueue assets in all pages for better compatibility with custom pages
* Reduced `is shop loop` detection checks to better compatibility with Elementor and relateds
* Added overlay when AJAX refreshing cart using `Run Custom AJAX` method
* Removed option `Don't apply this option to front page` (use `wbu_enable_quantity_input` filter instead)
* Removed option `Always enqueue assets for better compatibility`
* Removed option `Optimize to make Cart work better when embebed in other pages`

== Frequently Asked Questions ==

== Upgrade Notice ==
