<?php
/**
 * Astra Child — BlackVogel
 * functions.php
 *
 * Aquí van:
 *  1. Cargar los estilos del tema padre + hijo (obligatorio)
 *  2. Encolar Google Fonts
 *  3. Cualquier función PHP custom del proyecto
 */

add_action( 'wp_enqueue_scripts', 'astra_child_enqueue_styles' );

function astra_child_enqueue_styles() {

    // Estilo del tema PADRE (Astra)
    wp_enqueue_style(
        'astra-parent-style',          // handle único
        get_template_directory_uri() . '/style.css'  // ruta al padre
    );

    // Estilo del CHILD THEME (nuestro style.css)
    wp_enqueue_style(
        'astra-child-style',           // handle único
        get_stylesheet_directory_uri() . '/style.css',
        array( 'astra-parent-style' ), // depende del padre (se carga después)
        wp_get_theme()->get( 'Version' )
    );

}

// ============================================================
// 2. GOOGLE FONTS — Cormorant Garamond + Inter
// ============================================================
add_action( 'wp_enqueue_scripts', 'bv_enqueue_google_fonts' );

function bv_enqueue_google_fonts() {
    wp_enqueue_style(
        'bv-google-fonts',
        'https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;1,300;1,400&family=Inter:wght@300;400;500;600&display=swap',
        array(),
        null
    );
}

// ============================================================
// 3. SOPORTE DE TEMA — funciones opcionales
// ============================================================

// Habilitar imágenes destacadas (thumbnails) en posts y páginas
add_theme_support( 'post-thumbnails' );

// Tamaños de imagen custom para el proyecto
add_image_size( 'bv-card',    600, 400, true );  // cards de Our Work
add_image_size( 'bv-hero',   1920, 900, true );  // hero full-width
add_image_size( 'bv-portrait', 600, 800, true );  // foto Mariana

// ============================================================
// 4. FUNCIONES CUSTOM (agregar aquí a medida que avancemos)
// ============================================================

// Ejemplo: shortcode para el año actual en el footer
// Uso: [bv_year]
add_shortcode( 'bv_year', function() {
    return date( 'Y' );
});


// // Border top gradiente del footer
// add_action( 'astra_footer_before', function() {
//     echo '<div class="gradient"><div class="bv-border-top"></div></div>';
// });

// test
add_action('wp_footer', function() {
    ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const target = document.querySelector('#speaker');

            if(target){
                target.insertAdjacentHTML(
                    'beforeend',
                    '<div class="gradient"><div class="bv-spaeker-border-bot"></div></div>'
                );
            }
        });
    </script>
    <?php
});


//Splide para Sliders

add_action( 'wp_enqueue_scripts', 'bv_enqueue_splide' );
function bv_enqueue_splide() {
    wp_enqueue_style(
        'splide',
        'https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/css/splide.min.css',
        array(),
        '4.1.4'
    );
    wp_enqueue_script(
        'splide',
        'https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/js/splide.min.js',
        array(),
        '4.1.4',
        true
    );
}


// ============================================================
// TEAM SLIDER — Fix de inicialización Splide (The Raven Team)
// ============================================================
add_action( 'wp_footer', function() {
    if ( ! is_page( 2136 ) ) return;
    ?>
    <script>
    (function(){
      'use strict';

      var _flipped = null;

      function bvFlip(card){
        if(_flipped && _flipped !== card) _flipped.classList.remove('is-flipped');
        card.classList.toggle('is-flipped');
        _flipped = card.classList.contains('is-flipped') ? card : null;
      }

      function bvWireFlip(sliderEl){
        sliderEl.querySelectorAll('.bv-flip-card').forEach(function(card){
          ['bv-flip-more','bv-flip-close'].forEach(function(cls){
            card.querySelectorAll('.'+cls).forEach(function(el){
              var fresh = el.cloneNode(true);
              el.parentNode.replaceChild(fresh, el);
              fresh.addEventListener('click', function(e){
                e.preventDefault();
                e.stopPropagation();
                bvFlip(card);
              });
            });
          });
        });
      }

      function bvInitSlider(){
        var sliderEl = document.querySelector('.bv-team-slider');
        if(!sliderEl || typeof Splide === 'undefined') return;

        var track = sliderEl.querySelector('.splide__track');
        if(!track){
          console.warn('[BVTeam] sin .splide__track');
          return;
        }

        /* Limpiar estado previo de Splide para re-montar limpio */
        ['is-initialized','is-active','is-overflow'].forEach(function(c){
          sliderEl.classList.remove(c);
        });
        sliderEl.querySelectorAll('.splide__slide').forEach(function(s){
          s.style.width      = '';
          s.style.marginRight = '';
        });
        var list = sliderEl.querySelector('.splide__list');
        if(list){ list.style.transform = ''; list.style.width = ''; }

        var sp = new Splide(sliderEl, {
          type:    'slide',
          perPage: 3,
          perMove: 1,
          gap:     '24px',
          pagination: true,
          arrows:  false,
          drag:    true,
          breakpoints: {
            1024: { perPage: 2 },
            768:  { perPage: 1 }
          }
        });

        sp.mount();
        sliderEl.style.visibility = 'visible';
        sliderEl._bvSp = sp;

        /* Botones prev / next */
        ['bv-team-prev','bv-team-next'].forEach(function(cls, i){
          var btn = document.querySelector('.'+cls);
          if(!btn) return;
          var fresh = btn.cloneNode(true);
          btn.parentNode.replaceChild(fresh, btn);
          fresh.addEventListener('click', function(){ sp.go(i===0?'<':'>'); });
        });

        /* Flip */
        bvWireFlip(sliderEl);

        console.log('[BVTeam] montado con perPage:3');
      }

      /* Esperar window.load + 400 ms para garantizar que el inline script
         (DOMContentLoaded + setTimeout 150ms) ya terminó */
      window.addEventListener('load', function(){
        setTimeout(bvInitSlider, 400);
      });

    })();
    </script>
    <?php
}, 999 );


// ============================================================
// CACHE BUSTER — solo durante desarrollo, quitar antes de entregar
// ============================================================
function child_style() {
    wp_enqueue_style( 'parent-child', get_stylesheet_uri().'?'.time() );
}
add_action( 'wp_enqueue_scripts', 'child_style', 20 );