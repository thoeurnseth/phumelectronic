jQuery(document).ready( function() {

    jQuery("#verify-top-btn").click( function(e) {
        e.preventDefault(); 
        nonce = jQuery(this).attr("data-nonce");
        jQuery(this).attr("disabled", true);
        // jQuery('#loading').text('Preparing...');

        var otp_number 	= jQuery('#verify_otp_number');
        var phone 		= jQuery('#verify_phone');
        var user_id 	= jQuery('#verify_user_id');


        jQuery.ajax({
            type : "post",
            dataType : "json",
            url : myAjax.ajaxurl,
            data : {action: "verify_otp_action", otp_number: otp_number, phone: phone, user_id: user_id, nonce: nonce},
            success: function(response) {
                console.log(response);
            }
        });
    });
});



