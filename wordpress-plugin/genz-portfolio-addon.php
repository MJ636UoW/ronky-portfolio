<?php
/**
 * Plugin Name: Gen-Z Luxury Portfolio Addon for Elementor
 * Description: Registers premium, high-interaction custom widgets for a photographer & videographer portfolio (Hero, Magnetic Cursor, About with Counters, Portfolio Masonry Grid, Services upward flood, Testimonial sliders, and Marquees) with full Elementor controls.
 * Version: 1.0.0
 * Author: Kai Studio
 * Text Domain: genz-portfolio-addon
 * Requires at least: 5.8
 * Requires PHP: 7.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Main Gen-Z Portfolio Addon Class
 */
final class GenZ_Portfolio_Addon {

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
		wp_register_style(
			'genz-portfolio-styles',
			plugins_url( '/assets/css/genz-portfolio.css', __FILE__ ),
			[],
			self::VERSION
		);
	}

	public function register_frontend_scripts() {
		wp_register_script(
			'genz-portfolio-scripts',
			plugins_url( '/assets/js/genz-portfolio.js', __FILE__ ),
			[ 'jquery' ],
			self::VERSION,
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
}

GenZ_Portfolio_Addon::instance();
