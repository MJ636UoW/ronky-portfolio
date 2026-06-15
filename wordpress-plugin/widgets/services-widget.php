<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Elementor_GenZ_Services_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'genz-services-widget';
	}

	public function get_title() {
		return esc_html__( 'Genz Services Upward Flood', 'genz-portfolio-addon' );
	}

	public function get_icon() {
		return 'eicon-bullet-list';
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
		// Content Repeater Section
		$this->start_controls_section(
			'content_section',
			[
				'label' => esc_html__( 'Services List', 'genz-portfolio-addon' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'service_title',
			[
				'label' => esc_html__( 'Service Title', 'genz-portfolio-addon' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'PHOTOGRAPHY', 'genz-portfolio-addon' ),
			]
		);

		$repeater->add_control(
			'service_num',
			[
				'label' => esc_html__( 'Display Number', 'genz-portfolio-addon' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( '01', 'genz-portfolio-addon' ),
			]
		);

		$repeater->add_control(
			'service_desc',
			[
				'label' => esc_html__( 'Service Description', 'genz-portfolio-addon' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'default' => esc_html__( 'High-fashion editorial, street culture portraits, and campaign photography with high-contrast styles.', 'genz-portfolio-addon' ),
			]
		);

		$repeater->add_control(
			'service_icon_svg',
			[
				'label' => esc_html__( 'Custom Inline SVG Icon Code', 'genz-portfolio-addon' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'rows' => 4,
				'default' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>',
			]
		);

		$this->add_control(
			'services_list',
			[
				'label' => esc_html__( 'Services Card List', 'genz-portfolio-addon' ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[ 
						'service_title' => 'PHOTOGRAPHY', 
						'service_num' => '01', 
						'service_desc' => 'High-fashion editorial, street culture portraits, and campaign photography with high-contrast styles.',
						'service_icon_svg' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>'
					],
					[ 
						'service_title' => 'VIDEOGRAPHY', 
						'service_num' => '02', 
						'service_desc' => 'Creative commercials, visual campaign reels, and high-energy music videos containing rapid edits and transition styles.',
						'service_icon_svg' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><polygon points="23 7 16 12 23 17 23 7"/><rect x="1" y="5" width="15" height="14" rx="2" ry="2"/></svg>'
					],
					[ 
						'service_title' => 'DRONE SHOOTS', 
						'service_num' => '03', 
						'service_desc' => 'FPV acrobatics and high-altitude architectural views to add gravity-defying depth and scale to cinematic sequences.',
						'service_icon_svg' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M2 17h20M2 12h20M2 7h20"/><path d="M6 3v18M18 3v18"/></svg>'
					]
				],
				'title_field' => '{{{ service_num }}}. {{{ service_title }}}',
			]
		);

		$this->end_controls_section();

		// Flood Configuration
		$this->start_controls_section(
			'flood_section',
			[
				'label' => esc_html__( 'Flood Vector Settings', 'genz-portfolio-addon' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'flood_color',
			[
				'label' => esc_html__( 'Flood Accent Color', 'genz-portfolio-addon' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#B8FF00',
			]
		);

		$this->add_control(
			'flood_direction',
			[
				'label' => esc_html__( 'Flood Fill Direction', 'genz-portfolio-addon' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'up' => esc_html__( 'Bottom to Top', 'genz-portfolio-addon' ),
					'down' => esc_html__( 'Top to Bottom', 'genz-portfolio-addon' ),
					'right' => esc_html__( 'Left to Right', 'genz-portfolio-addon' ),
					'left' => esc_html__( 'Right to Left', 'genz-portfolio-addon' ),
				],
				'default' => 'up',
			]
		);

		$this->add_control(
			'flood_speed',
			[
				'label' => esc_html__( 'Hover Transition Speed (ms)', 'genz-portfolio-addon' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 100,
						'max' => 1000,
						'step' => 50,
					],
				],
				'default' => [
					'size' => 400,
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		?>
		<div class="wp-services-grid" 
			data-speed="<?php echo esc_attr( $settings['flood_speed']['size'] ); ?>"
			data-direction="<?php echo esc_attr( $settings['flood_direction'] ); ?>"
			data-flood-color="<?php echo esc_attr( $settings['flood_color'] ); ?>">
			
			<?php foreach ( $settings['services_list'] as $item ) : ?>
				<div class="wp-service-card">
					<div class="wp-service-flood-bg"></div>
					<div class="wp-service-content">
						<div class="service-header">
							<span class="service-num"><?php echo esc_html( $item['service_num'] ); ?></span>
							<div class="service-icon">
								<?php echo wp_kses( $item['service_icon_svg'], [
									'svg' => [
										'viewbox' => true,
										'fill' => true,
										'stroke' => true,
										'stroke-width' => true,
									],
									'path' => [
										'd' => true,
										'fill' => true,
										'stroke' => true,
									],
									'circle' => [
										'cx' => true,
										'cy' => true,
										'r' => true,
									],
									'polygon' => [
										'points' => true,
									],
									'rect' => [
										'x' => true,
										'y' => true,
										'width' => true,
										'height' => true,
										'rx' => true,
										'ry' => true,
									]
								] ); ?>
							</div>
						</div>
						<h3 class="service-title"><?php echo esc_html( $item['service_title'] ); ?></h3>
						<p class="service-desc"><?php echo esc_html( $item['service_desc'] ); ?></p>
						<span class="service-link">MORE DETAILS <span>→</span></span>
					</div>
				</div>
			<?php endforeach; ?>

		</div>
		<?php
	}
}
