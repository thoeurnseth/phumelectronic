<?php
    /*
    * Template Name: Wishlist
    * Template Post Type: post, page
    */
?>
<?php get_header(); ?>

<main class="wishlist">
    <div id="primary" class="content-area">
        <div class="container">
            <section>
                <?php echo do_shortcode('[yith_wcwl_wishlist]'); ?>
            </section>
        </div>
    </div>
</main>

<?php get_footer(); ?>
