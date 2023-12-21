<?php
/**
 * This file can be overridden by copying it to yourtheme/elementor-custom/post/view-1.php
 * 
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\Metro_Core;

use radiustheme\Metro\Helper;

$thumb_size = 'rdtheme-size3';
$query = $data['query'];

if ( !empty( $data['cat'] ) ) {
	$blog_permalink = get_category_link( $data['cat'] );
}
else {
	$blog_page = get_option( 'page_for_posts' );
	$blog_permalink = $blog_page ? get_permalink( $blog_page ) : home_url( '/' );
}
?>
<?php if ( $query->have_posts() ) :?>
	<div class="rt-el-post-1 rtin-<?php echo esc_attr( $data['bgtype'] ); ?>">
		<div class="rt-sec-title-area-1">
			<h3 class="rtin-sec-title"><?php echo esc_html( $data['title'] );?></h3>
			<div class="rtin-viewall"><a href="<?php echo esc_url( $blog_permalink ); ?>"><?php esc_html_e( 'View All', 'metro-core' );?><i class="fa fa-chevron-right"></i></a></div>
		</div>
		<div class="row">
			<?php while ( $query->have_posts() ) : $query->the_post();?>
				<div class="col-md-4 col-12">
					<div class="rtin-item">
						<?php if ( has_post_thumbnail() ): ?>
							<a class="rtin-thumb theme-overlay-2 theme-overlay-hover" href="<?php the_permalink(); ?>"><?php the_post_thumbnail( $thumb_size ); ?></a>
						<?php else: ?>
							<a class="rtin-thumb theme-overlay-2 theme-overlay-hover" href="<?php the_permalink(); ?>"><img src="<?php echo Helper::get_img( 'nothumb-size3.jpg' );?>"></a>
						<?php endif; ?>
						<div class="rtin-content">
							<div class="rtin-date"><?php the_time( get_option( 'date_format' ) ); ?></div>
							<h3 class="rtin-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
							<div class="rtin-author"><?php esc_html_e( "Posted by", 'metro-core' );?> <?php the_author_posts_link(); ?></div>
						</div>
					</div>
				</div>
			<?php endwhile;?>
		</div>
	</div>
<?php else: ?>
	<div><?php esc_html_e( 'Currently there are no posts', 'metro-core' ); ?></div>
<?php endif;?>
<?php wp_reset_postdata();?>