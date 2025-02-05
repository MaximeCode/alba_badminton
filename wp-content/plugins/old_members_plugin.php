<?php
/*
Plugin Name: Club Office Members
Description: Gestion des anciens membres du bureau avec upload d'image
Version: 1.1
*/

if (!defined('ABSPATH')) {
    exit;
}

// Register Custom Post Type for Office Members
function office_members_post_type(): void
{
    register_post_type('office_member', [
        'labels' => [
            'name' => 'Membres du bureau',
            'singular_name' => 'Membre du bureau',
            'add_new' => 'Ajouter un membre',
            'add_new_item' => 'Ajouter un nouveau membre',
            'edit_item' => 'Modifier le membre',
        ],
        'public' => true,
        'menu_icon' => 'dashicons-groups',
        'supports' => array('title'),
        'has_archive' => true
    ]);

    // Register Year Taxonomy
    register_taxonomy('office_year', 'office_member', [
        'labels' => [
            'name' => 'Ann&eacute;es',
            'singular_name' => 'Ann&eacute;e',
            'add_new_item' => 'Ajouter une nouvelle ann&eacute;e',
        ],
        'hierarchical' => true,
        'show_admin_column' => true
    ]);
}

add_action('init', 'office_members_post_type');

// Add Meta Box for Position
function add_office_member_meta_boxes(): void
{
    add_meta_box(
        'office_member_position',
        'Poste dans le bureau',
        'render_position_meta_box',
        'office_member',
        'normal',
    );
}

add_action('add_meta_boxes', 'add_office_member_meta_boxes');

// Add Meta Box for Image
function add_office_member_image_meta_box(): void
{
    add_meta_box(
        'office_member_image',
        'Photo du membre',
        'render_member_image_meta_box',
        'office_member',
        'normal',
    );
}

add_action('add_meta_boxes', 'add_office_member_image_meta_box');

// Render Position Meta Box
function render_position_meta_box($post): void
{
    wp_nonce_field('office_member_position_nonce', 'office_member_position_nonce');

    $position = get_post_meta($post->ID, '_office_position', true);
    $positions = getPositions();
    ?>
    <select name="office_position" id="office_position">
        <option value="">S&eacute;lectionner un poste</option>
        <?php foreach ($positions as $key => $label): ?>
            <option value="<?php echo $key; ?>" <?php echo ($position === $key) ? 'selected' : ''; ?>>
                <?php echo $label; ?>
            </option>
        <?php endforeach; ?>
    </select>
    <?php
}

// Render Image Meta Box
function render_member_image_meta_box($post): void
{
    wp_nonce_field('office_member_image_nonce', 'office_member_image_nonce');

    $image_id = get_post_meta($post->ID, '_office_member_image_id', true);
    ?>
    <div>
        <table style="width: fit-content" class="form-table">
            <tr>
                <th><label for="office_member_image">Photo :</label></th>
                <td>
                    <?php
                    $image = '';
                    if ($image_id) {
                        $image = wp_get_attachment_image($image_id, 'thumbnail');
                    }
                    ?>
                    <div id="office_member_image_container">
                        <?php echo $image; ?>
                    </div>
                    <input type="hidden"
                           id="office_member_image_id"
                           name="office_member_image_id"
                           value="<?php echo esc_attr($image_id); ?>"
                           style="margin-top: 10px;">
                    <button type="button"
                            class="button office_member_upload_image">
                        Sélectionner une image
                    </button>
                    <button type="button"
                            class="button office-member-remove-image"
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

            $('.office_member_upload_image').on('click', function (e) {
                e.preventDefault();

                customUploader.open();
                customUploader.on('select', function () {
                    let attachment = customUploader.state().get('selection').first().toJSON();
                    $('#office_member_image_id').val(attachment.id);
                    $('#office_member_image_container').html('<img src="' + attachment.url + '" style="max-width: 300px; height: auto;" alt="Image du membre">');
                    $('.office-member-remove-image').show();
                });
            });

            $('.office-member-remove-image').on('click', function (e) {
                e.preventDefault();
                $('#office_member_image_id').val('');
                $('#office_member_image_container').html('');
                $(this).hide();
            });
        });
    </script>
    <?php
}

