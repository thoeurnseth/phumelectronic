window.onload = function()
{ 
  let timerOn = true;
  function timer(remaining) {
    var m = Math.floor(remaining / 60);
    var s = remaining % 60;
    s = s < 10 ? '0' + s : s;
   
    document.getElementById('timer').innerHTML = s;
    remaining -= 1;
    console.log(remaining);
    if(remaining >= 0 && timerOn) {
      setTimeout(function() {
        timer(remaining);
      }, 1000);
      return;

    } 
    // else {

    //   var nonce = 122334;
    //   var phone = jQuery();

    //   jQuery.ajax({
    //     type: "POST",
    //     url: "/wp-admin/admin-ajax.php",
    //     data: {
    //         action: 'set_sms_opt_expired',
    //         phone: post_id,
    //         nonce: nonce,
    //     },
    //     dataType: 'JSON',
    //     success: function ( response ) {
    //       console.log( response.data );
    //     }
    //   });
    // }
    
  } 
  timer(60);
}

/**
 * Archivement Slider
 */
jQuery('.archivement-slider').slick({
  slidesToShow: 4,
  slidesToScroll: 4,
  autoplay: true,
  autoplaySpeed: 2000,
  responsive: [
      {
        breakpoint:1440,
        settings: {
          slidesToShow: 3,
          slidesToScroll: 3,
          infinite: true,
        }
      },
      {
          breakpoint: 1200,
          settings: {
            slidesToShow: 3,
            slidesToScroll: 3,
            infinite: true,
          }
      },
      {
          breakpoint: 992,
          settings: {
            slidesToShow: 2,
            slidesToScroll: 2,
            infinite: true,
          }
      },
      {
        breakpoint: 768,
        settings: {
          slidesToShow: 2,
          slidesToScroll: 2
        }
      },
      {
        breakpoint: 576,
        settings: {
          slidesToShow: 1,
          slidesToScroll: 1
        }
      }
    ]
});

// @Google Map
// var map;
// var ilat = parseFloat($('#lat').val());
// var ilng = parseFloat($('#lng').val());

// function initialize() {
//     var mapOptions = {
//         zoom: 15,
//         center: { lat: ilat, lng: ilng }
//     };

//     map = new google.maps.Map(document.getElementById('map'),mapOptions);

//     var marker = new google.maps.Marker({
//         position: { lat: ilat, lng: ilng },
//         map: map
//     });
//     google.maps.event.addListener(marker, 'click', function() {
//         infowindow.open(map, marker);
//     });
// }
// google.maps.event.addDomListener(window, 'load', initialize);

jQuery(document).ready(function(){
  var base_url = window.location.origin;
  jQuery('.elementor-element-7508c45 .rt-el-sale-banner-slider a.rtin-btn').attr('href',base_url + '/shop/');
});

jQuery('.footer-bottom-inner .copyright-text').each(function() {
  var text = jQuery(this).text();
  jQuery(this).text(text.replace('2020', '2021')); 
});

jQuery(document).ready(function() {

  jQuery('.meanmenu-reveal').append('<span></span><span></span>');

  var total = jQuery('.order-total .woocommerce-Price-amount.amount').text();
  jQuery('.pay-now-wrap .price .price-value').text(total);


  jQuery('.single-product .add_to_cart_button').each(function() {
    var text = jQuery(this).text();
    jQuery(this).text(text.replace('Select options', 'Add to cart')); 
  });

  jQuery('.post-type-archive-product .action-cart span').each(function() {
    var text = jQuery(this).text();
    jQuery(this).text(text.replace('Select options', 'Add to cart')); 
  });

  jQuery('.home .rtin-buttons .action-cart span').each(function() {
    var text = jQuery(this).text();
    jQuery(this).text(text.replace('Add to cart', 'Select Options')); 
  });

  jQuery('.single-product .single-product-bottom-1 .rtin-left a').each(function() {
    var text = jQuery(this).text();
    jQuery(this).text(text.replace('Add to cart', 'Select Options')); 
  });

  jQuery('.home .rtin-viewall a').attr('href','/shop/');

  jQuery('.single-product').removeAttr("style");

  jQuery('.woocommerce-MyAccount-navigation-link.woocommerce-MyAccount-navigation-link--edit-address a').each(function() {
    var text = jQuery(this).text();
    jQuery(this).text(text.replace('My Address', 'Shipping Address')); 
  });
  
  jQuery('.woocommerce-MyAccount-navigation-link.woocommerce-MyAccount-navigation-link--device a').each(function() {
    var text = jQuery(this).text();
    jQuery(this).text(text.replace('My Device', 'E-Warranty')); 
  });

   /*
   * @Switch Language
	*/
  jQuery('.switch-language .lang').click(function(){
    jQuery('.switch-language').toggleClass('open');
    jQuery('.switch-language .lang-wrapper').fadeToggle();
  });

  jQuery('.meanmenu-reveal').click(function(){
    console.log(jQuery(this).hasClass('meanclose'));
    if(!jQuery(this).hasClass('meanclose')){      
      jQuery('.meanmenu-reveal').html('<span></span><span></span><span></span>');
    }
  });

  var lang = jQuery('.language-chooser-item.active a').attr('title');
    lang = lang.slice(-3);
    lang = lang.split(")", 1);
  	jQuery('.switch-language .active-lang').attr('id', lang);

    
});
  jQuery('.form-row.form-row-last .button').click(function(){
      var a = jQuery('.checkout.woocommerce-checkout .woocommerce-checkout-review-order .order-total .woocommerce-Price-amount.amount > bdi').val();
      jQuery('.pay-now-wrap .amount .woocommerce-Price-amount.amount > bdi').val(a);
  });
  //var a = jQuery('.checkout.woocommerce-checkout .woocommerce-Price-amount.amount').val();
 // jQuery('.woocommerce-checkout-review-order .woocommerce-Price-amount.amount').val(a);
    
 



    
 