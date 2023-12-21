
function handlePasteOTP(e) {
    var clipboardData = e.clipboardData || window.clipboardData ||     e.originalEvent.clipboardData;
    var pastedData = clipboardData.getData('Text');
    var arrayOfText = pastedData.toString().split('');
    /* for number only */
    if (isNaN(parseInt(pastedData, 10))) {
        e.preventDefault();
        return;
    }
    for (var i = 0; i < arrayOfText.length; i++) { 
        if (i >= 0) {
            document.getElementById('otp-number-input-' + (i + 1)).value = arrayOfText[i];
        } else {
            return;
        }
    }
    e.preventDefault();
}

jQuery(document).ready(function() {
    jQuery('.otp-event').each(function(){
     var jQueryinput = jQuery(this).find('.otp-number-input');
     var jQuerysubmit = jQuery(this).find('.otp-submit');
     jQueryinput.keydown(function(ev) {
        otp_val = jQuery(this).val();
        if (ev.keyCode == 37) {
            jQuery(this).prev().focus();
            ev.preventDefault();
        } else if (ev.keyCode == 39) {
            jQuery(this).next().focus();
            ev.preventDefault();
        } else if (otp_val.length == 1 && ev.keyCode != 8 && ev.keyCode != 46) {
            otp_next_number = jQuery(this).next();
            if (otp_next_number.length == 1 && otp_next_number.val().length == 0) {
                otp_next_number.focus();
            }
        } else if (otp_val.length == 0 && ev.keyCode == 8) {
            jQuery(this).prev().val('');
            jQuery(this).prev().focus();
        } else if (otp_val.length == 1 && ev.keyCode == 8) {
            jQuery(this).val('');
        } else if (otp_val.length == 0 && ev.keyCode == 46) {
            next_input = jQuery(this).next();
            next_input.val('');
            while (next_input.next().length > 0) {
                next_input.val(next_input.next().val());
                next_input = next_input.next();
                if (next_input.next().length == 0) {
                    next_input.val('');
                    break;
                }
            }
        }


    }).focus(function() {
        jQuery(this).select();
        var otp_val = jQuery(this).prev().val();
        if (otp_val === '') {
            jQuery(this).prev().focus(); 
        }else if(jQuery(this).next().val()){
             jQuery(this).next().focus();  
        }
    }).keyup(function(ev) {
        otpCodeTemp = '';
        jQueryinput.each(function(i) {
            if (jQuery(this).val().length != 0) {
                jQuery(this).addClass('otp-filled-active');
            } else {
                jQuery(this).removeClass('otp-filled-active');
            }
            otpCodeTemp += jQuery(this).val();
        });
        if (jQuery(this).val().length == 1 && ev.keyCode != 37 && ev.keyCode != 39) {
            jQuery(this).next().focus();
            ev.preventDefault(); 
        }
        jQueryinput.each(function(i) {
         if(jQuery(this).val() != ''){
            jQuerysubmit.prop('disabled', false); 
         }else{
            jQuerysubmit.prop('disabled', true);
         }
        });
         
        var digit_1 = jQuery('#otp-number-input-1').val();
        var digit_2 = jQuery('#otp-number-input-2').val();
        var digit_3 = jQuery('#otp-number-input-3').val();
        var digit_4 = jQuery('#otp-number-input-4').val();
        var digit_5 = jQuery('#otp-number-input-5').val();
        var digit_6 = jQuery('#otp-number-input-6').val();
        jQuery('#otp_number').val(digit_1 + digit_2 + digit_3 + digit_4 + digit_5 + digit_6);
    });
    jQueryinput.on('paste', function(e) { 
        window.handlePasteOTP(e);
    });
    });
    
});