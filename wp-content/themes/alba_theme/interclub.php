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

  $teams = new WP_Query($args);
  ob_start();
  ?>
  <div class="flex flex-wrap justify-around gap-x-12 lg:gap-x-24 gap-y-12">
    <?php
    if ($teams->have_posts()) {
      while ($teams->have_posts()) {
        $teams->the_post();

        $team_captain = get_post_meta(get_the_ID(), '_sports_team_captain', true);
        $team_image_id = get_post_meta(get_the_ID(), '_sports_team_image', true);
        $team_players = get_post_meta(get_the_ID(), '_sports_team_players', true);
        ?>
        <div class="team-card">
          <div class="text-center">
            <h3 class="text-2xl text-primary-blue underline font-bold"><?php the_title(); ?></h3>
            <p class="text-xl">
              <span class="font-bold">Capitaine : </span> <?php echo esc_html($team_captain); ?>
            </p>
            <div class="text-lg">
              <span class="font-bold">Joueurs/ses : </span>
              <p><?php
                if (!empty($team_players)) {
                  echo implode("<br>", $team_players);
                } else {
                  echo 'Aucun joueur renseigné';
                }
                ?></p>
            </div>
          </div>
          <div class="mt-4 flex justify-center">
            <?php if (!empty($team_image_id)) {
              echo wp_get_attachment_image($team_image_id, 'large', false, [
                'loading' => 'lazy',
                'class' => 'lightbox-trigger cursor-pointer rounded-xl w-full h-96 object-cover object-center',
                'data-full-size' => wp_get_attachment_image_src($team_image_id, 'full')[0]
              ]);
            } else { ?>
              <img src="https://placehold.co/500x500?text=Aucune+image+renseignée" alt="Placeholder Image"
                   class="w-full h-96 object-cover object-center rounded-xl">
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
    <?= display_titlePage() ?>

    <?= do_shortcode('[sports-teams]'); ?>

  </section>

<?php
get_footer();