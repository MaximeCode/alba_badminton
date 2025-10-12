<?php
/* Template Name: presLeClub */

get_header();

global $alba_theme_variables;

$p = "text-justify text-lg md:text-xl text-balance";
$letters = "text-primary-blue text-6xl";

$image_id = get_option('club_family_img');
$legend = get_option('club_legend_img');

// bureau actuel
$saison = array_key_first(get_office_members_by_year());
$currentOffice = get_office_members_by_year($saison);

?>

<section>
    <?= display_titlePage(); ?>

    <div class="container w-full lg:w-3/4 m-auto">
        <!--ALBA-->
        <h2 class="text-center italic text-4xl font-bold mb-12 text-balance opacity-0 animate-fade-in hover:animate-pulse">
            <span class="<?= $letters ?> animate-letter">A</span>micale de
            <span class="<?= $letters ?> animate-letter">L</span>uc&eacute; de
            <span class="<?= $letters ?> animate-letter">BA</span>dminton
        </h2>

        <figure class="mb-12 relative">
            <?= wp_get_attachment_image($image_id, '', false, array(
                'loading' => 'lazy',
                'class' => "w-full h-full rounded-2xl object-cover object-center",
            )); ?>
            <figcaption class="leading-none p-2 md:p-6 text-center text-lg md:text-2xl italic font-bold
                        text-white absolute bottom-0 z-20 w-full bg-primary-blue/50 rounded-b-2xl">
                <?= $legend ?>
            </figcaption>
        </figure>

        <div class="prose max-w-none">
            <?php the_content(); ?>
        </div>

        <!--Le bureau-->
        <h3 class="mt-12 <?= $alba_theme_variables['h3'] ?>">Le bureau <?= $saison ?></h3>
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
    </div>
</section>

<?php
get_footer();
