<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Elementor_GenZ_Background_Effects_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'genz-background-effects';
	}

	public function get_title() {
		return esc_html__( 'Genz Background Engine', 'genz-portfolio-addon' );
	}

	public function get_icon() {
		return 'eicon-background';
	}

	public function get_categories() {
		return [ 'genz-portfolio' ];
	}

	public function get_script_depends() {
		return [ 'genz-portfolio-scripts' ];
	}

	public function get_style_depends() {
		return [ 'genz-portfolio-styles' ];
	}

	protected function register_controls() {
		// Toggles section
		$this->start_controls_section(
			'content_section',
			[
				'label' => esc_html__( 'Engine Layers', 'genz-portfolio-addon' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'noise_overlay',
			[
				'label' => esc_html__( 'Enable Noise Overlay', 'genz-portfolio-addon' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'genz-portfolio-addon' ),
				'label_off' => esc_html__( 'No', 'genz-portfolio-addon' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'grid_overlay',
			[
				'label' => esc_html__( 'Enable Cyber Grid Overlay', 'genz-portfolio-addon' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'genz-portfolio-addon' ),
				'label_off' => esc_html__( 'No', 'genz-portfolio-addon' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'aurora_mesh',
			[
				'label' => esc_html__( 'Enable Aurora Gradient Glow', 'genz-portfolio-addon' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'genz-portfolio-addon' ),
				'label_off' => esc_html__( 'No', 'genz-portfolio-addon' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'particles',
			[
				'label' => esc_html__( 'Enable Anti-Gravity Particles', 'genz-portfolio-addon' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'genz-portfolio-addon' ),
				'label_off' => esc_html__( 'No', 'genz-portfolio-addon' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'blobs',
			[
				'label' => esc_html__( 'Enable Vector Blobs', 'genz-portfolio-addon' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'genz-portfolio-addon' ),
				'label_off' => esc_html__( 'No', 'genz-portfolio-addon' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->end_controls_section();

		// Customizer values
		$this->start_controls_section(
			'config_section',
			[
				'label' => esc_html__( 'Engine Customizations', 'genz-portfolio-addon' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'glow_color',
			[
				'label' => esc_html__( 'Effects Primary Glow Color', 'genz-portfolio-addon' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#B8FF00',
			]
		);

		$this->add_control(
			'speed_scale',
			[
				'label' => esc_html__( 'Animations Rate/Speed multiplier', 'genz-portfolio-addon' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0.5,
						'max' => 8,
						'step' => 0.5,
					],
				],
				'default' => [
					'size' => 3.0,
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		?>
		<div class="wp-bg-canvas-wrapper wp-section-bg-canvas"
			data-noise="<?php echo esc_attr( $settings['noise_overlay'] ); ?>"
			data-grid="<?php echo esc_attr( $settings['grid_overlay'] ); ?>"
			data-aurora="<?php echo esc_attr( $settings['aurora_mesh'] ); ?>"
			data-particles="<?php echo esc_attr( $settings['particles'] ); ?>"
			data-blobs="<?php echo esc_attr( $settings['blobs'] ); ?>"
			data-speed="<?php echo esc_attr( $settings['speed_scale']['size'] ); ?>"
			data-color="<?php echo esc_attr( $settings['glow_color'] ); ?>">
			
			<canvas class="wp-section-canvas-el" style="position:absolute; top:0; left:0; width:100%; height:100%; pointer-events:none;"></canvas>
			
			<?php if ( $settings['noise_overlay'] === 'yes' ) : ?>
				<div class="wp-noise-overlay"></div>
			<?php endif; ?>

			<?php if ( $settings['grid_overlay'] === 'yes' ) : ?>
				<div class="wp-grid-overlay"></div>
			<?php endif; ?>

		</div>
		<?php
	}
}
