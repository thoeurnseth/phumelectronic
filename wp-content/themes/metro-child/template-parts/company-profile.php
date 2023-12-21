<?php
    /*
    * Template Name: Company Profile
    * Template Post Type: post, page
    */
?>
<?php get_header(); ?>

<main class="company-profile">
    <div id="primary" class="content-area">
        <div class="container">
            
            <section>
                <div class="growing-together">
                    <div class="main-title">
                        <h1>Growing Together</h1>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <div class="growing-thumbnail">
                                <img src="<?php echo get_field('company-profile-icon', 'option'); ?>" alt="">
                            </div>
                            <div class="title">
                                <h3><?php echo get_field('company-profile-title', 'option'); ?></h3>
                            </div>
                            <div class="description">
                                <p><?php echo get_field('company-profile-description', 'option'); ?></p>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                        <div class="growing-thumbnail">
                                <img src="<?php echo get_field('our-vision-icon', 'option'); ?>" alt="">
                            </div>
                            <div class="title">
                                <h3><?php echo get_field('our-vision-title', 'option'); ?></h3>
                            </div>
                            <div class="description">
                                <p><?php echo get_field('our-vision-description', 'option'); ?></p>
                            </div>
                        </div>
                        
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <div class="growing-thumbnail">
                                <img src="<?php echo get_field('our-mission-icon', 'option'); ?>" alt="">
                            </div>
                            <div class="title">
                                <h3><?php echo get_field('our-mission-title', 'option'); ?></h3>
                            </div>
                            <div class="description">
                                <p><?php echo get_field('our-mission-description', 'option'); ?></p>
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

                            <?php
                                $args = array(
                                    'post_type'		=> 'company-profile',
                                    'post_status'   => 'publish'
                                );

                                $query = new WP_Query( $args );

                                if( $query->have_posts() ):
                                    while( $query->have_posts() ):
                                        $query->the_post();

                                        $post_id = get_the_id();
                            ?>
                                <div class="col-sm-6 col-xs-12">
                                    <div class="brand-shop-thumbnail">
                                       <?php echo get_the_post_thumbnail( $post_id, 'full' ); ?>

                                        <div class="description">
                                            <ul>
                                                <li><h3><?php echo get_the_title(); ?></h3></li>
                                                <li><i class="fa fa-map-marker" aria-hidden="true"></i>&ensp;<?php echo get_field( 'address' ); ?></li>
                                                <li><i class="fa fa-phone" aria-hidden="true"></i>&ensp;<?php echo get_field( 'phone_number' ); ?></li>
                                                <li>
                                                    <i class="fa fa-clock-o" aria-hidden="true"></i>
                                                    ( <?php echo get_field( 'start_working_hour' ).' - '.get_field( 'end_working_hour' ); ?> )
                                                </li>
                                            </ul>
                                        </div>
                                        
                                    </div>
                                </div>
                                <div class="col-sm-6 col-xs-12">
                                    <div class="map-thumbnail">
                                        <?php $location = get_field('map'); ?>
                                        <div class="acf-map">
                                            <div class="marker" data-lat="<?php echo $location['lat']; ?>" data-lng="<?php echo $location['lng']; ?>"></div>
                                        </div>
                                    </div>  
                                </div>

                            <?php
                                endwhile;
                            endif;
                            ?>
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
                        <?php
                                    $args = array(
                                        'post_type'		=> 'archivement',
                                        'post_status'   => 'publish'
                                    );

                                    $query = new WP_Query( $args );

                                    if( $query->have_posts() ):
                                        while( $query->have_posts() ):
                                            $query->the_post();

                                            $post_id = get_the_id();

                        ?>
                            <div><?php echo get_the_post_thumbnail( $post_id, 'full' ); ?></div>

                        <?php
                        endwhile;
                        endif;
                        ?>
                    </div>
                </div>
            </section>
        </div>
    </div>
</main>

<?php get_footer(); ?>
