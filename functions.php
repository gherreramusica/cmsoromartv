<?php
function mi_tema_tailwind_styles() {
  wp_enqueue_style('tailwindcss', get_template_directory_uri() . '/style.css');
}
add_action('wp_enqueue_scripts', 'mi_tema_tailwind_styles');

add_action('add_meta_boxes', function() {
  add_meta_box('postimagediv', __('Imagen destacada'), 'post_thumbnail_meta_box', 'programa', 'side', 'low');
});

add_theme_support('post-thumbnails');



// Registro de CPT: Programas
function oromarplay_register_programas() {
  register_post_type('programa', array(
    'labels' => array(
      'name' => 'Programas',
      'singular_name' => 'Programa'
    ),
    'public' => true,
    'has_archive' => true,
    'rewrite' => array('slug' => 'programas'),
    'menu_icon' => 'dashicons-video-alt2',
    'supports' => array('title', 'editor', 'thumbnail'),
    'show_in_rest' => true,
  ));
}
add_action('init', 'oromarplay_register_programas');

// Registro de CPT: Episodios
function oromarplay_register_episodios() {
  register_post_type('episodio', array(
    'labels' => array(
      'name' => 'Episodios',
      'singular_name' => 'Episodio'
    ),
    'public' => true,
    'has_archive' => true,
    'rewrite' => array('slug' => 'episodios'),
    'menu_icon' => 'dashicons-controls-play',
    'supports' => array('title', 'editor', 'thumbnail'),
    'show_in_rest' => true,
  ));
}
add_action('init', 'oromarplay_register_episodios');

// Registro de CPT: Shorts
function oromarplay_register_shorts() {
  register_post_type('short', array(
    'labels' => array(
      'name' => 'Shorts',
      'singular_name' => 'Short'
    ),
    'public' => true,
    'has_archive' => true,
    'rewrite' => array('slug' => 'shorts'),
    'menu_icon' => 'dashicons-format-video',
    'supports' => array('title', 'editor', 'thumbnail'),
    'show_in_rest' => true,
  ));
}
add_action('init', 'oromarplay_register_shorts');

function filter_episodios_by_programa_relacionado( $args, $request ) {
  if ( ! empty( $request['programa_relacionado'] ) ) {
    $args['meta_query'] = [
      [
        'key' => 'programa_relacionado',
        'value' => (int) $request['programa_relacionado'],
        'compare' => '='
      ]
    ];
  }
  return $args;
}
add_filter( 'rest_episodio_query', 'filter_episodios_by_programa_relacionado', 10, 2 );

function oromar_add_programa_relacionado_filter() {
  // Expone el campo ACF en la respuesta
  register_rest_field('episodio', 'programa_relacionado', [
    'get_callback' => function ($post_arr) {
      return get_field('programa_relacionado', $post_arr['id']);
    },
    'schema' => null,
  ]);

  // Permite filtrar episodios por el campo programa_relacionado
  add_filter('rest_episodio_query', function ($args, $request) {
    if (!empty($request['programa_relacionado'])) {
      $args['meta_query'][] = [
        'key' => 'programa_relacionado',
        'value' => intval($request['programa_relacionado']),
        'compare' => '='
      ];
    }
    return $args;
  }, 10, 2);
}
add_action('rest_api_init', 'oromar_add_programa_relacionado_filter');

function agregar_banner_a_programas() {
  register_rest_field('programas', 'banner', [
    'get_callback' => function($post_arr) {
      $id = get_field('banner', $post_arr['id']);
      return $id ? wp_get_attachment_url($id) : '';
    },
    'schema' => null,
  ]);
}
add_action('rest_api_init', 'agregar_banner_a_programas');

function exponer_external_featured_image() {
  register_rest_field('programas', 'external_image', [
    'get_callback' => function($post_arr) {
      return get_field('external_featured_image', $post_arr['id']);
    },
    'schema' => null,
  ]);
}
add_action('rest_api_init', 'exponer_external_featured_image');
