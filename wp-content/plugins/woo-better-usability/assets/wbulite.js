jQuery(document).ready(function($){

    wbuGlobalQtyElement = null;
    wbuAjaxQueue = [];
    wbuUpdateTimeout = null;

    wbuEnqueueAjax = function(ajaxOpts) {
        wbuAjaxQueue.push(ajaxOpts);
        wbuRunQueuedAjax();
    };

    // execute AJAX queued list
    wbuRunQueuedAjax = function() {
        if ( wbuAjaxQueue.length ) {
            var ajaxOpts = wbuAjaxQueue[0];

            ajaxOpts.complete = function(){
                wbuAjaxQueue.shift(); // remove this ajax from queue

                wbuRunQueuedAjax();
            };

            var xhr = $.ajax(ajaxOpts);
        }
    };

    wbuQtyChangeCart = function(qtyElement) {

        // ask user if they really want to remove this product
        if ( !wbuZeroQuantityCheck(qtyElement) ) {
            return false;
        }

        // when qty is set to zero, then trigger default woocommerce remove product action
        if ( qtyElement.val() == 0 ) {
            var cartItem = qtyElement.closest('.cart_item');
            var removeLink = cartItem.find('.product-remove a');

            if ( !removeLink.length ) {
                removeLink = cartItem.find('.remove u');
            }
            
            removeLink.trigger('click');

            return false;
        }

        // run refresh callback timeout 
        wbuClearTimedoutQtyChange();
        wbuUpdateTimeout = setTimeout(function(){
            if ( wbuSettings.cart_ajax_method == 'simulate_update_button' ) {
                // new method
                wbuSimulateUpdateCartButtonClick(qtyElement);
            }
            else {
                // old ajax method
                wbuMakeAjaxCartUpdate(qtyElement);
            }
    
            wbuAfterCallUpdateCart(qtyElement);
        }, wbuSettings.ajax_timeout);

        return true;
    };

    wbuClearTimedoutQtyChange = function() {
        // clear previous timeout, if exists
        if ( wbuUpdateTimeout !== null ) {
            clearTimeout(wbuUpdateTimeout);
        }
    };

    wbuMakeAjaxCartUpdate = function(qtyElement) {

        if ( typeof qtyElement.attr('name') == 'undefined' ) {
            return;
        }

        matches = qtyElement.attr('name').match(/cart\[(\w+)\]/);
        
        if ( matches === null ) {
            return;
        }
        
        form = qtyElement.closest('form');

        $("<input type='hidden' name='update_cart' id='update_cart' value='1'>").appendTo(form);
        $("<input type='hidden' name='is_wbu_ajax' id='is_wbu_ajax' value='1'>").appendTo(form);

        wbuGlobalQtyElement = qtyElement;

        cart_item_key = matches[1];
        form.append( $("<input type='hidden' name='cart_item_key' id='cart_item_key'>").val(cart_item_key) );

        // get the form data before disable button
        formData = form.serialize();

        $("button[name='update_cart']").addClass('disabled');

        if ( wbuSettings.cart_updating_display == 'yes' ) {
            if ( wbuSettings.cart_updating_location == 'checkout_btn' ) {
                $("a.checkout-button.wc-forward").addClass('disabled').html( wbuSettings.cart_updating_text );
            }
            else {
                $("button[name='update_cart']").html( wbuSettings.cart_updating_text );
            }
        }

        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: formData,
            dataType: 'json',
            beforeSend: function() {
                wbuBlock( $( '.woocommerce-cart-form' ) );
            },
            success: function(resp) {
                wbuAjaxCartUpdateCallback(resp);
                wbuUnblock( $( '.woocommerce-cart-form' ) );
            }
        });
    };

    wbuAjaxCartUpdateCallback = function(resp) {
        $('.cart-collaterals').html(resp.html);
        
        wbuGlobalQtyElement.closest('.cart_item').find('.product-subtotal').html(resp.price);
        
        $('#update_cart').remove();
        $('#is_wbu_ajax').remove();
        $('#cart_item_key').remove();

        $("button[name='update_cart']").removeClass('disabled');
        
        if ( wbuSettings.cart_updating_display == 'yes' ) {
            if ( wbuSettings.cart_updating_location == 'checkout_btn' ) {
                $("a.checkout-button.wc-forward").removeClass('disabled').html(resp.checkout_label);
            }
            else {
                $("button[name='update_cart']").html(resp.update_label);
            }
        }

        // fix to update "Your order" totals when cart is inside Checkout page
        if ( $( '.woocommerce-checkout' ).length ) {
            $( document.body ).trigger( 'update_checkout' );
        }

        $( document.body ).trigger( 'updated_cart_totals' );
        $( document.body ).trigger( 'wc_fragment_refresh' );
    };

    wbuSimulateUpdateCartButtonClick = function(qtyElement) {
        // deal with update cart button
        if ( wbuSettings.cart_updating_display == 'yes' ) {
            // change the Checkout or Update cart button
            if ( wbuSettings.cart_updating_location == 'checkout_btn' ) {
                $("a.checkout-button.wc-forward").html( wbuSettings.cart_updating_text );
            }
            else {
                $("button[name='update_cart']").html( wbuSettings.cart_updating_text );
            }
        }

        // this small delay has been added to fix bug on firefox
        setTimeout(function(){
            $("button[name='update_cart']").trigger('click');
        }, 20);
    };
    
    wbuWhenCartUpdated = function(qtyElement) {
        // fix some event lost because the ajax reload
        wbuLockQtyInput();
        wbuAddItemRemoveEffect();
    };
    
    wbuAfterCallUpdateCart = function(qtyElement) {
        // this is an overridable function
    };
    
    wbuAddItemRemoveEffect = function() {
        // this is an overridable function
    };

    wbuZeroQuantityCheck = function(el_qty) {
        if ( wbuInfo.isCart && ( el_qty.val() == 0 ) && ( wbuSettings.confirmation_zero_qty === 'yes' ) ) {

            if ( !confirm( wbuSettings.zero_qty_confirmation_text ) ) {
                el_qty.val(1);
                return false;
            }
        }

        return true;
    };

    wbuListenChange = function() {

        if ( wbuSettings.enable_auto_update_cart == 'yes' ) {
            if ( wbuInfo.isCart ) {
                $(document).on('change', '.qty', function(){
                    return wbuQtyChangeCart( $(this) );
                });
            }
            else {
                $(document).on('change', '.woocommerce-cart-form .qty', function(){
                    return wbuQtyChangeCart( $(this) );
                });
            }

            // saasland theme compat
            $(document).on('click', '.ti-angle-up,.ti-angle-down', function(){
                return wbuQtyChangeCart( $(this).closest('.product-qty').find('.qty') );
            });
        }
    };

    wbuCartDeleteEvent = function() {
    };

    wbuQtyButtons = function() {
        // .woopq-quantity-input-plus
        // .woopq-quantity-input-minus
        
        $(document).on('click', '.wbu-btn-inc,.lafka-qty-plus', function(){
            return wbuQtyButtonClick( $(this), 1 );
        });

        $(document).on('click', '.wbu-btn-sub,.lafka-qty-minus', function(){
            return wbuQtyButtonClick( $(this), -1 );
        });

        wbuLockQtyInput();
    };

    wbuQtyButtonClick = function(element, factor) {
		// special conditions
        var lafkaCondition = ( $('.lafka-qty-plus').length && !$('body').hasClass('wbu-kafta-first') && !element.closest('.widget_shopping_cart').length );
		
		if ( lafkaCondition ) {
			return false;
		}

		if ( $('.oceanwp-row').length ) {
			return true;
		}
        
        var inputQty = element.parent().find('.qty');
        var currentQty = inputQty.val() != null && inputQty.val().length > 0 ? parseFloat(inputQty.val()) : 0;
        var step = 1;

        // check disabled
        if ( element.hasClass('disabled') ) {
            return false;
        }

        // respect the step value
        stepAttr = inputQty.attr('step');
        
        if ( typeof stepAttr !== typeof undefined && stepAttr !== false && parseFloat(stepAttr) > 0 ) {
            step = stepAttr;
        }

        var newQty = currentQty + ( factor * step );

        // respect the minimum value
        minAttr = inputQty.attr('min');
        if ( typeof minAttr !== typeof undefined && minAttr !== false && newQty < parseFloat(minAttr) ) {
            return false;
        }

        // respect the max stock limit
        maxAttr = inputQty.attr('max');
        if ( typeof maxAttr !== typeof undefined && maxAttr !== false && newQty > parseFloat(maxAttr) && ( factor != -1 ) ) {
            return false;
        }

        // when using Select, check if new quantity exists in options list
        if ( inputQty.is('select') && ( inputQty.find('option[value="'+newQty+'"]').length === 0 ) ) {
            return false;
        }

        inputQty.val( newQty );
        inputQty.change();

        return false;
    };

    
    wbuLockQtyInput = function() {
        // lock quantity input
        if ( wbuSettings.qty_buttons_lock_input == 'yes' ) {
            $('.qty').attr('readonly', 'readonly')
                     .css('background-color', 'lightgray');
        }
    };
    
    wbuQtyOnShop = function() {
        if ( wbuSettings.enable_quantity_on_shop != 'yes' ) {
            return;
        }
        
        $("form.cart").off('change.wbu_form_cart_qty').on("change.wbu_form_cart_qty", ".qty", function() {
            // loop on each add to cart button to set the correct quantity defined on input
            jQuery('.ajax_add_to_cart').each(function(){
                var qty = jQuery(this).parent().find('.qty').val();

                jQuery(this).data('quantity', qty);
                jQuery(this).attr('data-quantity', qty);
            });
        });

        // prevent bug caused in case the user was navegated using browser back button (so the quantity keep the previous state)
        // issue link: https://wordpress.org/support/topic/quantity-not-working-2/
        $('.add_to_cart_button').off('click.wbu_add_cart_btn').on('click.wbu_add_cart_btn', function(){
            // this was commented because causing excessive ajax requests when adding to cart (on shop page)
            // $('.qty').trigger('change');
            return true;
        });

        // make compatibility with infinite scrollings, forcing re-apply events
        $( document ).ajaxComplete(function() {
            // this was commented because was causing crash on browser, making infinite ajax requests (on shop page)
            // wbuQtyOnShop();
        });
    };
    
    wbuQtyOnCheckout = function() {

        if ( !wbuInfo.isCheckout || ( wbuSettings.checkout_allow_change_qty != 'yes' ) ) {
            return;
        }

        $("form.checkout").on("change", ".qty", function(){


            // run refresh callback timeout 
            wbuClearTimedoutQtyChange();
            wbuUpdateTimeout = setTimeout(function(){
                var data = {
                    action: 'wbu_update_checkout',
                    security: wc_checkout_params.update_order_review_nonce,
                    post_data: $( 'form.checkout' ).serialize()
                };
                
                $.ajax({
                    url: wbuInfo.ajaxUrl,
                    type: 'POST',
                    data: data,
                    beforeSend: function() {
                        wbuBlock( $( '.woocommerce-checkout-review-order-table' ) );
                    },
                    success: function(resp) {
                        wbuUnblock( $( '.woocommerce-checkout-review-order-table' ) );
                        $( document.body ).trigger( 'update_checkout' );
                        $( document.body ).trigger( 'wc_fragment_refresh' );
                    }
                });
            }, wbuSettings.ajax_timeout);
        });
        
        // add Quantity title on table
        // $('body').on('updated_checkout', function(){
        //     quantityTitle = '<div class="checkout-qty-title">' + wbuInfo.quantityLabel + '</div>';
        //     $('.shop_table th.product-name').append(quantityTitle);
        // });
    };
    
    wbuProductAddToCartAjax = function() {
        $(document).on('change', '.qty', function(){
            var newQty = $(this).val();
            var prodForm = $(this).closest('form');

            if ( prodForm.length ) {
                prodForm.find('.add_to_cart_button').data('quantity', newQty).attr('data-quantity', newQty);
                prodForm.find('[name="add-to-cart"]').data('quantity', newQty).attr('data-quantity', newQty);
            }

            return true;
        });
    };

    wbuCheckHideUpdateCartBtn = function() {
        // check hide update cart button
        if ( wbuSettings.cart_hide_update == 'yes' ) {
            $('button[name="update_cart"]').hide();
            $('.fusion-update-cart').hide();
        }
    };

    wbuCheckoutInputValidation = function() {
        var checkoutForm = $( 'form.checkout' );

        if ( checkoutForm.length && checkoutForm.find('.qty').length ) {
            checkoutForm.removeAttr( 'novalidate' );
        }
    };

    wbuFixPortoTheme = function() {
        // fixing strange behavior for Porto theme, displaying two quantity inputs in Shop page
        $('.add-links').each(function(){
            if ( $(this).find('.quantity').length > 1 ) {
                $(this).find('.quantity').eq(1).detach();
            }
        });

        // porto theme adds inline html with minus and plus resulting in strange duplicated buttons
        if ( $('#porto-style-inline-css').length ) {
            var checkExist = setInterval(function() {
                $('.mini_cart_item').each(function(){
                    if ( $(this).find('.plus').length > 1 ) {
                        $(this).find('.plus').eq(1).detach();
                    }
                    if ( $(this).find('.minus').length > 1 ) {
                        $(this).find('.minus').eq(1).detach();
                    }
                });
             }, 100);
        }
    };

    // functions ripped from woocommerce cart.js
    wbuBlock = function( element ) {
        if ( !element.length || !jQuery.fn.block || wbuIsBlocked( element ) ) {
            return;
        }

        try {
            element.addClass( 'processing' ).block( {
                message: null,
                overlayCSS: {
                    background: '#fff',
                    opacity: 0.6
                }
            } );
        }
        catch (e) {
        }
    };

    wbuUnblock = function( element ) {
        if ( !element.length || !jQuery.fn.block ) {
            return;
        }

        try {
            element.removeClass( 'processing' ).unblock();
        }
        catch (e) {
        }
    };

    wbuIsBlocked = function( $node ) {
        return $node.is( '.processing' ) || $node.parents( '.processing' ).length;
    };
    // END OF functions ripped from woocommerce cart.js
    
    // onload function calls
    wbuListenChange();
    wbuQtyButtons();
    wbuCartDeleteEvent();
    wbuQtyOnShop();
    wbuQtyOnCheckout();
    wbuProductAddToCartAjax();
    wbuFixPortoTheme();

    // hide add to cart button
    if ( wbuInfo.isShop && wbuSettings.hide_addtocart_button === 'yes' ) {
        $('.add_to_cart_button').hide();
    }

    // hide update cart button (Avada compatibility)
    if ( wbuInfo.isCart && wbuSettings.cart_hide_update === 'yes' ) {
        $('.fusion-update-cart').hide();
    }
    
    // hide product quantity on cart if needed
    if ( wbuSettings.cart_hide_quantity == 'yes' ) {
        $('.product-quantity').parent().find('th').css('width', '100%');
        $('.product-quantity').hide();
    }

    if ( wbuInfo.isCart ) {
        // fix enter key problem
        if ( wbuSettings.cart_fix_enter_key == 'yes' ) {
            $('.woocommerce-cart-form .qty.text').on('keyup keypress', function(e) {
                var keyCode = e.keyCode || e.which;

                if (keyCode === 13) { 
                    $('.woocommerce-cart-form .qty.text').trigger( "blur" );
                    e.preventDefault();
                    return false;
                }
            });
        }

        wbuCheckHideUpdateCartBtn();
    }

    if ( wbuInfo.isCheckout ) {
        wbuCheckoutInputValidation();    
    }

    $(document.body).on('updated_checkout', function(){
        setTimeout(function(){
            wbuCheckoutInputValidation();
        }, 500);
    });

    // listen when ajax cart has updated
    $(document.body).on('updated_wc_div', function(){
        wbuCheckHideUpdateCartBtn();
        wbuWhenCartUpdated();
    });
    
    // listen when product has added to cart
    $(document.body).on('added_to_cart', function(){
        if ( wbuSettings.hide_viewcart_link === 'yes' ) {
            $('.added_to_cart').css('display', 'none');
            setTimeout(function(){ $('.added_to_cart').css('display', 'none'); }, 50);
            setTimeout(function(){ $('.added_to_cart').css('display', 'none'); }, 100);
        }

        // prevent woocommerce to reload the page after Add to cart in certain conditions (outside Cart page)
        // the window.location.reload() was defined in woo cart.js script
		if ( $( '.woocommerce-cart-form' ).length == 0 ) {
            $('body').append('<div class="woocommerce-cart-form" style="display: none;"></div>');
		}
    });

    // listen minicart remove button click
    $(document).on('click', '.mini-cart-box .remove', function(){
        var productId = $(this).data('product_id');

        $('.shop_table').find('.remove').each(function(){
            if ( productId == $(this).data('product_id') ) {
                $(this).trigger('click');
            }
        });

        return false;
    });

    // listen berocket ajax products filter
    $(document).on('berocket_ajax_filtering_end', function(){
        wbuProductAddToCartAjax();
    });
});

