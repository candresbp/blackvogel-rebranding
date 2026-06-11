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
// CACHE BUSTER — solo durante desarrollo, quitar antes de entregar
// ============================================================
function child_style() {
    wp_enqueue_style( 'parent-child', get_stylesheet_uri().'?'.time() );
}
add_action( 'wp_enqueue_scripts', 'child_style', 20 );