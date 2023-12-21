/**
 * Validate Form
 */
jQuery("#form-card-number").validate({
	rules: {
		card_number: {
			required: true,
            number: true,
			normalizer: function(value) {
				return jQuery.trim(value);
			}
		}
	},
    messages: {
        card_number: {
            required: "Please enter the card number.",
            number: "Invalid number. Please enter numbre only."
        }
    }
});

/**
 * Filter member by Card
 */
jQuery('#filter-member-card').on( 'click', function()
{
    var card_number = jQuery( '#member_card_number' ).val();
    var nonce       = jQuery( this ).attr( 'nonce' );

    //console.log( nonce );
    //return false;
  
    // This does the ajax request
    jQuery.ajax({
        type: "POST",
        url: "/wp-admin/admin-ajax.php",
        data: {
            'action': 'filter_member_by_card_number',
            'data'  : card_number,
            'nonce' : nonce
        },
        success:function( seccess ) {
            var info = seccess.data;
            var card = "";

            card =
            '<div class="col col-lg-2 col-md-2 col-sm-3 col-xs-12">'+
                '<div class="item-wrapper" data="'+ info.id +'">'+
                    '<div class="item-profile">'+
                        '<img src="'+ info.profile +'" alt="Profile">'+
                    '</div>'+
                    '<div class="item-detail">'+
                        '<ul>'+
                            '<li>Name: '+ info.name +'</li>'+
                            '<li>DOB: '+ info.dob +'</li>'+
                            '<li>Card: '+ info.card +'</li>'+
                        '</ul>'+
                    '</div>'
                '</div>'+
            '</div>';
            
            jQuery('#block-card .no-data').hide(); // Hidden label no data
            jQuery('#block-card .row').append( card ); // Append member card

           // console.log(data);
        },
        error: function( error ){
            console.log(error);
        }
    });
});