<?php
/* Template Name: Palmares */
get_header();

global $alba_theme_variables;
?>

    <section>
        <?= display_titlePage() ?>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <?php for ($i = 0; $i < 5; $i++) { ?>
                <div class="bg-white rounded-2xl shadow-2xl p-3 sm:p-5 grid grid-cols-1 gap-4 sm:grid-cols-2 place-content-between hover:scale-105 <?= $alba_theme_variables['animBase'] ?>">
                    <div>
                        <h2 class="text-primary-blue underline decoration-primary-blue">
                            Victoire de l'équipe de Régionale 2 !
                        </h2>
                        <p>Passage en Régionale 1 pour la saison 2024 - 2025</p>
                    </div>
                    <div class="flex justify-end">
                        <?= wp_get_attachment_image(195, 'medium', false, [
                            'loading' => 'lazy',
                            'class' => 'rounded-xl max-h-48 object-cover object-center'
                        ]) ?>
                    </div>
                </div>
            <?php } ?>
        </div>
    </section>

<?php
get_footer();
