<?php
/* Template Name: presLeClub */

get_header();

global $alba_theme_variables;

$h3 = "mb-4 text-3xl underline decoration-primary-blue";
$p = "text-justify text-lg md:text-xl text-balance";
$letters = "text-primary-blue text-6xl";

// bureau actuel
$currentOffice = get_office_members_by_year('2024-2025');
//echo '<pre>';
//var_dump($currentOffice);
//echo '</pre>';
//die();

// membre de la ligue
$judgeType = array(
    'Juge Arbitre' => array(
        'm1' => array(
            'name' => 'Jean Dupont',
            'img' => 151,
        ),
        'm2' => array(
            'name' => 'Jeanne Dupont',
            'img' => 151,
        ),
        'm3' => array(
            'name' => 'Jean Dupont',
            'img' => 151,
        ),
        'm4' => array(
            'name' => 'Jeanne Dupont',
            'img' => 151,
        ),
    ),
    'Juge de lignes' => array(
        'm1' => array(
            'name' => 'Jean Dupont',
            'img' => 151,
        ),
        'm2' => array(
            'name' => 'Jeanne Dupont',
            'img' => 151,
        ),
        'm3' => array(
            'name' => 'Jean Dupont',
            'img' => 151,
        ),
        'm4' => array(
            'name' => 'Jeanne Dupont',
            'img' => 151,
        ),
    ),
);

function showGridJudges(array $judges): void
{
    // Itérer sur chaque membre du groupe
    foreach ($judges as $judge) {
        echo sprintf(
            '<div class="grid grid-rows-[2fr_auto] gap-4 justify-center text-center text-lg p-4">
                        <div class="row-span-1">%s</div>
                        <p class="row-span-1 italic text-xl">%s</p>
                    </div>',
            wp_get_attachment_image($judge['img'], '', false, array(
                'loading' => 'lazy',
                'class' => "w-1/2 max-w-56 m-auto rounded-2xl transform transition duration-300 ease-in-out hover:scale-105 row-span-2",
            )),
            ucwords(strtolower($judge['name']))
        );
    }
}

?>

    <section>
        <?= display_titlePage() ?>

        <div class="container w-full lg:w-3/4 m-auto">
            <!--ALBA-->
            <h2 class="text-center italic text-4xl font-bold mb-12 text-balance opacity-0 animate-fade-in hover:animate-pulse">
                <span class="<?= $letters ?> animate-letter">A</span>micale de
                <span class="<?= $letters ?> animate-letter">L</span>uc&eacute; de
                <span class="<?= $letters ?> animate-letter">BA</span>dminton
            </h2>

            <figure class="mb-12 relative">
                <?= wp_get_attachment_image(319, '', false, array(
                    'loading' => 'lazy',
                    'class' => "w-full h-full rounded-2xl object-cover object-center",
                )); ?>
                <figcaption
                        class="leading-none p-2 md:p-6 text-center text-lg md:text-2xl italic font-bold text-white absolute bottom-0 z-20 w-full bg-primary-blue/50 rounded-b-2xl">
                    Photo de famille suite au tournoi organisé à domicile les 23 & 24 novembre 2024
                </figcaption>
            </figure>

            <h3 class="<?= $h3 ?>">Présentation</h3>

            <!--Paragraphe de présentation-->
            <p class="mb-8 <?= $p ?>">
                Situ&eacute; en plein cœur de la ville de Luc&eacute;, le
                club ALBA a &eacute;t&eacute;
                fond&eacute; en 1987 pour permettre aux habitants de
                vivre leur passion du badminton et encourager le d&eacute;veloppement de la pratique de ce sport mill&eacute;naire.<br><br>

                L’ALBA est compos&eacute;e d’une centaine d’adh&eacute;rents de tous &acirc;ges (des d&eacute;butants
                aux v&eacute;t&eacute;rans)
                qui ont plaisir
                &agrave;
                se retrouver chaque semaine pour des sessions d’entra&icirc;nements et des tournois organis&eacute;s
                &agrave; travers
                la
                r&eacute;gion
                Centre-Val de Loire.<br><br>

                Le club est r&eacute;sident du gymnase Jean Boudrie qui comprend 7 terrains de jeux (simple et double)
                pr&eacute;sentant
                un sol combin&eacute; multisports de type Taraflex. Chaque terrain dispose du mat&eacute;riel ad&eacute;quat
                (poteaux,
                filets)
                pour la pratique du badminton. Le joueur b&eacute;n&eacute;ficie donc de conditions de jeu id&eacute;ales
                pour s’adonner &agrave; sa
                passion &excl;
            </p>

            <!--Le tournoi annuel-->
            <h3 class="<?= $h3 ?>">Notre tournoi annuel</h3>
            <p class="mb-12 <?= $p ?>">
                Organis&eacute; tous les ans au mois de novembre, le tournoi inter-r&eacute;gional du club r&eacute;unit
                pas moins de 200
                joueurs sur le weekend dans une ambiance conviviale et comp&eacute;titive.
            </p>

            <!--Le bureau-->
            <h3 class="<?= $h3 ?>">Le bureau 2024 - 2025</h3>
            <!--Tous les membres-->
            <?php showGridBureau($currentOffice); ?>

            <!--Btn voir all bureaux-->
            <?php $svg = '<svg class="w-[30px] h-[30px]" aria-hidden="true"
                             xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                  stroke-width="2.5" d="M19 12H5m14 0-4 4m4-4-4-4"/>
                        </svg>'; ?>
            <div class="grid place-items-center">
                <?= primaryButton(153, "Voir les bureaux des années précédentes $svg", null, null, "flex items-center my-16") ?>
            </div>

            <!--Les membres officiels de la ligue-->
            <h3 class="<?= $h3 ?>">Les membres officiels de la Ligue</h3>
            <div class="space-y-8">
                <?php foreach ($judgeType as $key => $judges) {
                    echo sprintf('<div class="bg-white rounded-2xl p-5">
                    <!--Afficher le titre du groupe-->
                    <h4 class="mb-3 text-2xl underline decoration-primary-blue" >%s</h4>
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 divide-y divide-primary-blue sm:divide-none">',
                        ucwords(strtolower($key)));
                    // Afficher les membres du groupe
                    showGridJudges($judges);
                    echo '</div></div>';
                }
                ?>
            </div>
        </div>
    </section>

<?php
get_footer();
