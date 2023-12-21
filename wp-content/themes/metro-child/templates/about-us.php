<?php
    /*
    * Template Name: About Us
    * Template Post Type: post, page
    */
?>
<?php get_header(); ?>

<main class="company-profile">
    <div id="primary" class="content-area pb-0">
        <div class="container">
            
            <section>
                <div class="growing-together">
                    <div class="main-title">
                        <h1>Growing Together</h1>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <div class="content-wrapper">
                                <img src="http://via.placeholder.com/100x100" alt="">
                                <h3>Company Profile</h3>
                                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. </p>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <div class="content-wrapper">
                                <img src="http://via.placeholder.com/100x100" alt="">
                                <h3>Vision</h3>
                                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. </p>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <div class="content-wrapper">
                                <img src="http://via.placeholder.com/100x100" alt="">
                                <h3>Mission</h3>
                                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. </p>
                            </div>
                        </div>
                    </div>
                
                </div>
            </section>
            <section>
                <div class="brand-shop">
                    <div class="main-title">
                        <h1>LG Brand Shop</h1>
                    </div>
                    <div class="brand-shop-content">
                        <div class="row">
                            <?php for($i=1; $i<=5; $i++){ ?>
                                <div class="col-sm-6 col-xs-12">
                                    <div class="brand-shop-thumbnail">
                                        <img src="http://via.placeholder.com/680x400" alt="">

                                        <div class="description">
                                            <ul>
                                                <li><h3>LG Brand Shop</h3></li>
                                                <li><i class="fa fa-map-marker" aria-hidden="true"></i>&ensp; LG Building N0 35 Monivong, Phnom Penh</li>
                                                <li><i class="fa fa-phone" aria-hidden="true"></i>&ensp; 012-354-459</li>
                                                <li><i class="fa fa-clock-o" aria-hidden="true"></i>&ensp; ( 8am - 8pm )</li>
                                            </ul>
                                        </div>
                                        
                                    </div>
                                </div>
                                <div class="col-sm-6 col-xs-12">
                                    <div class="map-thumbnail">
                                        <img src="http://via.placeholder.com/680x400" alt="">
                                    </div>  
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </section>

            <section>
                <div class="archivement-shop">
                    <div class="main-title">
                        <h1>Archivement</h1>
                    </div>
                    <div class="archivement-slider">
                        <div><img src="http://via.placeholder.com/300x400" alt=""></div>
                        <div><img src="http://via.placeholder.com/300x400" alt=""></div>
                        <div><img src="http://via.placeholder.com/300x400" alt=""></div>
                        <div><img src="http://via.placeholder.com/300x400" alt=""></div>
                        <div><img src="http://via.placeholder.com/300x400" alt=""></div>
                        <div><img src="http://via.placeholder.com/300x400" alt=""></div>
                        <div><img src="http://via.placeholder.com/300x400" alt=""></div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</main>

<?php get_footer(); ?>
