/**
 * NexaTech Store — Frontend JavaScript
 * Menu mobile, categorias, header sticky, animações,
 * WooCommerce e slider de produtos.
 */

(function () {
  'use strict';

  function nexatechReady(callback) {
    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', callback);
    } else {
      callback();
    }
  }

  nexatechReady(function () {

    /* ═══════════════════════════════════════
       1. MENU MOBILE
    ═══════════════════════════════════════ */
    var menuToggle = document.getElementById('nexatech-mobile-toggle');
    var mobileMenu = document.getElementById('nexatech-mobile-menu');

    function mobileMenuIsOpen() {
      if (!mobileMenu) {
        return false;
      }

      return (
        mobileMenu.classList.contains('nexatech-block') ||
        mobileMenu.classList.contains('nt-open')
      );
    }

    function setMobileMenu(open) {
      if (!menuToggle || !mobileMenu) {
        return;
      }

      mobileMenu.classList.toggle('nexatech-hidden', !open);
      mobileMenu.classList.toggle('nexatech-block', open);
      mobileMenu.classList.toggle('nt-open', open);

      menuToggle.setAttribute('aria-expanded', open ? 'true' : 'false');
      menuToggle.setAttribute(
        'aria-label',
        open ? 'Fechar menu' : 'Abrir menu'
      );
    }

    if (menuToggle && mobileMenu) {
      menuToggle.addEventListener('click', function (event) {
        event.preventDefault();
        event.stopPropagation();

        setMobileMenu(!mobileMenuIsOpen());
      });

      mobileMenu.querySelectorAll('a').forEach(function (link) {
        link.addEventListener('click', function () {
          setMobileMenu(false);
        });
      });
    }


    /* ═══════════════════════════════════════
       2. DROPDOWN DE CATEGORIAS
    ═══════════════════════════════════════ */
    var categoryMenus = document.querySelectorAll('.nt-category-menu');

    categoryMenus.forEach(function (categoryMenu) {
      var trigger = categoryMenu.querySelector('.nt-category-trigger');

      if (!trigger) {
        return;
      }

      trigger.setAttribute('aria-expanded', 'false');

      trigger.addEventListener('click', function (event) {
        event.preventDefault();
        event.stopPropagation();

        var willOpen = !categoryMenu.classList.contains('nt-open');

        categoryMenus.forEach(function (otherMenu) {
          otherMenu.classList.remove('nt-open');

          var otherTrigger = otherMenu.querySelector(
            '.nt-category-trigger'
          );

          if (otherTrigger) {
            otherTrigger.setAttribute('aria-expanded', 'false');
          }
        });

        categoryMenu.classList.toggle('nt-open', willOpen);
        trigger.setAttribute(
          'aria-expanded',
          willOpen ? 'true' : 'false'
        );
      });
    });


    /* ═══════════════════════════════════════
       3. FECHAR MENUS AO CLICAR FORA
    ═══════════════════════════════════════ */
    document.addEventListener('click', function (event) {
      categoryMenus.forEach(function (categoryMenu) {
        if (!categoryMenu.contains(event.target)) {
          categoryMenu.classList.remove('nt-open');

          var trigger = categoryMenu.querySelector(
            '.nt-category-trigger'
          );

          if (trigger) {
            trigger.setAttribute('aria-expanded', 'false');
          }
        }
      });

      if (
        mobileMenu &&
        menuToggle &&
        mobileMenuIsOpen() &&
        !mobileMenu.contains(event.target) &&
        !menuToggle.contains(event.target)
      ) {
        setMobileMenu(false);
      }
    });


    /* ═══════════════════════════════════════
       4. FECHAR MENUS COM ESC
    ═══════════════════════════════════════ */
    document.addEventListener('keydown', function (event) {
      if (event.key !== 'Escape') {
        return;
      }

      setMobileMenu(false);

      categoryMenus.forEach(function (categoryMenu) {
        categoryMenu.classList.remove('nt-open');

        var trigger = categoryMenu.querySelector(
          '.nt-category-trigger'
        );

        if (trigger) {
          trigger.setAttribute('aria-expanded', 'false');
        }
      });
    });


    /* ═══════════════════════════════════════
       5. HEADER AO FAZER SCROLL
    ═══════════════════════════════════════ */
    var header = document.getElementById('nexatech-header');

    function updateHeader() {
      if (!header) {
        return;
      }

      header.classList.toggle(
        'nexatech-scrolled',
        window.scrollY > 20
      );
    }

    if (header) {
      updateHeader();

      window.addEventListener('scroll', updateHeader, {
        passive: true
      });
    }


    /* ═══════════════════════════════════════
       6. SCROLL SUAVE PARA ÂNCORAS
    ═══════════════════════════════════════ */
    document.querySelectorAll('a[href^="#"]').forEach(function (anchor) {
      anchor.addEventListener('click', function (event) {
        var href = anchor.getAttribute('href');

        if (!href || href === '#') {
          return;
        }

        var target;

        try {
          target = document.querySelector(href);
        } catch (error) {
          return;
        }

        if (!target) {
          return;
        }

        event.preventDefault();

        target.scrollIntoView({
          behavior: 'smooth',
          block: 'start'
        });
      });
    });


    /* ═══════════════════════════════════════
       7. ANIMAÇÕES AO APARECER NA TELA
    ═══════════════════════════════════════ */
    var animatedElements = document.querySelectorAll(
      '.nexatech-animate'
    );

    if ('IntersectionObserver' in window) {
      var observer = new IntersectionObserver(
        function (entries) {
          entries.forEach(function (entry) {
            if (entry.isIntersecting) {
              entry.target.classList.add(
                'nexatech-visible'
              );

              observer.unobserve(entry.target);
            }
          });
        },
        {
          threshold: 0.12,
          rootMargin: '0px 0px -30px 0px'
        }
      );

      animatedElements.forEach(function (element) {
        observer.observe(element);
      });
    } else {
      animatedElements.forEach(function (element) {
        element.classList.add('nexatech-visible');
      });
    }


    /* ═══════════════════════════════════════
       8. SLIDER “MAIS VENDIDOS”
    ═══════════════════════════════════════ */
    var sliders = document.querySelectorAll(
      '[data-nexatech-slider]'
    );

    sliders.forEach(function (slider) {
      var wrapper = slider.closest('.nt-slider-wrap');

      if (!wrapper) {
        return;
      }

      var previousButton = wrapper.querySelector('.nt-slider-prev');
      var nextButton = wrapper.querySelector('.nt-slider-next');
      var updateFrame = null;

      function getSliderGap() {
        var sliderStyle = window.getComputedStyle(slider);

        var gapValue =
          sliderStyle.columnGap ||
          sliderStyle.gap ||
          '0';

        var parsedGap = parseFloat(gapValue);

        return Number.isNaN(parsedGap) ? 0 : parsedGap;
      }

      function getSlideStep() {
        var firstSlide = slider.querySelector('.nt-slide-item');

        if (!firstSlide) {
          return Math.max(slider.clientWidth * 0.8, 280);
        }

        return firstSlide.getBoundingClientRect().width +
          getSliderGap();
      }

      function getMaximumScroll() {
        return Math.max(
          0,
          slider.scrollWidth - slider.clientWidth
        );
      }

      function updateSliderButtons() {
        var maximumScroll = getMaximumScroll();
        var currentScroll = slider.scrollLeft;
        var hasOverflow = maximumScroll > 4;

        wrapper.classList.toggle(
          'nt-slider-static',
          !hasOverflow
        );

        if (previousButton) {
          previousButton.disabled =
            !hasOverflow || currentScroll <= 4;
        }

        if (nextButton) {
          nextButton.disabled =
            !hasOverflow ||
            currentScroll >= maximumScroll - 4;
        }
      }

      function scheduleButtonUpdate() {
        if (updateFrame) {
          window.cancelAnimationFrame(updateFrame);
        }

        updateFrame = window.requestAnimationFrame(
          updateSliderButtons
        );
      }

      function moveSlider(direction) {
        var movement = getSlideStep() * direction;

        slider.scrollBy({
          left: movement,
          behavior: 'smooth'
        });

        window.setTimeout(updateSliderButtons, 450);
      }

      if (previousButton) {
        previousButton.addEventListener('click', function () {
          moveSlider(-1);
        });
      }

      if (nextButton) {
        nextButton.addEventListener('click', function () {
          moveSlider(1);
        });
      }

      slider.addEventListener('scroll', scheduleButtonUpdate, {
        passive: true
      });

      slider.addEventListener('keydown', function (event) {
        if (event.key === 'ArrowLeft') {
          event.preventDefault();
          moveSlider(-1);
        }

        if (event.key === 'ArrowRight') {
          event.preventDefault();
          moveSlider(1);
        }
      });

      window.addEventListener('resize', scheduleButtonUpdate, {
        passive: true
      });

      window.setTimeout(updateSliderButtons, 100);
      window.setTimeout(updateSliderButtons, 600);
    });


    /* ═══════════════════════════════════════
       9. FEEDBACK DO BOTÃO “ADICIONAR”
    ═══════════════════════════════════════ */
    document.addEventListener('click', function (event) {
      var button = event.target.closest(
        '.nexatech-add-to-cart'
      );

      if (!button) {
        return;
      }

      if (!button.dataset.originalText) {
        button.dataset.originalText =
          button.textContent.trim();
      }

      button.textContent = 'A adicionar…';

      window.setTimeout(function () {
        button.textContent = '✓ Adicionado';
        button.classList.add('nexatech-btn-success');
      }, 250);

      window.setTimeout(function () {
        button.textContent =
          button.dataset.originalText || 'Adicionar';

        button.classList.remove('nexatech-btn-success');
      }, 1900);
    });


    /* ═══════════════════════════════════════
       10. ANIMAÇÃO DA CONTAGEM DO CARRINHO
    ═══════════════════════════════════════ */
    function pulseCartCount() {
      var cartCounts = document.querySelectorAll(
        '.woocommerce-cart-count, .nt-cart-count'
      );

      cartCounts.forEach(function (cartCount) {
        cartCount.classList.remove('nexatech-cart-pulse');

        void cartCount.offsetWidth;

        cartCount.classList.add('nexatech-cart-pulse');

        window.setTimeout(function () {
          cartCount.classList.remove(
            'nexatech-cart-pulse'
          );
        }, 650);
      });
    }

    document.body.addEventListener(
      'wc_fragment_refresh',
      pulseCartCount
    );

    document.body.addEventListener(
      'wc_fragments_refreshed',
      pulseCartCount
    );


    /* ═══════════════════════════════════════
       11. EVENTOS JQUERY DO WOOCOMMERCE
    ═══════════════════════════════════════ */
    if (window.jQuery) {
      window.jQuery(function ($) {
        $(document.body).on(
          'added_to_cart wc_fragments_refreshed updated_wc_div',
          function () {
            pulseCartCount();
          }
        );
      });
    }

  });
})();