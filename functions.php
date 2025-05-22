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

function exponer_banner_url_programa() {
  register_rest_field('programa', 'banner_url', [
    'get_callback' => function($post_arr) {
      $id = get_field('banner', $post_arr['id']);
      if (is_numeric($id)) {
        $url = wp_get_attachment_url((int) $id);
        return $url ?: '';
      }
      return '';
    },
    'schema' => null,
  ]);
}
add_action('rest_api_init', 'exponer_banner_url_programa');

function register_post_type_guia_programacion() {
  register_post_type('guia', [
    'label' => 'Guía de Programación',
    'public' => true,
    'show_in_rest' => true,
    'menu_icon' => 'dashicons-schedule',
    'supports' => ['title'], // puedes activar 'custom-fields' si no usas ACF
  ]);
}
add_action('init', 'register_post_type_guia_programacion');

add_action('rest_api_init', function () {
  register_rest_field('guia', 'acf_fields', [
    'get_callback' => function ($post_arr) {
      $bloques = get_field('bloques', $post_arr['id']);
      return [
        'canal' => get_field('canal', $post_arr['id']),
        'logo' => get_field('logo', $post_arr['id']) ?: '',
        'dial' => get_field('dial', $post_arr['id']),
        'descripcion' => get_field('descripcion', $post_arr['id']),
        'programas' => array_map(function ($b) {
          return [
            'name' => $b['name'],
            'start' => $b['start'],
            'end' => $b['end'],
            'dias' => $b['dias'] ?: [], // ✅ Aquí agregamos los días seleccionados
          ];
        }, $bloques ?: []),
      ];
    },
    'schema' => null,
  ]);
});




