<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\Metro;

class LoadMore {

	protected static $instance = null;
	
	private function __construct() {
		add_action('wp_ajax_rdtheme_loadmore',          array( $this, 'loadmore' ) );
		add_action('wp_ajax_nopriv_rdtheme_loadmore',   array( $this, 'loadmore' ) );
	}

	public static function instance() {
		if ( null == self::$instance ) {
			self::$instance = new self;
		}
		return self::$instance;
	}

	public function init( $type = 'loadmore' ) {
		$this->print_data_html();

		if ( $type == 'loadmore' ) {
			$this->print_btn_html();
		}
		else {
			$this->print_spinner_html();
		}
	}

	public function loadmore() {
		check_ajax_referer( 'metro_loadmore_nonce', 'nonce' );

		$args = json_decode( stripslashes( $_POST['query'] ), true );
		$args['paged'] = intval( $_POST['paged'] ) + 1;
		$view = !empty($_POST['view']) && 'list' === $_POST['view'] ? 'list' : 'grid';
		$_REQUEST['ajax_product_loadmore'] = 1;

		$query = new \WP_Query($args);

		if( $query->have_posts() ) :
			while( $query->have_posts() ): $query->the_post();
				Helper::get_template_part( 'woocommerce/content-product', array( 'isloadmore' => true, 'view' => $view ) );
			endwhile;
		endif;

		wp_die();
	}

	private function print_data_html() {
		global $wp_query;
		$paged = get_query_var( 'paged' ) ? absint( get_query_var( 'paged' ) ) : 1;
		$nonce =  wp_create_nonce( 'metro_loadmore_nonce' );
		echo '<div class="rdtheme-loadmore-data" data-query="'.esc_attr( json_encode( $wp_query->query_vars ) ).'" data-paged="'.esc_attr( $paged ).'" data-max="'.esc_attr( $wp_query->max_num_pages ).'" data-nonce="'.esc_attr( $nonce ).'"></div>';
	}

	private function print_btn_html() {
		?>
		<div class="rdtheme-loadmore-btn-area">
			<button class="rdtheme-loadmore-btn">
				<span class="rdtheme-loadmore-btn-text"><?php esc_html_e( 'Load More', 'metro' ); ?></span>
				<span class="rdtheme-loadmore-btn-icon"><i class="fa fa-spinner fa-spin"></i></span>
			</button>
		</div>
		<?php
	}

	private function print_spinner_html() {
		?>
		<div class="rdtheme-infscroll-icon"><i class="fa fa-spinner fa-spin"></i></div>
		<?php
	}
}

LoadMore::instance();