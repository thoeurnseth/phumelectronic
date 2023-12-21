<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\Metro_Core;

use \WP_Widget;
use \RT_Widget_Fields;

class About_Widget extends WP_Widget {
	public function __construct() {
		$id = METRO_CORE_THEME_PREFIX . '_about';
		parent::__construct(
            $id, // Base ID
            esc_html__( 'Metro: About', 'metro-core' ), // Name
            array( 'description' => esc_html__( 'Metro: About( Footer )', 'metro-core' )
        ) );
	}

	public function widget( $args, $instance ){
		echo wp_kses_post( $args['before_widget'] );

		if ( !empty( $instance['logo'] ) ) {
			$html = wp_get_attachment_image( $instance['logo'], 'full' );
		}
		elseif ( !empty( $instance['title'] ) ) {
			$html = apply_filters( 'widget_title', $instance['title'] );
			$html = $args['before_title'] . $html .$args['after_title'];
		}
		else {
			$html = '';
		}

		echo wp_kses_post( $html );

		$items = array(
			array(
				'icon' => 'flaticon-placeholder',
				'text' => $instance['address'],
			),
			array(
				'icon' => 'flaticon-phone-call',
				'text' => $instance['phone'],
			),
			array(
				'icon' => 'flaticon-envelope',
				'text' => $instance['email'],
			),	
		);
		?>
		<ul>
			<?php if ( $instance['address'] ): ?>
				<li><i class="flaticon-placeholder"></i><span class="rtin-content"><?php echo wp_kses_post( $instance['address'] );?></span></li>
			<?php endif; ?>
			<?php while(has_sub_field('telephone', 'option')){ ?>

				<li><i class="flaticon-phone-call"></i><a class="rtin-content" href="tel:<?php the_sub_field('phone_number'); ?>">
					<?php the_sub_field('phone_number'); ?>
				</a></li>

			<?php } ?>

			<?php if ( $instance['email'] ): ?>
				<li><i class="flaticon-envelope"></i><a class="rtin-content" href="mailto:<?php echo esc_attr( $instance['email'] );?>"><?php echo esc_html( $instance['email'] );?></a></li>
			<?php endif; ?>
		</ul>

		<?php
		echo wp_kses_post( $args['after_widget'] );
	}

	public function update( $new_instance, $old_instance ){
		$instance            = array();
		$instance['title']   = ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : '';
		$instance['logo']    = ( ! empty( $new_instance['logo'] ) ) ? sanitize_text_field( $new_instance['logo'] ) : '';
		$instance['address'] = ( ! empty( $new_instance['address'] ) ) ? wp_kses_post( $new_instance['address'] ) : '';
		$instance['phone']   = ( ! empty( $new_instance['phone'] ) ) ? sanitize_text_field( $new_instance['phone'] ) : '';
		$instance['email']   = ( ! empty( $new_instance['email'] ) ) ? sanitize_text_field( $new_instance['email'] ) : '';
		return $instance;
	}

	public function form( $instance ){
		$defaults = array(
			'title'   => '',
			'logo'    => '',
			'address' => '',
			'phone'   => '',
			'email'   => '',
		);
		$instance = wp_parse_args( (array) $instance, $defaults );

		$fields = array(
			'title'       => array(
				'label'   => esc_html__( 'Title', 'metro-core' ),
				'type'    => 'text',
			),
			'logo'        => array(
				'label'   => esc_html__( 'Logo', 'metro-core' ),
				'type'    => 'image',
			),
			'address' => array(
				'label'   => esc_html__( 'Address', 'metro-core' ),
				'type'    => 'textarea',
			),
			'phone' => array(
				'label'   => esc_html__( 'Phone', 'metro-core' ),
				'type'    => 'text',
			),
			'email' => array(
				'label'   => esc_html__( 'Email', 'metro-core' ),
				'type'    => 'text',
			),
		);

		RT_Widget_Fields::display( $fields, $instance, $this );
	}
}