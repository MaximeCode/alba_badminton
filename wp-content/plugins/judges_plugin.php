<?php
/*
Plugin Name: Gestion des juges
Description: Page conçue pour l'administration des juges officiels
Version: 1.0
Author: M.B.
*/

// Img par défaut en PROD : 538
// Img par défaut en DEV : 523

if (!defined('ABSPATH')) {
    exit;
}

// image par défaut : 538

// Register Custom Post Type for judges
function all_judges_post_type(): void
{
    $labels = array(
        'name' => 'Les juges officiels',
        'singular_name' => 'Juge',
        'menu_name' => 'Les juges officiels',
        'add_new' => 'Ajouter un juge',
        'add_new_item' => 'Ajouter un juge',
        'edit_item' => 'Modifier un juge',
        'new_item' => 'Nouveau juge',
        'view_item' => 'Voir le juge',
        'search_items' => 'Rechercher des juges',
        'not_found' => 'Aucun juge trouvé',
        'not_found_in_trash' => 'Aucun juge trouvé dans la corbeille'
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'has_archive' => true,
        'publicly_queryable' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'judge'),
        'capability_type' => 'post',
        'hierarchical' => false,
        'supports' => array('title', 'editor', 'page-attributes'),
        'menu_icon' => 'dashicons-businessman',
        'show_in_rest' => true,
        'taxonomies' => array('tax_judges_types')
    );

    register_post_type('all_judges', $args);

    // Register Year Taxonomy with enhanced settings
    $taxonomy_labels = array(
        'name' => 'Types de juges',
        'singular_name' => 'Type de juge',
        'search_items' => 'Rechercher des types',
        'all_items' => 'Tous les types',
        'parent_item' => 'Type parent',
        'parent_item_colon' => 'Type parent:',
        'edit_item' => 'Modifier le type',
        'update_item' => 'Mettre à jour le type',
        'add_new_item' => 'Ajouter un nouveau type de juge',
        'new_item_name' => 'Nouveau type de juge',
        'menu_name' => 'Types de juges'
    );

    register_taxonomy('tax_judges_types', 'all_judges', array(
        'labels' => $taxonomy_labels,
        'hierarchical' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'show_in_rest' => true,  // Add this for Gutenberg support
        'query_var' => true,
        'rewrite' => array('slug' => 'judge-type'),
        'public' => true,        // Make sure it's public
        'show_in_menu' => true   // Show in menu
    ));
}

add_action('init', 'all_judges_post_type');

// Add Custom Meta Boxes for judge Details
function all_judges_meta_boxes(): void
{
    add_meta_box(
        'all_judges_details',
        'Détails des juges officiels du club',
        'render_judge_details_meta_box',
        'all_judges',
        'normal',
    );
}

add_action('add_meta_boxes', 'all_judges_meta_boxes');

// Render Meta Box Content
function render_judge_details_meta_box($post): void
{
    wp_nonce_field('all_judges_details_nonce', 'all_judges_details_nonce');

    $judge_image_id = get_post_meta($post->ID, 'judge_img_id', true);
    ?>

    <div>
        <table style="width: fit-content" class="form-table">
            <tr>
                <th><label for="judge_img_id">Photo du juge :</label></th>
                <td>
                    <?php
                    $image = '';
                    if ($judge_image_id) {
                        $image = wp_get_attachment_image($judge_image_id, 'thumbnail');
                    }
                    ?>
                    <div id="judge_img_container">
                        <?php echo $image; ?>
                    </div>
                    <input type="hidden"
                           id="judge_img_id"
                           name="judge_img_id"
                           value="<?php echo esc_attr($judge_image_id); ?>"
                           style="margin-top: 10px;">
                    <button type="button"
                            class="button judges_upload_image">
                        Sélectionner une image
                    </button>
                    <button type="button"
                            class="button judges-remove-image"
                            style="color: red; border: 1px solid red; display:<?php echo $image ? 'inline-block' : 'none'; ?>;">
                        Supprimer l'image
                    </button>
                </td>
            </tr>
        </table>
    </div>

    <script>
        jQuery(document).ready(function ($) {
            let customUploader = wp.media({
                title: 'Sélectionner une image',
                button: {
                    text: 'Utiliser cette image'
                },
                multiple: false
            });

            $('.judges_upload_image').on('click', function (e) {
                e.preventDefault();
                customUploader.open();
                customUploader.on('select', function () {
                    let attachment = customUploader.state().get('selection').first().toJSON();
                    $('#judge_img_id').val(attachment.id);
                    $('#judge_img_container').html('<img src="' + attachment.url + '" style="max-width: 300px; height: auto;" alt="Image du juge">');
                    $('.judges-remove-image').show();
                });
            });

            $('.judges-remove-image').on('click', function (e) {
                e.preventDefault();
                $('#judge_img_id').val('');
                $('#judge_img_container').html('');
                $(this).hide();
            });
        });
    </script>
    <?php
}

