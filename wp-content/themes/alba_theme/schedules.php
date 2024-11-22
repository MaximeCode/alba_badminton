<?php /** @noinspection ALL */
/* Template Name: schedules */
get_header();

// faire un tableau pour chaque entrainement contenant les infos suivantes :
// - training : entraînement ou jeu libre
// - adults : adultes ou jeunes
// - compet : compétiteurs ou loisirs
// - difficult : niveau de difficulté (nb de raquettes, 1 pour débutant, 2 pour intermédiaire, 3 pour confirmé, 4 pour expert)
// - day : jour de la semaine
// - hour : heure de début - heure de fin
// - text : texte de présentation de l'entraînement
// - photo : numéro de la photo des joueurs participant à l'entraînement

$seances = array(
    array(
        'training' => true,
        'adults' => true,
        'compet' => "Compétiteurs",
        'difficult' => 4,
        'day' => 'Mardi',
        'hour' => '19h30 - 21h',
        'text' => 'Pour les badistes en quête de performance sur les tournois et interclubs, session intensive physiquement et techniquement.',
        'photo' => 1,
        'age' => ''
    ),
    array(
        'training' => true,
        'adults' => true,
        'compet' => "Loisirs",
        'difficult' => 1,
        'day' => 'Vendredi',
        'hour' => '19h - 20h30',
        'text' => 'Pour les badistes recherchant une ambiance conviviale tout en jouant au badminton pour s’amuser.',
        'photo' => 2,
        'age' => ''
    ),
    array(
        'training' => true,
        'adults' => false,
        'compet' => "",
        'difficult' => 2,
        'day' => 'Mardi',
        'hour' => '18h - 19h30',
        'text' => 'Pour les apprentis badistes qui veulent découvrir le badminton ou s’améliorer.',
        'photo' => 3,
        'age' => '(-10 ans)'
    ),
    array(
        'training' => true,
        'adults' => false,
        'compet' => "",
        'difficult' => 3,
        'day' => 'Mercredi',
        'hour' => '19h - 20h30',
        'text' => 'Pour les jeunes badistes voulant acquérir un bon niveau et participer aux compétitions.',
        'photo' => 4,
        'age' => '(+10 ans)'
    ),
);

function showTraining(bool $training, bool $adults, string $compet, int $difficult, string $text, string $day, string $hour, int $photo, string $age = ""): void
{
    $isTraining = $training ? 'Entraînement' : 'Jeu libre';
    $isAdults = $adults ? 'Adultes' : 'Jeunes';
    echo sprintf('<div class="flex flex-col md:flex-row gap-x-4 lg:gap-x-20 gap-y-10">
            <div class="basis-4/6 flex flex-col justify-between gap-y-6">
                <div class="text-2xl font-bold flex flex-row justify-between items-center">
                    <h4 class="underline flex-1">%s %s %s <br class="lg:hidden">%s</h4>
                    <span class="flex-none">%s</span>
                </div>
                <p class="text-xl text-justify">%s</p>
                <div class="grid place-content-center">
                    <span class="text-lg border-2 border-primary-blue rounded-full px-6 py-1"><span class="font-bold">%s</span> : <span class="italic">%s</span></span>
                </div>
            </div>
            <div class="basis-2/6">
                <div class="w-full h-36 md:h-48 bg-gray-900/50 rounded-2xl grid place-content-center text-center">
                    Photo des joueurs participant à l\'entraînement %s
                </div>
            </div>
        </div>', $isTraining, $isAdults, $compet, $age, str_repeat('🏸', $difficult), $text, $day, $hour, $photo);
} ?>

    <section class="space-y-8">
        <h2 class="<?= $classTitle ?>"><?php the_title(); ?></h2>

        <div><h3 class="text-3xl font-bold mt-12 underline decoration-primary-blue">Les séances d'entraînements</h3>
        </div>

        <!--img-->
        <!--                <img src="--><?php //echo get_the_post_thumbnail_url(); ?><!--" alt="-->
        <?php //the_title(); ?><!--"-->
        <!--                     class="w-full h-auto">-->
        <!--Fin img-->

        <div class="space-y-12">
            <?php foreach ($seances as $seance) {
                showTraining($seance['training'], $seance['adults'], $seance['compet'], $seance['difficult'], $seance['text'], $seance['day'], $seance['hour'], $seance['photo'], $seance['age']);
            } ?>
        </div>

        <div><h3 class="text-3xl font-bold mt-16 underline decoration-primary-blue">Les séances de jeux libres</h3>
        </div>

        <div class="flex flex-col md:flex-row gap-x-12 gap-y-10">
            <div class="basis-4/6 flex flex-col justify-around gap-y-6">
                <p class="text-xl text-justify">Séances où tous les adhérents peuvent venir faire des matchs amicaux
                    entre les horaires indiqués ci-dessous :</p>
                <div class="grid place-content-center">
                    <span class="text-lg border-2 border-primary-blue rounded-full px-6 py-1">
                        <span class="font-bold">Lundi & Jeudi</span> :
                        <span class="italic">17h - 22h</span>
                    </span>
                </div>
            </div>
            <div class="basis-2/6">
                <div class="w-full h-36 md:h-48 bg-gray-900/50 rounded-2xl grid place-content-center">
                    Photo des joueurs participant au jeu libre
                </div>
            </div>
        </div>

    </section>

<?php
get_footer();
