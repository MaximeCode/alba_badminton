<?php
/*
Plugin Name: Équipes d'interclubs
Description: Plugin conçu pour l'administration des équipes d'interclubs.
Version: 2.0
Author: M.B.
*/

if (!defined('ABSPATH')) {
    exit;
}

// Register Custom Post Type for Teams
function sports_club_team_post_type(): void
{
    $labels = array(
            'name' => "Les équipes d'interclubs",
            'singular_name' => 'Team',
            'menu_name' => "Les équipes d'interclubs",
            'add_new' => 'Ajouter une équipe',
            'add_new_item' => 'Ajouter une équipe',
            'edit_item' => 'Modifier une équipe',
            'new_item' => 'Nouvelle équipe',
            'view_item' => 'Voir l\'équipe',
            'search_items' => 'Search Teams',
            'not_found' => 'No teams found',
            'not_found_in_trash' => 'No teams found in Trash'
    );

    $args = array(
            'labels' => $labels,
            'public' => true,
            'has_archive' => true,
            'publicly_queryable' => true,
            'query_var' => true,
            'rewrite' => array('slug' => 'team'),
            'capability_type' => 'post',
            'hierarchical' => true,
            'supports' => array('title', 'editor', 'page-attributes'),
            'menu_icon' => 'dashicons-groups',
            'show_in_rest' => true
    );

    register_post_type('sports_team', $args);
}

add_action('init', 'sports_club_team_post_type');

// No longer needed - using custom post type directly

// Add Custom Meta Boxes for Team Details
function sports_club_team_meta_boxes(): void
{
    add_meta_box(
            'sports_team_details',
            'Détails des équipes',
            'render_team_details_meta_box',
            'sports_team',
            'normal',
    );
}

add_action('add_meta_boxes', 'sports_club_team_meta_boxes');

