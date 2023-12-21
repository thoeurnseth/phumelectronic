<?php
/**
 * This file can be overridden by copying it to yourtheme/elementor-custom/text-with-button/class.php
 * 
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\Metro_Core;

use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit;

class Banner_With_Link extends Custom_Widget_Base {

	public function __construct( $data = [], $args = null ){
		$this->rt_name = __( 'Banner With Link', 'metro-core' );
		$this->rt_base = 'rt-banner-with-link';
		parent::__construct( $data, $args );
	}

	public function rt_fields(){
		$fields = array(
			array(
				'mode'    => 'section_start',
				'id'      => 'sec_general',
				'label'   => __( 'General', 'metro-core' ),
			),
			array(
				'type'    => \Elementor\Group_Control_Background::get_type(),
				'mode'    => 'group',
				'types'   => array( 'classic' ),
				'id'      => 'background',
				'label'   => __( 'Background', 'classima-core' ),
				'selector' => '{{WRAPPER}} .rt-el-banner-with-link .rtin-item',
			),
			array(
				'type' => Controls_Manager::SLIDER,
				'mode' => 'responsive',
				'id'   => 'height',
				'size_units' => array( 'px' ),
				'range' => array(
					'px' => array(
						'min' => 0,
						'max' => 1000,
					),
				),
				'default' => array(
					'unit' => 'px',
					'size' => 235,
				),
				'label'   => __( 'Height', 'metro-core' ),
				'selectors' => array(
					'{{WRAPPER}} .rt-el-banner-with-link .rtin-item' => 'height: {{SIZE}}{{UNIT}};',
				)
			),
			array(
				'type'  => Controls_Manager::URL,
				'id'    => 'url',
				'label' => __( 'Link', 'metro-core' ),
				'placeholder' => 'https://your-link.com',
			),
			array(
				'type'    => Controls_Manager::TEXT,
				'id'      => 'linktext',
				'label'   => __( 'Link Text', 'metro-core' ),
				'default' => '@loremipsum',
			),
			array(
				'mode' => 'section_end',
			),
		);
		return $fields;
	}

	protected function render() {
		$data = $this->get_settings();

		$template = 'view';

		return $this->rt_template( $template, $data );
	}
}