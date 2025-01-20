<?php

/* Template Name: judges */

get_header();

global $alba_theme_variables;

// Récupération des données de la Meta Box
$prefix = 'judge_';
$default = 'Aucune donnée renseignée';

// Juge Arbitre
$judges = [];
$judges['arbitre'] = rwmb_meta($prefix . 'juge_arbitres') ?: $default;
$judges['arbitre']['title'] = 'Juge Arbitre';
// Juge de lignes
$judges['lines'] = rwmb_meta($prefix . 'juge_de_lignes') ?: $default;
$judges['lines']['title'] = 'Juge de Lignes';
//echo '<pre>';
//var_dump($judges);
//echo '</pre>';
//die();

function showGridJudges(array $judges): void
{
    // Itérer sur chaque membre du groupe
    foreach ($judges as $key => $judge) {
        if ($key === 'title') {
            continue;
        }

        $name = explode(' ', $judge, 2);

        echo sprintf(
            '<div class="grid grid-rows-[2fr_auto] gap-4 justify-center text-center text-lg p-4">
                        <div class="row-span-1">%s</div>
                        <p class="row-span-1 italic text-xl">%s</p>
                    </div>',
            wp_get_attachment_image(151, '', false, array(
                'loading' => 'lazy',
                'class' => "w-1/2 max-w-56 m-auto rounded-2xl transform transition duration-300 ease-in-out hover:scale-105 row-span-2",
            )),
            strtoupper($name[0]) . ' ' . ucwords(strtolower($name[1]))
        );
    }
}

?>

    <section>
        <?= display_titlePage() ?>

        <!--Les membres officiels de la ligue-->
        <h3 class="<?= $alba_theme_variables['h3'] ?>">Les membres officiels de la Ligue pour la saison <span
                    class="text-primary-blue">2024 - 2025</span></h3>
        <div class="space-y-8">
            <?php foreach ($judges as $judge) {
                echo sprintf('<div class="bg-white rounded-2xl p-5">
                    <!--Afficher le titre du groupe-->
                    <h4 class="mb-3 text-2xl underline decoration-primary-blue" >%s</h4>
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 divide-y divide-primary-blue sm:divide-none">',
                    $judge['title']);
                // Afficher les membres du groupe
                showGridJudges($judge);
                echo '</div></div>';
            }
            ?>
        </div>
    </section>

<?php
get_footer();
