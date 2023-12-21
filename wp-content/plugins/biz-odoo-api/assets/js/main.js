jQuery(document).ready( function() {    

    jQuery('#preloading').hide();
    var bar = new ldBar(".loading_bar", {
        "stroke": '#0c75bc',
        "stroke-width": 10,
        "preset": "circle",
        "value": 0
    });

    var ordered_num = 1;
    var page_number = 1;


    function setDelay(i, log_str) {
        setTimeout(function(){
            jQuery('#loading').html( jQuery('#loading').html() + log_str );
                    
            var element = document.getElementById("log-textarea");
            element.scrollTop = element.scrollHeight;
        }, 500 * i);
    }


    function call_ajax(nonce, category_ids, brand_ids)
    {
        jQuery.ajax({
            type : "post",
            dataType : "json",
            url : myAjax.ajaxurl,
            data : {action: "odoo_sync_action", stage : "products", page_number : page_number, params : JSON.stringify({categories: category_ids, brands : brand_ids}), nonce: nonce},
            success: function(response) {
                if(page_number == 0)
                {
                    page_number = 1;
                }
                else
                {
                    page_number++;
                }

                if(page_number <= response.total_page)
                {
                    let percentage = ( page_number / response.total_page ) * 100;
                    bar.set(percentage, true);
                    call_ajax(nonce, category_ids, brand_ids);
                }
                if( page_number <= (response.total_page + 1) )
                {
                    let log_str = '';
                    if( response.woocommerce_result.hasOwnProperty('update') )
                    for(var i=0; i<response.woocommerce_result.update.length; i++)
                    {
                        log_str = ordered_num + ". [Updated] " + response.woocommerce_result.update[i].name + "<br>";
                        setDelay(i, log_str);
                        ordered_num++;
                    }
                    if( response.woocommerce_result.hasOwnProperty('create') )
                    for(var i=0; i<response.woocommerce_result.create.length; i++)
                    {
                        log_str = ordered_num + ". [Created] " + response.woocommerce_result.create[i].name + "<br>";
                        setDelay(i, log_str);
                        ordered_num++;
                    }
                }
                if( page_number == (response.total_page + 1) )
                {
                    jQuery(this).attr("disabled", false);
                    jQuery('#loading').html(jQuery('#loading').html() + "<br><span class=\"dashicons dashicons-yes\"></span> Completed");
                    jQuery('#preloading').hide();
                }
            }
        });
    }


    /**
     * Sync Data From Odoo
     */
    jQuery("#sync_odoo").click( function(e) {
        e.preventDefault(); 
        post_id = jQuery(this).attr("data-post_id");
        nonce = jQuery(this).attr("data-nonce");
        jQuery(this).attr("disabled", true);
        jQuery('#loading').text('Preparing...');

        var age_groups = [];
        var category_ids = [];
        var brand_ids = [];

        jQuery('#preloading').show();

        // Stage 1
        // Sync Locations
        jQuery.ajax({
            type : "post",
            dataType : "json",
            url : myAjax.ajaxurl,
            data : {action: "odoo_sync_action", stage : "locations", page_number : page_number, nonce: nonce},
            success: function(response) {
                jQuery('#loading').html(jQuery('#loading').html() + "<br><span class=\"dashicons dashicons-yes\"></span> Provinces have been synced.");
                jQuery('#loading').html(jQuery('#loading').html() + "<br><span class=\"dashicons dashicons-yes\"></span> Districts have been synced.");
                var element = document.getElementById("log-textarea");
                element.scrollTop = element.scrollHeight;

                // Stage 2 Page 1
                // Sync Locations Communes
                jQuery.ajax({
                    type : "post",
                    dataType : "json",
                    url : myAjax.ajaxurl,
                    data : {action: "odoo_sync_action", stage : "locations_commune", page_number : 1, nonce: nonce},
                    success: function(response) {
                        jQuery('#loading').html(jQuery('#loading').html() + "<br><span class=\"dashicons dashicons-yes\"></span> 100 Communes have been synced.");
                        var element = document.getElementById("log-textarea");
                        element.scrollTop = element.scrollHeight;

                        // Stage 2 Page 2
                        // Sync Locations Communes
                        jQuery.ajax({
                            type : "post",
                            dataType : "json",
                            url : myAjax.ajaxurl,
                            data : {action: "odoo_sync_action", stage : "locations_commune", page_number : 2, nonce: nonce},
                            success: function(response) {
                                jQuery('#loading').html(jQuery('#loading').html() + "<br><span class=\"dashicons dashicons-yes\"></span> 200 Communes have been synced.");
                                var element = document.getElementById("log-textarea");
                                element.scrollTop = element.scrollHeight;

                                // Stage 2 Page 3
                                // Sync Locations Communes
                                jQuery.ajax({
                                    type : "post",
                                    dataType : "json",
                                    url : myAjax.ajaxurl,
                                    data : {action: "odoo_sync_action", stage : "locations_commune", page_number : 3, nonce: nonce},
                                    success: function(response) {
                                        jQuery('#loading').html(jQuery('#loading').html() + "<br><span class=\"dashicons dashicons-yes\"></span> 300 Communes have been synced.");
                                        var element = document.getElementById("log-textarea");
                                        element.scrollTop = element.scrollHeight;

                                        // Stage 2 Page 4
                                        // Sync Locations Communes
                                        jQuery.ajax({
                                            type : "post",
                                            dataType : "json",
                                            url : myAjax.ajaxurl,
                                            data : {action: "odoo_sync_action", stage : "locations_commune", page_number : 4, nonce: nonce},
                                            success: function(response) {
                                                jQuery('#loading').html(jQuery('#loading').html() + "<br><span class=\"dashicons dashicons-yes\"></span> 400 Communes have been synced.");
                                                var element = document.getElementById("log-textarea");
                                                element.scrollTop = element.scrollHeight;

                                                // Stage 2 Page 5
                                                // Sync Locations Communes
                                                jQuery.ajax({
                                                    type : "post",
                                                    dataType : "json",
                                                    url : myAjax.ajaxurl,
                                                    data : {action: "odoo_sync_action", stage : "locations_commune", page_number : 5, nonce: nonce},
                                                    success: function(response) {
                                                        jQuery('#loading').html(jQuery('#loading').html() + "<br><span class=\"dashicons dashicons-yes\"></span> 500 Communes have been synced.");
                                                        var element = document.getElementById("log-textarea");
                                                        element.scrollTop = element.scrollHeight;

                                                        // Stage 2 Page 6
                                                        // Sync Locations Communes
                                                        jQuery.ajax({
                                                            type : "post",
                                                            dataType : "json",
                                                            url : myAjax.ajaxurl,
                                                            data : {action: "odoo_sync_action", stage : "locations_commune", page_number : 6, nonce: nonce},
                                                            success: function(response) {
                                                                jQuery('#loading').html(jQuery('#loading').html() + "<br><span class=\"dashicons dashicons-yes\"></span> 600 Communes have been synced.");
                                                                var element = document.getElementById("log-textarea");
                                                                element.scrollTop = element.scrollHeight;

                                                                // Stage 2 Page 7
                                                                // Sync Locations Communes
                                                                jQuery.ajax({
                                                                    type : "post",
                                                                    dataType : "json",
                                                                    url : myAjax.ajaxurl,
                                                                    data : {action: "odoo_sync_action", stage : "locations_commune", page_number : 7, nonce: nonce},
                                                                    success: function(response) {
                                                                        jQuery('#loading').html(jQuery('#loading').html() + "<br><span class=\"dashicons dashicons-yes\"></span> 700 Communes have been synced.");
                                                                        var element = document.getElementById("log-textarea");
                                                                        element.scrollTop = element.scrollHeight;

                                                                        // Stage 2 Page 8
                                                                        // Sync Locations Communes
                                                                        jQuery.ajax({
                                                                            type : "post",
                                                                            dataType : "json",
                                                                            url : myAjax.ajaxurl,
                                                                            data : {action: "odoo_sync_action", stage : "locations_commune", page_number : 8, nonce: nonce},
                                                                            success: function(response) {
                                                                                jQuery('#loading').html(jQuery('#loading').html() + "<br><span class=\"dashicons dashicons-yes\"></span> 800 Communes have been synced.");
                                                                                var element = document.getElementById("log-textarea");
                                                                                element.scrollTop = element.scrollHeight;

                                                                                // Stage 2 Page 9
                                                                                // Sync Locations Communes
                                                                                jQuery.ajax({
                                                                                    type : "post",
                                                                                    dataType : "json",
                                                                                    url : myAjax.ajaxurl,
                                                                                    data : {action: "odoo_sync_action", stage : "locations_commune", page_number : 9, nonce: nonce},
                                                                                    success: function(response) {
                                                                                        jQuery('#loading').html(jQuery('#loading').html() + "<br><span class=\"dashicons dashicons-yes\"></span> 900 Communes have been synced.");
                                                                                        var element = document.getElementById("log-textarea");
                                                                                        element.scrollTop = element.scrollHeight;

                                                                                        // Stage 2 Page 10
                                                                                        // Sync Locations Communes
                                                                                        jQuery.ajax({
                                                                                            type : "post",
                                                                                            dataType : "json",
                                                                                            url : myAjax.ajaxurl,
                                                                                            data : {action: "odoo_sync_action", stage : "locations_commune", page_number : 10, nonce: nonce},
                                                                                            success: function(response) {
                                                                                                jQuery('#loading').html(jQuery('#loading').html() + "<br><span class=\"dashicons dashicons-yes\"></span> 1000 Communes have been synced.");
                                                                                                var element = document.getElementById("log-textarea");
                                                                                                element.scrollTop = element.scrollHeight;

                                                                                                // Stage 2 Page 11
                                                                                                // Sync Locations Communes
                                                                                                jQuery.ajax({
                                                                                                    type : "post",
                                                                                                    dataType : "json",
                                                                                                    url : myAjax.ajaxurl,
                                                                                                    data : {action: "odoo_sync_action", stage : "locations_commune", page_number : 11, nonce: nonce},
                                                                                                    success: function(response) {
                                                                                                        jQuery('#loading').html(jQuery('#loading').html() + "<br><span class=\"dashicons dashicons-yes\"></span> 1100 Communes have been synced.");
                                                                                                        var element = document.getElementById("log-textarea");
                                                                                                        element.scrollTop = element.scrollHeight;

                                                                                                        // Stage 2 Page 12
                                                                                                        // Sync Locations Communes
                                                                                                        jQuery.ajax({
                                                                                                            type : "post",
                                                                                                            dataType : "json",
                                                                                                            url : myAjax.ajaxurl,
                                                                                                            data : {action: "odoo_sync_action", stage : "locations_commune", page_number : 12, nonce: nonce},
                                                                                                            success: function(response) {
                                                                                                                jQuery('#loading').html(jQuery('#loading').html() + "<br><span class=\"dashicons dashicons-yes\"></span> 1200 Communes have been synced.");
                                                                                                                var element = document.getElementById("log-textarea");
                                                                                                                element.scrollTop = element.scrollHeight;

                                                                                                                // Stage 2 Page 13
                                                                                                                // Sync Locations Communes
                                                                                                                jQuery.ajax({
                                                                                                                    type : "post",
                                                                                                                    dataType : "json",
                                                                                                                    url : myAjax.ajaxurl,
                                                                                                                    data : {action: "odoo_sync_action", stage : "locations_commune", page_number : 13, nonce: nonce},
                                                                                                                    success: function(response) {
                                                                                                                        jQuery('#loading').html(jQuery('#loading').html() + "<br><span class=\"dashicons dashicons-yes\"></span> All Communes have been synced.");
                                                                                                                        var element = document.getElementById("log-textarea");
                                                                                                                        element.scrollTop = element.scrollHeight;

                                                                                                                        // Stage 3
                                                                                                                        // Sync Age Group

                                                                                                                        // jQuery.ajax({
                                                                                                                        //     type : "post",
                                                                                                                        //     dataType : "json",
                                                                                                                        //     url : myAjax.ajaxurl,
                                                                                                                        //     data : {action: "odoo_sync_action", stage : "age_groups", page_number : page_number, nonce: nonce},
                                                                                                                        //     success: function(response) {
                                                                                                                        //         ordered_num = 1;
                                                                                                                        //         age_groups = response;
                                                                                                                        //         jQuery('#loading').html(jQuery('#loading').html() + "<br><span class=\"dashicons dashicons-yes\"></span> Age Groups have been synced.");
                                                                                                                        //         var element = document.getElementById("log-textarea");
                                                                                                                        //         element.scrollTop = element.scrollHeight;

                                                                                                                                // Stage 4
                                                                                                                                // Sync Categories Done
                                                                                                                                jQuery.ajax({
                                                                                                                                    type : "post",
                                                                                                                                    dataType : "json",
                                                                                                                                    url : myAjax.ajaxurl,
                                                                                                                                    data : {action: "odoo_sync_action", stage : "categories", page_number : page_number, nonce: nonce},
                                                                                                                                    success: function(response) {
                                                                                                                                        category_ids = response;
                                                                                                                                        jQuery('#loading').html(jQuery('#loading').html() + "<br><span class=\"dashicons dashicons-yes\"></span> Categories have been synced.");
                                                                                                                                        var element = document.getElementById("log-textarea");
                                                                                                                                        element.scrollTop = element.scrollHeight;

                                                                                                                                        // Move Product to Trash
                                                                                                                                        jQuery.ajax({
                                                                                                                                            type : "post",
                                                                                                                                            dataType : "json",
                                                                                                                                            url : myAjax.ajaxurl,
                                                                                                                                            data : {action: "odoo_sync_action", stage : "trash_products", page_number : page_number, nonce: nonce},
                                                                                                                                            success: function(response) {
                                                                                                                                                
                                                                                                                                                jQuery('#loading').html(jQuery('#loading').html() + "<br><span class=\"dashicons dashicons-yes\"></span> Aready checked move product to trash.");
                                                                                                                                                jQuery('#loading').html(jQuery('#loading').html() + "<br> Fetching products...<br>");
                                                                                                                                                var element = document.getElementById("log-textarea");
                                                                                                                                                element.scrollTop = element.scrollHeight;

                                                                                                                                                // Stage 5
                                                                                                                                                // Sync Brands Done
                                                                                                                                                jQuery.ajax({
                                                                                                                                                    type : "post",
                                                                                                                                                    dataType : "json",
                                                                                                                                                    url : myAjax.ajaxurl,
                                                                                                                                                    data : {action: "odoo_sync_action", stage : "brands", page_number : page_number, nonce: nonce},
                                                                                                                                                    success: function(response) {


                                                                                                                                                        console.log( response );

                                                                                                                                                        brand_ids = response;
                                                                                                                                                        jQuery('#loading').html(jQuery('#loading').html() + "<br><span class=\"dashicons dashicons-yes\"></span> Brands have been synced.");
                                                                                                                                                        jQuery('#loading').html(jQuery('#loading').html() + "<br> Fetching products...<br>");
                                                                                                                                                        var element = document.getElementById("log-textarea");
                                                                                                                                                        element.scrollTop = element.scrollHeight;

                                                                                                                                                        call_ajax( nonce, category_ids, brand_ids);
                                                                                                                                                                   
                                                                                                                                                    }
                                                                                                                                                });
                                                                                                                                            }
                                                                                                                                        });
                                                                                                                                    }
                                                                                                                                });
                                                                                                                        //    }
                                                                                                                        // });
                                                                                                                    }
                                                                                                                });
                                                                                                            }
                                                                                                        });
                                                                                                    }
                                                                                                });
                                                                                            }
                                                                                        });
                                                                                    }
                                                                                });
                                                                            }
                                                                        });
                                                                    }
                                                                });
                                                            }
                                                        });
                                                    }
                                                });
                                            }
                                        });
                                    }
                                });
                            }
                        });
                    }
                });
            }
        });
    });
});