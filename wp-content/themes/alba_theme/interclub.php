<?php
/* Template Name: interclub */
get_header();

// Display Teams on the Frontend
function display_sports_teams(): false|string
{
    $args = array(
            'post_type' => 'sports_team',
            'posts_per_page' => -1,
            'orderby' => 'meta_value_num',
            'meta_key' => '_sports_team_order',
            'order' => 'ASC',
    );

    // Récupération de toutes les équipes
    $teams = new WP_Query($args);

    // insertion de chaque équipe dans leur saison respective
    $allSeasons = [];
    if ($teams->have_posts()) {
        while ($teams->have_posts()) {
            $teams->the_post();

            $season = get_post_meta(get_the_ID(), '_sports_team_season', true);

            $allSeasons[$season][get_the_ID()]['teamName'] = get_the_title(); // $allSeasons[2024-2025][233]['teamName'] = "Régionale 1"
            $allSeasons[$season][get_the_ID()]['captain'] = get_post_meta(get_the_ID(), '_sports_team_captain', true); // $allSeasons[2024-2025][233]['captain'] = "Adrien UJHELY"
            $allSeasons[$season][get_the_ID()]['img'] = get_post_meta(get_the_ID(), '_sports_team_image', true); // $allSeasons[2024-2025][233]['img'] = 547
            $allSeasons[$season][get_the_ID()]['players'] = get_post_meta(get_the_ID(), '_sports_team_players', true); // $allSeasons[2024-2025][233]['players'] = array("Amandine CHAINEAU", "Joueur 2", "Joueur 3")
            $allSeasons[$season][get_the_ID()]['articleId'] = get_post_meta(get_the_ID(), '_sports_team_article_id', true); // $allSeasons[2024-2025][233]['articleId'] = 679
        }
    } else {
        echo 'No teams found.';
    }

    wp_reset_postdata();

//    echo "<pre>";
//    var_dump($allSeasons);
//    echo "</pre>";
//    die();

    ob_start();
    ?>
    <?php foreach ($allSeasons as $season => $teamsInSeason): ?>
    <h1 class="text-3xl font-bold my-12 underline text-primary-blue decoration-primary-blue">Saison <?= $season ?></h1>

    <div class="flex flex-wrap justify-around gap-x-12 lg:gap-x-24 gap-y-12">
        <?php foreach ($teamsInSeason as $postId => $team): ?>
            <div class="team-card">
                <div class="text-center">
                    <h3 class="text-2xl text-primary-blue underline font-bold">
                        <a href="<?= get_permalink($postId); ?>"
                           class="inline-flex items-center justify-center gap-2 hover:gap-3 transition-all duration-300 hover:text-blue-600 group">
                            <span><?= $team['teamName'] ?></span>
                            <svg class="w-5 h-5 opacity-70 group-hover:opacity-100 group-hover:translate-x-1 transition-all" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                      d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                      clip-rule="evenodd"></path>
                            </svg>
                        </a>
                    </h3>
                    <p class=" text-xl">
                        <span class="font-bold">Capitaine : </span> <?= esc_html($team['captain']); ?>
                    </p>
                    <div class="text-lg">
                        <span class="font-bold">Joueurs/ses : </span>
                        <p><?php
                            if (!empty($team['players'])) {
                                echo implode(', ', array_map('esc_html', $team['players']));
                            } else {
                                echo 'Aucun joueur renseigné';
                            }
                            ?></p>
                    </div>
                </div>
                <div class="mt-4 flex justify-center">
                    <?php if (!empty($team['img'])) {
                        echo wp_get_attachment_image($team['img'], 'large', false, [
                                'loading' => 'lazy',
                                'class' => 'lightbox-trigger cursor-pointer rounded-xl w-full h-96 object-cover object-center',
                                'data-full-size' => wp_get_attachment_image_src($team['img'], 'full')[0]
                        ]);
                    } else { ?>
                        <img src="https://placehold.co/500x500?text=Aucune+image+renseignée" alt="Placeholder Image"
                             class="w-full h-96 object-cover object-center rounded-xl">
                    <?php } ?>
                </div>
            </div>
        <?php
        endforeach; // teams
        ?>
    </div>
<?php
endforeach; // seasons
    return ob_get_clean();
}

add_shortcode('sports-teams', 'display_sports_teams');

?>

    <section>
        <?= display_titlePage() ?>

        <?= do_shortcode('[sports-teams]'); ?>
    </section>

<?php
get_footer();