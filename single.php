<?php get_header(); ?>

<main class="min-h-screen text-gray-900">
  <div class="container mx-auto py-8">

    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
      <article class="bg-white shadow rounded p-6 mb-10">

        <h1 class="text-3xl font-bold mb-4"><?php the_title(); ?></h1>

        <!-- Video principal (ACF: video_url) -->
        <?php if ($video_url = get_field('video_url')) : ?>
          <div class="aspect-w-16 aspect-h-9 mb-6">
            <iframe class="w-full h-full rounded" src="<?php echo esc_url($video_url); ?>" frameborder="0" allowfullscreen></iframe>
          </div>
        <?php endif; ?>

        <div class="prose max-w-none">
          <?php the_content(); ?>
        </div>

      </article>

      <!-- Relacionados -->
      <section>
        <h2 class="text-2xl font-bold mb-4">Episodios relacionados</h2>

        <div class="grid sm:grid-cols-2 md:grid-cols-3 gap-4">
          <?php
          $related = new WP_Query([
            'post_type'      => get_post_type(), // mismo tipo
            'posts_per_page' => 6,
            'post__not_in'   => [get_the_ID()],
            'orderby'        => 'date',
            'order'          => 'DESC',
            'meta_query'     => [], // puedes usarlo para filtrar por campos si necesitas
          ]);

          if ($related->have_posts()) :
            while ($related->have_posts()) : $related->the_post(); ?>
              <a href="<?php the_permalink(); ?>" class="block bg-gray-100 p-4 rounded hover:bg-gray-200 transition">
                <?php if (has_post_thumbnail()) : ?>
                  <div class="aspect-w-16 aspect-h-9 mb-2">
                    <?php the_post_thumbnail('medium', ['class' => 'w-full h-full object-cover rounded']); ?>
                  </div>
                <?php endif; ?>
                <h3 class="text-lg font-semibold"><?php the_title(); ?></h3>
              </a>
          <?php endwhile;
            wp_reset_postdata();
          else :
            echo '<p>No hay episodios relacionados aún.</p>';
          endif;
          ?>
        </div>
      </section>

    <?php endwhile; endif; ?>

  </div>
</main>

<?php get_footer(); ?>
