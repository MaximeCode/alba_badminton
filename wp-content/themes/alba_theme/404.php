<?php get_header(); ?>
<section class="container text-center">
    <h2 class="text-4xl md:text-6xl font-bold text-primary-blue">
        Oops ! Page non trouvée.</h2>
    <?= wp_get_attachment_image(521, '', false, array('class' => 'h-96 w-auto mx-auto hover:animate-spin')); ?>
    <p class="text-gray-600 text-xl">
        La page que vous recherchez semble introuvable. <br>
        Vous pouvez revenir à la page d'accueil pour explorer davantage.<br>
        Ou vous pouvez rechercher une ressource grâce au formulaire ci-dessous.
    </p>

    <div class="max-w-md mx-auto mt-8">
        <?= get_search_form() ?>
    </div>

    <a href="/"
       class="text-xl mt-6 inline-block px-8 py-3 text-white bg-primary-blue rounded-lg shadow-lg hover:bg-secondary-blue transition-all">
        Retour à l'accueil
    </a>
</section>
<?php get_footer(); ?>
