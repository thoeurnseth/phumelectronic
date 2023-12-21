// @Brand
jQuery('.slide-wrap').slick({
    slidesToShow: 6,
    slidesToScroll: 1,
    arrows: false,
    autoplay: true,
    speed: 1500,
    autoplaySpeed: 2000,
        responsive: [
            {
                breakpoint: 1445,
                settings: {
                    slidesToShow: 6,
                    slidesToScroll: 1,
                    infinite: true,
                    dots: false
                }
            },
            {
                breakpoint: 1025,
                settings: {
                    slidesToShow: 4,
                    slidesToScroll: 1
                }
            },
            {
                breakpoint: 768,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 1
                }
            },            
            {
                breakpoint: 400,
                settings: {
                    slidesToShow: 5,
                    slidesToScroll: 1
                }
            }
        ]
});

// @Brand
jQuery('.product-slide-wrap').slick({
    speed: 170,
    slidesToShow: 4,
    slidesToScroll: 1,
    autoplay: true,
    autoplaySpeed: 5000,
    responsive: [
        {
          breakpoint: 768,
          settings: {
            slidesToShow: 1,
            slidesToScroll: 1,
          },
        },
    ],
  
});
// jQuery('.tbn-wishlist').click(function(){
//     var wislist = jQuery(this).closest('.wislist-112');
//         wislist.find('.spiner_image').show();

//         $.ajax({
//             accepts: {
//               mycustomtype: 'application/x-some-custom-type'
//             },
           
//             // Instructions for how to deserialize a `mycustomtype`
//             converters: {
//               'text mycustomtype': function(result) {
//                 // Do Stuff
//                 return newresult;
//               }
//             },
           
//             // Expect a `mycustomtype` back from server
//             dataType: 'mycustomtype'
//           });
// });
jQuery('.btn-add-remove-wistlist').click(function(e) {
    e.preventDefault();
    var wislist = jQuery(this).closest('.wislist-112');
    var wislistIcon = jQuery(this).find('.wishlist-icon');
    wislist.find('.spiner_image').show();
    var user_id = jQuery(this).data('user-id');
    var product_id = jQuery(this).data('product-id');
    if(user_id && product_id <= 0){
        window.location.href = "http://prod.phumelectronic/my-account/";
    }
    jQuery.ajax({
        type : "post",
        dataType : "json",
        url : myAjax.ajaxurl,
        data : {
                action: "add_remove_wistlist_web", 
                user_id: user_id, 
                product_id: product_id
            },
        success: function(response) {
            wislist.find('.spiner_image').hide();
            wislistIcon.removeClass('fa-heart-o');
            wislistIcon.addClass('fa-heart');
        }
    });
});