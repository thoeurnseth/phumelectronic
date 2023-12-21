
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
			jQuery(this).prev().val("");
			jQuery(this).prev().focus();
		} else if (otp_val.length == 1 && ev.keyCode == 8) {
			jQuery(this).val("");
		} else if (otp_val.length == 0 && ev.keyCode == 46) {
			next_input = jQuery(this).next();
			next_input.val("");
			while (next_input.next().length > 0) {
				next_input.val(next_input.next().val());
				next_input = next_input.next();
				if (next_input.next().length == 0) {
					next_input.val("");
					break;
				}
			}
		}
		
	}).focus(function() {
		jQuery(this).select();
		var otp_val = jQuery(this).prev().val();
		if (otp_val === "") {
			jQuery(this).prev().focus(); 
		}else if(jQuery(this).next().val()){
			 jQuery(this).next().focus();  
		}
	}).keyup(function(ev) {
		otpCodeTemp = "";
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
		 
	});
	jQueryinput.on("paste", function(e) { 
		window.handlePasteOTP(e);
	});
	});
	
});

// @Check All On cart

jQuery(document).ready(function(){
	// Check or Uncheck All checkboxes
	jQuery("#checkall").change(function(){
	  var checked = jQuery(this).is(':checked');
	  if(checked){
		jQuery(".checkbox").each(function(){
		  jQuery(this).prop("checked",true);
		});
	  }else{
		jQuery(".checkbox").each(function(){
		  jQuery(this).prop("checked",false);
		});
	  }
	});
  
   // Changing state of CheckAll checkbox 
   jQuery(".checkbox").click(function(){
  
	 if(jQuery(".checkbox").length == jQuery(".checkbox:checked").length) {
	   jQuery("#checkall").prop("checked", false);
	 } else {
	   jQuery("#checkall").removeAttr("checked");
	 }
 
   });
 });

 // Upload Profile
 function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
           jQuery('#imagePreview').css('background-image', 'url('+e.target.result +')');
           jQuery('#imagePreview').hide();
           jQuery('#imagePreview').fadeIn(650);
        }
        reader.readAsDataURL(input.files[0]);
    }
}
jQuery("#imageUpload").change(function() {
    readURL(this);
});

jQuery(document).ready(function(){
	jQuery("#province, #district").select2();

	// Get Province
	jQuery('#province').on('change', function() {
		var province_id = jQuery(this).val();
		get_district( province_id );
	})

	// Get Province
	jQuery('#province option').each(function() {
		if(jQuery(this).is(':selected')) {
			var province_id = jQuery(this).val();
			var old_district_id = jQuery('#old_district_id').val();

			get_district( province_id, old_district_id );
		}
	});
	
	// Get District by Province
	function get_district( province_id, old_district_id ) {

		jQuery.ajax({
			type: "POST",
			url: "/wp-admin/admin-ajax.php",
			data: {
				action: 'get_district',
				province_id: province_id,
				nonce: '',
			},
			dataType: 'JSON',
			success: function (response) {
				var the_status = response.status;
				var the_data = response.data;

				jQuery("#district").html('').select2({
					data: the_data 
				});
				
				if(old_district_id != null) {
					jQuery('#district').val(old_district_id);
					jQuery('#district').trigger('change');
				}
			}
		});
	}
});

jQuery('.my-address-wrapper').attr('id', 'my-address-wrapper');
jQuery('.facebook-login').attr('id', 'btn-fb');
jQuery('.fa.fa-trash').attr('id', 'trash-icon');

