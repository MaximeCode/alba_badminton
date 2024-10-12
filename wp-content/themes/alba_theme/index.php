<?php get_header() ?>
    <style>
        :root {
            --background-image-home: url('<?= wp_get_attachment_url(6); ?>');
        }
    </style>

    <!--Img back-->
    <div class="bg-home-bg bg-scroll aspect-auto w-full min-h-screen absolute top-0 left-0"></div>

    <section class="backdrop-blur-sm mx-auto h-1/2 text-center lg:py-4 relative
            grid grid-cols-1 xl:grid-cols-2 gap-24 md:gap-48 rounded-3xl">

        <!--Left Col-->
        <div class="text-white flex-col items-center justify-center">
            <h1 class="text-4xl md:text-4xl">Faisons vivre notre passion commune, rejoignez ALBA 🏸 !</h1>
            <p class="text-lg md:text-2xl my-10 xl:my-24">Depuis 1987, le club ALBA, situ&eacute; au c&oelig;ur
                de Luc&eacute;,
                rassemble les amoureux du badminton de tous &acirc;ges et niveaux. Avec plus de 100 membres actifs,
                nous nous retrouvons
                chaque semaine dans une ambiance conviviale au gymnase Jean Boudrie, &eacute;quip&eacute; de
                7 terrains de jeu aux standards professionnels.
                Que vous soyez d&eacute;butant ou v&eacute;t&eacute;ran, rejoignez-nous pour des entra&icirc;nements
                dynamiques et des tournois passionnants &agrave; travers la r&eacute;gion Centre-Val de Loire.
            </p>
            <button type="button"
                    class="text-lg md:text-xl text-white bg-primary-blue/50 hover:text-white border-4 border-primary-blue hover:bg-primary-blue rounded-lg px-4 py-2">
                En savoirs plus sur le club
            </button>
        </div>

        <!--Right Col-->
        <div class="text-white flex-col content-center items-center justify-center">
			<?php echo wp_get_attachment_image( 12, 'medium rounded-2xl mx-auto', false, array('loading' => 'lazy') ); ?>
            <button type="button"
                    class="text-lg md:text-xl text-white bg-primary-blue/50 hover:text-white border-4 border-primary-blue hover:bg-primary-blue rounded-lg px-4 py-2 mt-8">
                Voir l'article
            </button>
        </div>
    </section>


    <!-- Add section with the 3 last articles published -->
    <section class="py-10">
        <div>
            <h2 class="mb-8 text-3xl font-extrabold text-center underline">Les derniers articles publiés :</h2>
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
				<?php
				// Paramètres pour récupérer les 3 derniers articles
				$args = array(
					'post_type'      => 'post',
					'posts_per_page' => 3,
					'orderby'        => 'date',
					'order'          => 'DESC',
				);

				$query = new WP_Query( $args );

				if ( $query->have_posts() ) :
					while ( $query->have_posts() ) : $query->the_post(); ?>
                        <!-- Template de carte horizontale avec un lien vers l'article -->
                        <a href="<?php the_permalink(); ?>"
                           class="flex flex-col items-center bg-white border border-gray-200 rounded-lg shadow md:flex-row md:max-w-xl hover:bg-gray-100">
                            <!-- Image de mise en avant de l'article -->
                            <div class="h-48 bg-gray-200 bg-center bg-cover rounded-t-lg md:h-auto md:w-48"
                                 style="background-image: url('<?php the_post_thumbnail_url(); ?>');"></div>

                            <!-- Titre et extrait de l'article -->
                            <div class="flex flex-col justify-between p-4 leading-normal">
                                <h3 class="mb-2 text-xl font-semibold tracking-tight text-gray-900">
									<?php the_title(); ?>
                                </h3>
                                <p class="mb-3 font-normal text-gray-700">
									<?php echo wp_trim_words( get_the_excerpt(), 20 ); // Limiter à 20 mots ?>
                                </p>
                            </div>
                        </a>
					<?php endwhile;
					wp_reset_postdata();
				endif; ?>
            </div>
        </div>
    </section>

    <!--Card-->
    <a href="#"
       class="flex flex-col items-center bg-white border border-gray-200 rounded-lg shadow md:flex-row md:max-w-xl hover:bg-gray-100">
        <img class="object-cover w-full rounded-t-lg h-96 md:h-auto md:w-48 md:rounded-none md:rounded-s-lg"
             src="" alt="">
        <div class="flex flex-col justify-between p-4 leading-normal">
            <h5 class="mb-2 text-xl font-bold tracking-tight text-gray-900">Noteworthy technology
                acquisitions 2021</h5>
            <p class="mb-3 font-normal text-gray-700">Here are the biggest enterprise technology
                acquisitions of 2021 so far, in reverse chronological order.</p>
        </div>
    </a>

<?php get_footer() ?>