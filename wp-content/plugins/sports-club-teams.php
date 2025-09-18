<?php
/*
Plugin Name: Équipes d'interclubs
Description: Page conçu pour l'administration des équipes d'interclubs.
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

// Create category for team articles on plugin activation
function sports_club_create_team_category()
{
    $category_name = "Les équipes d'interclubs";
    $category_slug = 'equipes-interclubs';

    // Check if category already exists
    $existing_category = get_category_by_slug($category_slug);

    if (!$existing_category) {
        wp_insert_term(
                $category_name,
                'category',
                array(
                        'slug' => $category_slug,
                        'description' => 'Articles des équipes d\'interclubs'
                )
        );
    }
}

register_activation_hook(__FILE__, 'sports_club_create_team_category');

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
    $linked_article_id = get_post_meta($post->ID, '_sports_team_article_id', true);

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
    $theteamOrderValue = $team_order ? $team_order : $teamCount + 1;

    // Get current season if not set
    if (!$team_season) {
        $current_year = date('Y');
        $next_year = $current_year + 1;
        $team_season = $current_year . '-' . $next_year;
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
                           value="<?php echo esc_attr($theteamOrderValue); ?>"
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
            <?php if ($linked_article_id): ?>
                <tr>
                    <th><label>Article lié :</label></th>
                    <td>
                        <?php
                        $article = get_post($linked_article_id);
                        if ($article && $article->post_status !== 'trash'):
                            ?>
                            <a href="<?php echo get_edit_post_link($linked_article_id); ?>" target="_blank">
                                📝 <?php echo esc_html($article->post_title); ?>
                            </a>
                            <br>
                            <a href="<?php echo get_permalink($linked_article_id); ?>" target="_blank">
                                👁️ Voir l'article
                            </a>
                        <?php else: ?>
                            <span style="color: red;">Article supprimé ou introuvable</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endif; ?>
        </table>
        <ol>
            <p style="font-size: 20px; font-weight: bold">Liste de toutes les équipes actuelles et leur position :</p>
            <!--Show all teams in order of position-->
            <?php
            if ($teams->have_posts()) {
                while ($teams->have_posts()) {
                    $teams->the_post();
                    $current_season = get_post_meta(get_the_ID(), '_sports_team_season', true);
                    ?>
                    <li style="margin-left: 20px; font-size: medium">
                        <strong><?php the_title(); ?></strong>
                        <?php if ($current_season): ?>
                            <span style="color: #666; font-size: small;">(<?php echo esc_html($current_season); ?>)</span>
                        <?php endif; ?>
                    </li>
                    <?php
                }
                wp_reset_postdata();
            }
            ?>
        </ol>
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
                $players ?: array()
        );
    }

    // Save Team Order
    if (isset($_POST['team_order'])) {
        $new_order = intval($_POST['team_order']);
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
            $current_team_order = intval(get_post_meta(get_the_ID(), '_sports_team_order', true));
            $existing_teams[get_the_ID()] = $current_team_order;
        }
        wp_reset_postdata();

        // Check if the new order is already taken
        if (in_array($new_order, $existing_teams)) {
            // Shift orders for teams at or above the new order
            foreach ($existing_teams as $team_id => $team_order) {
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

    // Create or update linked article
    create_or_update_team_article($post_id);
}

add_action('save_post_sports_team', 'save_sports_team_meta_data');

// Create or update team article
function create_or_update_team_article($team_id)
{
    $team_post = get_post($team_id);
    if (!$team_post) return;

    $team_title = $team_post->post_title;
    $team_season = get_post_meta($team_id, '_sports_team_season', true);
    $linked_article_id = get_post_meta($team_id, '_sports_team_article_id', true);

    // Get team category
    $category = get_category_by_slug('equipes-interclubs');
    if (!$category) {
        sports_club_create_team_category();
        $category = get_category_by_slug('equipes-interclubs');
    }

    $article_title = $team_title . ($team_season ? ' - Saison ' . $team_season : '');

    // Check if article exists
    if ($linked_article_id) {
        $existing_article = get_post($linked_article_id);
        if ($existing_article && $existing_article->post_status !== 'trash') {
            // Update existing article
            wp_update_post(array(
                    'ID' => $linked_article_id,
                    'post_title' => $article_title,
            ));
            return;
        }
    }

    // Create new article
    $captain = get_post_meta($team_id, '_sports_team_captain', true);
    $players = get_post_meta($team_id, '_sports_team_players', true);

    $content = '<h2>Composition de l\'équipe</h2>';
    if ($captain) {
        $content .= '<p><strong>Capitaine :</strong> ' . esc_html($captain) . '</p>';
    }

    if ($players && is_array($players)) {
        $content .= '<p><strong>Joueurs :</strong></p><ul>';
        foreach ($players as $player) {
            $content .= '<li>' . esc_html($player) . '</li>';
        }
        $content .= '</ul>';
    }

    $content .= '<p><em>Cette page sera enrichie avec les actualités et résultats de l\'équipe.</em></p>';

    $article_data = array(
            'post_title' => $article_title,
            'post_content' => $content,
            'post_status' => 'draft', // Created as draft
            'post_type' => 'post',
            'post_category' => array($category->term_id)
    );

    $article_id = wp_insert_post($article_data);

    if ($article_id && !is_wp_error($article_id)) {
        // Link article to team
        update_post_meta($team_id, '_sports_team_article_id', $article_id);

        // Add team reference to article
        update_post_meta($article_id, '_linked_team_id', $team_id);
    }
}

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
            'linked_article' => 'Article',
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

        case 'linked_article':
            $article_id = get_post_meta($post_id, '_sports_team_article_id', true);
            if ($article_id) {
                $article = get_post($article_id);
                if ($article && $article->post_status !== 'trash') {
                    $status_icon = $article->post_status === 'publish' ? '✅' : '📝';
                    echo '<a href="' . get_edit_post_link($article_id) . '" title="Modifier l\'article">';
                    echo $status_icon . ' ' . esc_html($article->post_title);
                    echo '</a>';
                } else {
                    echo '<span style="color: red;">❌ Article supprimé</span>';
                }
            } else {
                echo '<span style="color: orange;">Aucun article</span>';
            }
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

// Delete linked article when team is deleted
function delete_linked_article_on_team_deletion($post_id)
{
    if (get_post_type($post_id) === 'sports_team') {
        $linked_article_id = get_post_meta($post_id, '_sports_team_article_id', true);
        if ($linked_article_id) {
            wp_delete_post($linked_article_id, true); // Force delete
        }
    }
}

add_action('before_delete_post', 'delete_linked_article_on_team_deletion');

// Add admin notice for new articles created
function sports_team_admin_notices()
{
    if (isset($_GET['post']) && isset($_GET['message']) && $_GET['message'] == '1') {
        $post_id = intval($_GET['post']);
        if (get_post_type($post_id) === 'sports_team') {
            $article_id = get_post_meta($post_id, '_sports_team_article_id', true);
            if ($article_id) {
                $article = get_post($article_id);
                if ($article) {
                    echo '<div class="notice notice-info is-dismissible">';
                    echo '<p>✅ Article créé automatiquement : ';
                    echo '<a href="' . get_edit_post_link($article_id) . '">' . esc_html($article->post_title) . '</a>';
                    echo ' (Statut: Brouillon)</p>';
                    echo '</div>';
                }
            }
        }
    }
}

add_action('admin_notices', 'sports_team_admin_notices');