// Save Meta Data
function save_all_judges_meta_data($post_id): void
{
    if (!isset($_POST['all_judges_details_nonce']) ||
        !wp_verify_nonce($_POST['all_judges_details_nonce'], 'all_judges_details_nonce')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // Save judge image
    if (isset($_POST['judge_img_id'])) {
        update_post_meta(
            $post_id,
            'judge_img_id',
            $_POST['judge_img_id'] ? absint($_POST['judge_img_id']) : 538
        );
    }

    // Save judge types
    if (isset($_POST['tax_judges_types'])) {
        wp_set_post_terms(
            $post_id,
            $_POST['tax_judges_types'],
            'tax_judges_types'
        );
    }
}

add_action('save_post', 'save_all_judges_meta_data');

// Modification des colonnes pour afficher les types
function all_judges_custom_columns($columns): array
{
    return array(
        'cb' => $columns['cb'],
        'title' => 'Nom du juge',
        'tax_judges_types' => 'Types',
        'judge_img' => 'Photo',
        'date' => 'Date'
    );
}

add_filter('manage_all_judges_posts_columns', 'all_judges_custom_columns');

// Update Custom Column Content
function all_judges_custom_column_content($column, $post_id): void
{
    if ($column == 'judge_img') {
        $image_id = get_post_meta($post_id, 'judge_img_id', true);
        echo wp_get_attachment_image($image_id ?: 523, array(100, 100));
    }

    if ($column == 'tax_judges_types') {
        $types = wp_get_post_terms($post_id, 'tax_judges_types', array('fields' => 'names'));
        echo implode(', ', $types);
    }
}

add_action('manage_all_judges_posts_custom_column', 'all_judges_custom_column_content', 10, 2);

// Get all judges with their types
function get_judges_with_types(): array
{
    $args = array(
        'post_type' => 'all_judges',
        'posts_per_page' => -1,
        'orderby' => 'title',
        'order' => 'ASC'
    );

    $judges = array();
    $query = new WP_Query($args);

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $post_id = get_the_ID();

            // Get taxonomy terms for this judge
            $types = wp_get_post_terms($post_id, 'tax_judges_types', array('fields' => 'all'));

            $judges[] = array(
                'id' => $post_id,
                'name' => get_the_title(),
                'types' => $types,
                'image_id' => get_post_meta($post_id, 'judge_img_id', true)
            );
        }
    }
    wp_reset_postdata();

    return $judges;
}

// Get judges by specific type
function get_judges_by_type(string $type_slug): array
{
    $args = array(
        'post_type' => 'all_judges',
        'posts_per_page' => -1,
        'orderby' => 'title',
        'order' => 'ASC',
        'tax_query' => array(
            array(
                'taxonomy' => 'tax_judges_types',
                'field' => 'slug',
                'terms' => $type_slug
            )
        )
    );

    $judges = array();
    $query = new WP_Query($args);

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $post_id = get_the_ID();

            $judges[] = array(
                'id' => $post_id,
                'name' => get_the_title(),
                'image_id' => get_post_meta($post_id, 'judge_img_id', true)
            );
        }
    }
    wp_reset_postdata();

    return $judges;
}

// Get all judge types
function get_all_judge_types(): array
{
    return get_terms(array(
        'taxonomy' => 'tax_judges_types',
        'hide_empty' => false
    ));
}

add_filter('pre_insert_term', function ($term, $taxonomy) {
    if ($taxonomy === 'tax_judges_types') {
        $term = ucwords(strtolower($term));
    }
    return $term;
}, 10, 2);