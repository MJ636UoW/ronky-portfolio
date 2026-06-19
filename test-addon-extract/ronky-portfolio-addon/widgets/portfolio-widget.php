<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Elementor_GenZ_Portfolio_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'genz-portfolio-widget';
	}

	public function get_title() {
		return esc_html__( 'Genz Portfolio Masonry Grid', 'genz-portfolio-addon' );
	}

	public function get_icon() {
		return 'eicon-gallery-grid';
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
		// Category Configuration
		$this->start_controls_section(
			'categories_section',
			[
				'label' => esc_html__( 'Filters Configuration', 'genz-portfolio-addon' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'filter_categories',
			[
				'label' => esc_html__( 'Filter Categories (Comma Separated)', 'genz-portfolio-addon' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => 'all, fashion, editorial, drone, video',
				'description' => esc_html__( 'Enter lowercase filter identifiers. The first element is always active.', 'genz-portfolio-addon' ),
			]
		);

		$this->end_controls_section();

		// Portfolio Items Repeater Section
		$this->start_controls_section(
			'items_section',
			[
				'label' => esc_html__( 'Portfolio Items', 'genz-portfolio-addon' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'item_title',
			[
				'label' => esc_html__( 'Project Title', 'genz-portfolio-addon' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'PROJECT NAME', 'genz-portfolio-addon' ),
			]
		);

		$repeater->add_control(
			'item_category',
			[
				'label' => esc_html__( 'Display Category Name', 'genz-portfolio-addon' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Fashion Shoot', 'genz-portfolio-addon' ),
			]
		);

		$repeater->add_control(
			'item_filter_class',
			[
				'label' => esc_html__( 'Filter Class Tag (from filters above)', 'genz-portfolio-addon' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => 'fashion',
				'description' => esc_html__( 'Match the filter category exactly to filter this item.', 'genz-portfolio-addon' ),
			]
		);

		$repeater->add_control(
			'item_image',
			[
				'label' => esc_html__( 'Project Cover Image', 'genz-portfolio-addon' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
			]
		);

		$repeater->add_control(
			'item_video_link',
			[
				'label' => esc_html__( 'Project Video Loop Link (Optional)', 'genz-portfolio-addon' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'description' => esc_html__( 'Provide direct MP4 URL to autoplay on hover / display in lightbox.', 'genz-portfolio-addon' ),
			]
		);

		$repeater->add_control(
			'item_size',
			[
				'label' => esc_html__( 'Grid Layout Card Size', 'genz-portfolio-addon' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'size-tall' => esc_html__( 'Tall Column (2x4)', 'genz-portfolio-addon' ),
					'size-wide' => esc_html__( 'Wide Column (4x3)', 'genz-portfolio-addon' ),
					'size-medium' => esc_html__( 'Medium Card (2x3)', 'genz-portfolio-addon' ),
				],
				'default' => 'size-medium',
			]
		);

		$this->add_control(
			'portfolio_list',
			[
				'label' => esc_html__( 'Portfolio List', 'genz-portfolio-addon' ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[ 'item_title' => 'CYBER SUITS', 'item_category' => 'Fashion Editorial', 'item_filter_class' => 'fashion', 'item_size' => 'size-tall' ],
					[ 'item_title' => 'NEON RAIN', 'item_category' => 'Creative Portrait', 'item_filter_class' => 'editorial', 'item_size' => 'size-wide' ],
					[ 'item_title' => 'SKATE CHRONICLES', 'item_category' => 'Cinematic Video', 'item_filter_class' => 'video', 'item_size' => 'size-medium', 'item_video_link' => 'https://assets.mixkit.co/videos/preview/mixkit-cinematic-shot-of-a-skater-performing-tricks-43306-large.mp4' ],
				],
				'title_field' => '{{{ item_title }}} - {{{ item_category }}}',
			]
		);

		$this->end_controls_section();

		// Explore More Button Section
		$this->start_controls_section(
			'more_button_section',
			[
				'label' => esc_html__( 'Explore More Button', 'genz-portfolio-addon' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'show_more_btn',
			[
				'label' => esc_html__( 'Show "Explore More" Button', 'genz-portfolio-addon' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'genz-portfolio-addon' ),
				'label_off' => esc_html__( 'Hide', 'genz-portfolio-addon' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'more_btn_text',
			[
				'label' => esc_html__( 'Button Text', 'genz-portfolio-addon' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'EXPLORE ALL WORKS', 'genz-portfolio-addon' ),
				'condition' => [
					'show_more_btn' => 'yes',
				],
			]
		);

		$this->add_control(
			'more_btn_url',
			[
				'label' => esc_html__( 'Button Link URL', 'genz-portfolio-addon' ),
				'type' => \Elementor\Controls_Manager::URL,
				'placeholder' => esc_html__( 'https://your-link.com', 'genz-portfolio-addon' ),
				'default' => [
					'url' => '#',
					'is_external' => false,
					'nofollow' => false,
				],
				'condition' => [
					'show_more_btn' => 'yes',
				],
			]
		);

		$this->end_controls_section();

		// Style Controls Section
		$this->start_controls_section(
			'style_section',
			[
				'label' => esc_html__( 'Hover Reveal Effects', 'genz-portfolio-addon' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'hover_scale',
			[
				'label' => esc_html__( 'Hover Zoom Scale', 'genz-portfolio-addon' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 1.3,
						'step' => 0.02,
					],
				],
				'default' => [
					'size' => 1.08,
				],
				'selectors' => [
					'{{WRAPPER}} .wp-portfolio-item:hover .wp-portfolio-media' => 'transform: scale({{SIZE}});',
				],
			]
		);

		$this->add_control(
			'hover_blur',
			[
				'label' => esc_html__( 'Hover Blur Amount (px)', 'genz-portfolio-addon' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 10,
						'step' => 0.5,
					],
				],
				'default' => [
					'size' => 2,
				],
				'selectors' => [
					'{{WRAPPER}} .wp-portfolio-item:hover .wp-portfolio-media' => 'filter: blur({{SIZE}}px) brightness(0.8);',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		
		// Parse filters
		$filters = array_map( 'trim', explode( ',', $settings['filter_categories'] ) );
		?>
		<section class="wp-portfolio-section">
			<div class="wp-portfolio-header">
				<h2 class="section-title">SELECTED <span class="accent-text">WORKS</span></h2>
				
				<?php if ( ! empty( $filters ) ) : ?>
					<div class="wp-portfolio-filters">
						<?php foreach ( $filters as $idx => $filter ) : 
							$class_active = ( $idx === 0 ) ? 'active' : '';
						?>
							<button class="filter-btn <?php echo esc_attr( $class_active ); ?>" data-filter="<?php echo esc_attr( strtolower( $filter ) ); ?>">
								<?php echo esc_html( ucfirst( $filter ) ); ?>
							</button>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>

			<div class="wp-portfolio-grid">
				<?php foreach ( $settings['portfolio_list'] as $item ) : 
					$video_src = ! empty( $item['item_video_link'] ) ? esc_url( $item['item_video_link'] ) : '';
					$filter_tags = array_map( 'trim', explode( ',', $item['item_filter_class'] ) );
					$filter_class_str = implode( ' ', $filter_tags );
				?>
					<div class="wp-portfolio-item <?php echo esc_attr( $item['item_size'] ); ?> <?php echo esc_attr( $filter_class_str ); ?>"
						data-src="<?php echo esc_url( $item['item_image']['url'] ); ?>"
						data-video-src="<?php echo esc_attr( $video_src ); ?>"
						data-title="<?php echo esc_attr( $item['item_title'] ); ?>"
						data-category="<?php echo esc_attr( $item['item_category'] ); ?>">
						
						<div class="portfolio-media-wrapper">
							<?php if ( ! empty( $video_src ) ) : ?>
								<video src="<?php echo esc_url( $video_src ); ?>" muted loop playsinline class="portfolio-media video-preview"></video>
							<?php else : ?>
								<img src="<?php echo esc_url( $item['item_image']['url'] ); ?>" alt="<?php echo esc_attr( $item['item_title'] ); ?>" class="portfolio-media">
							<?php endif; ?>
							
							<div class="wp-portfolio-overlay">
								<div class="portfolio-info">
									<span class="portfolio-cat"><?php echo esc_html( strtoupper( $item['item_category'] ) ); ?></span>
									<h3 class="portfolio-title"><?php echo esc_html( $item['item_title'] ); ?></h3>
								</div>
								<?php if ( ! empty( $video_src ) ) : ?>
									<span class="portfolio-play-icon">▶</span>
								<?php else : ?>
									<span class="portfolio-arrow">↗</span>
								<?php endif; ?>
							</div>
						</div>

					</div>
			</div>

			<?php if ( 'yes' === $settings['show_more_btn'] ) : 
				$target = $settings['more_btn_url']['is_external'] ? ' target="_blank"' : '';
				$nofollow = $settings['more_btn_url']['nofollow'] ? ' rel="nofollow"' : '';
				$url = ! empty( $settings['more_btn_url']['url'] ) ? $settings['more_btn_url']['url'] : '#';
			?>
				<div class="portfolio-more-container" style="margin-top: 50px; display: flex; justify-content: center; width: 100%;">
					<a href="<?php echo esc_url( $url ); ?>"<?php echo $target . $nofollow; ?> class="btn-primary magnetic" data-magnetic-strength="0.3" style="text-decoration: none;">
						<span><?php echo esc_html( $settings['more_btn_text'] ); ?></span>
					</a>
				</div>
			<?php endif; ?>
		</section>
		<?php
	}
}

