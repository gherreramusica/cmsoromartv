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
      'singular_name' => 'Programa',
      'add_new' => 'Añadir Programa',
      'add_new_item' => 'Añadir nuevo Programa',
      'edit_item' => 'Editar Programa',
      'new_item' => 'Nuevo Programa',
      'view_item' => 'Ver Programa',
      'search_items' => 'Buscar Programas',
      'not_found' => 'No se encontraron Programas',
      'not_found_in_trash' => 'No hay Programas en la papelera',
      'all_items' => 'Todos los Programas',
      'menu_name' => 'Programas',
      'name_admin_bar' => 'Programa' // 👈 Esto reemplaza "Add Post"
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
      'singular_name' => 'Episodio',
      'add_new' => 'Añadir Episodio',
      'add_new_item' => 'Añadir nuevo Episodio',
      'edit_item' => 'Editar Episodio',
      'new_item' => 'Nuevo Episodio',
      'view_item' => 'Ver Episodio',
      'search_items' => 'Buscar Episodios',
      'not_found' => 'No se encontraron Episodios',
      'not_found_in_trash' => 'No hay Episodios en la papelera',
      'all_items' => 'Todos los Episodios',
      'menu_name' => 'Episodios',
      'name_admin_bar' => 'Episodio' // 👈 Esto cambia “Add Post”
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

// Registro de CPT: Banner
function oromarplay_register_banner() {
  register_post_type('banner', array(
    'labels' => array(
      'name' => 'Banner',
      'singular_name' => 'Banner'
    ),
    'public' => true,
    'has_archive' => true,
    'rewrite' => array('slug' => 'banner'),
    'menu_icon' => 'dashicons-format-video',
    'supports' => array('title', 'editor', 'thumbnail'),
    'show_in_rest' => true,
  ));
}
add_action('init', 'oromarplay_register_banner');

function oromarplay_register_clips() {
  register_post_type('clip', array(
    'labels' => array(
      'name' => 'Clips',
      'singular_name' => 'Clip',
      'add_new' => 'Añadir Clip',
      'add_new_item' => 'Añadir nuevo Clip',
      'edit_item' => 'Editar Clip',
      'new_item' => 'Nuevo Clip',
      'view_item' => 'Ver Clip',
      'search_items' => 'Buscar Clips',
      'not_found' => 'No se encontraron Clips',
      'not_found_in_trash' => 'No hay Clips en la papelera',
      'all_items' => 'Todos los Clips',
      'menu_name' => 'Clips',
      'name_admin_bar' => 'Clip' // 👈 Esto reemplaza "Add Post"
    ),
    'public' => true,
    'has_archive' => true,
    'rewrite' => array('slug' => 'clip'),
    'menu_icon' => 'dashicons-format-video',
    'supports' => array('title', 'editor', 'thumbnail'),
    'show_in_rest' => true,
  ));
}
add_action('init', 'oromarplay_register_clips');


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

add_filter('manage_episodio_posts_columns', function ($columns) {
    $columns['programa_relacionado'] = 'Programa relacionado';
    return $columns;
});

add_action('manage_episodio_posts_custom_column', function ($column, $post_id) {
    if ($column === 'programa_relacionado') {
        $programa = get_field('programa_relacionado', $post_id);
        echo $programa ? esc_html(get_the_title($programa)) : '<em>—</em>';
    }
}, 10, 2);

// Agrega un dropdown de filtro por programa relacionado en el admin de Episodios
add_action('restrict_manage_posts', function () {
    global $typenow;
    if ($typenow !== 'episodio') return;

    $programas = get_posts([
        'post_type' => 'programa',
        'posts_per_page' => -1,
        'orderby' => 'title',
        'order' => 'ASC'
    ]);

    $selected = $_GET['programa_relacionado'] ?? '';

    echo '<select name="programa_relacionado">';
    echo '<option value="">Todos los programas</option>';
    foreach ($programas as $programa) {
        $isSelected = $selected == $programa->ID ? 'selected' : '';
        echo '<option value="' . esc_attr($programa->ID) . '" ' . $isSelected . '>' . esc_html($programa->post_title) . '</option>';
    }
    echo '</select>';
});

// Filtra la lista de episodios por programa relacionado
add_filter('parse_query', function ($query) {
    global $pagenow;
    if (
        is_admin() &&
        $pagenow === 'edit.php' &&
        $query->get('post_type') === 'episodio' &&
        isset($_GET['programa_relacionado']) &&
        $_GET['programa_relacionado'] != ''
    ) {
        $meta_query = [
            [
                'key' => 'programa_relacionado',
                'value' => (int) $_GET['programa_relacionado'],
                'compare' => '='
            ]
        ];
        $query->set('meta_query', $meta_query);
    }
});


// add_action('rest_api_init', function () {
//   register_rest_field('guia', 'acf_fields', [
//     'get_callback' => function ($post_arr) {
//       $bloques = get_field('bloques', $post_arr['id']);
//       return [
//         'canal' => get_field('canal', $post_arr['id']),
//         'logo' => get_field('logo', $post_arr['id']) ?: '',
//         'dial' => get_field('dial', $post_arr['id']),
//         'descripcion' => get_field('descripcion', $post_arr['id']),
//         'programas' => array_map(function ($b) {
//           return [
//             'name' => $b['name'],
//             'start' => $b['start'],
//             'end' => $b['end'],
//             'dias' => $b['dias'] ?: [], // ✅ Aquí agregamos los días seleccionados
//           ];
//         }, $bloques ?: []),
//       ];
//     },
//     'schema' => null,
//   ]);
// });




