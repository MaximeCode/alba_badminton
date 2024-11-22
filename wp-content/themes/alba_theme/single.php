<?php
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
                the_title('<h1 class="<?= $classTitle ?>">', '</h1>'); // Affiche le titre de l'article
                echo("<h6 class='subtitle mb-6'>Article publié le " . get_the_date() . "</h6>"); // Affiche la date de publication de l'article

                echo("<div class='prose'>");
                the_content(); // Affiche le contenu de l'article
                echo("</div>");
            endwhile;
        endif;
        ?>
    </article>

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
            class="<?= $classBtn ?>">
        <a href="<?= get_permalink(26); ?>">Voir d'autres articles </a>
    </button>
</div>

<?php get_footer(); ?>
