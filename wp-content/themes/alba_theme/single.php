<?php
get_header(); ?>

<main>
  <article>
	  <?php
	  if ( have_posts() ) :
		  while ( have_posts() ) : the_post();
			  the_title( '<h1 class="text-4xl font-bold mb-8">', '</h1>' ); // Affiche le titre de l'article
			  echo( "<h6 class='subtitle mb-6'>Article publié le " . get_the_date() . "</h6>" ); // Affiche la date de publication de l'article

			  echo( "<div class='prose'>" );
			  the_content(); // Affiche le contenu de l'article
			  echo( "</div>" );
		  endwhile;
	  endif;
	  ?>

  </article>
</main>

<div class="grid place-content-center mt-12">
  <button type="button"
          class="<?= $classBtn ?>">
    <a href="<?= get_permalink( 26 ); ?>">Voir d'autres articles </a>
  </button>
</div>

<?php get_footer(); ?>
