/* ==========================================================================
   GEN-Z PORTFOLIO ELEMENTOR FRONTEND SCRIPTS
   ========================================================================== */

(function($) {
  'use strict';

  // Mouse coordinate tracking for global interactions
  const mouse = { x: 0, y: 0, targetX: 0, targetY: 0 };
  
  window.addEventListener('mousemove', (e) => {
    mouse.targetX = e.clientX;
    mouse.targetY = e.clientY;
  });

  // Helper function: Magnetic attraction logic
  const applyMagneticEffect = ($scope) => {
    const magnetics = $scope.find('.magnetic');
    
    magnetics.each(function() {
      const el = $(this);
      const strengthAttr = parseFloat(el.data('magnetic-strength') || 0.4);

      el.on('mousemove', function(e) {
        const rect = this.getBoundingClientRect();
        const elX = rect.left + rect.width / 2;
        const elY = rect.top + rect.height / 2;
        
        const deltaX = e.clientX - elX;
        const deltaY = e.clientY - elY;
        
        const strength = strengthAttr * 1.0; // Multiplying by a baseline factor
        
        el.css('transform', `translate(${deltaX * strength}px, ${deltaY * strength}px)`);

        // If custom cursor is active, snap its target coordinates
        const ring = $('#wp-custom-cursor-ring');
        if (ring.length) {
          mouse.targetX = elX + (deltaX * strength * 0.5);
          mouse.targetY = elY + (deltaY * strength * 0.5);
        }
      });

      el.on('mouseleave', function() {
        el.css('transform', 'translate(0px, 0px)');
      });
    });
  };

  // Helper function: Add cursor morph states on hover
  const applyCursorHoverMorphs = ($scope) => {
    const hoverables = $scope.find('a, button, .filter-btn, .wp-service-card, .wp-portfolio-item');
    
    hoverables.on('mouseenter', function() {
      $('body').addClass('wp-cursor-hover');
    });
    
    hoverables.on('mouseleave', function() {
      $('body').removeClass('wp-cursor-hover');
    });
  };

  // ---------------------------------------------------------
  // 1. CURSOR WIDGET HANDLER
  // ---------------------------------------------------------
  const GenzCursorHandler = function($scope) {
    // Remove duplicate cursors if any
    $('#wp-custom-cursor-dot, #wp-custom-cursor-ring').remove();

    const enabled = $scope.find('.wp-cursor-config').data('enable') === 'yes';
    if (!enabled) {
      $('body').removeClass('wp-custom-cursor-enabled');
      return;
    }

    $('body').addClass('wp-custom-cursor-enabled');
    $('body').append('<div id="wp-custom-cursor-ring"></div><div id="wp-custom-cursor-dot"></div>');

    const dot = $('#wp-custom-cursor-dot');
    const ring = $('#wp-custom-cursor-ring');

    const size = $scope.find('.wp-cursor-config').data('size') || 8;
    const ringSize = $scope.find('.wp-cursor-config').data('ring-size') || 40;
    const glow = $scope.find('.wp-cursor-config').data('glow') || 0.6;
    const color = $scope.find('.wp-cursor-config').data('color') || '#B8FF00';

    document.documentElement.style.setProperty('--cursor-size', `${size}px`);
    document.documentElement.style.setProperty('--cursor-ring-size', `${ringSize}px`);
    document.documentElement.style.setProperty('--cursor-glow', glow);
    document.documentElement.style.setProperty('--cursor-color', color);

    let ringX = 0, ringY = 0;
    let dotX = 0, dotY = 0;

    const animateCursor = () => {
      if (!$('body').hasClass('wp-custom-cursor-enabled')) return;

      dotX += (mouse.targetX - dotX) * 0.35;
      dotY += (mouse.targetY - dotY) * 0.35;
      dot.css({ left: `${dotX}px`, top: `${dotY}px` });

      ringX += (mouse.targetX - ringX) * 0.15;
      ringY += (mouse.targetY - ringY) * 0.15;
      ring.css({ left: `${ringX}px`, top: `${ringY}px` });

      requestAnimationFrame(animateCursor);
    };

    animateCursor();
    applyCursorHoverMorphs($scope);
    applyMagneticEffect($scope);
  };

  // ---------------------------------------------------------
  // 2. HERO WIDGET HANDLER (Anti-Gravity elements)
  // ---------------------------------------------------------
  const GenzHeroHandler = function($scope) {
    applyCursorHoverMorphs($scope);
    applyMagneticEffect($scope);

    const tags = $scope.find('.wp-floating-tag');
    const container = $scope.find('.wp-floating-tags-container');
    const config = container.data('config') || {};

    if (config.enable !== 'yes') {
      tags.css('transform', 'translate(0px, 0px)');
      return;
    }

    let floatTime = 0;
    const animateFloating = () => {
      if (!$.contains(document, container[0])) return; // Exit if element removed

      floatTime += 0.005 * (config.speed || 2.0);

      tags.each(function(idx) {
        const tag = $(this);
        const speed = parseFloat(tag.data('speed') || 1.0);
        const depth = parseFloat(tag.data('depth') || 0.08);
        const dir = parseInt(tag.data('dir') || 1);
        const distance = config.distance || 50;

        // Trigonometric orbit equations
        const xOrbit = Math.cos(floatTime * speed) * distance * dir;
        const yOrbit = Math.sin(floatTime * speed * 1.2) * distance * dir;

        // Mouse follow offset vectors
        const dx = (mouse.targetX - window.innerWidth / 2) * depth * (config.parallax || 0.5);
        const dy = (mouse.targetY - window.innerHeight / 2) * depth * (config.parallax || 0.5);

        tag.css('transform', `translate(${xOrbit + dx}px, ${yOrbit + dy}px)`);
      });

      requestAnimationFrame(animateFloating);
    };

    animateFloating();
  };

  // ---------------------------------------------------------
  // 3. ABOUT WIDGET HANDLER
  // ---------------------------------------------------------
  const GenzAboutHandler = function($scope) {
    applyCursorHoverMorphs($scope);
    applyMagneticEffect($scope);

    // Speed setting for rotating badge
    const badgeSvg = $scope.find('.wp-rotating-badge-svg');
    const badgeSpeed = badgeSvg.parent().data('speed') || 10;
    badgeSvg.css('animation-duration', `${160 / badgeSpeed}s`);

    // Counter animations on reveal
    const stats = $scope.find('.wp-stat-box');
    let animated = false;

    const runCounters = () => {
      stats.each(function() {
        const box = $(this);
        const target = parseInt(box.data('stat-target') || 0);
        const counterText = box.find('.wp-counter');
        
        let current = 0;
        const duration = 1500;
        const step = Math.max(Math.floor(duration / target), 15);

        const timer = setInterval(() => {
          current += 1;
          counterText.text(current);
          if (current >= target) {
            counterText.text(target);
            clearInterval(timer);
          }
        }, step);
      });
    };

    // Trigger waypoint countup
    if (typeof elementorModules !== 'undefined') {
      new Waypoint({
        element: $scope[0],
        handler: function() {
          if (!animated) {
            animated = true;
            runCounters();
          }
        },
        offset: '75%'
      });
    } else {
      runCounters(); // Fallback if Waypoints not loaded
    }
  };

  // ---------------------------------------------------------
  // 4. PORTFOLIO WIDGET HANDLER (Filters & Lightbox)
  // ---------------------------------------------------------
  const GenzPortfolioHandler = function($scope) {
    applyCursorHoverMorphs($scope);
    applyMagneticEffect($scope);

    const filterBtns = $scope.find('.filter-btn');
    const items = $scope.find('.wp-portfolio-item');

    filterBtns.on('click', function() {
      filterBtns.removeClass('active');
      $(this).addClass('active');

      const filterVal = $(this).data('filter');

      items.each(function() {
        const item = $(this);
        if (filterVal === 'all' || item.hasClass(filterVal)) {
          item.removeClass('filtered-out');
        } else {
          item.addClass('filtered-out');
        }
      });
    });

    // Hover Video previews autoplay
    items.each(function() {
      const item = $(this);
      const video = item.find('video');
      if (video.length) {
        item.on('mouseenter', () => video[0].play());
        item.on('mouseleave', () => {
          video[0].pause();
          video[0].currentTime = 0;
        });
      }
    });

    // Lightbox modal setup
    const itemsWrapper = $scope.find('.wp-portfolio-grid');
    
    // Add dynamic lightbox if not present on page
    if ($('#wp-portfolio-lightbox').length === 0) {
      $('body').append(`
        <div id="wp-portfolio-lightbox" class="lightbox-overlay">
          <button class="lightbox-close">×</button>
          <div class="lightbox-content"></div>
          <div class="lightbox-details">
            <span class="lightbox-category"></span>
            <h4 class="lightbox-title"></h4>
          </div>
        </div>
      `);
    }

    const lightbox = $('#wp-portfolio-lightbox');
    const lightboxContent = lightbox.find('.lightbox-content');
    const lightboxClose = lightbox.find('.lightbox-close');
    const lightboxCat = lightbox.find('.lightbox-category');
    const lightboxTitle = lightbox.find('.lightbox-title');

    items.on('click', function() {
      const item = $(this);
      const src = item.data('src');
      const videoSrc = item.data('video-src');
      const title = item.data('title');
      const category = item.data('category');

      lightboxContent.empty();

      if (videoSrc) {
        lightboxContent.append(`<video src="${videoSrc}" controls autoplay loop></video>`);
      } else if (src) {
        lightboxContent.append(`<img src="${src}" alt="${title}">`);
      }

      lightboxCat.text(category);
      lightboxTitle.text(title);
      lightbox.addClass('active');
    });

    lightboxClose.on('click', () => {
      lightbox.removeClass('active');
      lightboxContent.empty();
    });

    lightbox.on('click', function(e) {
      if (e.target === this || $(e.target).hasClass('lightbox-content')) {
        lightbox.removeClass('active');
        lightboxContent.empty();
      }
    });
  };

  // ---------------------------------------------------------
  // 5. SERVICES CARD FLOOD CONFIGS
  // ---------------------------------------------------------
  const GenzServicesHandler = function($scope) {
    applyCursorHoverMorphs($scope);
    applyMagneticEffect($scope);

    const grid = $scope.find('.wp-services-grid');
    const speed = grid.data('speed') || 400;
    const direction = grid.data('direction') || 'up';
    const floodColor = grid.data('flood-color') || '#B8FF00';

    grid.find('.wp-service-flood-bg').css('background-color', floodColor);
    
    grid.find('.wp-service-card').each(function() {
      const card = $(this);
      card.removeClass('wp-flood-down wp-flood-right wp-flood-left');
      if (direction !== 'up') {
        card.addClass(`wp-flood-${direction}`);
      }
    });

    $scope.css('--services-speed', `${speed}ms`);
  };

  // ---------------------------------------------------------
  // 6. TESTIMONIALS SLIDER
  // ---------------------------------------------------------
  const GenzTestimonialsHandler = function($scope) {
    applyCursorHoverMorphs($scope);
    applyMagneticEffect($scope);

    const container = $scope.find('.wp-testimonials-container');
    const slider = $scope.find('.wp-testimonials-slider');
    const slides = $scope.find('.wp-testimonial-slide');
    const prevBtn = $scope.find('.prev-btn');
    const nextBtn = $scope.find('.next-btn');

    if (!slider.length) return;

    const transitionSpeed = container.data('transition') || 600;
    $scope.css('--slider-speed', `${transitionSpeed}ms`);

    let currentSlide = 0;
    const slideCount = slides.length;
    let timer;

    const getTranslateX = () => {
      const slideWidth = slides.eq(0).outerWidth(true);
      return -currentSlide * slideWidth;
    };

    const updateSlider = () => {
      slider.css('transform', `translateX(${getTranslateX()}px)`);
    };

    const next = () => {
      currentSlide = (currentSlide + 1) % slideCount;
      updateSlider();
    };

    const prev = () => {
      currentSlide = (currentSlide - 1 + slideCount) % slideCount;
      updateSlider();
    };

    const startTimer = () => {
      clearInterval(timer);
      timer = setInterval(next, 5000);
    };

    nextBtn.on('click', () => {
      next();
      startTimer();
    });

    prevBtn.on('click', () => {
      prev();
      startTimer();
    });

    // Simple Drag & Swipe logic
    let startX = 0, isDragging = false, currentX = 0;

    slider.on('mousedown touchstart', function(e) {
      clearInterval(timer);
      startX = e.pageX || e.originalEvent.touches[0].pageX;
      isDragging = true;
    });

    $(window).on('mousemove touchmove', function(e) {
      if (!isDragging) return;
      const x = e.pageX || e.originalEvent.touches[0].pageX;
      const walk = (x - startX) * 0.7;
      slider.css('transform', `translateX(${getTranslateX() + walk}px)`);
    });

    $(window).on('mouseup touchend', function(e) {
      if (!isDragging) return;
      isDragging = false;
      const endX = e.pageX || e.originalEvent.changedTouches[0].pageX;
      const diff = endX - startX;

      if (Math.abs(diff) > 50) {
        if (diff < 0) {
          next();
        } else {
          prev();
        }
      } else {
        updateSlider();
      }
      startTimer();
    });

    startTimer();
  };

  // ---------------------------------------------------------
  // 7. MARQUEE TICKER HANDLER
  // ---------------------------------------------------------
  const GenzMarqueeHandler = function($scope) {
    applyCursorHoverMorphs($scope);
    const wrap = $scope.find('.wp-marquee-wrap');
    const speed = wrap.data('speed') || 20;
    
    wrap.css('--marquee-duration', `${speed}s`);
  };

  // ---------------------------------------------------------
  // 8. BACKGROUND EFFECTS HANDLER (CANVAS DRAWINGS)
  // ---------------------------------------------------------
  const GenzBackgroundHandler = function($scope) {
    const canvasWrap = $scope.find('.wp-bg-canvas-wrapper');
    const enableParticles = canvasWrap.data('particles') === 'yes';
    const enableAurora = canvasWrap.data('aurora') === 'yes';
    const enableBlobs = canvasWrap.data('blobs') === 'yes';
    const speed = parseFloat(canvasWrap.data('speed') || 3.0);
    const accent = canvasWrap.data('color') || '#B8FF00';

    const canvas = canvasWrap.find('canvas')[0];
    if (!canvas) return;

    const ctx = canvas.getContext('2d');
    let width = canvas.width = canvasWrap.width();
    let height = canvas.height = canvasWrap.height();

    $(window).on('resize', () => {
      width = canvas.width = canvasWrap.width();
      height = canvas.height = canvasWrap.height();
    });

    // Particle pool setup
    const particles = [];
    if (enableParticles) {
      for (let i = 0; i < 40; i++) {
        particles.push({
          x: Math.random() * width,
          y: height + Math.random() * 50,
          size: Math.random() * 2 + 0.5,
          speedY: -(Math.random() * 1.2 + 0.3),
          alpha: Math.random() * 0.35 + 0.1,
          angle: Math.random() * Math.PI
        });
      }
    }

    // Convert hex accent to RGB
    const hexToRgb = (hex) => {
      const r = parseInt(hex.slice(1, 3), 16);
      const g = parseInt(hex.slice(3, 5), 16);
      const b = parseInt(hex.slice(5, 7), 16);
      return `${r}, ${g}, ${b}`;
    };
    const rgb = hexToRgb(accent);

    // Vector Blobs setup
    const blobs = [
      { x: width * 0.3, y: height * 0.4, r: 120, a: 0 },
      { x: width * 0.7, y: height * 0.6, r: 160, a: Math.PI }
    ];

    let t = 0;
    let auroraAngle = 0;

    const drawLoop = () => {
      if (!$.contains(document, canvas)) return; // Stop loop if removed

      t++;
      ctx.clearRect(0, 0, width, height);

      // 1. Draw Aurora Gradient Mesh
      if (enableAurora) {
        auroraAngle += 0.002 * speed;
        const ax = width / 2 + Math.cos(auroraAngle) * (width * 0.15);
        const ay = height / 2 + Math.sin(auroraAngle * 1.3) * (height * 0.15);

        const grad = ctx.createRadialGradient(ax, ay, 20, ax, ay, Math.max(width, height) * 0.5);
        grad.addColorStop(0, `rgba(${rgb}, 0.06)`);
        grad.addColorStop(0.4, `rgba(${rgb}, 0.01)`);
        grad.addColorStop(1, 'rgba(0,0,0,0)');

        ctx.fillStyle = grad;
        ctx.fillRect(0, 0, width, height);
      }

      // 2. Draw Vector Blobs
      if (enableBlobs) {
        blobs.forEach(b => {
          b.a += 0.003 * speed;
          const wobble = Math.sin(b.a) * 15;
          const radius = b.r + wobble;

          ctx.fillStyle = `rgba(${rgb}, 0.03)`;
          ctx.beginPath();
          for (let i = 0; i < 6; i++) {
            const currAngle = (i / 6) * Math.PI * 2;
            const offset = Math.sin(t * 0.001 + i) * 10;
            const px = b.x + Math.cos(currAngle) * (radius + offset);
            const py = b.y + Math.sin(currAngle) * (radius + offset);
            if (i === 0) ctx.moveTo(px, py);
            else ctx.lineTo(px, py);
          }
          ctx.closePath();
          ctx.fill();
        });
      }

      // 3. Draw Particles
      if (enableParticles) {
        ctx.fillStyle = `rgba(${rgb}, 0.3)`;
        particles.forEach(p => {
          p.y += p.speedY;
          p.x += Math.sin(p.angle) * 0.2;
          p.angle += 0.01;

          if (p.y < -10) {
            p.y = height + 10;
            p.x = Math.random() * width;
          }

          ctx.beginPath();
          ctx.arc(p.x, p.y, p.size, 0, Math.PI * 2);
          ctx.fillStyle = `rgba(${rgb}, ${p.alpha})`;
          ctx.fill();
        });
      }

      requestAnimationFrame(drawLoop);
    };

    drawLoop();
  };

  // ---------------------------------------------------------
  // BIND ELEMENTOR FRONTEND LIFE CYCLE HOOKS
  // ---------------------------------------------------------
  $(window).on('elementor/frontend/init', function() {
    elementorFrontend.hooks.addAction('frontend/element_ready/genz-cursor-widget.default', GenzCursorHandler);
    elementorFrontend.hooks.addAction('frontend/element_ready/genz-hero-widget.default', GenzHeroHandler);
    elementorFrontend.hooks.addAction('frontend/element_ready/genz-about-widget.default', GenzAboutHandler);
    elementorFrontend.hooks.addAction('frontend/element_ready/genz-portfolio-widget.default', GenzPortfolioHandler);
    elementorFrontend.hooks.addAction('frontend/element_ready/genz-services-widget.default', GenzServicesHandler);
    elementorFrontend.hooks.addAction('frontend/element_ready/genz-testimonials-widget.default', GenzTestimonialsHandler);
    elementorFrontend.hooks.addAction('frontend/element_ready/genz-marquee-widget.default', GenzMarqueeHandler);
    elementorFrontend.hooks.addAction('frontend/element_ready/genz-background-effects.default', GenzBackgroundHandler);
  });

})(jQuery);
