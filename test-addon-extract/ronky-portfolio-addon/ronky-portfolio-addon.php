<?php
/**
 * Plugin Name: Ronky Portfolio Elementor Addon
 * Description: Registers premium, high-interaction custom widgets for a photographer & videographer portfolio (Hero, Magnetic Cursor, About with Counters, Portfolio Masonry Grid, Services upward flood, Testimonial sliders, and Marquees) with full Elementor controls.
 * Version: 1.0.0
 * Author: Ronky Edits
 * Text Domain: ronky-portfolio-addon
 * Requires at least: 5.8
 * Requires PHP: 7.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Main Gen-Z Portfolio Addon Class
 */
final class Ronky_Portfolio_Addon {

	const VERSION = '1.0.0';
	const MINIMUM_ELEMENTOR_VERSION = '3.0.0';
	const MINIMUM_PHP_VERSION = '7.4';

	private static $_instance = null;

	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	public function __construct() {
		add_action( 'plugins_loaded', [ $this, 'init' ] );
	}

	public function init() {
		// Check if Elementor is installed and activated
		if ( ! did_action( 'elementor/loaded' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_missing_elementor' ] );
			return;
		}

		// Check Elementor version
		if ( ! version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_minimum_elementor_version' ] );
			return;
		}

		// Check PHP version
		if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_minimum_php_version' ] );
			return;
		}

		// Register categories, widgets, styles and scripts
		add_action( 'elementor/elements/categories_registered', [ $this, 'register_widget_categories' ] );
		add_action( 'elementor/widgets/register', [ $this, 'register_widgets' ] );
		add_action( 'elementor/frontend/after_register_styles', [ $this, 'register_frontend_styles' ] );
		add_action( 'elementor/frontend/after_register_scripts', [ $this, 'register_frontend_scripts' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_custom_fonts' ] );
		add_action( 'init', [ $this, 'register_submission_cpt' ] );
	}

	public function admin_notice_missing_elementor() {
		if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );
		$message = sprintf(
			esc_html__( '"%1$s" requires "%2$s" to be active.', 'genz-portfolio-addon' ),
			'<strong>' . esc_html__( 'Gen-Z Luxury Portfolio Addon', 'genz-portfolio-addon' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', 'genz-portfolio-addon' ) . '</strong>'
		);
		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
	}

	public function admin_notice_minimum_elementor_version() {
		if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );
		$message = sprintf(
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'genz-portfolio-addon' ),
			'<strong>' . esc_html__( 'Gen-Z Luxury Portfolio Addon', 'genz-portfolio-addon' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', 'genz-portfolio-addon' ) . '</strong>',
			self::MINIMUM_ELEMENTOR_VERSION
		);
		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
	}

	public function admin_notice_minimum_php_version() {
		if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );
		$message = sprintf(
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'genz-portfolio-addon' ),
			'<strong>' . esc_html__( 'Gen-Z Luxury Portfolio Addon', 'genz-portfolio-addon' ) . '</strong>',
			'<strong>' . esc_html__( 'PHP', 'genz-portfolio-addon' ) . '</strong>',
			self::MINIMUM_PHP_VERSION
		);
		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
	}

	public function register_widget_categories( $elements_manager ) {
		$elements_manager->add_category(
			'genz-portfolio',
			[
				'title' => esc_html__( 'Gen-Z Studio Widgets', 'genz-portfolio-addon' ),
				'icon' => 'fa fa-camera-retro',
			]
		);
	}

	public function register_widgets( $widgets_manager ) {
		require_once( __DIR__ . '/widgets/cursor-widget.php' );
		require_once( __DIR__ . '/widgets/hero-widget.php' );
		require_once( __DIR__ . '/widgets/about-widget.php' );
		require_once( __DIR__ . '/widgets/portfolio-widget.php' );
		require_once( __DIR__ . '/widgets/services-widget.php' );
		require_once( __DIR__ . '/widgets/testimonials-widget.php' );
		require_once( __DIR__ . '/widgets/marquee-widget.php' );
		require_once( __DIR__ . '/widgets/contact-widget.php' );
		require_once( __DIR__ . '/widgets/background-effects.php' );

		$widgets_manager->register( new \Elementor_GenZ_Cursor_Widget() );
		$widgets_manager->register( new \Elementor_GenZ_Hero_Widget() );
		$widgets_manager->register( new \Elementor_GenZ_About_Widget() );
		$widgets_manager->register( new \Elementor_GenZ_Portfolio_Widget() );
		$widgets_manager->register( new \Elementor_GenZ_Services_Widget() );
		$widgets_manager->register( new \Elementor_GenZ_Testimonials_Widget() );
		$widgets_manager->register( new \Elementor_GenZ_Marquee_Widget() );
		$widgets_manager->register( new \Elementor_GenZ_Contact_Widget() );
		$widgets_manager->register( new \Elementor_GenZ_Background_Effects_Widget() );
	}

	public function register_frontend_styles() {
		$css_file = plugin_dir_path( __FILE__ ) . 'assets/css/ronky-portfolio.css';
		$ver = file_exists( $css_file ) ? filemtime( $css_file ) : self::VERSION;
		wp_register_style(
			'ronky-portfolio-styles',
			plugins_url( '/assets/css/ronky-portfolio.css', __FILE__ ),
			[],
			$ver
		);
	}

	public function register_frontend_scripts() {
		$js_file = plugin_dir_path( __FILE__ ) . 'assets/js/ronky-portfolio.js';
		$ver = file_exists( $js_file ) ? filemtime( $js_file ) : self::VERSION;
		wp_register_script(
			'ronky-portfolio-scripts',
			plugins_url( '/assets/js/ronky-portfolio.js', __FILE__ ),
			[ 'jquery' ],
			$ver,
			true
		);
	}

	public function enqueue_custom_fonts() {
		wp_enqueue_style(
			'genz-portfolio-google-fonts',
			'https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&family=Syne:wght@700;800&display=swap',
			[],
			self::VERSION
		);
	}

	public function register_submission_cpt() {
		if ( post_type_exists( 'ronky_submission' ) ) {
			return;
		}

		$labels = [
			'name'               => _x( 'Submissions', 'post type general name', 'ronky-portfolio-addon' ),
			'singular_name'      => _x( 'Submission', 'post type singular name', 'ronky-portfolio-addon' ),
			'menu_name'          => _x( 'Submissions', 'admin menu', 'ronky-portfolio-addon' ),
			'name_admin_bar'     => _x( 'Submission', 'add new on admin bar', 'ronky-portfolio-addon' ),
			'add_new'            => _x( 'Add New', 'submission', 'ronky-portfolio-addon' ),
			'add_new_item'       => __( 'Add New Submission', 'ronky-portfolio-addon' ),
			'new_item'           => __( 'New Submission', 'ronky-portfolio-addon' ),
			'edit_item'          => __( 'View Submission', 'ronky-portfolio-addon' ),
			'view_item'          => __( 'View Submission', 'ronky-portfolio-addon' ),
			'all_items'          => __( 'All Submissions', 'ronky-portfolio-addon' ),
			'search_items'       => __( 'Search Submissions', 'ronky-portfolio-addon' ),
			'parent_item_colon'  => __( 'Parent Submissions:', 'ronky-portfolio-addon' ),
			'not_found'          => __( 'No submissions found.', 'ronky-portfolio-addon' ),
			'not_found_in_trash' => __( 'No submissions found in Trash.', 'ronky-portfolio-addon' )
		];

		$args = [
			'labels'             => $labels,
			'public'             => false,
			'publicly_queryable' => false,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => false,
			'capability_type'    => 'post',
			'capabilities'       => [
				'create_posts' => 'do_not_allow', // Disable manual creation of submissions
			],
			'map_meta_cap'       => true,
			'has_archive'        => false,
			'hierarchical'       => false,
			'menu_position'      => 25,
			'menu_icon'          => 'dashicons-email-alt',
			'supports'           => [ 'title' ]
		];

		register_post_type( 'ronky_submission', $args );
	}
}

Ronky_Portfolio_Addon::instance();
