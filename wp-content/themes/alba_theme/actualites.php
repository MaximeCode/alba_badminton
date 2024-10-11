<?php
/* Template Name: actualites */
get_header(); ?>

<div class="container mx-auto py-8">
    <h2 class="text-4xl font-bold mb-8">Nos Actualités</h2>
	<?php the_content(); ?>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
		<?php
		$args  = array(
			'post_type'      => 'post',
			'posts_per_page' => 10,
		);
		$query = new WP_Query( $args );

		if ( $query->have_posts() ) :
			while ( $query->have_posts() ) : $query->the_post(); ?>
                <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                    <a href="<?php the_permalink(); ?>">
						<?php if ( has_post_thumbnail() ) : ?>
                            <img src="<?php the_post_thumbnail_url( 'medium' ); ?>" alt="<?php the_title(); ?>"
                                 class="w-full h-48 object-cover">
						<?php endif; ?>
                        <div class="p-4">
                            <h2 class="text-xl font-bold text-primary-blue underline"><?php the_title(); ?></h2>
                            <p class="text-gray-700 text-center"><?php the_excerpt(); ?></p>


                        </div>
                    </a>
                </div>
			<?php endwhile;
			wp_reset_postdata();
		else : ?>
            <p>Aucun article trouvé.</p>
		<?php endif; ?>
    </div>
</div>

<?php get_footer(); ?>
