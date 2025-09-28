<?php

get_header();

/* Template Name: Page d'accueil ALBA */

$nb_mainActus = 1;

global $alba_theme_variables;

// Récupération des données des options (paramètres du club menu)
$mainTitle = get_option('club_title_homepage');
$mainDesc = get_option('club_paragraph_homepage');
// classes des img des partners
// Récupération des données de la Meta Box
$prefix = 'home_';
$default = 'Aucune donnée renseignée';

// Récupération des images des partenaires
$partners = rwmb_meta($prefix . 'img_id');
?>

    <section>
        <main class="grid grid-cols-1 gap-y-16 xl:gap-x-28 xl:grid-cols-2 2xl:gap-x-48 min-h-[75vh]">
            <!--Left Col-->
            <div class="col text-primary-blue flex flex-col items-center justify-between space-y-8 text-center bg-white rounded-2xl py-10 px-5">
                <h1 class="text-3xl md:text-4xl font-bold tracking-wide text-balance">
                    <?= $mainTitle ?>
                </h1>
                <p class="text-lg md:text-2xl"><?= $mainDesc ?></p>
                <?= primaryButton(149, "En savoir plus sur le club") ?>
            </div>

            <!--Right Col-->
            <div class="col text-primary-blue flex flex-col items-center justify-between space-y-8 bg-white rounded-2xl p-10 px-5">
                <h2 class="text-3xl md:text-4xl italic text-center font-bold tracking-wide underline">
                    Derni&egrave;re actualit&eacute; :
                </h2>
                <?php
                // Paramètres pour récupérer les 3 derniers articles
                $args = array(
                        'post_type' => 'post',
                        'posts_per_page' => 1,
                        'orderby' => 'date',
                        'order' => 'DESC',
                );

                $query = new WP_Query($args);

                if ($query->have_posts()) :
                    while ($query->have_posts()) : $query->the_post(); ?>
                        <a href="<?php the_permalink(); ?>"
                           class="w-full md:max-w-lg transform transition duration-300 ease-in-out hover:scale-105">
                            <h3 class="text-center mb-4 text-2xl"><?= get_the_title() ?></h3>
                            <?php echo wp_get_attachment_image(get_post_thumbnail_id(), '', false, array(
                                    'class' => "lg:max-h-[450px] object-scale-down object-center rounded-2xl mx-auto",
                            )); ?>
                        </a>
                        <?= primaryButton(get_the_ID(), "Voir l'article complet");
                    endwhile;
                    wp_reset_postdata();
                endif;
                ?>
            </div>
        </main>

        <div class="flex items-center justify-center">
            <a href="#stats" class="mt-6" id="goToStats">
                <svg class="w-8 h-8 text-primary-blue rounded-full hover:bg-primary-blue/50 animate-bounce"
                     aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                     width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="m19 9-7 7-7-7"/>
                </svg>
            </a>
        </div>
    </section>

    <!-- Number and stats section -->
    <section class="mt-8 py-8 md:py-16" id="stats">
        <div class="container mx-auto w-full md:w-3/4 grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-16">
            <?php
            $nbMembers = get_option('club_members_count');
            $age = date('Y') - 1987;
            $args = array(
                    'post_type' => 'sports_team',
                    'posts_per_page' => -1,
                    'orderby' => 'meta_value_num',
                    'meta_key' => '_sports_team_order',
                    'order' => 'ASC',
            );
            $teams = new WP_Query($args);
            $nbInterclubs = $teams->post_count;

            $stats = [
                    ["$nbMembers+", 'Membres'],
                    [7, 'Terrains'],
                    [$age, 'Ans'],
                    [6, 'Équipes']
            ];

            foreach ($stats as $stat) : ?>
                <div class="bg-white p-6 rounded-lg text-center">
                    <span class="text-5xl font-bold text-primary-blue"><?= $stat[0] ?></span>
                    <p class="text-2xl text-secondary-blue"><?= $stat[1] ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Add section with the 3 last articles published --> <!-- ! FINISHED !-->
    <article class="py-10">
        <div>
            <h2 class="mb-8 text-3xl font-extrabold underline">Les derniers articles publiés :</h2>
            <div class="grid gap-y-12">
                <?php
                // Paramètres pour récupérer les 3 derniers articles sans le dernier article
                $arguments = array(
                        'post_type' => 'post',
                        'posts_per_page' => 1,
                        'orderby' => 'date',
                        'order' => 'DESC',
                );
                $theQuery = new WP_Query($arguments);

                if ($theQuery->have_posts()) :
                    while ($theQuery->have_posts()) : $theQuery->the_post();
                        $idOfLastPost = get_the_ID();
                    endwhile;
                    wp_reset_postdata();
                endif;

                $args = array(
                        'post_type' => 'post',
                        'posts_per_page' => 3,
                        'orderby' => 'date',
                        'order' => 'DESC',
                        'post__not_in' => array($idOfLastPost),
                );

                $query = new WP_Query($args);

                if ($query->have_posts()) :
                    while ($query->have_posts()) : $query->the_post(); ?>
                        <!-- Template de carte horizontale avec un lien vers l'article -->
                        <a href="<?php the_permalink(); ?>"
                           class="flex flex-col md:flex-row xl:gap-6 justify-between bg-white rounded-2xl overflow-hidden shadow-card lg:w-3/4 mx-auto <?= $alba_theme_variables['animCardNews'] ?>">

                            <!-- Titre et extrait de l'article -->
                            <div class="flex flex-col justify-around p-4 leading-normal">
                                <h3 class="mb-2 text-2xl text-primary-blue underline font-bold tracking-tight">
                                    <?php the_title(); ?>
                                </h3>
                                <h6 class="subtitle italic"><?= get_the_date(); ?></h6>
                                <p class="mb-3 text-lg text-gray-700">
                                    <?php echo wp_trim_words(get_the_excerpt(), 30); // Limiter à 30 mots ?>
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
            <!--Btn see all posts-->
            <div class="grid place-items-center">
                <?php
                $svg = '<svg class="w-[30px] h-[30px]" aria-hidden="true"
                         xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                              stroke-width="2.5" d="M19 12H5m14 0-4 4m4-4-4-4"/></svg>';
                $classSup = "mt-16 flex items-center";
                echo primaryButton(26, "Voir tous les articles $svg", null, null, $classSup);
                ?>
            </div>

        </div>
    </article>

    <!-- Add section with all partnaires -->
    <section class="py-10">
        <div>
            <h2 class="mb-8 text-3xl font-extrabold underline">Nos partenaires :</h2>

            <div class="container">
                <section class="partners-logo slider">
                    <?php
                    if (empty($partners)) {
                        echo "<p class='text-center text-lg md:text-2xl italic'>$default</p>";
                    } else {
                        foreach ($partners as $key) {
                            foreach ($key as $img_id) {
                                echo "<div class='slide'>";
                                echo wp_get_attachment_image($img_id, 'large', false, array(
                                        'class' => 'w-4/5 h-48 object-contain mx-auto has-transparency',
                                ));
                                echo "</div>";
                            }
                        }
                    }
                    ?>
                </section>
            </div>
        </div>
    </section>

    <script>
        // Fonction pour faire défiler vers la section des dernières actualités
        const goToStats = document.getElementById("goToStats");
        goToStats.addEventListener("click", function (e) {
            e.preventDefault();
            document.getElementById("stats").scrollIntoView({behavior: "smooth"});
        });
    </script>

<?php get_footer() ?>