<?php
/* Template Name: schedules */
get_header();

//echo '<pre>';
//print_r(get_schedules());
//echo '</pre>';
//die();

// Tous les créneaux horaires
$slots = get_schedules();

foreach ($slots as $slot) {
    if ($slot['difficulty'] == 0) {
        // Jeu libre
        $freeGame[] = $slot;
    } else {
        // Entraînement
        $training[] = $slot;
    }
}

/**
 * Affiche un créneau horaire
 * @param string $title
 * @param int $difficult
 * @param string $desc
 * @param string $day
 * @param string $time_start
 * @param string $time_end
 * @param string $imgId
 */

function showTraining(string $title, int $difficult, string $desc, string $day, string $time_start, string $time_end, string $imgId = '', string $day2 = ''): void
{
    $days = [
            'monday' => 'Lundi',
            'tuesday' => 'Mardi',
            'wednesday' => 'Mercredi',
            'thursday' => 'Jeudi',
            'friday' => 'Vendredi',
            'saturday' => 'Samedi',
            'sunday' => 'Dimanche',
    ];

    $time = $time_start . ' - ' . $time_end;
    $theDay = $days[$day] . ($day2 ? ' & ' . $days[$day2] : '');

    $image = !empty($imgId) ?
            wp_get_attachment_image($imgId, 'full', false, ['class' => 'w-full h-36 md:h-64 object-cover object-center rounded-2xl']) :
            '<div class="w-full h-36 md:h-64 bg-gray-900/50 rounded-2xl grid place-content-center">
            Aucune image disponible
         </div>';

    echo '<div class="flex flex-col md:flex-row gap-x-4 lg:gap-x-20 gap-y-10 bg-white/50 px-3 lg:px-6 py-6 rounded-2xl shadow-lg items-center">
    <div class="basis-4/6 flex flex-col justify-between gap-y-6">
        <div class="text-2xl font-bold flex flex-col xl:flex-row xl:justify-between xl:items-center">
            <h4 class="underline flex-1">' . ucwords($title) . '</h4>
            <span class="flex-none">' . str_repeat('🏸', $difficult) . '</span>
        </div>
        <p class="text-lg text-justify">' . $desc . '</p>
        <div class="grid place-content-center">
            <span class="text-lg border-2 border-primary-blue rounded-full px-6 py-1 space-x-2">
                <span class="font-bold">' . $theDay . '</span>
                <span class="italic">' . $time . '</span>
            </span>
        </div>
    </div>
    <div class="basis-2/6">' . $image . '</div>
</div>';
}

?>

    <section>
        <?= display_titlePage() ?>

        <h3 class="text-3xl font-bold mt-12 underline decoration-primary-blue">Les séances d'entraînements</h3>

        <div class="text-primary-blue flex justify-end items-center mt-8">🏸
            <svg class="rtl:rotate-180 inline-block w-4 h-4 mx-1" aria-hidden="true"
                 xmlns="http://www.w3.org/2000/svg"
                 width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                <path fill-rule="evenodd"
                      d="M3 4a1 1 0 0 0-.822 1.57L6.632 12l-4.454 6.43A1 1 0 0 0 3 20h13.153a1 1 0 0 0 .822-.43l4.847-7a1 1 0 0 0 0-1.14l-4.847-7a1 1 0 0 0-.822-.43H3Z"
                      clip-rule="evenodd"/>
            </svg>
            <i class="fa-solid fa-arrow-right"></i><span class="italic mr-8">Niveau de difficulté</span>
        </div>
        <div class="space-y-12">
            <?php
            if (!empty($training)) {
                foreach ($training as $slot) {
                    showTraining($slot['title'], $slot['difficulty'], $slot['desc'], $slot['day'], $slot['time_start'], $slot['time_end'], $slot['image_id']);
                }
                echo '<p class="pb-8 text-xl text-center text-primary-blue">Les autres séances seront bien ajoutées ! 😉</p>';
            } else {
                echo '<p class="text-xl">Aucune séance d\'entraînement ajoutée.</p>';
            }
            ?>
        </div>

        <h3 class="text-3xl font-bold mt-8 lg:mt-16 underline decoration-primary-blue mb-8">Les séances de jeux libres</h3>

        <div class="space-y-12">
            <?php
            if (!empty($freeGame)) {
                foreach ($freeGame as $slot) {
                    showTraining($slot['title'], $slot['difficulty'], $slot['desc'], $slot['day'], $slot['time_start'], $slot['time_end'], $slot['image_id'], $slot['day2']);
                }
            } else {
                echo '<p class="text-xl">Aucune séance de jeu libre ajoutée.</p>';
            }
            ?>
        </div>

    </section>

<?php
get_footer();
