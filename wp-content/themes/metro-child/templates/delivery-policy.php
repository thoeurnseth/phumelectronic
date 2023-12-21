<?php
    /*
    * Template Name:Delivery Policy
    * Template Post Type: page
    */
    get_header();
?>
    <main class="term-policy">
        <section>
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <?php echo get_field('delivery_policy_description','option'); ?>
                    </div>
                </div>
            </div>
        </section>
    </main>
<?php
    get_footer();
?>