// Render Meta Box Content
function render_team_details_meta_box($post): void
{
    // Add a nonce field for security
    wp_nonce_field('sports_team_details_nonce', 'sports_team_details_nonce');

    // Retrieve existing meta values
    $captain = get_post_meta($post->ID, '_sports_team_captain', true);
    $team_image_id = get_post_meta($post->ID, '_sports_team_image', true);
    $team_season = get_post_meta($post->ID, '_sports_team_season', true);

    $args = array(
            'post_type' => 'sports_team',
            'posts_per_page' => -1,
            'orderby' => 'meta_value_num',
            'meta_key' => '_sports_team_order',
            'order' => 'ASC',
    );

    $teams = new WP_Query($args);
    // count the number of teams
    $teamCount = $teams->post_count;

    $team_order = get_post_meta($post->ID, '_sports_team_order', true);
    $theTeamOrderValue = $team_order ?: $teamCount + 1;

    if ($teams->have_posts()) {
        while ($teams->have_posts()) {
            $teams->the_post();
            $current_season = get_post_meta(get_the_ID(), '_sports_team_season', true);
            $teamsBySeasons[$current_season][] = [get_the_title(), get_the_ID()];
        }
        wp_reset_postdata();
    }

    ?>

    <div style="display: flex; justify-content: space-around">
        <table style="width: fit-content" class="form-table">
            <tr>
                <th><label for="team_season">Saison :</label></th>
                <td>
                    <input type="text" id="team_season" name="team_season"
                           value="<?php echo esc_attr($team_season); ?>"
                           placeholder="2024-2025"
                           class="regular-text">
                    <p class="description">Format: YYYY-YYYY (ex: 2024-2025)</p>
                </td>
            </tr>
            <tr>
                <th><label for="team_captain">Capitaine de l'&eacute;quipe :</label></th>
                <td>
                    <input type="text" id="team_captain" name="team_captain"
                           value="<?php echo esc_attr($captain); ?>"
                           class="regular-text">
                </td>
            </tr>
            <tr>
                <th><label for="team_image">Photo de l'équipe :</label></th>
                <td>
                    <?php
                    // Image upload/selection
                    $image = '';
                    if ($team_image_id) {
                        $image = wp_get_attachment_image($team_image_id, 'medium');
                    }
                    ?>
                    <div id="team_image_container">
                        <?php echo $image; ?>
                    </div>
                    <input type="hidden" id="team_image_id"
                           name="team_image_id"
                           value="<?php echo esc_attr($team_image_id); ?>"
                           style="margin-top: 10px;"
                    >
                    <button type="button"
                            class="button sports-team-upload-image">
                        Sélectionner une image
                    </button>
                    <button type="button"
                            class="button sports-team-remove-image"
                            style="color: red; border: 1px solid red; display:<?php echo $image ? 'inline-block' : 'none'; ?>;">
                        Supprimer l'image
                    </button>
                </td>
            </tr>
            <tr>
                <th><label for="team_order">Position de l'équipe sur la page :</label></th>
                <td>
                    <input type="number" id="team_order" name="team_order"
                           value="<?php echo esc_attr($theTeamOrderValue); ?>"
                           class="regular-text">
                </td>
            </tr>
            <!--Liste des joueurs (un champ texte pour chaque-->
            <tr>
                <th><label for="team_players">Joueurs de l'équipe :</label></th>
                <td>
          <textarea name="team_players" id="team_players"
                    rows="10" class="regular-text"><?php
              $players = get_post_meta($post->ID, '_sports_team_players', true);
              if ($players) {
                  echo implode("\n", $players);
              }
              ?></textarea>
                    <p class="description">Entrez les noms des joueurs, un par ligne.<br><strong>Ne pas ajouter le capitaine.</strong></p>
                </td>
            </tr>
        </table>
        <?php if (isset($teamsBySeasons)) { ?>
            <div>
                <p style="font-size: 20px; font-weight: bolder">Liste de toutes les équipes actuelles et leur position : (<?= get_the_title() ?>)</p>
                <!--Show all teams in order of position-->
                <div style="margin-left: 20px">
                    <?php
                    foreach ($teamsBySeasons as $season => $teams) {
                        echo "<span style='font-size: 18px; font-weight: bold'>Saison " . $season . " :</span>";
                        echo "<ol>";
                        foreach ($teams as $team) {
                            echo "<li style='font-size: 16px; " . ($team[1] === $post->ID ? 'text-decoration: underline' : '') . "'>" . $team[0] . "</li>";
                        }
                        echo "</ol>";
                    }
                    ?>
                </div>
            </div>
        <?php } ?>
    </div>

    <?php
}

// Save Meta Box Data
function save_sports_team_meta_data($post_id): void
{
    // Check nonce for security
    if (!isset($_POST['sports_team_details_nonce']) ||
            !wp_verify_nonce($_POST['sports_team_details_nonce'], 'sports_team_details_nonce')) {
        return;
    }

    // Check autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Check user permissions
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // Save Season
    if (isset($_POST['team_season'])) {
        update_post_meta(
                $post_id,
                '_sports_team_season',
                sanitize_text_field($_POST['team_season'])
        );
    }

    // Save Captain Name
    if (isset($_POST['team_captain'])) {
        // mot 1 (firstname) : first letter in Upper
        // mot 2 (name) : all in UPPER
        $fullName = explode(' ', $_POST['team_captain'], 2);
        if (count($fullName) >= 2) {
            $fullName[0] = ucwords(strtolower($fullName[0]));
            $fullName[1] = strtoupper($fullName[1]);
            $name = implode(' ', $fullName);
        } else {
            $name = ucwords(strtolower($_POST['team_captain']));
        }

        update_post_meta(
                $post_id,
                '_sports_team_captain',
                sanitize_text_field($name)
        );
    }

    // Save Team Image
    if (isset($_POST['team_image_id'])) {
        update_post_meta(
                $post_id,
                '_sports_team_image',
                $_POST['team_image_id'] ? intval($_POST['team_image_id']) : ''
        );
    }

    // Save Team Players
    if (isset($_POST['team_players'])) {
        $players = array_filter(array_map('trim', explode("\n", $_POST['team_players'])));
        update_post_meta(
                $post_id,
                '_sports_team_players',
                $players ? $players : array()
        );
    }

    // Save Team Order
    if (isset($_POST['team_order'])) {
        $new_order = intval($_POST['team_order']);
        $seasonOfNewOrder = intval($_POST['team_season']);
        $args = array(
                'post_type' => 'sports_team',
                'posts_per_page' => -1,
                'orderby' => 'meta_value_num',
                'meta_key' => '_sports_team_order',
                'order' => 'ASC',
                'post__not_in' => array($post_id) // Exclude current team
        );

        $teams_query = new WP_Query($args);
        $existing_teams = array();

        // Collect existing team orders
        while ($teams_query->have_posts()) {
            $teams_query->the_post();
            $current_team_season = intval(get_post_meta(get_the_ID(), '_sports_team_season', true));
            $current_team_order = intval(get_post_meta(get_the_ID(), '_sports_team_order', true));
            $existing_teams[$current_team_season][get_the_ID()] = $current_team_order;
        }
        wp_reset_postdata();

        // Check if the new order is already taken
        if (in_array($new_order, $existing_teams[$seasonOfNewOrder])) {
            // Shift orders for teams at or above the new order
            foreach ($existing_teams[$seasonOfNewOrder] as $team_id => $team_order) {
                if ($team_order >= $new_order) {
                    update_post_meta(
                            $team_id,
                            '_sports_team_order',
                            $team_order + 1
                    );
                }
            }
        }

        // Update the current team's order
        update_post_meta(
                $post_id,
                '_sports_team_order',
                $new_order
        );
    }
}

add_action('save_post_sports_team', 'save_sports_team_meta_data');

// Enqueue Scripts for Media Upload
function sports_team_admin_scripts($hook): void
{
    global $post_type;

    if ($post_type !== 'sports_team') {
        return;
    }

    wp_enqueue_media();
}

add_action('admin_enqueue_scripts', 'sports_team_admin_scripts');

// Add JavaScript for Media Upload Functionality
function sports_team_media_upload_script()
{
    ?>
    <script type="text/javascript">
        jQuery(document).ready(function ($) {
            // Media Uploader
            $(".sports-team-upload-image").on("click", function (e) {
                e.preventDefault();
                var button = $(this);
                var imageContainer = $("#team_image_container");
                var imageIdInput = $("#team_image_id");

                var mediaUploader = wp.media({
                    title: "Choisir une photo pour l'équipe",
                    button: {
                        text: "Sélectionner"
                    },
                    multiple: false
                });

                mediaUploader.on("select", function () {
                    var attachment = mediaUploader.state().get("selection").first().toJSON();
                    imageContainer.html("<img src=\"" + attachment.url + "\" alt=\"Img\" style=\"max-width:300px;\">");
                    imageIdInput.val(attachment.id);
                    button.next(".sports-team-remove-image").show();
                });

                mediaUploader.open();
            });

            // Remove Image
            $(".sports-team-remove-image").on("click", function (e) {
                e.preventDefault();
                $("#team_image_container").html("");
                $("#team_image_id").val("");
                $(this).hide();
            });
        });
    </script>
    <?php
}

add_action('admin_footer', 'sports_team_media_upload_script');

// Custom Columns for Teams
function sports_team_custom_columns($columns): array
{
    return array(
            'cb' => $columns['cb'],
            'team_order' => 'Position',
            'title' => 'Nom de l\'équipe',
            'season' => 'Saison',
            'captain' => 'Capitaine',
            'team_image' => 'Photo',
            'date' => 'Date'
    );
}

add_filter('manage_sports_team_posts_columns', 'sports_team_custom_columns');

// Populate Custom Columns
function sports_team_custom_column_content($column, $post_id): void
{
    switch ($column) {
        case 'captain':
            $captain = get_post_meta($post_id, '_sports_team_captain', true);
            echo esc_html($captain);
            break;

        case 'season':
            $season = get_post_meta($post_id, '_sports_team_season', true);
            echo esc_html($season);
            break;

        case 'team_image':
            $image_id = get_post_meta($post_id, '_sports_team_image', true);
            if ($image_id) {
                echo wp_get_attachment_image($image_id, 'thumbnail');
            }
            break;

        case 'team_order':
            $team_order = get_post_meta($post_id, '_sports_team_order', true);
            echo esc_html($team_order);
            break;
    }
}

add_action('manage_sports_team_posts_custom_column', 'sports_team_custom_column_content', 10, 2);

// Make custom columns sortable
function sports_team_sortable_columns($columns)
{
    $columns['season'] = 'season';
    $columns['team_order'] = 'team_order';
    $columns['captain'] = 'captain';
    return $columns;
}

add_filter('manage_edit-sports_team_sortable_columns', 'sports_team_sortable_columns');

// Handle custom column sorting
function sports_team_column_orderby($query)
{
    if (!is_admin() || !$query->is_main_query()) {
        return;
    }

    $orderby = $query->get('orderby');

    switch ($orderby) {
        case 'season':
            $query->set('meta_key', '_sports_team_season');
            $query->set('orderby', 'meta_value');
            break;
        case 'team_order':
            $query->set('meta_key', '_sports_team_order');
            $query->set('orderby', 'meta_value_num');
            break;
        case 'captain':
            $query->set('meta_key', '_sports_team_captain');
            $query->set('orderby', 'meta_value');
            break;
    }
}

add_action('pre_get_posts', 'sports_team_column_orderby');

function set_default_sports_team_query_ordering($query): void
{
    if (is_admin() && $query->get('post_type') === 'sports_team' && !$query->get('orderby')) {
        $query->set('meta_key', '_sports_team_order');
        $query->set('orderby', 'meta_value_num');
        $query->set('order', 'ASC');
    }
}

add_action('pre_get_posts', 'set_default_sports_team_query_ordering');

// Block custom
// Ajout du champ dans l’admin
add_action('add_meta_boxes', function () {
    add_meta_box(
            'interclub_iframe_url',
            'Lien de l\'iframe Interclub',
            function ($post) {
                $value = get_post_meta($post->ID, '_interclub_iframe_url', true);
                echo '<input type="text" name="interclub_iframe_url" value="' . esc_attr($value) . '" style="width:100%">';
            },
            'sports_team'
    );
});

// Sauvegarde du champ
add_action('save_post', function ($post_id) {
    if (isset($_POST['interclub_iframe_url'])) {
        update_post_meta($post_id, '_interclub_iframe_url', sanitize_text_field($_POST['interclub_iframe_url']));
    }
});
