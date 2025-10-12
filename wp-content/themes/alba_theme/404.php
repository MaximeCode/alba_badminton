<?php get_header(); ?>
<section class="container text-center">
    <h2 class="text-4xl md:text-6xl font-bold text-primary-blue">
        Oops ! Page non trouvée.</h2>
    <?= wp_get_attachment_image(
        get_attachment_id_by_title('404_img'),
        '',
        false,
        array('class' => 'h-96 w-auto mx-auto')
    ); ?>
    <p class="text-gray-600 text-xl">
        <strong>La page que vous recherchez semble introuvable.</strong><br>
        Vous pouvez rechercher une ressource présente sur le site grâce au formulaire ci-dessous.
    </p>
    <div class="max-w-md mx-auto my-4">
        <?= get_search_form() ?>
    </div>

    <p class="text-gray-600 text-xl">
        Vous pouvez également revenir à la page d'accueil pour explorer davantage.
    </p>

    <a href="/"
        class="text-xl mt-6 inline-block px-8 py-3 text-white bg-primary-blue rounded-lg shadow-lg hover:bg-secondary-blue transition-all">
        Retour à l'accueil
    </a>
</section>
<?php get_footer(); ?>