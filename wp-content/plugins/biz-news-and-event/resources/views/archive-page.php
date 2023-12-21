<?php
/**
 * Template Name: List all News and Event
 */
?>

<main class="news-and-event">
    <div class="container">
        <div class="row">
            <?php
                $obj_new_event = new News_And_Event_Autoloader();
                $new_event    = $obj_new_event::get_news_and_event();      

                if( $new_event->have_posts() )
                {
                    while( $new_event->have_posts() ) {
                        $new_event->the_post();

                        $title = get_the_title();
                        $thumbnail = get_the_post_thumbnail_url( get_the_id(), 'full');
                        $description = get_the_excerpt();
                        $event_date = get_field('event-date');                    

                        _e('
                            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                                <div class="item-wrapper">
                                    <div class="item-thumbnail">
                                    <a href="'. get_permalink( get_the_id() ) .'"><img src="'. $thumbnail .'" alt="Thumbnail"></a>
                                    </div>
                                    <div class="item-detail">
                                        <a href="'. get_permalink( get_the_id() ) .'">
                                            <h3>'. $title .'</h3>
                                        </a>
                                        <p>'. $description .'</p>
                                        <span>'.$event_date.'</span>
                                    </div>
                                </div>
                            </div>
                        ');
                    }
                }
            ?>
        </div>
    </div>
</main>

