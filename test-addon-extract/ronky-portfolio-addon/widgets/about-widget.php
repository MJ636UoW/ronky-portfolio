<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Elementor_GenZ_About_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'genz-about-widget';
	}

	public function get_title() {
		return esc_html__( 'Genz About & Counters', 'genz-portfolio-addon' );
	}

	public function get_icon() {
		return 'eicon-person';
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
		// Section Content
		$this->start_controls_section(
			'content_section',
			[
				'label' => esc_html__( 'About Profile Content', 'genz-portfolio-addon' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'profile_image',
			[
				'label' => esc_html__( 'Profile Image', 'genz-portfolio-addon' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
			]
		);

		$this->add_control(
			'title',
			[
				'label' => esc_html__( 'About Headline', 'genz-portfolio-addon' ),
				'type' => \Elementor\Controls_Manager::WYSIWYG,
				'default' => __( 'THE MIND BEHIND <span class="accent-text">THE LENS</span>', 'genz-portfolio-addon' ),
			]
		);

		$this->add_control(
			'story_text',
			[
				'label' => esc_html__( 'Story Description', 'genz-portfolio-addon' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'default' => esc_html__( 'I capture raw, electric moments sitting at the intersection of streetwear subculture, dynamic sports, and high-fashion editorial. By blending high-speed drone visuals with cinematic grain and glass refraction techniques, I create visual poetry that leaves a trace.', 'genz-portfolio-addon' ),
			]
		);

		$this->end_controls_section();

		// Badge Config
		$this->start_controls_section(
			'badge_section',
			[
				'label' => esc_html__( 'Spinning Circular Badge', 'genz-portfolio-addon' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'enable_badge',
			[
				'label' => esc_html__( 'Enable Badge', 'genz-portfolio-addon' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'genz-portfolio-addon' ),
				'label_off' => esc_html__( 'No', 'genz-portfolio-addon' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'badge_text',
			[
				'label' => esc_html__( 'Circular Badge Text', 'genz-portfolio-addon' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'CREATIVE VISION • BEYOND THE LENS • EST. 2024 •', 'genz-portfolio-addon' ),
			]
		);

		$this->add_control(
			'badge_speed',
			[
				'label' => esc_html__( 'Rotation VelocityMultiplier', 'genz-portfolio-addon' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 30,
						'step' => 1,
					],
				],
				'default' => [
					'size' => 10,
				],
			]
		);

		$this->end_controls_section();

		// Stats Counters Section
		$this->start_controls_section(
			'stats_section',
			[
				'label' => esc_html__( 'Statistics Counters', 'genz-portfolio-addon' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'stat_num',
			[
				'label' => esc_html__( 'Stat Number Target', 'genz-portfolio-addon' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'default' => 100,
			]
		);

		$repeater->add_control(
			'stat_suffix',
			[
				'label' => esc_html__( 'Stat Suffix (e.g. +, %)', 'genz-portfolio-addon' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '+',
			]
		);

		$repeater->add_control(
			'stat_label',
			[
				'label' => esc_html__( 'Stat Description Label', 'genz-portfolio-addon' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Projects Completed', 'genz-portfolio-addon' ),
			]
		);

		$this->add_control(
			'stats_list',
			[
				'label' => esc_html__( 'Counters List', 'genz-portfolio-addon' ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[ 'stat_num' => 150, 'stat_suffix' => '+', 'stat_label' => 'Projects Completed' ],
					[ 'stat_num' => 98, 'stat_suffix' => '%', 'stat_label' => 'Happy Clients' ],
					[ 'stat_num' => 12, 'stat_suffix' => '+', 'stat_label' => 'Countries Served' ],
					[ 'stat_num' => 5, 'stat_suffix' => '+', 'stat_label' => 'Years Experience' ],
				],
				'title_field' => '{{{ stat_num }}}{{{ stat_suffix }}} - {{{ stat_label }}}',
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		?>
		<section class="wp-about-section">
			<div class="wp-about-grid">
				
				<div class="wp-about-visual">
					<div class="wp-profile-card">
						<img src="<?php echo esc_url( $settings['profile_image']['url'] ); ?>" alt="Profile Media" class="wp-profile-img">
					</div>
					
					<?php if ( $settings['enable_badge'] === 'yes' ) : ?>
						<div class="wp-rotating-badge-wrapper" data-speed="<?php echo esc_attr( $settings['badge_speed']['size'] ); ?>">
							<svg viewBox="0 0 100 100" class="wp-rotating-badge-svg">
								<path id="wpCirclePath" d="M 50, 50 m -37, 0 a 37,37 0 1,1 74,0 a 37,37 0 1,1 -74,0" fill="none" />
								<text class="wp-rotating-badge-text">
									<textPath href="#wpCirclePath" startOffset="0%">
										<?php echo esc_html( $settings['badge_text'] ); ?>
									</textPath>
								</text>
							</svg>
							<div class="wp-badge-dot"></div>
						</div>
					<?php endif; ?>
				</div>

				<div class="wp-about-content">
					<h2 class="section-title"><?php echo wp_kses_post( htmlspecialchars_decode( $settings['title'] ) ); ?></h2>
					<p class="about-text"><?php echo esc_html( $settings['story_text'] ); ?></p>
					
					<div class="wp-stats-grid">
						<?php foreach ( $settings['stats_list'] as $item ) : ?>
							<div class="wp-stat-box glass-card" data-stat-target="<?php echo esc_attr( $item['stat_num'] ); ?>">
								<div class="stat-number"><span class="wp-counter">0</span><?php echo esc_html( $item['stat_suffix'] ); ?></div>
								<div class="stat-label"><?php echo esc_html( $item['stat_label'] ); ?></div>
							</div>
						<?php endforeach; ?>
					</div>
				</div>

			</div>
		</section>
		<?php
	}
}

