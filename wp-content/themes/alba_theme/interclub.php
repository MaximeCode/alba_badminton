<?php
/* Template Name: interclub */
get_header();

//$interclubs = get_post_meta(get_the_ID(), 'custom_interclubs', true);
//
//echo "Les interclubs ICI : <pre>";
//var_dump($interclubs);
//echo "</pre>";
//die();

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

    $teams = new WP_Query($args);
    ob_start();
    ?>
    <div class="flex flex-wrap justify-around gap-12">
        <?php
        if ($teams->have_posts()) {
            while ($teams->have_posts()) {
                $teams->the_post();

                $team_captain = get_post_meta(get_the_ID(), '_sports_team_captain', true);
                $team_image_id = get_post_meta(get_the_ID(), '_sports_team_image', true);
//                $team_order = get_post_meta(get_the_ID(), '_sports_team_order', true);
                $team_image_url = wp_get_attachment_image_url($team_image_id, 'medium');
                ?>
                <div class="team-card">
                    <div class="text-center">
                        <h3 class="text-2xl text-primary-blue underline font-bold"><?php the_title(); ?></h3>
                        <p class="text-xl"><span class="font-bold">Capitaine : </span> <?php echo esc_html($team_captain); ?></p>
                        <!--                        <p>Order: --><?php //= $team_order ?><!--</p>-->
                    </div>
                    <div class="mt-4">
                        <?php if ($team_image_url) { ?>
                            <img src="<?php echo esc_url($team_image_url); ?>" alt="<?php the_title(); ?> Team" class="w-full rounded-2xl">
                        <?php } ?>
                    </div>
                </div>
                <?php
            }
            wp_reset_postdata();
        } else {
            echo 'No teams found.';
        }
        ?>
    </div>
    <?php
    return ob_get_clean();
}

add_shortcode('sports-teams', 'display_sports_teams');
?>

    <section>
        <h2 class="<?= $classTitle ?>"><?php the_title(); ?></h2>

        <?= do_shortcode('[sports-teams]'); ?>

    </section>

<?php
get_footer();

// <div class="text-center">
//   <h3 class='text-3xl font-bold mb-6 underline text-primary-blue'>Une équipe</h3>
//   <h4 class="text-xl">Capitaine : <span class="font-bold">Joueur/se</span></h4>
//   <div class="w-96 h-56 bg-gray-900/50 rounded-2xl grid place-content-center">
//       Photo des joueurs de l'équipe
//   </div>
// </div>