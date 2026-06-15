<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Elementor_GenZ_Testimonials_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'genz-testimonials-widget';
	}

	public function get_title() {
		return esc_html__( 'Genz Testimonial Slider', 'genz-portfolio-addon' );
	}

	public function get_icon() {
		return 'eicon-testimonial-carousel';
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
		// Content repeater
		$this->start_controls_section(
			'content_section',
			[
				'label' => esc_html__( 'Client Testimonials', 'genz-portfolio-addon' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'client_quote',
			[
				'label' => esc_html__( 'Testimonial Quote', 'genz-portfolio-addon' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'default' => esc_html__( '"Kai completely redefined our streetwear brand\'s campaign. The custom website integrations are incredible."', 'genz-portfolio-addon' ),
			]
		);

		$repeater->add_control(
			'client_name',
			[
				'label' => esc_html__( 'Client Name', 'genz-portfolio-addon' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Amara Vance', 'genz-portfolio-addon' ),
			]
		);

		$repeater->add_control(
			'client_role',
			[
				'label' => esc_html__( 'Client Subtitle/Role', 'genz-portfolio-addon' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Founder, NEO-GRID Tokyo', 'genz-portfolio-addon' ),
			]
		);

		$repeater->add_control(
			'client_avatar',
			[
				'label' => esc_html__( 'Client Avatar Photo', 'genz-portfolio-addon' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
			]
		);

		$this->add_control(
			'testimonials_list',
			[
				'label' => esc_html__( 'Testimonials Slider List', 'genz-portfolio-addon' ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[ 'client_name' => 'Amara Vance', 'client_role' => 'Founder, NEO-GRID Tokyo', 'client_quote' => '"Kai completely redefined our streetwear brand\'s campaign. The floating drone shots combined with acid green color grading gave us a distinct edge on our website. Absolutely next level."' ],
					[ 'client_name' => 'Jaden Miller', 'client_role' => 'Director, SATELLITE Records', 'client_quote' => '"Working with Kai was seamless. The edits match exactly what modern Gen-Z visual design looks like: punchy, authentic, and cinematic."' ]
				],
				'title_field' => '{{{ client_name }}} - {{{ client_role }}}',
			]
		);

		$this->end_controls_section();

		// Slider Config
		$this->start_controls_section(
			'slider_section',
			[
				'label' => esc_html__( 'Slider Settings', 'genz-portfolio-addon' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'transition_speed',
			[
				'label' => esc_html__( 'Transition Duration (ms)', 'genz-portfolio-addon' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 200,
						'max' => 2000,
						'step' => 100,
					],
				],
				'default' => [
					'size' => 600,
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		?>
		<div class="wp-testimonials-container" data-transition="<?php echo esc_attr( $settings['transition_speed']['size'] ); ?>">
			
			<div class="wp-testimonials-slider">
				<?php foreach ( $settings['testimonials_list'] as $item ) : ?>
					<div class="wp-testimonial-slide glass-card">
						<div class="testimonial-quote"><?php echo esc_html( $item['client_quote'] ); ?></div>
						<div class="testimonial-client">
							<div class="client-avatar">
								<img src="<?php echo esc_url( $item['client_avatar']['url'] ); ?>" alt="<?php echo esc_attr( $item['client_name'] ); ?>">
							</div>
							<div class="client-info">
								<div class="client-name"><?php echo esc_html( $item['client_name'] ); ?></div>
								<div class="client-role"><?php echo esc_html( $item['client_role'] ); ?></div>
							</div>
						</div>
					</div>
				<?php endforeach; ?>
			</div>

			<div class="slider-nav">
				<button class="slider-btn prev-btn magnetic" aria-label="Prev">←</button>
				<button class="slider-btn next-btn magnetic" aria-label="Next">→</button>
			</div>

		</div>
		<?php
	}
}
