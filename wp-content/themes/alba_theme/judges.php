<?php

/* Template Name: judges */

get_header();

global $alba_theme_variables;

// Récupération des données de la Meta Box
$prefix = 'judge_';
$default = 'Aucune donnée renseignée';

//echo '<pre>';

// Retrieve judge types dynamically
$judge_names = get_post_meta(get_the_ID(), 'lePtnDeNom', true);
//print_r($judge_names);
$judge_types = get_post_meta(get_the_ID(), 'lePtnDeType', true);
//print_r($judge_types);
//die();

$allJudges = [];

if ($judge_types) {

    // Get all judge types
    $allJudgeTypes = [];
    foreach ($judge_types as $judge_type) {
        foreach ($judge_type as $type) {
            if (in_array($type, $allJudgeTypes)) {
                continue;
            }
            $allJudgeTypes[] = $type;
        }
    }

    // Get all judges name
    $i = 0;
    foreach ($allJudgeTypes as $judgeType) {
        $allJudges[$i]['title'] = $judgeType;
        foreach ($judge_names as $key => $judge_name) {
            if (in_array($judgeType, $judge_types[$key])) {
                $allJudges[$i]['judges'][] = $judge_name;
            }
        }
        $i++;
    }
}

//var_dump($allJudges);
//die();

function showGridJudges(array $judges): void
{
//    var_dump($judges);
    foreach ($judges['judges'] as $judge_name) {
        $name = explode(' ', $judge_name, 2);
        echo sprintf('<div class="grid grid-rows-[2fr_auto] gap-4 justify-center text-center text-lg p-4">
                        <div class="row-span-1">%s</div>
                        <p class="row-span-1 italic text-xl">%s</p>
                    </div>',
            wp_get_attachment_image(151, '', false, array(
                    'loading' => 'lazy',
                    'class' => "w-1/2 max-w-56 m-auto rounded-2xl transform transition duration-300 ease-in-out hover:scale-105",)
            ),
            strtoupper($name[0]) . ' ' . ucwords(strtolower($name[1])));
    }
}

?>

    <section>
        <?= display_titlePage() ?>

        <!--Les membres officiels de la ligue-->
        <h3 class="<?= $alba_theme_variables['h3'] ?>">Les membres officiels de la Ligue pour la saison
            <span class="text-primary-blue">2024 - 2025</span>
        </h3>
        <div class="space-y-8">
            <?php if ($allJudges) {
                foreach ($allJudges as $judge) {
                    echo sprintf('<div class="bg-white rounded-2xl p-5">
                    <!--Afficher le titre du groupe-->
                    <h4 class="mb-3 text-2xl underline decoration-primary-blue">%s</h4>
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 divide-y divide-primary-blue sm:divide-none">', ucwords($judge['title']));
                    showGridJudges($judge);
                    echo '</div></div>';
                }
            } else {
                echo '<p class="text-2xl mt-8">Aucun juge n\'a été trouvé</p>';
            }
            ?>
        </div>
    </section>

<?php
get_footer();
