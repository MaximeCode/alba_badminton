<?php

/* Template Name: judges */

get_header();

global $alba_theme_variables;

$all_judges = []; // Array -> [type] -> [judges['name'], judges['image_id']]
$allJudgeTypes = get_all_judge_types();

foreach ($allJudgeTypes as $type) {
    $judges_of_type = get_judges_by_type($type->slug);

    // Only add to $all_judges if there are judges of this type
    if (!empty($judges_of_type)) {
        $all_judges[] = [
            'title' => $type->name,
            'judges' => $judges_of_type
        ];
    }
}

// Returns the current season as "YYYY - YYYY+1", starting at September 1st
function get_current_season_years(): string
{
    $now = new DateTime();
    $year = (int)$now->format('Y');
    $month = (int)$now->format('n');
    // If before September, season is previous year - current year
    if ($month < 9) {
        $start = $year - 1;
        $end = $year;
    } else {
        $start = $year;
        $end = $year + 1;
    }
    return "{$start} - {$end}";
}

function showGridJudges(array $judges): void
{
    foreach ($judges as $judge) {
        echo sprintf(
            '<div class="grid grid-rows-[2fr_auto] gap-4 justify-center text-center text-lg p-4">
                        <div class="row-span-1">%s</div>
                        <p class="row-span-1 italic text-xl">%s</p>
                    </div>',
            wp_get_attachment_image(
                $judge['image_id'],
                '',
                false,
                array(
                    'loading' => 'lazy',
                    'class' => "w-1/2 max-w-56 m-auto rounded-2xl transform transition duration-300 ease-in-out hover:scale-105",
                )
            ),
            $judge['name']
        );
    }
}

?>

<section>
    <?= display_titlePage() ?>

    <!--Les membres officiels de la ligue-->
    <h3 class="<?= $alba_theme_variables['h3'] ?>">Les membres officiels de la Ligue pour la saison
        <span class="text-primary-blue"><?= get_current_season_years(); ?></span>
    </h3>
    <div class="space-y-8">
        <?php if ($all_judges) {
            foreach ($all_judges as $judge) {
                echo sprintf('<div class="bg-white rounded-2xl p-5">
                    <!--Afficher le titre du groupe-->
                    <h4 class="mb-3 text-2xl underline decoration-primary-blue">%s</h4>
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 divide-y divide-primary-blue sm:divide-none">', $judge['title']);
                showGridJudges($judge['judges']);
                echo '</div></div>';
            }
        } else {
            echo '<p class="text-2xl mt-8">Aucun juge n\'a encore été ajouté ! Ça arrive bientôt... 😁🧑‍⚖️</p>';
        }
        ?>
    </div>
</section>

<?php
get_footer();
