<?php
/**
 * Template Name: List all News and Event
 */
?>

<main class="news-and-event-detail">
    <div class="container">
        <section>
            <?php
                $id = get_the_id();
                $thumbnail = get_the_post_thumbnail_url( $id, 'full');
                $title     = get_the_title($id);
                $description = get_the_excerpt($id);
                $event_date  = get_field('event-date', $id);

                $category_id  = get_post_meta($id, 'event_categories', true);
            ?>
            <div class="row">
                <div class="col-8">
                    <div class="detail-wrapper">
                        <div class="main-thumbnail">
                            <!-- <img src="https://via.placeholder.com/1010x500" alt=""> -->
                            <img src="<?= $thumbnail ?>" alt="">
                        </div>
                        <div class="main-title">
                            <h2><?= $title ?></h2>
                        </div>
                        <div class="date">
                            <span><?= $event_date ?></span>
                        </div>
                        <div class="description">
                            <p><?= $description ?></p>
                            <div class="socail-share">SHARE TO : &nbsp;
                            <?php echo do_shortcode('[Sassy_Social_Share]') ?></div>
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="latest-wrapper">
                        <div class="main-title">
                            <h4 class="top-title">Latest posts</h4>
                        </div>
                        <div class="lastest-content">
                            <?php 
                               $obj_latest_event = new News_And_Event_Autoloader();
                               $latest_event    = $obj_latest_event::latest_news_and_event( $id );
                
                                if( $latest_event->have_posts() )
                                {
                                    while( $latest_event->have_posts() ) {
                                        $latest_event->the_post();
                
                                        $title = get_the_title();
                                        $thumbnail = get_the_post_thumbnail_url( get_the_id(), 'full');
                                        $description = get_the_excerpt();

                                    _e('
                                        <div class="item">
                                            <div class="row"> 
                                                <div class="col-5">
                                                    <div class="latest-thumbnail">
                                                    <a href="'. get_permalink( get_the_id() ) .'"><img src="'. $thumbnail .'" alt="Thumbnail"></a>  
                                                    </div>                          
                                                </div>
                                                <div class="col-7">
                                                    <div class="latest-title">
                                                        <a href="'. get_permalink( get_the_id() ) .'">
                                                            <h6 class="sub-title">'. $title .'</h6>
                                                        </a>
                                                        <p class="description">'. $description .'</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    ');
                                    }
                                }
                            ?>
                        </div> 
                    </div>  
                </div>
            </div>
        </section>
        <section>
            <div class="related-wrapper">
                <div class="row">
                    <div class="col-8">
                        <div class="main-title">
                            <h4 class="top-title">Related Posts</h4>
                        </div>
                        <div class="row">
                            <?php
                                $related_news_event = new News_And_Event_Autoloader();
                                $related = $related_news_event::related_news_and_event( $id, $category_id );
                                
                                if( $related->have_posts() ):
                                    while( $related->have_posts() ):
                                        $related->the_post();

                                        $title = get_the_title();
                                        $content = get_the_content();
                                        $link = get_permalink();
                                        $thumbnail = get_the_post_thumbnail_url( get_the_id(), 'full');

                                        echo '
                                            <div class="col-4">
                                                <div class="item-wrapper">
                                                    <div class="related-thumbnail">
                                                    <a href="'. get_permalink( get_the_id() ) .'"><img src="'. $thumbnail .'" alt="Thumbnail"></a>
                                                    </div>
                                                    <div class="item-detail">
                                                        <a href="'. $link .'">
                                                            <h3 class="sub-title">'. $title .'</h3>
                                                        </a>
                                                        <p class="description">'. $content .'</p>
                                                    </div>
                                                </div>
                                            </div>
                                        ';

                                    endwhile;
                                endif;
                            ?> 
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div> <!-- container -->
</main>

