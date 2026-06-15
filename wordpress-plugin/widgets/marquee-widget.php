<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Elementor_GenZ_Marquee_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'genz-marquee-widget';
	}

	public function get_title() {
		return esc_html__( 'Genz Infinite Ticker Marquee', 'genz-portfolio-addon' );
	}

	public function get_icon() {
		return 'eicon-marquee';
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
		// Content settings
		$this->start_controls_section(
			'content_section',
			[
				'label' => esc_html__( 'Marquee Text Content', 'genz-portfolio-addon' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'text_val',
			[
				'label' => esc_html__( 'Text String', 'genz-portfolio-addon' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'CREATIVE STORYTELLER', 'genz-portfolio-addon' ),
			]
		);

		$this->add_control(
			'text_list',
			[
				'label' => esc_html__( 'Ticker Text Strings', 'genz-portfolio-addon' ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[ 'text_val' => 'AVAILABLE WORLDWIDE' ],
					[ 'text_val' => 'BOOKINGS OPEN' ],
					[ 'text_val' => 'CREATIVE STORYTELLER' ],
					[ 'text_val' => 'CINEMATIC VIDEOGRAPHY' ]
				],
				'title_field' => '{{{ text_val }}}',
			]
		);

		$this->end_controls_section();

		// Ticker settings
		$this->start_controls_section(
			'ticker_section',
			[
				'label' => esc_html__( 'Ticker Parameters', 'genz-portfolio-addon' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'speed_duration',
			[
				'label' => esc_html__( 'Scrolling Velocity (Duration in Secs)', 'genz-portfolio-addon' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 5,
						'max' => 50,
						'step' => 1,
					],
				],
				'default' => [
					'size' => 20,
				],
			]
		);

		$this->add_control(
			'direction_reverse',
			[
				'label' => esc_html__( 'Reverse Direction (Right to Left)', 'genz-portfolio-addon' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Reverse', 'genz-portfolio-addon' ),
				'label_off' => esc_html__( 'Normal', 'genz-portfolio-addon' ),
				'return_value' => 'yes',
				'default' => 'no',
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		
		$reverse_class = ( $settings['direction_reverse'] === 'yes' ) ? 'wp-marquee-reverse' : '';
		
		// Build strings twice to ensure continuous looping
		$items = $settings['text_list'];
		$loop_items = array_merge( $items, $items );
		?>
		<div class="wp-marquee-wrap <?php echo esc_attr( $reverse_class ); ?>" data-speed="<?php echo esc_attr( $settings['speed_duration']['size'] ); ?>">
			<div class="wp-marquee-container">
				<div class="wp-marquee-track">
					<?php foreach ( $loop_items as $index => $item ) : ?>
						<span><?php echo esc_html( $item['text_val'] ); ?></span>
						<span class="dot">★</span>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
		<?php
	}
}
