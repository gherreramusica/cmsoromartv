<?php get_header(); ?>

<main class="min-h-screen text-gray-900">
  <h1 class="text-center font-bold text-3xl my-8">Administrador de OTV</h1>

  <!-- Últimos Episodios -->
  <div class="container mx-auto py-8">
    <h2 class="text-4xl font-bold mb-6">Últimos Episodios</h2>

    <?php
    $episodios = new WP_Query(array(
      'post_type' => 'episodio',
      'posts_per_page' => 5,
    ));
    if ($episodios->have_posts()) :
    ?>
      <ul class="flex gap-6">
        <?php while ($episodios->have_posts()) : $episodios->the_post(); ?>
          <li class="bg-white w-[300px] aspect-16-9 overflow-hidden">
            <?php if (has_post_thumbnail()) : ?>
              <a href="<?php the_permalink(); ?>">
                <?php the_post_thumbnail('medium', ['class' => 'w-full h-48 object-cover']); ?>
              </a>
            <?php endif; ?>
            <div class="p-2">
              <a href="<?php the_permalink(); ?>" class="block font-semibold text-lg hover:underline">
                <?php the_title(); ?>
              </a>
            </div>
          </li>
        <?php endwhile; ?>
      </ul>
      <?php wp_reset_postdata(); ?>
    <?php else : ?>
      <p>No hay episodios aún</p>
    <?php endif; ?>
  </div>

  <!-- Últimos Programas -->
  <div class="container mx-auto py-8">
    <h2 class="text-4xl font-bold mb-6">Últimos Programas</h2>

    <?php
    $programas = new WP_Query(array(
      'post_type' => 'programa',
      'posts_per_page' => 5,
    ));
    if ($programas->have_posts()) :
    ?>
      <ul class="flex gap-6">
        <?php while ($programas->have_posts()) : $programas->the_post(); ?>
          <li class="bg-white w-[300px] aspect-16-9 overflow-hidden">
            <?php if (has_post_thumbnail()) : ?>
              <a href="<?php the_permalink(); ?>">
                <?php the_post_thumbnail('medium', ['class' => 'w-full h-48 object-cover']); ?>
              </a>
            <?php endif; ?>
            <div class="p-2">
              <a href="<?php the_permalink(); ?>" class="block font-semibold text-lg hover:underline">
                <?php the_title(); ?>
              </a>
            </div>
          </li>
        <?php endwhile; ?>
      </ul>
      <?php wp_reset_postdata(); ?>
    <?php else : ?>
      <p>No hay programas aún.</p>
    <?php endif; ?>
  </div>

  <!-- Últimos Shorts -->
  <div class="container mx-auto py-8">
    <h2 class="text-4xl font-bold mb-6">Últimos Shorts</h2>

    <?php
    $shorts = new WP_Query(array(
      'post_type' => 'short',
      'posts_per_page' => 5,
    ));
    if ($shorts->have_posts()) :
    ?>
      <ul class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
        <?php while ($shorts->have_posts()) : $shorts->the_post(); ?>
          <li class="bg-white shadow rounded overflow-hidden">
            <?php if (has_post_thumbnail()) : ?>
              <a href="<?php the_permalink(); ?>">
                <?php the_post_thumbnail('medium', ['class' => 'w-full h-48 object-cover']); ?>
              </a>
            <?php endif; ?>
            <div class="p-4 text-center">
              <a href="<?php the_permalink(); ?>" class="block font-semibold text-lg hover:underline">
                <?php the_title(); ?>
              </a>
            </div>
          </li>
        <?php endwhile; ?>
      </ul>
      <?php wp_reset_postdata(); ?>
    <?php else : ?>
      <p>No hay shorts aún.</p>
    <?php endif; ?>
  </div>
</main>

<?php get_footer(); ?>
