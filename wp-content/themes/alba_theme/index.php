<?php get_header() ?>
    <style>
        :root {
            --background-image-home: url('<?= wp_get_attachment_url(6); ?>');
        }
    </style>

    <section
            class="bg-center bg-no-repeat bg-home-bg bg-gray-500 bg-blend-multiply">
        <div class="px-4 text-center py-24 lg:py-56">
            <h1 class="mb-4 text-4xl font-extrabold tracking-tight leading-none text-white md:text-5xl lg:text-6xl">We
                invest in the world’s potential</h1>
            <p class="mb-8 text-lg font-normal text-gray-300 lg:text-xl sm:px-16 lg:px-48">Here at Flowbite we focus on
                markets where technology, innovation, and capital can unlock long-term value and drive economic
                growth.</p>
            <div class="flex flex-col space-y-4 sm:flex-row sm:justify-center sm:space-y-0">
                <a href="#"
                   class="inline-flex justify-center items-center py-3 px-5 text-base font-medium text-center text-white rounded-lg bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 dark:focus:ring-blue-900">
                    Get started
                    <svg class="w-3.5 h-3.5 ms-2 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                         fill="none" viewBox="0 0 14 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M1 5h12m0 0L9 1m4 4L9 9"/>
                    </svg>
                </a>
                <a href="#"
                   class="inline-flex justify-center hover:text-gray-900 items-center py-3 px-5 sm:ms-4 text-base font-medium text-center text-white rounded-lg border border-white hover:bg-gray-100 focus:ring-4 focus:ring-gray-400">
                    Learn more
                </a>
            </div>
        </div>
    </section>


    <!-- Add section with the 3 last articles published -->
    <section class="py-10">
        <div>
            <h2 class="mb-8 text-3xl font-extrabold text-center">The latest articles</h2>
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
                           class="flex flex-col items-center bg-white border border-gray-200 rounded-lg shadow md:flex-row md:max-w-xl hover:bg-gray-100 dark:border-gray-700 dark:bg-gray-800 dark:hover:bg-gray-700">
                            <!-- Image de mise en avant de l'article -->
                            <div class="h-48 bg-gray-200 bg-center bg-cover rounded-t-lg md:h-auto md:w-48"
                                 style="background-image: url('<?php the_post_thumbnail_url(); ?>');"></div>

                            <!-- Titre et extrait de l'article -->
                            <div class="flex flex-col justify-between p-4 leading-normal">
                                <h3 class="mb-2 text-xl font-semibold tracking-tight text-gray-900 dark:text-white">
									<?php the_title(); ?>
                                </h3>
                                <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">
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
       class="flex flex-col items-center bg-white border border-gray-200 rounded-lg shadow md:flex-row md:max-w-xl hover:bg-gray-100 dark:border-gray-700 dark:bg-gray-800 dark:hover:bg-gray-700">
        <img class="object-cover w-full rounded-t-lg h-96 md:h-auto md:w-48 md:rounded-none md:rounded-s-lg"
             src="" alt="">
        <div class="flex flex-col justify-between p-4 leading-normal">
            <h5 class="mb-2 text-xl font-bold tracking-tight text-gray-900 dark:text-white">Noteworthy technology
                acquisitions 2021</h5>
            <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">Here are the biggest enterprise technology
                acquisitions of 2021 so far, in reverse chronological order.</p>
        </div>
    </a>

<?php get_footer() ?>

<?php get_footer() ?>