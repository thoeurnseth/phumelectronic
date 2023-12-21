<?php
/**
 * This file can be overridden by copying it to yourtheme/elementor-custom/post/view-6.php
 * 
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\Metro_Core;

use radiustheme\Metro\Helper;

$thumb_size = 'rdtheme-size3';
$query = $data['query'];
?>
<?php if ( $query->have_posts() ) :?>
	<div class="rt-el-post-7">
		<?php if ( $data['title'] ): ?>
			<div class="rt-sec-title-area-1 no-after-style">
				<h3 class="rtin-sec-title"><?php echo esc_html( $data['title'] );?></h3>
				
				<?php if ( $data['title_right_link_text'] ): ?>
					<div class="rtin-viewall"><a href="<?php echo esc_url( $blog_permalink ); ?>"><?php echo esc_html( $data['title_right_link_text'] );?><i class="fa fa-chevron-right"></i></a></div>
				<?php endif; ?>
			</div>
		<?php endif; ?>
		<div class="row">
			<?php while ( $query->have_posts() ) : $query->the_post();?>
				<?php
				$content = Helper::get_current_post_content();
				$content = wp_trim_words( $content, $data['count'] );
				?>
				<div class="col-md-4 col-12">
					<div class="rtin-item elementwidth elwidth-300">
						<div class="rtin-thumb-area">
							<?php if ( has_post_thumbnail() ): ?>
								<a class="rtin-thumb metro-scale-animation" href="<?php the_permalink(); ?>">
									<?php the_post_thumbnail( $thumb_size ); ?>										
								</a>
							<?php else: ?>
								<a class="rtin-thumb metro-scale-animation" href="<?php the_permalink(); ?>">
									<img src="<?php echo Helper::get_img( 'nothumb-size3.jpg' );?>">
								</a>
							<?php endif; ?>
							<div class="rtin-date">
								<div class="rtin-d1"><?php the_time( 'd' ); ?></div>
								<div class="rtin-d2"><?php the_time( 'M' ); ?></div>
							</div>
						</div>
						<div class="rtin-content">
							<div class="rtin-cats"><?php the_category( ', ' );?></div>
							<h3 class="rtin-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
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