<?php
/**
 * Template Name: Gallery Page Template
 *
 * @package Gen-Z_Luxury_Portfolio_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>
<!doctype html>
<html <?php language_attributes(); ?>>
  <head>
    <meta charset="<?php bloginfo( 'charset' ) ; ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <?php wp_head(); ?>
  </head>
  <body <?php body_class( 'gallery-page' ); ?>>
    <?php wp_body_open(); ?>

    <!-- Background Canvas Engine -->
    <canvas id="bg-canvas"></canvas>
    <div id="noise-overlay"></div>
    <div id="grid-overlay"></div>

    <!-- Magnetic Custom Cursor -->
    <div id="custom-cursor-ring"></div>
    <div id="custom-cursor-dot"></div>

    <!-- Main Navigation Bar -->
    <header class="main-header">
      <div class="header-container">
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="logo magnetic" data-magnetic-strength="0.3">
          <?php 
          $site_name = get_bloginfo( 'name' );
          if ( empty( $site_name ) || 'WordPress' === $site_name ) {
              echo 'RONKY<span>•</span>EDITS';
          } else {
              echo str_replace( array(' ', '•'), '<span>•</span>', esc_html( $site_name ) );
          }
          ?>
        </a>
        <?php
        if ( has_nav_menu( 'primary' ) ) {
            wp_nav_menu( array(
                'theme_location' => 'primary',
                'container'      => false,
                'menu_class'     => 'nav-links',
                'items_wrap'     => '<ul id="%1$s" class="%2$s">%3$s</ul>',
                'fallback_cb'    => false,
            ) );
        } else {
            ?>
            <nav class="nav-links">
              <a href="<?php echo esc_url( home_url( '/#about' ) ); ?>" class="nav-link magnetic" data-magnetic-strength="0.4"><?php esc_html_e( 'About', 'genz-portfolio-theme' ); ?></a>
              <a href="<?php echo esc_url( home_url( '/#portfolio' ) ); ?>" class="nav-link magnetic" data-magnetic-strength="0.4"><?php esc_html_e( 'Works', 'genz-portfolio-theme' ); ?></a>
              <a href="<?php echo esc_url( home_url( '/#services' ) ); ?>" class="nav-link magnetic" data-magnetic-strength="0.4"><?php esc_html_e( 'Services', 'genz-portfolio-theme' ); ?></a>
              <a href="<?php echo esc_url( home_url( '/#testimonials' ) ); ?>" class="nav-link magnetic" data-magnetic-strength="0.4"><?php esc_html_e( 'Reviews', 'genz-portfolio-theme' ); ?></a>
              <a href="<?php echo esc_url( home_url( '/#contact' ) ); ?>" class="nav-link magnetic" data-magnetic-strength="0.4"><?php esc_html_e( 'Book', 'genz-portfolio-theme' ); ?></a>
            </nav>
            <?php
        }
        ?>
        <div class="header-action">
          <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn-primary magnetic" data-magnetic-strength="0.5">
            <span><?php esc_html_e( 'BACK TO HOME', 'genz-portfolio-theme' ); ?></span>
          </a>
        </div>
        <!-- Mobile Menu Trigger -->
        <button class="mobile-menu-trigger" aria-label="<?php esc_attr_e( 'Toggle Menu', 'genz-portfolio-theme' ); ?>">
          <span></span>
          <span></span>
        </button>
      </div>
    </header>

    <!-- Mobile Navigation Overlay -->
    <div class="mobile-nav-overlay">
      <?php
      if ( has_nav_menu( 'primary' ) ) {
          wp_nav_menu( array(
              'theme_location' => 'primary',
              'container'      => false,
              'menu_class'     => 'mobile-nav-links',
              'items_wrap'     => '<ul id="%1$s" class="%2$s">%3$s</ul>',
              'fallback_cb'    => false,
          ) );
      } else {
          ?>
          <nav class="mobile-nav-links">
            <a href="<?php echo esc_url( home_url( '/#about' ) ); ?>" class="mobile-nav-link"><?php esc_html_e( 'About', 'genz-portfolio-theme' ); ?></a>
            <a href="<?php echo esc_url( home_url( '/#portfolio' ) ); ?>" class="mobile-nav-link"><?php esc_html_e( 'Works', 'genz-portfolio-theme' ); ?></a>
            <a href="<?php echo esc_url( home_url( '/#services' ) ); ?>" class="mobile-nav-link"><?php esc_html_e( 'Services', 'genz-portfolio-theme' ); ?></a>
            <a href="<?php echo esc_url( home_url( '/#testimonials' ) ); ?>" class="mobile-nav-link"><?php esc_html_e( 'Reviews', 'genz-portfolio-theme' ); ?></a>
            <a href="<?php echo esc_url( home_url( '/#contact' ) ); ?>" class="mobile-nav-link"><?php esc_html_e( 'Book Now', 'genz-portfolio-theme' ); ?></a>
          </nav>
          <?php
      }
      ?>
    </div>

    <!-- MAIN SCROLL CONTAINER -->
    <main class="scroll-container">
      
      <!-- GALLERY HEADER SECTION -->
      <section class="gallery-hero-section">
        <div class="section-container">
          <div class="gallery-hero-content">
            <h1 class="gallery-title scroll-reveal" data-reveal="fade-up">
              PORTFOLIO <span class="accent-text text-glow">GALLERY</span>
            </h1>
            <p class="gallery-subtitle scroll-reveal" data-reveal="fade-up" data-delay="0.1">
              Explore the complete visual anthology of streetwear campaigns, high-fashion editorials, and cinematic motion films.
            </p>
          </div>
        </div>
      </section>

      <!-- PORTFOLIO SECTION -->
      <section id="portfolio" class="portfolio-section spacer-bottom">
        <div class="section-container">
          <div class="portfolio-header">
            <div class="portfolio-filters">
              <button class="filter-btn active" data-filter="all"><?php esc_html_e( 'All Works', 'genz-portfolio-theme' ); ?></button>
              <button class="filter-btn" data-filter="fashion"><?php esc_html_e( 'Fashion', 'genz-portfolio-theme' ); ?></button>
              <button class="filter-btn" data-filter="editorial"><?php esc_html_e( 'Editorial', 'genz-portfolio-theme' ); ?></button>
              <button class="filter-btn" data-filter="video"><?php esc_html_e( 'Video', 'genz-portfolio-theme' ); ?></button>
            </div>
          </div>

          <!-- Masonry Grid -->
          <div class="portfolio-grid" id="portfolio-grid">
            
            <?php
            $args = array(
                'post_type'      => 'post',
                'posts_per_page' => -1, // Display all items in the gallery
                'post_status'    => 'publish',
            );
            $portfolio_query = new WP_Query( $args );
            if ( $portfolio_query->have_posts() ) :
                while ( $portfolio_query->have_posts() ) : $portfolio_query->the_post();
                    $categories = get_the_category();
                    $cat_classes = array();
                    $cat_names = array();
                    if ( ! empty( $categories ) ) {
                        foreach ( $categories as $category ) {
                            $cat_classes[] = esc_attr( strtolower( $category->slug ) );
                            $cat_names[] = esc_html( strtoupper( $category->name ) );
                        }
                    }
                    $cat_class_str = implode( ' ', $cat_classes );
                    $cat_display = ! empty( $cat_names ) ? implode( ' / ', $cat_names ) : 'PORTFOLIO';

                    $post_index = $portfolio_query->current_post;
                    $img_src = '';
                    if ( has_post_thumbnail() ) {
                        $img_src = get_the_post_thumbnail_url( get_the_ID(), 'full' );
                    } else {
                        $fallback_imgs = array(
                            '/assets/images/portfolio1.png',
                            '/assets/images/portfolio2.png',
                            '/assets/images/portfolio3.png',
                            '/assets/images/portfolio4.png'
                        );
                        $img_src = get_template_directory_uri() . $fallback_imgs[$post_index % count($fallback_imgs)];
                    }

                    $video_src = get_post_meta( get_the_ID(), 'video_url', true );
                    $is_video = ! empty( $video_src ) || in_array( 'video', $cat_classes );
                    // Vary layouts dynamically
                    $grid_size = 'size-medium';
                    if ( $post_index % 3 == 0 ) {
                        $grid_size = 'size-tall';
                    } elseif ( $post_index % 3 == 1 ) {
                        $grid_size = 'size-wide';
                    }
                    ?>
                    <div class="portfolio-item <?php echo esc_attr( $grid_size . ' ' . $cat_class_str ); ?> scroll-reveal" 
                         data-reveal="fade-up" 
                         <?php if ( $is_video ) : ?>
                         data-video-src="<?php echo esc_url( $video_src ); ?>"
                         <?php else : ?>
                         data-src="<?php echo esc_url( $img_src ); ?>"
                         <?php endif; ?>
                         data-title="<?php echo esc_attr( get_the_title() ); ?>" 
                         data-category="<?php echo esc_attr( $cat_display ); ?>">
                      <div class="portfolio-media-wrapper">
                        <?php if ( $is_video ) : ?>
                        <video src="<?php echo esc_url( $video_src ); ?>" poster="<?php echo esc_url( $img_src ); ?>" muted loop playsinline class="portfolio-media video-preview"></video>
                        <div class="portfolio-overlay">
                          <div class="portfolio-info">
                            <span class="portfolio-cat"><?php echo $cat_display; ?></span>
                            <h3 class="portfolio-title"><?php the_title(); ?></h3>
                          </div>
                          <span class="portfolio-play-icon">▶</span>
                        </div>
                        <?php else : ?>
                        <img src="<?php echo esc_url( $img_src ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>" class="portfolio-media">
                        <div class="portfolio-overlay">
                          <div class="portfolio-info">
                            <span class="portfolio-cat"><?php echo $cat_display; ?></span>
                            <h3 class="portfolio-title"><?php the_title(); ?></h3>
                          </div>
                          <span class="portfolio-arrow">↗</span>
                        </div>
                        <?php endif; ?>
                      </div>
                    </div>
                    <?php
                endwhile;
                wp_reset_postdata();
            else :
                // FALLBACK STATIC SHOWCASE ITEMS
                ?>
                <div class="portfolio-item size-tall fashion scroll-reveal" data-reveal="fade-up" data-src="<?php echo get_template_directory_uri(); ?>/assets/images/portfolio1.png" data-title="CYBER SUITS" data-category="Fashion Editorial">
                  <div class="portfolio-media-wrapper">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/portfolio1.png" alt="Cyber Suits" class="portfolio-media">
                    <div class="portfolio-overlay">
                      <div class="portfolio-info">
                        <span class="portfolio-cat">FASHION</span>
                        <h3 class="portfolio-title">CYBER SUITS</h3>
                      </div>
                      <span class="portfolio-arrow">↗</span>
                    </div>
                  </div>
                </div>

                <div class="portfolio-item size-wide editorial scroll-reveal" data-reveal="fade-up" data-src="<?php echo get_template_directory_uri(); ?>/assets/images/portfolio2.png" data-title="REFRACTED LIGHT" data-category="Creative Portrait">
                  <div class="portfolio-media-wrapper">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/portfolio2.png" alt="Refracted Light" class="portfolio-media">
                    <div class="portfolio-overlay">
                      <div class="portfolio-info">
                        <span class="portfolio-cat">EDITORIAL</span>
                        <h3 class="portfolio-title">NEON RAIN</h3>
                      </div>
                      <span class="portfolio-arrow">↗</span>
                    </div>
                  </div>
                </div>

                <div class="portfolio-item size-medium video scroll-reveal" data-reveal="fade-up" data-video-src="https://assets.mixkit.co/videos/preview/mixkit-cinematic-shot-of-a-skater-performing-tricks-43306-large.mp4" data-title="SKATE CHRONICLES" data-category="Cinematic Video">
                  <div class="portfolio-media-wrapper">
                    <video src="https://assets.mixkit.co/videos/preview/mixkit-cinematic-shot-of-a-skater-performing-tricks-43306-large.mp4" poster="<?php echo get_template_directory_uri(); ?>/assets/images/portfolio3.png" muted loop playsinline class="portfolio-media video-preview"></video>
                    <div class="portfolio-overlay">
                      <div class="portfolio-info">
                        <span class="portfolio-cat">VIDEO</span>
                        <h3 class="portfolio-title">SKATE MOTION</h3>
                      </div>
                      <span class="portfolio-play-icon">▶</span>
                    </div>
                  </div>
                </div>

                <div class="portfolio-item size-wide editorial scroll-reveal" data-reveal="fade-up" data-src="<?php echo get_template_directory_uri(); ?>/assets/images/portfolio3.png" data-title="CONCRETE CURVES" data-category="Architectural Space">
                  <div class="portfolio-media-wrapper">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/portfolio3.png" alt="Concrete Curves" class="portfolio-media">
                    <div class="portfolio-overlay">
                      <div class="portfolio-info">
                        <span class="portfolio-cat">EDITORIAL</span>
                        <h3 class="portfolio-title">CONCRETE CURVES</h3>
                      </div>
                      <span class="portfolio-arrow">↗</span>
                    </div>
                  </div>
                </div>

                <div class="portfolio-item size-tall fashion editorial scroll-reveal" data-reveal="fade-up" data-src="<?php echo get_template_directory_uri(); ?>/assets/images/portfolio4.png" data-title="GLASS IDENTITY" data-category="Fashion Editorial">
                  <div class="portfolio-media-wrapper">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/portfolio4.png" alt="Glass Identity" class="portfolio-media">
                    <div class="portfolio-overlay">
                      <div class="portfolio-info">
                        <span class="portfolio-cat">FASHION</span>
                        <h3 class="portfolio-title">GLASS EYE</h3>
                      </div>
                      <span class="portfolio-arrow">↗</span>
                    </div>
                  </div>
                </div>

                <div class="portfolio-item size-medium video scroll-reveal" data-reveal="fade-up" data-video-src="https://assets.mixkit.co/videos/preview/mixkit-top-view-of-cars-on-a-highway-41852-large.mp4" data-title="METROPOLIS FLOW" data-category="Urban Cinematography">
                  <div class="portfolio-media-wrapper">
                    <video src="https://assets.mixkit.co/videos/preview/mixkit-top-view-of-cars-on-a-highway-41852-large.mp4" poster="<?php echo get_template_directory_uri(); ?>/assets/images/portfolio2.png" muted loop playsinline class="portfolio-media video-preview"></video>
                    <div class="portfolio-overlay">
                      <div class="portfolio-info">
                        <span class="portfolio-cat">CINEMATIC VIDEO</span>
                        <h3 class="portfolio-title">METROPOLIS</h3>
                      </div>
                      <span class="portfolio-play-icon">▶</span>
                    </div>
                  </div>
                </div>
                <?php
            endif;
            ?>

          </div>
        </div>
      </section>

      <!-- FOOTER -->
      <footer class="main-footer">
        <div class="section-container footer-content">
          <p>© <?php echo date('Y'); ?> <?php bloginfo( 'name' ); ?>. <?php esc_html_e( 'ALL RIGHTS RESERVED.', 'genz-portfolio-theme' ); ?></p>
        </div>
      </footer>

    </main>

    <!-- FULLSCREEN PORTFOLIO LIGHTBOX -->
    <div id="portfolio-lightbox" class="lightbox-overlay">
      <button class="lightbox-close" aria-label="<?php esc_attr_e( 'Close Lightbox', 'genz-portfolio-theme' ); ?>">×</button>
      <button class="lightbox-prev" aria-label="<?php esc_attr_e( 'Previous Slide', 'genz-portfolio-theme' ); ?>">←</button>
      <button class="lightbox-next" aria-label="<?php esc_attr_e( 'Next Slide', 'genz-portfolio-theme' ); ?>">→</button>
      <div class="lightbox-content">
        <!-- Media will be inserted here dynamically -->
      </div>
      <div class="lightbox-details">
        <span class="lightbox-category"></span>
        <h4 class="lightbox-title"></h4>
      </div>
    </div>

    <?php wp_footer(); ?>
  </body>
</html>
