<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Elementor_GenZ_Cursor_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'genz-cursor-widget';
	}

	public function get_title() {
		return esc_html__( 'Genz Custom Magnetic Cursor', 'genz-portfolio-addon' );
	}

	public function get_icon() {
		return 'eicon-mouse-pointer';
	}

	public function get_categories() {
		return [ 'genz-portfolio' ];
	}

	public function get_script_depends() {
		return [ 'ronky-portfolio-scripts' ];
	}

	public function get_style_depends() {
		return [ 'ronky-portfolio-styles' ];
	}

	protected function register_controls() {
		$this->start_controls_section(
			'content_section',
			[
				'label' => esc_html__( 'Cursor Configuration', 'genz-portfolio-addon' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'enable_cursor',
			[
				'label' => esc_html__( 'Enable Custom Cursor', 'genz-portfolio-addon' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'genz-portfolio-addon' ),
				'label_off' => esc_html__( 'Hide', 'genz-portfolio-addon' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'cursor_color',
			[
				'label' => esc_html__( 'Cursor Accent Color', 'genz-portfolio-addon' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#B8FF00',
			]
		);

		$this->add_control(
			'cursor_size',
			[
				'label' => esc_html__( 'Cursor Dot Size (px)', 'genz-portfolio-addon' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 4,
						'max' => 20,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 8,
				],
			]
		);

		$this->add_control(
			'cursor_ring_size',
			[
				'label' => esc_html__( 'Cursor Ring Size (px)', 'genz-portfolio-addon' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 20,
						'max' => 90,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 40,
				],
			]
		);

		$this->add_control(
			'cursor_glow',
			[
				'label' => esc_html__( 'Cursor Glow Intensity', 'genz-portfolio-addon' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1,
						'step' => 0.05,
					],
				],
				'default' => [
					'size' => 0.6,
				],
			]
		);

		$this->add_control(
			'magnetic_strength',
			[
				'label' => esc_html__( 'Magnetic StrengthMultiplier', 'genz-portfolio-addon' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0.1,
						'max' => 2,
						'step' => 0.1,
					],
				],
				'default' => [
					'size' => 0.5,
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		?>
		<div class="wp-cursor-config" 
			data-enable="<?php echo esc_attr( $settings['enable_cursor'] ); ?>"
			data-size="<?php echo esc_attr( $settings['cursor_size']['size'] ); ?>"
			data-ring-size="<?php echo esc_attr( $settings['cursor_ring_size']['size'] ); ?>"
			data-glow="<?php echo esc_attr( $settings['cursor_glow']['size'] ); ?>"
			data-color="<?php echo esc_attr( $settings['cursor_color'] ); ?>"
			data-magnetic="<?php echo esc_attr( $settings['magnetic_strength']['size'] ); ?>">
			<!-- Genz Magnetic Cursor configuration loaded -->
		</div>
		<?php
	}
}

