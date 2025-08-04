<?php

global $alba_theme_variables;
get_header(); ?>

<style>
    .prose, .prose img {
        max-width: 100%;
        margin: auto;
    }
</style>

<main>
    <article>
        <?php
        if (have_posts()) :
            while (have_posts()) : the_post();
                echo '<h2 class="text-4xl font-bold mb-4">' . get_the_title() . '</h2>';

                echo("<div class='prose text-lg lg:text-xl'>");
                the_content(); // Affiche le contenu de l'article
                echo("</div>");
            endwhile;
        endif;
        ?>
        <div class="grid place-content-center">
            <?= primaryButton(190, "Voir les autres équipes du club") ?>
        </div>
    </article>
</main>


<?php get_footer(); ?>
