<?php get_header();
/* Template Name: Page d'accueil ALBA */
$nb_mainActus = 1;

// classes des img des partners
$imgPartners = array( 148, 144, 143, 142, 148, 144, 143, 142 );
?>

  <section>
    <div class="grid grid-cols-1 gap-y-16 xl:gap-x-28 xl:grid-cols-2 2xl:gap-x-48">
      <!--Left Col-->
      <div
        class="col text-primary-blue flex flex-col items-center justify-between space-y-8 text-center bg-white/50 rounded-2xl py-10 px-5">
        <h1 class="text-3xl md:text-5xl font-bold tracking-wide text-balance">
          Faisons vivre notre passion commune, rejoignez ALBA 🏸 !
        </h1>
        <p class="text-lg md:text-2xl">Depuis 1987, le club ALBA, situ&eacute; au c&oelig;ur
          de Luc&eacute;,
          rassemble les amoureux du badminton de tous &acirc;ges et niveaux. Avec plus de 100 membres actifs,
          nous nous retrouvons
          chaque semaine dans une ambiance conviviale au gymnase Jean Boudrie, &eacute;quip&eacute; de
          7 terrains de jeu aux standards professionnels.
          Que vous soyez d&eacute;butant ou v&eacute;t&eacute;ran, rejoignez-nous pour des entra&icirc;nements
          dynamiques et des tournois passionnants &agrave; travers la r&eacute;gion Centre-Val de Loire.
        </p>
        <button type="button" class="<?= $classBtn ?>">
          <a href="#">En savoirs plus sur le club</a>
        </button>
      </div>

      <!--Right Col-->
      <div
        class="col text-primary-blue flex flex-col items-center justify-between space-y-8 bg-white/50 rounded-2xl p-10">
        <h1 class="text-3xl md:text-4xl italic text-center font-bold tracking-wide underline">Actualité populaire :</h1>
        <a href="<?php the_permalink( $nb_mainActus ); ?>" class="w-5/6 max-w-96 md:max-w-md lg:max-w-lg
                 md:w-3/4">
			<?php echo wp_get_attachment_image( 146, '', false, array(
				'loading' => 'lazy',
				'class'   => "rounded-2xl transform transition duration-300 ease-in-out hover:scale-105",
			) ); ?>
        </a>
        <button type="button" class="<?= $classBtn ?>">
          <a href="<?php the_permalink( $nb_mainActus ); ?>">Voir l'article complet</a>
        </button>
      </div>
    </div>

    <a href="#lastNews" class="flex items-center justify-center mt-6" id="goToLastNews">
      <svg class="w-8 h-8 text-primary-blue rounded-full hover:bg-primary-blue/50 animate-bounce"
           aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
           width="24" height="24" fill="none" viewBox="0 0 24 24">
        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="m19 9-7 7-7-7" />
      </svg>
    </a>
  </section>

  <!-- Add section with the 3 last articles published --> <!-- ! FINISHED !-->
  <section class="py-10">
    <div>
      <h2 id="lastNews" class="mb-8 text-3xl font-extrabold underline">Les derniers articles publiés :</h2>
      <div class="grid gap-y-12">
		  <?php
		  // Paramètres pour récupérer les 3 derniers articles
		  $args = array(
			  'post_type'      => 'post',
			  'posts_per_page' => 4,
			  'orderby'        => 'date',
			  'order'          => 'DESC',
		  );

		  $query = new WP_Query( $args );

		  if ( $query->have_posts() ) :
			  while ( $query->have_posts() ) : $query->the_post(); ?>
                <!-- Template de carte horizontale avec un lien vers l'article -->
                <a href="<?php the_permalink(); ?>"
                   class="flex flex-col md:flex-row xl:gap-6 justify-between bg-white rounded-2xl
                           overflow-hidden shadow-card lg:w-3/4 mx-auto <?= $animCardNews ?>">

                  <!-- Titre et extrait de l'article -->
                  <div class="flex flex-col justify-around p-4 leading-normal">
                    <h3 class="mb-2 text-2xl text-primary-blue underline font-bold tracking-tight">
						<?php the_title(); ?>
                    </h3>
                    <h6 class="subtitle italic"><?= get_the_date(); ?></h6>
                    <p class="mb-3 text-lg text-gray-700">
						<?php echo wp_trim_words( get_the_excerpt(), 30 ); // Limiter à 30 mots ?>
                    </p>
                  </div>

                  <div class="md:max-w-56 lg:max-w-64 2xl:max-w-80 w-full">
                    <!-- Image de mise en avant de l'article -->
                    <img src="<?php the_post_thumbnail_url(); ?>" alt="<?php the_title(); ?>"
                         class="h-48 w-full mx-auto lg:mx-0 object-cover object-center">
                  </div>
                </a>
			  <?php endwhile;
			  wp_reset_postdata();
		  endif; ?>
      </div>
    </div>
  </section>

  <!-- Add section with all partnaires -->
  <section class="py-10">
    <div>
      <h2 class="mb-8 text-3xl font-extrabold underline">Nos partenaires :</h2>

      <div class="container">
        <section class="partners-logo slider">
			<?php
			foreach ( $imgPartners as $imgPartner ) {
				echo "<div class='slide'>";
				echo wp_get_attachment_image( $imgPartner, 'large', false, array(
					'class' => 'w-4/5 h-48 object-contain mx-auto',
				) );
				echo "</div>";
			}
			?>
        </section>
      </div>
    </div>
  </section>

<?php get_footer() ?>