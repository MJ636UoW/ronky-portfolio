<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Elementor_GenZ_Hero_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'genz-hero-widget';
	}

	public function get_title() {
		return esc_html__( 'Genz Premium Hero Split', 'genz-portfolio-addon' );
	}

	public function get_icon() {
		return 'eicon-heading';
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
		// Content Controls Section
		$this->start_controls_section(
			'content_section',
			[
				'label' => esc_html__( 'Left Column Content', 'genz-portfolio-addon' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'title',
			[
				'label' => esc_html__( 'Hero Title', 'genz-portfolio-addon' ),
				'type' => \Elementor\Controls_Manager::WYSIWYG,
				'default' => __( 'SHAPING <span class="accent-text">VISIONS</span> INTO ART.', 'genz-portfolio-addon' ),
			]
		);

		$this->add_control(
			'subtitle',
			[
				'label' => esc_html__( 'Hero Subtitle', 'genz-portfolio-addon' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'default' => esc_html__( 'Premium fashion-editorial photographer & drone pilot defining the visual language of 2026.', 'genz-portfolio-addon' ),
			]
		);

		$this->add_control(
			'btn1_text',
			[
				'label' => esc_html__( 'Primary Button Text', 'genz-portfolio-addon' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'VIEW SHOWREEL', 'genz-portfolio-addon' ),
			]
		);

		$this->add_control(
			'btn1_link',
			[
				'label' => esc_html__( 'Primary Button Link', 'genz-portfolio-addon' ),
				'type' => \Elementor\Controls_Manager::URL,
				'placeholder' => esc_html__( 'https://your-link.com', 'genz-portfolio-addon' ),
				'default' => [
					'url' => '#portfolio',
				],
			]
		);

		$this->add_control(
			'btn2_text',
			[
				'label' => esc_html__( 'Secondary Button Text', 'genz-portfolio-addon' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'COLLABORATE', 'genz-portfolio-addon' ),
			]
		);

		$this->add_control(
			'btn2_link',
			[
				'label' => esc_html__( 'Secondary Button Link', 'genz-portfolio-addon' ),
				'type' => \Elementor\Controls_Manager::URL,
				'placeholder' => esc_html__( 'https://your-link.com', 'genz-portfolio-addon' ),
				'default' => [
					'url' => '#contact',
				],
			]
		);

		$this->end_controls_section();

		// Right Column Controls
		$this->start_controls_section(
			'right_column_section',
			[
				'label' => esc_html__( 'Right Cinematic Media', 'genz-portfolio-addon' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'cinematic_image',
			[
				'label' => esc_html__( 'Cinematic Background Image', 'genz-portfolio-addon' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
			]
		);

		$this->add_control(
			'cinematic_video',
			[
				'label' => esc_html__( 'Cinematic Video URL (Self-hosted or external loop)', 'genz-portfolio-addon' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'description' => esc_html__( 'Provide a direct link to an MP4 video to replace the static image background.', 'genz-portfolio-addon' ),
			]
		);

		$this->end_controls_section();

		// Floating Anti-Gravity Tags Controls
		$this->start_controls_section(
			'floating_tags_section',
			[
				'label' => esc_html__( 'Anti-Gravity Elements', 'genz-portfolio-addon' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'enable_floating',
			[
				'label' => esc_html__( 'Enable Anti-Gravity Tags', 'genz-portfolio-addon' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'genz-portfolio-addon' ),
				'label_off' => esc_html__( 'No', 'genz-portfolio-addon' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'floating_speed',
			[
				'label' => esc_html__( 'Movement Speed', 'genz-portfolio-addon' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0.1,
						'max' => 5,
						'step' => 0.1,
					],
				],
				'default' => [
					'size' => 2.0,
				],
			]
		);

		$this->add_control(
			'floating_parallax',
			[
				'label' => esc_html__( 'Mouse Parallax Influence', 'genz-portfolio-addon' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 2,
						'step' => 0.1,
					],
				],
				'default' => [
					'size' => 0.5,
				],
			]
		);

		$this->add_control(
			'floating_distance',
			[
				'label' => esc_html__( 'Movement Range (px)', 'genz-portfolio-addon' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 200,
						'step' => 5,
					],
				],
				'default' => [
					'size' => 50,
				],
			]
		);

		// Repeater for dynamic tags
		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'tag_text',
			[
				'label' => esc_html__( 'Tag Label', 'genz-portfolio-addon' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Creator', 'genz-portfolio-addon' ),
			]
		);

		$repeater->add_control(
			'tag_depth',
			[
				'label' => esc_html__( 'Layer Depth', 'genz-portfolio-addon' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'0.05' => esc_html__( 'Far Background (Slow)', 'genz-portfolio-addon' ),
					'0.10' => esc_html__( 'Medium Depth', 'genz-portfolio-addon' ),
					'0.18' => esc_html__( 'Foreground (Fast)', 'genz-portfolio-addon' ),
				],
				'default' => '0.10',
			]
		);

		$repeater->add_control(
			'tag_speed',
			[
				'label' => esc_html__( 'Orbit Speed multiplier', 'genz-portfolio-addon' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'default' => 1.5,
			]
		);

		$repeater->add_control(
			'tag_direction',
			[
				'label' => esc_html__( 'Orbit Direction', 'genz-portfolio-addon' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'1' => esc_html__( 'Clockwise', 'genz-portfolio-addon' ),
					'-1' => esc_html__( 'Counter-Clockwise', 'genz-portfolio-addon' ),
				],
				'default' => '1',
			]
		);

		$this->add_control(
			'tags_list',
			[
				'label' => esc_html__( 'Floating Tags List', 'genz-portfolio-addon' ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[ 'tag_text' => 'Photographer', 'tag_depth' => '0.05', 'tag_speed' => 1.2, 'tag_direction' => '1' ],
					[ 'tag_text' => 'Videographer', 'tag_depth' => '0.18', 'tag_speed' => 1.8, 'tag_direction' => '-1' ],
					[ 'tag_text' => 'Drone Pilot', 'tag_depth' => '0.05', 'tag_speed' => 0.8, 'tag_direction' => '1' ],
					[ 'tag_text' => 'Storyteller', 'tag_depth' => '0.18', 'tag_speed' => 2.2, 'tag_direction' => '-1' ],
					[ 'tag_text' => 'Creative Director', 'tag_depth' => '0.10', 'tag_speed' => 1.5, 'tag_direction' => '1' ],
				],
				'title_field' => '{{{ tag_text }}}',
			]
		);

		$this->end_controls_section();

		// Styles Panel
		$this->start_controls_section(
			'style_section',
			[
				'label' => esc_html__( 'Hero Styling', 'genz-portfolio-addon' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'accent_color',
			[
				'label' => esc_html__( 'Hero Accent Color', 'genz-portfolio-addon' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#B8FF00',
				'selectors' => [
					'{{WRAPPER}} .accent-text' => 'color: {{VALUE}};',
					'{{WRAPPER}} .wp-floating-tag:hover span' => 'border-color: {{VALUE}}; color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		
		$config = [
			'enable' => $settings['enable_floating'],
			'speed' => $settings['floating_speed']['size'],
			'parallax' => $settings['floating_parallax']['size'],
			'distance' => $settings['floating_distance']['size']
		];
		?>
		<section class="wp-hero-section">
			
			<div class="wp-hero-split-left">
				<div class="wp-hero-content-wrapper">
					<h1 class="wp-hero-title"><?php echo wp_kses_post( htmlspecialchars_decode( $settings['title'] ) ); ?></h1>
					<?php if ( ! empty( $settings['subtitle'] ) ) : ?>
						<p class="wp-hero-subtitle"><?php echo esc_html( $settings['subtitle'] ); ?></p>
					<?php endif; ?>
					<div class="wp-hero-actions">
						<?php if ( ! empty( $settings['btn1_text'] ) && ! empty( $settings['btn1_link']['url'] ) ) : ?>
							<a href="<?php echo esc_url( $settings['btn1_link']['url'] ); ?>" class="btn-primary magnetic" data-magnetic-strength="0.3">
								<span><?php echo esc_html( $settings['btn1_text'] ); ?></span>
							</a>
						<?php endif; ?>
						<?php if ( ! empty( $settings['btn2_text'] ) && ! empty( $settings['btn2_link']['url'] ) ) : ?>
							<a href="<?php echo esc_url( $settings['btn2_link']['url'] ); ?>" class="btn-secondary magnetic" data-magnetic-strength="0.3">
								<span><?php echo esc_html( $settings['btn2_text'] ); ?></span>
							</a>
						<?php endif; ?>
					</div>
				</div>
			</div>

			<div class="wp-hero-split-right">
				<div class="wp-diagonal-mask">
					<div class="wp-cinematic-bg-wrapper">
						<?php if ( ! empty( $settings['cinematic_video'] ) ) : ?>
							<video src="<?php echo esc_url( $settings['cinematic_video'] ); ?>" autoplay muted loop playsinline class="wp-cinematic-bg-media"></video>
						<?php else : ?>
							<img src="<?php echo esc_url( $settings['cinematic_image']['url'] ); ?>" alt="Cinematic Visual" class="wp-cinematic-bg-media">
						<?php endif; ?>
						<div class="wp-cinematic-overlay"></div>
					</div>
				</div>
			</div>

			<!-- Dynamic Floating Tags markup -->
			<div class="wp-floating-tags-container" data-config="<?php echo esc_attr( wp_json_encode( $config ) ); ?>">
				<?php foreach ( $settings['tags_list'] as $index => $item ) : 
					$depth_class = 'depth-2';
					if ( $item['tag_depth'] == '0.05' ) $depth_class = 'depth-1';
					if ( $item['tag_depth'] == '0.18' ) $depth_class = 'depth-3';
				?>
					<div class="wp-floating-tag <?php echo esc_attr( $depth_class ); ?>" 
						data-speed="<?php echo esc_attr( $item['tag_speed'] ); ?>" 
						data-depth="<?php echo esc_attr( $item['tag_depth'] ); ?>" 
						data-dir="<?php echo esc_attr( $item['tag_direction'] ); ?>">
						<span><?php echo esc_html( $item['tag_text'] ); ?></span>
					</div>
				<?php endforeach; ?>
			</div>

		</section>
		<?php
	}
}

