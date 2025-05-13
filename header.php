<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php wp_head(); ?>
</head>
<body <?php body_class('bg-white text-gray-900 antialiased'); ?>>

  <!-- Header site -->
  <header class="bg-gray-500 text-white shadow">
    <div class="container mx-auto px-4 py-4 flex justify-between items-center">
      <h1 class="text-xl font-bold">
        <a href="<?php echo esc_url(home_url('/')); ?>" class="hover:underline">
          <?php bloginfo('name'); ?>
        </a>
      </h1>
      <nav class="space-x-4">
        <a href="#" class="hover:underline">Inicio</a>
        <a href="#" class="hover:underline">Noticias</a>
        <a href="#" class="hover:underline">Contacto</a>
      </nav>
    </div>
  </header>

  <!-- Main wrapper -->
  <div class="container mx-auto px-4 py-6">
