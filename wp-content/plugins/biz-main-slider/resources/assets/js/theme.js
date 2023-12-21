jQuery(".main-slider").not('.slick-initialized').slick({
    dots: true,
    fade: true,
    infinite: true,
    autoplay: false,
    autoplaySpeed: 2000,
    slidesToShow: 1,
    slidesToScroll: 1,
    speed: 2000
});

jQuery(".seasonal-product-slider").not('.slick-initialized').slick({
    dots: true,
    fade: true,
    prevArrow: false,
    nextArrow: false,
    infinite: true,
    autoplay: false,
    autoplaySpeed: 2000,
    slidesToShow: 1,
    slidesToScroll: 1,
    speed: 2000
});

jQuery(".archivement-slider").not('.slick-initialized').slick({
    // dots: true,
    // fade: true,
    infinite: true,
    autoplay: false,
    autoplaySpeed: 2000,
    slidesToShow: 4,
    slidesToScroll: 4,
    speed: 2000,
    responsive: [
        {
            breakpoint: 1025,
            settings: {
                slidesToShow: 3,
                slidesToScroll: 3,
            }
        },
        {
            breakpoint: 580,
            settings: {
                slidesToShow: 2,
                slidesToScroll: 2,
            }
        }
    ]
});