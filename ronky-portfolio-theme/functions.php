<?php
/**
 * Gen-Z Luxury Portfolio Theme Functions
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// 1. Theme Setup
function genz_portfolio_theme_setup() {
    // Add support for post thumbnails (featured images)
    add_theme_support( 'post-thumbnails' );

    // Register dynamic menu support
    register_nav_menus( array(
        'primary' => esc_html__( 'Primary Menu', 'genz-portfolio-theme' ),
    ) );
}
add_action( 'after_setup_theme', 'genz_portfolio_theme_setup' );

// Automatically inject custom styles/attributes to dynamic menu items
function ronky_add_menu_link_class( $atts, $item, $args ) {
    if ( property_exists( $args, 'theme_location' ) && 'primary' === $args->theme_location ) {
        if ( isset( $args->menu_class ) && strpos( $args->menu_class, 'mobile-nav-links' ) !== false ) {
            $atts['class'] = 'mobile-nav-link';
        } else {
            $atts['class'] = 'nav-link magnetic';
            $atts['data-magnetic-strength'] = '0.4';
        }
    }
    return $atts;
}
add_filter( 'nav_menu_link_attributes', 'ronky_add_menu_link_class', 10, 3 );

// Auto-create default portfolio categories if they do not exist
function ronky_create_portfolio_categories() {
    if ( ! term_exists( 'Fashion', 'category' ) ) {
        wp_insert_term( 'Fashion', 'category', array( 'slug' => 'fashion' ) );
    }
    if ( ! term_exists( 'Editorial', 'category' ) ) {
        wp_insert_term( 'Editorial', 'category', array( 'slug' => 'editorial' ) );
    }
    if ( ! term_exists( 'Video', 'category' ) ) {
        wp_insert_term( 'Video', 'category', array( 'slug' => 'video' ) );
    }
}
add_action( 'init', 'ronky_create_portfolio_categories' );

// 2. Enqueue Scripts and Styles
function genz_portfolio_theme_scripts() {
    // Google Fonts
    wp_enqueue_style( 
        'genz-portfolio-fonts', 
        'https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&family=Syne:wght@700;800&display=swap', 
        array(), 
        null 
    );

    wp_enqueue_style( 
        'genz-portfolio-style', 
        get_template_directory_uri() . '/assets/css/theme-style.css', 
        array(), 
        file_exists( get_template_directory() . '/assets/css/theme-style.css' ) ? filemtime( get_template_directory() . '/assets/css/theme-style.css' ) : time()
    );

    // Dynamic Customizer CSS overrides
    $float_speed = get_theme_mod( 'genz_hero_float_speed', '1.0' );
    $custom_css = "
        :root {
            --float-speed-multiplier: " . esc_attr( $float_speed ) . ";
        }
    ";
    wp_add_inline_style( 'genz-portfolio-style', $custom_css );

    // Main JS script
    wp_enqueue_script( 
        'genz-portfolio-script', 
        get_template_directory_uri() . '/assets/js/theme-main.js', 
        array(), 
        file_exists( get_template_directory() . '/assets/js/theme-main.js' ) ? filemtime( get_template_directory() . '/assets/js/theme-main.js' ) : time(), 
        true 
    );

    // Pass AJAX URL to Javascript
    wp_localize_script( 
        'genz-portfolio-script', 
        'genz_ajax', 
        array(
            'ajax_url' => admin_url( 'admin-ajax.php' )
        ) 
    );
}
add_action( 'wp_enqueue_scripts', 'genz_portfolio_theme_scripts' );

// 3. Register Customizer Settings for Full Editability
function genz_portfolio_customize_register( $wp_customize ) {
    
    // ==========================================
    // HERO SECTION CUSTOMIZER
    // ==========================================
    $wp_customize->add_section( 'genz_hero_section', array(
        'title'      => esc_html__( 'Hero Section Settings', 'genz-portfolio-theme' ),
        'priority'   => 30,
    ) );

    // Hero Title
    $wp_customize->add_setting( 'genz_hero_title', array(
        'default'   => 'SHAPING Visions INTO ART.',
        'sanitize_callback' => 'wp_kses_post',
    ) );
    $wp_customize->add_control( 'genz_hero_title', array(
        'label'    => esc_html__( 'Hero Title (Use HTML tags like <br> or <span class="accent-text text-glow">)', 'genz-portfolio-theme' ),
        'section'  => 'genz_hero_section',
        'type'     => 'textarea',
    ) );

    // Hero Subtitle
    $wp_customize->add_setting( 'genz_hero_subtitle', array(
        'default'   => 'Premium fashion-editorial photographer & videographer defining the visual language of 2026.',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'genz_hero_subtitle', array(
        'label'    => esc_html__( 'Hero Subtitle', 'genz-portfolio-theme' ),
        'section'  => 'genz_hero_section',
        'type'     => 'textarea',
    ) );

    // Hero Showcase Image
    $wp_customize->add_setting( 'genz_hero_image', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ) );
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'genz_hero_image', array(
        'label'    => esc_html__( 'Hero Showcase Image', 'genz-portfolio-theme' ),
        'section'  => 'genz_hero_section',
        'settings' => 'genz_hero_image',
    ) ) );

    // Hero Floating Tag 1 Text
    $wp_customize->add_setting( 'genz_hero_tag1', array( 'default' => 'Photographer', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'genz_hero_tag1', array( 'label' => 'Floating Tag 1 Text', 'section' => 'genz_hero_section', 'type' => 'text' ) );

    // Hero Floating Tag 2 Text
    $wp_customize->add_setting( 'genz_hero_tag2', array( 'default' => 'Videographer', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'genz_hero_tag2', array( 'label' => 'Floating Tag 2 Text', 'section' => 'genz_hero_section', 'type' => 'text' ) );

    // Hero Floating Tag 3 Text
    $wp_customize->add_setting( 'genz_hero_tag3', array( 'default' => 'Director', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'genz_hero_tag3', array( 'label' => 'Floating Tag 3 Text', 'section' => 'genz_hero_section', 'type' => 'text' ) );

    // Hero Floating Tag 4 Text
    $wp_customize->add_setting( 'genz_hero_tag4', array( 'default' => 'Storyteller', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'genz_hero_tag4', array( 'label' => 'Floating Tag 4 Text', 'section' => 'genz_hero_section', 'type' => 'text' ) );

    // Hero Floating Tag 5 Text
    $wp_customize->add_setting( 'genz_hero_tag5', array( 'default' => 'Creative Director', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'genz_hero_tag5', array( 'label' => 'Floating Tag 5 Text', 'section' => 'genz_hero_section', 'type' => 'text' ) );

    // Hero Floating Speed Multiplier
    $wp_customize->add_setting( 'genz_hero_float_speed', array( 'default' => '1.0', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'genz_hero_float_speed', array(
        'label'    => 'Floating Elements Speed Multiplier (e.g. 1.0, 1.5, 2.0, 0.5)',
        'section'  => 'genz_hero_section',
        'type'     => 'text',
    ) );

    // ==========================================
    // ABOUT SECTION CUSTOMIZER
    // ==========================================
    $wp_customize->add_section( 'genz_about_section', array(
        'title'      => esc_html__( 'About Section Settings', 'genz-portfolio-theme' ),
        'priority'   => 40,
    ) );

    // About Main Title
    $wp_customize->add_setting( 'genz_about_title', array(
        'default'   => 'THE MIND<br>BEHIND <span class="accent-text">THE LENS</span>',
        'sanitize_callback' => 'wp_kses_post',
    ) );
    $wp_customize->add_control( 'genz_about_title', array(
        'label'    => esc_html__( 'About Title', 'genz-portfolio-theme' ),
        'section'  => 'genz_about_section',
        'type'     => 'textarea',
    ) );

    // About Text description
    $wp_customize->add_setting( 'genz_about_text', array(
        'default'   => 'I capture raw, electric moments sitting at the intersection of streetwear subculture, dynamic sports, and high-fashion editorial. By blending creative camera movements with cinematic grain and glass refraction techniques, I create visual poetry that leaves a trace.',
        'sanitize_callback' => 'sanitize_textarea_field',
    ) );
    $wp_customize->add_control( 'genz_about_text', array(
        'label'    => esc_html__( 'About Description Text', 'genz-portfolio-theme' ),
        'section'  => 'genz_about_section',
        'type'     => 'textarea',
    ) );

    // About Profile Image
    $wp_customize->add_setting( 'genz_about_image', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ) );
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'genz_about_image', array(
        'label'    => esc_html__( 'About Profile Image', 'genz-portfolio-theme' ),
        'section'  => 'genz_about_section',
        'settings' => 'genz_about_image',
    ) ) );

    // Counter 1: Projects Completed
    $wp_customize->add_setting( 'genz_stat_projects', array( 'default' => '150', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'genz_stat_projects', array( 'label' => 'Projects Completed Count', 'section' => 'genz_about_section', 'type' => 'text' ) );

    // Counter 2: Happy Clients
    $wp_customize->add_setting( 'genz_stat_clients', array( 'default' => '98', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'genz_stat_clients', array( 'label' => 'Happy Clients %', 'section' => 'genz_about_section', 'type' => 'text' ) );

    // Counter 3: Countries Served
    $wp_customize->add_setting( 'genz_stat_countries', array( 'default' => '12', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'genz_stat_countries', array( 'label' => 'Countries Served Count', 'section' => 'genz_about_section', 'type' => 'text' ) );

    // Counter 4: Experience
    $wp_customize->add_setting( 'genz_stat_experience', array( 'default' => '5', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'genz_stat_experience', array( 'label' => 'Years Experience Count', 'section' => 'genz_about_section', 'type' => 'text' ) );

    // ==========================================
    // SERVICES SECTION CUSTOMIZER
    // ==========================================
    $wp_customize->add_section( 'genz_services_section', array(
        'title'      => esc_html__( 'Services Settings', 'genz-portfolio-theme' ),
        'priority'   => 45,
    ) );

    // Service 1
    $wp_customize->add_setting( 'genz_service1_title', array( 'default' => 'PHOTOGRAPHY', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'genz_service1_title', array( 'label' => 'Service 1 Title', 'section' => 'genz_services_section', 'type' => 'text' ) );
    $wp_customize->add_setting( 'genz_service1_desc', array( 'default' => 'High-fashion editorial, street culture portraits, and artist campaign photography with high-contrast cinematic styles.', 'sanitize_callback' => 'sanitize_textarea_field' ) );
    $wp_customize->add_control( 'genz_service1_desc', array( 'label' => 'Service 1 Description', 'section' => 'genz_services_section', 'type' => 'textarea' ) );
    $wp_customize->add_setting( 'genz_service1_media', array( 'default' => '/assets/images/portfolio1.png,/assets/images/portfolio2.png,/assets/images/portfolio4.png,/assets/images/profile.png', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'genz_service1_media', array( 'label' => 'Service 1 Gallery Media (Comma-separated URLs)', 'section' => 'genz_services_section', 'type' => 'textarea' ) );

    // Service 2
    $wp_customize->add_setting( 'genz_service2_title', array( 'default' => 'VIDEOGRAPHY', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'genz_service2_title', array( 'label' => 'Service 2 Title', 'section' => 'genz_services_section', 'type' => 'text' ) );
    $wp_customize->add_setting( 'genz_service2_desc', array( 'default' => 'Creative commercials, visual reels, and high-energy music videos containing rapid edits and camera-movement aesthetics.', 'sanitize_callback' => 'sanitize_textarea_field' ) );
    $wp_customize->add_control( 'genz_service2_desc', array( 'label' => 'Service 2 Description', 'section' => 'genz_services_section', 'type' => 'textarea' ) );
    $wp_customize->add_setting( 'genz_service2_media', array( 'default' => 'https://assets.mixkit.co/videos/preview/mixkit-cinematic-shot-of-a-skater-performing-tricks-43306-large.mp4,https://assets.mixkit.co/videos/preview/mixkit-top-view-of-cars-on-a-highway-41852-large.mp4', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'genz_service2_media', array( 'label' => 'Service 2 Gallery Media (Comma-separated URLs)', 'section' => 'genz_services_section', 'type' => 'textarea' ) );

    // Service 3
    $wp_customize->add_setting( 'genz_service3_title', array( 'default' => 'EDITING & POST', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'genz_service3_title', array( 'label' => 'Service 3 Title', 'section' => 'genz_services_section', 'type' => 'text' ) );
    $wp_customize->add_setting( 'genz_service3_desc', array( 'default' => 'Distortion overlays, glitch textures, custom color correction, and dynamic transitions to fit modern branding requirements.', 'sanitize_callback' => 'sanitize_textarea_field' ) );
    $wp_customize->add_control( 'genz_service3_desc', array( 'label' => 'Service 3 Description', 'section' => 'genz_services_section', 'type' => 'textarea' ) );
    $wp_customize->add_setting( 'genz_service3_media', array( 'default' => '/assets/images/portfolio2.png,/assets/images/portfolio4.png', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'genz_service3_media', array( 'label' => 'Service 3 Gallery Media (Comma-separated URLs)', 'section' => 'genz_services_section', 'type' => 'textarea' ) );

    // Service 4
    $wp_customize->add_setting( 'genz_service4_title', array( 'default' => 'BRAND STYLING', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'genz_service4_title', array( 'label' => 'Service 4 Title', 'section' => 'genz_services_section', 'type' => 'text' ) );
    $wp_customize->add_setting( 'genz_service4_desc', array( 'default' => 'Complete visual direction to establish unique styling, layout pacing, and visual identities across platforms.', 'sanitize_callback' => 'sanitize_textarea_field' ) );
    $wp_customize->add_control( 'genz_service4_desc', array( 'label' => 'Service 4 Description', 'section' => 'genz_services_section', 'type' => 'textarea' ) );
    $wp_customize->add_setting( 'genz_service4_media', array( 'default' => '/assets/images/portfolio1.png,/assets/images/profile.png', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'genz_service4_media', array( 'label' => 'Service 4 Gallery Media (Comma-separated URLs)', 'section' => 'genz_services_section', 'type' => 'textarea' ) );

    // ==========================================
    // TESTIMONIALS SECTION CUSTOMIZER
    // ==========================================
    $wp_customize->add_section( 'genz_testimonials_section', array(
        'title'      => esc_html__( 'Testimonials Settings', 'genz-portfolio-theme' ),
        'priority'   => 48,
    ) );

    // Testimonial 1
    $wp_customize->add_setting( 'genz_testimonial1_quote', array( 'default' => 'Ronky Edits completely redefined our streetwear brand\'s campaign. The dynamic camera movements combined with acid green color grading gave us a distinct edge on our website. Absolutely next level.', 'sanitize_callback' => 'sanitize_textarea_field' ) );
    $wp_customize->add_control( 'genz_testimonial1_quote', array( 'label' => 'Testimonial 1 Quote', 'section' => 'genz_testimonials_section', 'type' => 'textarea' ) );
    $wp_customize->add_setting( 'genz_testimonial1_name', array( 'default' => 'Amara Vance', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'genz_testimonial1_name', array( 'label' => 'Testimonial 1 Name', 'section' => 'genz_testimonials_section', 'type' => 'text' ) );
    $wp_customize->add_setting( 'genz_testimonial1_role', array( 'default' => 'Founder, NEO-GRID Tokyo', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'genz_testimonial1_role', array( 'label' => 'Testimonial 1 Role', 'section' => 'genz_testimonials_section', 'type' => 'text' ) );
    $wp_customize->add_setting( 'genz_testimonial1_avatar', array( 'default' => '', 'sanitize_callback' => 'esc_url_raw' ) );
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'genz_testimonial1_avatar', array( 'label' => 'Testimonial 1 Avatar', 'section' => 'genz_testimonials_section', 'settings' => 'genz_testimonial1_avatar' ) ) );

    // Testimonial 2
    $wp_customize->add_setting( 'genz_testimonial2_quote', array( 'default' => 'Working with Ronky Edits was seamless. The edits match exactly what modern Gen-Z visual design looks like: punchy, authentic, and cinematic. The custom website integrations are incredible.', 'sanitize_callback' => 'sanitize_textarea_field' ) );
    $wp_customize->add_control( 'genz_testimonial2_quote', array( 'label' => 'Testimonial 2 Quote', 'section' => 'genz_testimonials_section', 'type' => 'textarea' ) );
    $wp_customize->add_setting( 'genz_testimonial2_name', array( 'default' => 'Jaden Miller', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'genz_testimonial2_name', array( 'label' => 'Testimonial 2 Name', 'section' => 'genz_testimonials_section', 'type' => 'text' ) );
    $wp_customize->add_setting( 'genz_testimonial2_role', array( 'default' => 'Director, SATELLITE Records', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'genz_testimonial2_role', array( 'label' => 'Testimonial 2 Role', 'section' => 'genz_testimonials_section', 'type' => 'text' ) );
    $wp_customize->add_setting( 'genz_testimonial2_avatar', array( 'default' => '', 'sanitize_callback' => 'esc_url_raw' ) );
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'genz_testimonial2_avatar', array( 'label' => 'Testimonial 2 Avatar', 'section' => 'genz_testimonials_section', 'settings' => 'genz_testimonial2_avatar' ) ) );

    // Testimonial 3
    $wp_customize->add_setting( 'genz_testimonial3_quote', array( 'default' => 'Highly recommended for architectural and fashion-editorial work. The eye for detail and post-production coloring is amazing. We got an Awwwards nominee using this material.', 'sanitize_callback' => 'sanitize_textarea_field' ) );
    $wp_customize->add_control( 'genz_testimonial3_quote', array( 'label' => 'Testimonial 3 Quote', 'section' => 'genz_testimonials_section', 'type' => 'textarea' ) );
    $wp_customize->add_setting( 'genz_testimonial3_name', array( 'default' => 'Sofia Laurent', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'genz_testimonial3_name', array( 'label' => 'Testimonial 3 Name', 'section' => 'genz_testimonials_section', 'type' => 'text' ) );
    $wp_customize->add_setting( 'genz_testimonial3_role', array( 'default' => 'Creative Lead, MAISON Agency', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'genz_testimonial3_role', array( 'label' => 'Testimonial 3 Role', 'section' => 'genz_testimonials_section', 'type' => 'text' ) );
    $wp_customize->add_setting( 'genz_testimonial3_avatar', array( 'default' => '', 'sanitize_callback' => 'esc_url_raw' ) );
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'genz_testimonial3_avatar', array( 'label' => 'Testimonial 3 Avatar', 'section' => 'genz_testimonials_section', 'settings' => 'genz_testimonial3_avatar' ) ) );

    // ==========================================
    // CONTACT DETAIL CUSTOMIZER
    // ==========================================
    $wp_customize->add_section( 'genz_contact_section', array(
        'title'      => esc_html__( 'Contact Settings', 'genz-portfolio-theme' ),
        'priority'   => 50,
    ) );

    // Contact Email
    $wp_customize->add_setting( 'genz_contact_email', array( 'default' => 'hello@ronkyedits.studio', 'sanitize_callback' => 'sanitize_email' ) );
    $wp_customize->add_control( 'genz_contact_email', array( 'label' => 'Email Address', 'section' => 'genz_contact_section', 'type' => 'text' ) );

    // Contact Whatsapp Link
    $wp_customize->add_setting( 'genz_contact_whatsapp', array( 'default' => '+1 (555) 019-2026', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'genz_contact_whatsapp', array( 'label' => 'WhatsApp Number', 'section' => 'genz_contact_section', 'type' => 'text' ) );

    // Contact Instagram Username
    $wp_customize->add_setting( 'genz_contact_instagram', array( 'default' => '@ronky.edits', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'genz_contact_instagram', array( 'label' => 'Instagram Handle', 'section' => 'genz_contact_section', 'type' => 'text' ) );

    // Fluent Form ID
    $wp_customize->add_setting( 'genz_fluent_form_id', array( 'default' => '1', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'genz_fluent_form_id', array( 'label' => 'Fluent Form ID (for Contact Form integration)', 'section' => 'genz_contact_section', 'type' => 'text' ) );

    // ==========================================
    // PORTFOLIO SECTION CUSTOMIZER
    // ==========================================
    $wp_customize->add_section( 'genz_portfolio_section', array(
        'title'      => esc_html__( 'Portfolio Grid Settings', 'genz-portfolio-theme' ),
        'priority'   => 42,
    ) );

    // Always Show Explore More Button
    $wp_customize->add_setting( 'genz_always_show_explore_more', array(
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ) );
    $wp_customize->add_control( 'genz_always_show_explore_more', array(
        'label'    => esc_html__( 'Always Show "Explore More" Button (even if posts <= 6)', 'genz-portfolio-theme' ),
        'section'  => 'genz_portfolio_section',
        'type'     => 'checkbox',
    ) );
}
add_action( 'customize_register', 'genz_portfolio_customize_register' );

// Auto-populate empty Elementor pages with our default homepage template
function genz_auto_populate_elementor_data( $value, $object_id, $meta_key, $single ) {
    if ( '_elementor_data' === $meta_key ) {
        // Temporarily remove filter to avoid infinite recursion
        remove_filter( 'get_post_metadata', 'genz_auto_populate_elementor_data', 10 );
        $db_value = get_post_meta( $object_id, '_elementor_data', true );
        add_filter( 'get_post_metadata', 'genz_auto_populate_elementor_data', 10, 4 );

        if ( empty( $db_value ) && 'page' === get_post_type( $object_id ) ) {
            $template_file = get_template_directory() . '/ronky-homepage-template.json';
            if ( file_exists( $template_file ) ) {
                $json_content = file_get_contents( $template_file );
                if ( $json_content ) {
                    $decoded = json_decode( $json_content, true );
                    if ( isset( $decoded['content'] ) ) {
                        // Return JSON string of the content elements
                        $layout_content = json_encode( $decoded['content'] );
                        return $single ? $layout_content : array( $layout_content );
                    }
                }
            }
        }
    }
    return $value;
}
add_filter( 'get_post_metadata', 'genz_auto_populate_elementor_data', 10, 4 );

// Fluent Forms Custom Ajax Submission Handler Helper: extract leaf input fields
function ronky_extract_fluent_form_inputs( $fields ) {
    $inputs = array();
    if ( ! is_array( $fields ) ) {
        return $inputs;
    }
    
    foreach ( $fields as $field ) {
        if ( isset( $field['element'] ) ) {
            $el_type = $field['element'];
            
            $key = '';
            if ( isset( $field['attributes']['name'] ) ) {
                $key = $field['attributes']['name'];
            } elseif ( isset( $field['name'] ) ) {
                $key = $field['name'];
            }
            
            $label = '';
            if ( isset( $field['settings']['label'] ) ) {
                $label = $field['settings']['label'];
            } elseif ( isset( $field['settings']['admin_field_label'] ) ) {
                $label = $field['settings']['admin_field_label'];
            }
            
            $choices = array();
            if ( isset( $field['settings']['input_options'] ) ) {
                $choices = $field['settings']['input_options'];
            } elseif ( isset( $field['settings']['choices'] ) ) {
                $choices = $field['settings']['choices'];
            }
            
            if ( $key ) {
                $inputs[] = array(
                    'key'     => $key,
                    'element' => $el_type,
                    'label'   => $label,
                    'choices' => $choices
                );
            }
            
            if ( isset( $field['fields'] ) && is_array( $field['fields'] ) ) {
                $inputs = array_merge( $inputs, ronky_extract_fluent_form_inputs( $field['fields'] ) );
            }
            if ( isset( $field['columns'] ) && is_array( $field['columns'] ) ) {
                foreach ( $field['columns'] as $col ) {
                    if ( isset( $col['fields'] ) && is_array( $col['fields'] ) ) {
                        $inputs = array_merge( $inputs, ronky_extract_fluent_form_inputs( $col['fields'] ) );
                    }
                }
            }
        }
    }
    return $inputs;
}

// Fluent Forms Custom Ajax Submission Handler Helper: match choices
function ronky_match_service_choice( $submitted_val, $choices ) {
    if ( empty( $choices ) ) {
        return $submitted_val;
    }
    
    $keywords = array(
        'videography' => array( 'video', 'reel', 'film', 'movie', 'showreel' ),
        'photography' => array( 'photo', 'shoot', 'cam', 'pic', 'image' ),
        'editing'     => array( 'edit', 'post', 'grade', 'grading' ),
        'custom'      => array( 'direction', 'styling', 'custom', 'consult', 'full' )
    );
    
    $target_keywords = isset( $keywords[$submitted_val] ) ? $keywords[$submitted_val] : array( $submitted_val );
    
    foreach ( $choices as $choice ) {
        $val = '';
        $lbl = '';
        if ( is_array( $choice ) ) {
            $val = isset( $choice['value'] ) ? $choice['value'] : '';
            $lbl = isset( $choice['label'] ) ? $choice['label'] : $val;
        } else {
            $val = $choice;
            $lbl = $choice;
        }
        
        foreach ( $target_keywords as $kw ) {
            if ( stripos( $val, $kw ) !== false || stripos( $lbl, $kw ) !== false ) {
                return $val;
            }
        }
    }
    
    $first_choice = reset( $choices );
    if ( is_array( $first_choice ) ) {
        return isset( $first_choice['value'] ) ? $first_choice['value'] : '';
    }
    return $first_choice;
}

// Fluent Forms Custom Ajax Submission Handler
function ronky_submit_custom_contact_form() {
    $form_id_raw = isset( $_POST['fluent_form_id'] ) && ! empty( $_POST['fluent_form_id'] ) ? sanitize_text_field( $_POST['fluent_form_id'] ) : get_theme_mod( 'genz_fluent_form_id', '1' );
    
    // Self-healing shortcode extractor: extract form ID if user pasted [fluentform id="3"]
    $form_id = 1;
    if ( preg_match( '/id=["\']?(\d+)["\']?/', $form_id_raw, $matches ) ) {
        $form_id = intval( $matches[1] );
    } else {
        $form_id = intval( preg_replace( '/[^0-9]/', '', $form_id_raw ) );
    }
    if ( ! $form_id ) {
        $form_id = 1;
    }
    
    // Sanitize input
    $name = isset($_POST['name']) ? sanitize_text_field($_POST['name']) : '';
    $email = isset($_POST['email']) ? sanitize_email($_POST['email']) : '';
    $phone = isset($_POST['phone']) ? sanitize_text_field($_POST['phone']) : '';
    $service = isset($_POST['service']) ? sanitize_text_field($_POST['service']) : '';
    $budget = isset($_POST['budget']) ? sanitize_text_field($_POST['budget']) : '';
    $message = isset($_POST['message']) ? sanitize_textarea_field($_POST['message']) : '';

    global $wpdb;
    $table_name = $wpdb->prefix . 'fluentform_forms';
    $form_row = null;
    $extracted_inputs = array();

    // Query form fields from database dynamically
    $form_row = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$table_name} WHERE id = %d", $form_id ) );

    // SELF-HEALING: If requested form does not exist, or has no valid fields, lookup 'Ronky Edits Portfolio Form'
    if ( ! $form_row || empty( $form_row->form_fields ) ) {
        $better_form = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$table_name} WHERE title = %s OR title LIKE %s ORDER BY id DESC LIMIT 1", 'Ronky Edits Portfolio Form', '%Ronky%' ) );
        if ( $better_form && ! empty( $better_form->form_fields ) ) {
            $form_row = $better_form;
            $form_id = intval( $better_form->id );
        }
    }

    if ( $form_row && ! empty( $form_row->form_fields ) ) {
        $decoded_fields = json_decode( $form_row->form_fields, true );
        if ( isset( $decoded_fields['fields'] ) ) {
            $extracted_inputs = ronky_extract_fluent_form_inputs( $decoded_fields['fields'] );
        }
    }

    $mapped = array(
        'form_id' => intval( $form_id ),
        'action'  => 'fluentform_submit',
        'ronky_custom_submit' => true
    );

    if ( ! empty( $extracted_inputs ) ) {
        // Dynamic field mapping based on Fluent Form structure
        foreach ( $extracted_inputs as $input ) {
            $key = $input['key'];
            $el = $input['element'];
            $lbl = strtolower( $input['label'] );
            
            // 1. Name Mapping
            if ( $el === 'name_fields' || $key === 'names' ) {
                $name_parts = explode( ' ', trim( $name ), 2 );
                $mapped[$key] = array(
                    'first_name' => $name_parts[0],
                    'last_name'  => isset( $name_parts[1] ) ? $name_parts[1] : ''
                );
            } elseif ( $el === 'input_text' && ( strpos( $key, 'name' ) !== false || strpos( $lbl, 'name' ) !== false ) && ! isset( $mapped[$key] ) ) {
                $mapped[$key] = $name;
            }
            
            // 2. Email Mapping
            if ( $el === 'input_email' || $key === 'email' || strpos( $lbl, 'email' ) !== false ) {
                $mapped[$key] = $email;
            }
            
            // 3. Phone Mapping
            if ( $el === 'phone' || $el === 'numeric-field' || $key === 'phone' || strpos( $key, 'phone' ) !== false || strpos( $lbl, 'phone' ) !== false || strpos( $lbl, 'number' ) !== false ) {
                if ( ! isset( $mapped[$key] ) ) {
                    $mapped[$key] = $phone;
                }
            }
            
            // 4. Service Dropdown Mapping
            if ( ( $el === 'select' || $el === 'select-choices' || $el === 'dropdown' || $el === 'radio' ) && ( strpos( $key, 'service' ) !== false || strpos( $lbl, 'service' ) !== false ) ) {
                if ( ! isset( $mapped[$key] ) ) {
                    $mapped[$key] = ronky_match_service_choice( $service, $input['choices'] );
                }
            }
            
            // 5. Budget Dropdown Mapping
            if ( ( $el === 'select' || $el === 'select-choices' || $el === 'dropdown' || $el === 'radio' ) && ( strpos( $key, 'budget' ) !== false || strpos( $lbl, 'budget' ) !== false || strpos( $lbl, 'price' ) !== false ) ) {
                if ( ! isset( $mapped[$key] ) ) {
                    $mapped[$key] = ronky_match_service_choice( $budget, $input['choices'] );
                }
            }
            
            // 6. Message Mapping
            if ( $el === 'textarea' || $key === 'message' || strpos( $lbl, 'message' ) !== false || strpos( $lbl, 'project' ) !== false || strpos( $lbl, 'about' ) !== false ) {
                if ( ! isset( $mapped[$key] ) ) {
                    $mapped[$key] = $message;
                }
            }
        }

        // Catch-all: map any remaining unmapped Fluent Form fields to prevent validation errors
        foreach ( $extracted_inputs as $input ) {
            $key = $input['key'];
            if ( ! isset( $mapped[$key] ) ) {
                $mapped[$key] = '';
            }
        }
    } else {
        // Fallback to default mapping if database query failed
        $mapped['names'] = array(
            'first_name' => $name,
            'last_name'  => ''
        );
        $mapped['name'] = $name;
        $mapped['email'] = $email;
        $mapped['phone'] = $phone;
        $mapped['service'] = $service;
        $mapped['budget'] = $budget;
        $mapped['message'] = $message;
    }

    // Prepare the fields data for URL encoding (as Fluent Forms expects a serialized query string in $_POST['data'])
    $fields_data = array();
    foreach ( $mapped as $k => $v ) {
        if ( ! in_array( $k, array( 'form_id', 'action', 'ronky_custom_submit' ) ) ) {
            $fields_data[$k] = $v;
        }
    }
    
    $post_data = array(
        'form_id' => intval( $form_id ),
        'action'  => 'fluentform_submit',
        'data'    => http_build_query( $fields_data ),
        'ronky_custom_submit' => true
    );

    // Overwrite $_POST and $_REQUEST globals with mapped data
    $_POST = $post_data;
    $_REQUEST = array_merge( $_REQUEST, $post_data );

    // Try to update Fluent Forms' internal Request wrapper instance if instantiated
    if ( function_exists( 'wpFluentForm' ) ) {
        try {
            $request = wpFluentForm( 'request' );
            if ( is_object( $request ) ) {
                if ( method_exists( $request, 'merge' ) ) {
                    $request->merge( $post_data );
                }
                if ( isset( $request->request ) && is_object( $request->request ) && method_exists( $request->request, 'add' ) ) {
                    $request->request->add( $post_data );
                }
            }
        } catch ( \Exception $e ) {
            // Ignore container resolution exceptions
        }
    }

    // Execute Fluent Forms AJAX action
    if ( is_user_logged_in() ) {
        do_action( 'wp_ajax_fluentform_submit' );
    } else {
        do_action( 'wp_ajax_nopriv_fluentform_submit' );
    }
    wp_die();
}
add_action( 'wp_ajax_ronky_submit_custom_contact_form', 'ronky_submit_custom_contact_form' );
add_action( 'wp_ajax_nopriv_ronky_submit_custom_contact_form', 'ronky_submit_custom_contact_form' );

// Fluent Forms Custom Ajax Submission Nonce Verification Bypass
function ronky_bypass_fluent_form_nonce( $status, $form_id ) {
    if ( isset( $_REQUEST['ronky_custom_submit'] ) && $_REQUEST['ronky_custom_submit'] ) {
        return false;
    }
    return $status;
}
add_filter( 'fluentform_nonce_verify', 'ronky_bypass_fluent_form_nonce', 99, 2 );
add_filter( 'fluentform/nonce_verify', 'ronky_bypass_fluent_form_nonce', 99, 2 );

// Auto-create Fluent Form for Ronky Edits Portfolio
function ronky_create_default_fluent_form() {
    if ( ! class_exists( '\FluentForm\App' ) && ! defined( 'FLUENTFORM' ) ) {
        return;
    }
    
    if ( get_option( 'ronky_fluent_form_created' ) ) {
        return;
    }
    
    global $wpdb;
    $table_name = $wpdb->prefix . 'fluentform_forms';
    
    $existing = $wpdb->get_var( $wpdb->prepare( "SELECT id FROM {$table_name} WHERE title = %s", 'Ronky Edits Portfolio Form' ) );
    if ( $existing ) {
        update_option( 'ronky_fluent_form_created', $existing );
        set_theme_mod( 'genz_fluent_form_id', $existing );
        return;
    }
    
    $fields = array(
        'fields' => array(
            array(
                'index' => 0,
                'element' => 'input_text',
                'attributes' => array(
                    'type' => 'text',
                    'name' => 'name',
                    'value' => '',
                    'placeholder' => 'Full Name',
                    'class' => 'fluentform-control'
                ),
                'settings' => array(
                    'label' => 'Full Name',
                    'is_required' => 'yes',
                    'validation_rules' => array(
                        'required' => array(
                            'value' => true,
                            'message' => 'Full Name is required'
                        )
                    )
                ),
                'editor_options' => array(
                    'title' => 'Simple Text',
                    'icon_class' => 'ff-edit-text',
                    'template' => 'inputText'
                ),
                'uniqElKey' => 'el_1781671000001'
            ),
            array(
                'index' => 1,
                'element' => 'input_email',
                'attributes' => array(
                    'type' => 'email',
                    'name' => 'email',
                    'value' => '',
                    'placeholder' => 'Email Address',
                    'class' => 'fluentform-control'
                ),
                'settings' => array(
                    'label' => 'Email Address',
                    'is_required' => 'yes',
                    'validation_rules' => array(
                        'required' => array(
                            'value' => true,
                            'message' => 'Email Address is required'
                        ),
                        'email' => array(
                            'value' => true,
                            'message' => 'This field must contain a valid email'
                        )
                    )
                ),
                'editor_options' => array(
                    'title' => 'Email Address',
                    'icon_class' => 'ff-edit-email',
                    'template' => 'inputText'
                ),
                'uniqElKey' => 'el_1781671000002'
            ),
            array(
                'index' => 2,
                'element' => 'input_text',
                'attributes' => array(
                    'type' => 'tel',
                    'name' => 'phone',
                    'value' => '',
                    'placeholder' => 'Phone (Optional)',
                    'class' => 'fluentform-control'
                ),
                'settings' => array(
                    'label' => 'Phone (Optional)',
                    'is_required' => 'no'
                ),
                'editor_options' => array(
                    'title' => 'Phone Number',
                    'icon_class' => 'ff-edit-phone',
                    'template' => 'inputText'
                ),
                'uniqElKey' => 'el_1781671000003'
            ),
            array(
                'index' => 3,
                'element' => 'select',
                'attributes' => array(
                    'name' => 'service',
                    'value' => '',
                    'class' => 'fluentform-control'
                ),
                'settings' => array(
                    'label' => 'Service Needed',
                    'is_required' => 'yes',
                    'input_options' => array(
                        array( 'label' => 'Photography Campaign', 'value' => 'photography' ),
                        array( 'label' => 'Videography / Showreel', 'value' => 'videography' ),
                        array( 'label' => 'Editing & Post-Production', 'value' => 'editing' ),
                        array( 'label' => 'Full Visual Direction', 'value' => 'custom' )
                    ),
                    'validation_rules' => array(
                        'required' => array(
                            'value' => true,
                            'message' => 'Service selection is required'
                        )
                    )
                ),
                'editor_options' => array(
                    'title' => 'Dropdown',
                    'icon_class' => 'ff-edit-dropdown',
                    'template' => 'select'
                ),
                'uniqElKey' => 'el_1781671000004'
            ),
            array(
                'index' => 4,
                'element' => 'select',
                'attributes' => array(
                    'name' => 'budget',
                    'value' => '',
                    'class' => 'fluentform-control'
                ),
                'settings' => array(
                    'label' => 'Project Budget',
                    'is_required' => 'yes',
                    'input_options' => array(
                        array( 'label' => '$1,500 - $3,000', 'value' => 'starter' ),
                        array( 'label' => '$3,000 - $7,000', 'value' => 'medium' ),
                        array( 'label' => '$7,000 - $15,000', 'value' => 'premium' ),
                        array( 'label' => '$15,000+', 'value' => 'agency' )
                    ),
                    'validation_rules' => array(
                        'required' => array(
                            'value' => true,
                            'message' => 'Budget selection is required'
                        )
                    )
                ),
                'editor_options' => array(
                    'title' => 'Dropdown',
                    'icon_class' => 'ff-edit-dropdown',
                    'template' => 'select'
                ),
                'uniqElKey' => 'el_1781671000005'
            ),
            array(
                'index' => 5,
                'element' => 'textarea',
                'attributes' => array(
                    'name' => 'message',
                    'value' => '',
                    'placeholder' => 'Tell us about your project',
                    'class' => 'fluentform-control',
                    'rows' => 4,
                    'cols' => 50
                ),
                'settings' => array(
                    'label' => 'Tell us about your project',
                    'is_required' => 'yes',
                    'validation_rules' => array(
                        'required' => array(
                            'value' => true,
                            'message' => 'Message details are required'
                        )
                    )
                ),
                'editor_options' => array(
                    'title' => 'Text Area',
                    'icon_class' => 'ff-edit-textarea',
                    'template' => 'inputTextarea'
                ),
                'uniqElKey' => 'el_1781671000006'
            )
        ),
        'submitButton' => array(
            'uniqElKey' => 'el_1781671000007',
            'element' => 'button',
            'attributes' => array(
                'type' => 'submit',
                'class' => 'ff-btn-submit'
            ),
            'settings' => array(
                'align' => 'left',
                'button_style' => 'default',
                'button_size' => 'md',
                'btn_text' => 'Submit Form'
            ),
            'editor_options' => array(
                'title' => 'Submit Button'
            )
        )
    );
    
    $settings = array(
        'confirmation' => array(
            'redirectTo' => 'samePage',
            'messageToShow' => 'Thank you for your message. We will get back to you shortly!',
            'samePageFormBehavior' => 'hide_form'
        ),
        'notifications' => array(
            array(
                'name' => 'Admin Email Notification',
                'sendTo' => 'admin_email',
                'subject' => 'New Contact Form Submission from {name}',
                'message' => 'You have a new contact form submission:<br><br>Name: {name}<br>Email: {email}<br>Phone: {phone}<br>Service: {service}<br>Budget: {budget}<br>Message: {message}',
                'status' => 'active'
            )
        )
    );
    
    $insert_data = array(
        'title' => 'Ronky Edits Portfolio Form',
        'form_fields' => json_encode( $fields ),
        'status' => 'published',
        'created_at' => current_time( 'mysql' ),
        'updated_at' => current_time( 'mysql' )
    );
    
    $result = $wpdb->insert( $table_name, $insert_data );
    if ( $result ) {
        $new_id = $wpdb->insert_id;
        
        // Insert settings and notifications into fluentform_form_meta
        $meta_table = $wpdb->prefix . 'fluentform_form_meta';
        
        $wpdb->insert( $meta_table, array(
            'form_id' => $new_id,
            'meta_key' => 'formSettings',
            'value' => json_encode( array(
                'confirmation' => $settings['confirmation']
            ) )
        ) );
        
        $wpdb->insert( $meta_table, array(
            'form_id' => $new_id,
            'meta_key' => 'notifications',
            'value' => json_encode( $settings['notifications'] )
        ) );
        
        update_option( 'ronky_fluent_form_created', $new_id );
        set_theme_mod( 'genz_fluent_form_id', $new_id );
    }
}
add_action( 'init', 'ronky_create_default_fluent_form', 20 );

// ==========================================
// TESTIMONIAL CUSTOM POST TYPE & META BOX
// ==========================================

// Register Testimonial Custom Post Type
function ronky_register_testimonial_cpt() {
    $labels = array(
        'name'               => _x( 'Testimonials', 'post type general name', 'genz-portfolio-theme' ),
        'singular_name'      => _x( 'Testimonial', 'post type singular name', 'genz-portfolio-theme' ),
        'menu_name'          => _x( 'Testimonials', 'admin menu', 'genz-portfolio-theme' ),
        'name_admin_bar'     => _x( 'Testimonial', 'add new on admin bar', 'genz-portfolio-theme' ),
        'add_new'            => _x( 'Add New', 'testimonial', 'genz-portfolio-theme' ),
        'add_new_item'       => __( 'Add New Testimonial', 'genz-portfolio-theme' ),
        'new_item'           => __( 'New Testimonial', 'genz-portfolio-theme' ),
        'edit_item'          => __( 'Edit Testimonial', 'genz-portfolio-theme' ),
        'view_item'          => __( 'View Testimonial', 'genz-portfolio-theme' ),
        'all_items'          => __( 'All Testimonials', 'genz-portfolio-theme' ),
        'search_items'       => __( 'Search Testimonials', 'genz-portfolio-theme' ),
        'parent_item_colon'  => __( 'Parent Testimonials:', 'genz-portfolio-theme' ),
        'not_found'          => __( 'No testimonials found.', 'genz-portfolio-theme' ),
        'not_found_in_trash' => __( 'No testimonials found in Trash.', 'genz-portfolio-theme' )
    );

    $args = array(
        'labels'             => $labels,
        'public'             => false,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'testimonial' ),
        'capability_type'    => 'post',
        'has_archive'        => false,
        'hierarchical'       => false,
        'menu_position'      => 25,
        'menu_icon'          => 'dashicons-editor-quote',
        'supports'           => array( 'title', 'editor', 'thumbnail' ), // title = Name, editor = Quote, thumbnail = Avatar
        'show_in_rest'       => true, // Enable Gutenberg block editor support
    );

    register_post_type( 'testimonial', $args );
}
add_action( 'init', 'ronky_register_testimonial_cpt' );

// Add Meta Box for Testimonial Role
function ronky_testimonial_add_meta_box() {
    add_meta_box(
        'ronky_testimonial_role_box',
        __( 'Client Details', 'genz-portfolio-theme' ),
        'ronky_testimonial_role_box_callback',
        'testimonial',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'ronky_testimonial_add_meta_box' );

function ronky_testimonial_role_box_callback( $post ) {
    // Add nonce for security
    wp_nonce_field( 'ronky_testimonial_save_meta', 'ronky_testimonial_meta_nonce' );

    // Retrieve existing value
    $value = get_post_meta( $post->ID, '_client_role', true );

    echo '<p><label for="ronky_client_role">' . esc_html__( 'Client Role / Designation (e.g., Founder, NEO-GRID Tokyo):', 'genz-portfolio-theme' ) . '</label></p>';
    echo '<input type="text" id="ronky_client_role" name="ronky_client_role" value="' . esc_attr( $value ) . '" size="50" style="width:100%; max-width:500px; padding: 8px;" placeholder="Founder, NEO-GRID Tokyo" />';
}

function ronky_testimonial_save_meta( $post_id ) {
    // Check nonce
    if ( ! isset( $_POST['ronky_testimonial_meta_nonce'] ) || ! wp_verify_nonce( $_POST['ronky_testimonial_meta_nonce'], 'ronky_testimonial_save_meta' ) ) {
        return;
    }

    // Check autosave
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    // Check permissions
    if ( isset( $_POST['post_type'] ) && 'testimonial' === $_POST['post_type'] ) {
        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }
    }

    // Save/update field
    if ( isset( $_POST['ronky_client_role'] ) ) {
        update_post_meta( $post_id, '_client_role', sanitize_text_field( $_POST['ronky_client_role'] ) );
    }
}
add_action( 'save_post', 'ronky_testimonial_save_meta' );

// ==========================================
// GALLERY PAGE AUTO-CREATOR
// ==========================================

// Auto-create Gallery Page if it doesn't exist
function ronky_auto_create_gallery_page() {
    if ( get_option( 'ronky_gallery_page_created' ) ) {
        return;
    }

    // Check if a page with slug 'gallery' already exists
    $page = get_page_by_path( 'gallery' );
    if ( $page ) {
        update_option( 'ronky_gallery_page_created', $page->ID );
        update_post_meta( $page->ID, '_wp_page_template', 'page-gallery.php' );
        return;
    }

    // Create the page
    $page_data = array(
        'post_title'    => 'Portfolio Gallery',
        'post_name'     => 'gallery',
        'post_status'   => 'publish',
        'post_type'     => 'page',
        'post_content'  => '', // Rendered by page-gallery.php
    );

    $page_id = wp_insert_post( $page_data );
    if ( $page_id && ! is_wp_error( $page_id ) ) {
        update_option( 'ronky_gallery_page_created', $page_id );
        update_post_meta( $page_id, '_wp_page_template', 'page-gallery.php' );
    }
}
add_action( 'init', 'ronky_auto_create_gallery_page', 30 );
