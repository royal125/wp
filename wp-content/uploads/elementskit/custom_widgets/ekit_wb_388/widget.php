<?php

namespace Elementor;

defined('ABSPATH') || exit;

class Ekit_Wb_388 extends Widget_Base {

	public function get_name() {
		return 'ekit_wb_388';
	}


	public function get_title() {
		return esc_html__( 'footer', 'elementskit-lite' );
	}


	public function get_categories() {
		return ['woocommerce-elements'];
	}


	public function get_icon() {
		return 'eicon-cog';
	}


	protected function register_controls() {

		$this->start_controls_section(
			'content_section_388_0',
			array(
				'label' => esc_html__( 'Title', 'elementskit-lite' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'ekit_wb_388_text',
			array(
				'label' => esc_html__( 'Home', 'elementskit-lite' ),
				'type'  => Controls_Manager::TEXT,
				'default' =>  esc_html( 'Some Text' ),
				'show_label' => true,
				'label_block' => false,
				'input_type' => 'text',
			)
		);

		$this->end_controls_section();


		$this->start_controls_section(
			'style_section_388_0',
			array(
				'label' => esc_html__( 'Title', 'elementskit-lite' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'ekit_wb_388_icons',
			array(
				'label' => esc_html__( 'Icons', 'elementskit-lite' ),
				'type'  => Controls_Manager::ICONS,
				'show_label' => true ,
				'label_block' => true ,
				'skin' => 'media' ,
				'default' => array(
					'value' => 'fab fa-vimeo-square',
					'library' => 'fa-brands',
				)
			)
		);

		$this->end_controls_section();

	}


	protected function render() {
	}


}