// Save Position and Year Meta Data
function save_office_member_meta($post_id): void
{
    if (!isset($_POST['office_member_position_nonce']) ||
        !wp_verify_nonce($_POST['office_member_position_nonce'], 'office_member_position_nonce')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (isset($_POST['office_position'])) {
        update_post_meta(
            $post_id,
            '_office_position',
            sanitize_text_field($_POST['office_position'])
        );
    }

    if (isset($_POST['office_year'])) {
        wp_set_post_terms(
            $post_id,
            sanitize_text_field($_POST['office_year']),
            'office_year',
        );
    }
}

add_action('save_post_office_member', 'save_office_member_meta');

// Save Image Meta Data
function save_office_member_image_meta($post_id): void
{
    if (!isset($_POST['office_member_image_nonce']) ||
        !wp_verify_nonce($_POST['office_member_image_nonce'], 'office_member_image_nonce')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (isset($_POST['office_member_image_id'])) {
        update_post_meta(
            $post_id,
            '_office_member_image_id',
            $_POST['office_member_image_id'] ? absint($_POST['office_member_image_id']) : ''
        );
    }
}

add_action('save_post_office_member', 'save_office_member_image_meta');

// Enqueue Scripts for Media Upload
function old_members_admin_scripts($hook): void
{
    global $post_type;

    if ($post_type !== 'office_member') {
        return;
    }

    wp_enqueue_media();
}

add_action('admin_enqueue_scripts', 'old_members_admin_scripts');

// Custom Columns for Old Members
function old_members_custom_columns($columns): array
{
    return array(
        'cb' => $columns['cb'],
        'title' => 'Membre',
        'image' => 'Photo',
        'position' => 'Poste',
        'year' => 'Ann&eacute;e(s)',
        'date' => 'Date'
    );
}

add_filter('manage_office_member_posts_columns', 'old_members_custom_columns');

// Populate Custom Columns
function old_members_custom_column_content($column, $post_id): void
{
    switch ($column) {
        case 'year':
            $year_terms = wp_get_post_terms($post_id, 'office_year');
            if (empty($year_terms)) {
                echo 'Non défini';
                break;
            }
            foreach ($year_terms as $term) {
                echo esc_html($term->name);
                echo '<br>';
            }
            break;
        case 'position':
            $position = get_post_meta($post_id, '_office_position', true);
            $positions = getPositions();
            echo isset($positions[$position]) ? esc_html($positions[$position]) : 'Non défini';
            break;
        case 'image':
            $image_id = get_post_meta($post_id, '_office_member_image_id', true);
            if ($image_id) {
                echo wp_get_attachment_image($image_id, array(100, 100));
            } else {
                echo '—';
            }
            break;
    }
}

add_action('manage_office_member_posts_custom_column', 'old_members_custom_column_content', 10, 2);

// Function to get office members by year
function get_office_members_by_year($year = null): array
{
    $args = [
        'post_type' => 'office_member',
        'posts_per_page' => -1,
        'orderby' => 'menu_order',
        'order' => 'ASC'
    ];

    if ($year) {
        $args['tax_query'] = [[
            'taxonomy' => 'office_year',
            'field' => 'slug',
            'terms' => $year
        ]];
    }

    $positions = getPositions();

    $members = [];
    $query = new WP_Query($args);

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $year_terms = wp_get_post_terms(get_the_ID(), 'office_year');
            $year_slug = [];
            foreach ($year_terms as $term) {
                $year_slug[] = $term->slug;
            }

            // récupération de la clé 'position'
            $position_key = get_post_meta(get_the_ID(), '_office_position', true);
            $position = $positions[$position_key] ?? 'Non défini';

            // Récupération de l'image
            $image_id = get_post_meta(get_the_ID(), '_office_member_image_id', true);

            if (!$year) {
                foreach ($year_slug as $year_oui) {
                    $members[$year_oui][] = [
                        'id' => get_the_ID(),
                        'name' => get_the_title(),
                        'position' => $position,
                        'image_id' => $image_id,
                    ];
                }
            } else {
                $members[] = [
                    'id' => get_the_ID(),
                    'name' => get_the_title(),
                    'position' => $position,
                    'image_id' => $image_id,
                ];
            }
        }
    }
    wp_reset_postdata();

    return $members;
}

// Liste des positions disponibles
function getPositions(): array
{
    return [
        'president' => 'Pr&eacute;sident(e)',
        'vice_president' => 'Vice-pr&eacute;sident(e)',
        'treasurer' => 'Tr&eacute;sorier(e)',
        'secretary' => 'Secr&eacute;taire',
        'assistant_secretary' => 'Secr&eacute;taire adjoint(e)',
        'member' => 'Membre'
    ];
}