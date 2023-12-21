jQuery(document).ready(function($) {
 
    // Add default 'Select one'
    $( '[data-taxonomy="province"] select' ).prepend( $('<option></option>').val(0).html('Select').attr({ selected: 'selected', disabled: 'disabled'}) );
 
    /**
     * Get country options
     *
     */
    $( '[data-taxonomy="province"] select' ).change(function () {
 
        var selected_country = ''; // Selected value
 
        // Get selected value
        $( '[data-taxonomy="province"] select option:selected' ).each(function() {
            selected_country += $( this ).val();
        });
        console.log(selected_country);
 
        $( '[data-taxonomy="district"] select' ).attr( 'disabled', 'disabled' );
 
        // If default is not selected get areas for selected country
        if( selected_country > 0 ) {

            // Send AJAX request
            data = {
                action: 'pa_add_district',
                pa_nonce: pa_vars.pa_nonce,
                province: selected_country,
            };

 
            // Get response and populate area select field
            $.post( pa_vars.ajaxurl, data, function(response) {
 
                if( response ){
                    // Disable 'Select Area' field until country is selected
                    $( '[data-taxonomy="district"] select' ).html( $('<option></option>').val(0).html('Select').attr({ selected: 'selected', disabled: 'disabled'}) );
 
                    // Add areas to select field options
                    $.each(response, function(val, text) {
                        $( '[data-taxonomy="district"] select' ).append( $('<option></option>').val(text).html(text) );
                    });
 
                    // Enable 'Select Area' field
                    $( '[data-taxonomy="district"] select' ).removeAttr( 'disabled' );
                };
            });
        }
    }).change();
});