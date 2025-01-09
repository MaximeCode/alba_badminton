<?php

global $alba_theme_variables;
get_header(); ?>

<style>
    .comments-section {
        padding: 1rem;
        background-color: rgb(255 255 255);
        border: 1px solid #ddd;
        border-radius: 12px;
    }

    .comments-section h3 {
        font-size: 1.5rem;
        margin-bottom: 1rem;
    }

    .comments-section .comment-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .comments-section .comment {
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid #ccc;
    }

    .comments-section .comment .comment-author {
        font-weight: bold;
        margin-bottom: 0.5rem;
    }

    .comments-section .comment .comment-meta {
        font-size: 0.875rem;
        color: #555;
    }

    .comments-section form label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: bold;
    }

    .comments-section form input,
    .comments-section form textarea {
        width: 100%;
        padding: 0.5rem;
        margin-bottom: 1rem;
        border: 1px solid rgb(31, 162, 218, 0.5);
        border-radius: 5px;
    }

    .comments-section form input[type="submit"] {
        background-color: #1FA2DA; /* Couleur principale de votre site */
        color: white;
        border: none;
        padding: 0.5rem 1rem;
        cursor: pointer;
        border-radius: 4px;
    }

    .comments-section form input[type="submit"]:hover {
        background-color: #007bb5;
    }

    .comment-form > p.logged-in-as {
        display: none;
    }
</style>

<main>
    <article>
        <?php
        if (have_posts()) :
            while (have_posts()) : the_post();
                echo display_titlePage(); // Affiche le titre de l'article
                echo("<h6 class='text-lg mb-6'>Article publié le " . get_the_date() . "</h6>"); // Affiche la date de publication de l'article

                echo("<div class='prose'>");
                the_content(); // Affiche le contenu de l'article
                echo("</div>");
            endwhile;
        endif;
        ?>
    </article>

    <!--Affichage des articles avec la catégorie "R1"-->
    <section class="py-10">
        <div>
            <h2 id="lastNews" class="mb-8 text-3xl font-extrabold underline">Articles concernant l'équipe :</h2>
            <div class="grid gap-y-12">
                <?php
                // Paramètres pour récupérer les 3 derniers articles
                $args = array(
                    'post_type' => 'post',
                    'posts_per_page' => 4,
                    'orderby' => 'date',
                    'order' => 'DESC',
                    'category_name' => 'r1',
                );

                $query = new WP_Query($args);

                if ($query->have_posts()) :
                    while ($query->have_posts()) : $query->the_post(); ?>
                        <!-- Template de carte horizontale avec un lien vers l'article -->
                        <a href="<?php the_permalink(); ?>"
                           class="flex flex-col md:flex-row xl:gap-6 justify-between bg-white rounded-2xl
                            overflow-hidden shadow-card lg:w-3/4 mx-auto <?= $alba_theme_variables['animCardNews'] ?>">

                            <!-- Titre et extrait de l'article -->
                            <div class="p-4">
                                <h2 class="text-xl font-bold text-primary-blue underline"><?php the_title(); ?></h2>
                                <h6 class="subtitle"><?= get_the_date(); ?></h6>
                                <p class="text-gray-700 text-center"><?php the_excerpt(); ?></p>
                            </div>

                            <div class="md:max-w-56 lg:max-w-64 2xl:max-w-80 w-full">
                                <!-- Image de mise en avant de l'article -->
                                <img src="<?php the_post_thumbnail_url(); ?>" alt="<?php the_title(); ?>"
                                     class="h-48 w-full mx-auto lg:mx-0 object-cover object-center">
                            </div>
                        </a>
                    <?php endwhile;
                    wp_reset_postdata();
                else : ?>
                    <p>Aucun article trouvé.</p>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Section pour afficher les commentaires -->
    <section class="comments-section w-3/4 m-auto">
        <?php
        if (comments_open() || get_comments_number()) :
            comments_template(); // Charge le template des commentaires
        endif;
        ?>
    </section>
</main>

<div class="grid place-content-center mt-12">
    <button type="button"
            class="<?= $alba_theme_variables['classBtn'] ?>">
        <a href="<?= get_permalink(26); ?>">Voir d'autres articles </a>
    </button>
</div>

<?php get_footer(); ?>
