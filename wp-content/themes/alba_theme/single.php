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

<main class="space-y-16">
    <article>
        <?php
        if (have_posts()) :
            while (have_posts()) : the_post();
                echo display_titlePage(); // Affiche le titre de l'article
                echo("<h6 class='text-lg mb-6'>Article publié le " . get_the_date() . "</h6>"); // Affiche la date de publication de l'article

                echo("<div class='prose'>");
                the_content(); // Affiche le contenu de l'article
                echo("</div>");

                // get the post id
                $post_id = get_the_ID();

                // get the post category
                $categories = get_the_category();
                $category = $categories[0]->slug;
                //get the parent category
                $parentCategory = get_category($categories[0]->parent);
                $parentCategorySlug = $parentCategory->slug;
                $parentCategoryName = $parentCategory->name;
            endwhile;
        endif;
        ?>
    </article>
    <section>
        <div>
            <!--Si d'autres articles ont la même catégorie-->
            <?php
            // Check if there are other posts in the same category
            $args = array(
                'post_type' => 'post',
                'posts_per_page' => 4,
                'orderby' => 'date',
                'order' => 'DESC',
                'category_name' => $category,
                'post__not_in' => array($post_id) // Exclude current post
            );

            $query = new WP_Query($args);

            // Only display the section if there are other posts
            if ($query->have_posts()) : ?>

                <h2 id="lastNews" class="mb-8 text-3xl font-extrabold underline">Articles concernant l'équipe :</h2>
                <div class="grid gap-y-12">
                    <?php
                    while ($query->have_posts()) : $query->the_post();
                        ?>
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
                    <?php endwhile; ?>
                </div>
                <?php
                wp_reset_postdata();
            else :
                ?>
                <h4 class="text-xl">Aucun autre article trouvé à propos de la catégorie <?= $category ?></h4>
            <?php endif; ?>
        </div>
    </section>
    <?php
    if ($parentCategory->slug && $parentCategory->slug !== '') {
        // Check if there are other posts with the same parent category
        $args_parent = array(
            'post_type' => 'post',
            'posts_per_page' => 1,
            'category_name' => $parentCategory->slug,
            'post__not_in' => array($post_id)
        );

        $query_parent = new WP_Query($args_parent);

        // Only display the button if there are other posts in the parent category
        if ($query_parent->have_posts()) :
            ?>
            <div class="grid place-content-center">
                <?= primaryButton(26, "Voir d'autres articles concernant la catégorie $parentCategoryName", "categoryName", $parentCategory->slug) ?>
            </div>
        <?php endif;
        wp_reset_postdata();
    }
    ?>

    <!-- Section pour afficher les commentaires -->
    <section class="comments-section w-full md:w-3/4 m-auto">
        <?php
        if (comments_open() || get_comments_number()) :
            comments_template(); // Charge le template des commentaires
        endif;
        ?>
    </section>

    <div class="grid place-content-center">
        <?= primaryButton(26, "Voir d'autres articles") ?>
    </div>
</main>


<?php get_footer(); ?>
