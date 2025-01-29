<?php
/* Template Name: RGPD */
get_header();
?>

    <div class="prose text-justify text-lg lg:text-xl w-full sm:w-5/6 lg:w-2/3 xl:w-1/2 m-auto">
        <span class="text-left"><?= display_titlePage() ?></span>
        <h5 class="italic">Dernière mise à jour : <?= get_the_modified_date('j F Y'); ?></h5>
        <?php the_content(); ?>
    </div>

<?php
get_footer();
