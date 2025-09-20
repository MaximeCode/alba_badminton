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
                    'name' => 'Années',
                    'singular_name' => 'Année',
                    'add_new_item' => 'Ajouter une nouvelle Année',
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
                $_POST['office_member_image_id'] ? absint($_POST['office_member_image_id']) : 538
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
            'year' => 'Année(s)',
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
            foreach (array_reverse($year_terms) as $term) {
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
        // On retire le tri WordPress pour faire notre propre tri
            'orderby' => 'ID',
            'order' => 'ASC',
            'post_status' => 'publish'
    ];

    if ($year) {
        $args['tax_query'] = [[
                'taxonomy' => 'office_year',
                'field' => 'slug',
                'terms' => $year,
        ]];
    } else {
        // Si aucune année n'est spécifiée, on récupère tous les membres
        $args['tax_query'] = [[
                'taxonomy' => 'office_year',
                'field' => 'slug',
                'terms' => get_terms([
                        'taxonomy' => 'office_year',
                        'fields' => 'slugs',
                        'hide_empty' => false,
                ]),
                'operator' => 'IN',
        ]];
    }

    $positions = getPositions();
    $position_hierarchy = getPositionHierarchy();
    $members = [];

    $query = new WP_Query($args);

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();

            $year_terms = get_the_terms(get_the_ID(), 'office_year');

            $year_slug = [];
            if ($year_terms && !is_wp_error($year_terms)) {
                foreach (array_reverse($year_terms) as $term) {
                    $year_slug[] = $term->slug;
                }
            }

            // récupération de la clé 'position'
            $position_key = get_post_meta(get_the_ID(), '_office_position', true);
            $position = $positions[$position_key] ?? 'Non défini';

            // Récupération de l'image
            $image_id = get_post_meta(get_the_ID(), '_office_member_image_id', true) ?: 538;

            $member_data = [
                    'id' => get_the_ID(),
                    'name' => get_the_title(),
                    'position' => $position,
                    'image_id' => $image_id,
                    'position_key' => $position_key, // On garde la clé pour le tri
            ];

            if (!$year) {
                // Si aucune année spécifiée, grouper par année
                foreach ($year_slug as $year_oui) {
                    $members[$year_oui][] = $member_data;
                }
            } else {
                // Si année spécifiée, ajouter directement
                $members[] = $member_data;
            }
        }
    }

    wp_reset_postdata();

    // Trier chaque année par ordre hiérarchique des positions
    if ($year === null) {
        // Cas : toutes les années
        foreach ($members as $the_year => $year_members) {
            $members[$the_year] = filterByHierarchy($year_members, $position_hierarchy);
        }
    } else {
        // Cas : année spécifique
        $members = filterByHierarchy($members, $position_hierarchy);
    }

    // Trier par ordre chronologique si pas d'année spécifiée
    if (!$year && !empty($members)) {
        uksort($members, function ($a, $b) {
            $year_a = (int)substr($a, 0, 4);
            $year_b = (int)substr($b, 0, 4);
            return $year_b <=> $year_a;
        });
    }

    return $members; // ← Retourner $members, pas $members[0]
}

function filterByHierarchy(mixed $year_members, array $position_hierarchy): array
{
    if (is_array($year_members)) {
        usort($year_members, function ($a, $b) use ($position_hierarchy) {
            // Récupérer l'ordre hiérarchique de chaque position
            $hierarchy_a = $position_hierarchy[$a['position_key']] ?? 99;
            $hierarchy_b = $position_hierarchy[$b['position_key']] ?? 99;

            // Tri principal par hiérarchie
            $hierarchy_diff = $hierarchy_a <=> $hierarchy_b;

            // Si même niveau hiérarchique, tri alphabétique par nom
            if ($hierarchy_diff === 0) {
                return strcasecmp($a['name'], $b['name']);
            }

            return $hierarchy_diff;
        });

        // Supprimer position_key sans référence
        foreach ($year_members as $index => $member) {
            unset($year_members[$index]['position_key']);
        }
    }

    return $year_members; // ← Retourner directement, pas array($year_members)
}

// Rendre la colonne Position triable
function make_office_member_columns_sortable($sortable_columns): array
{
    $sortable_columns['position'] = 'position';
    return $sortable_columns;
}

add_filter('manage_edit-office_member_sortable_columns', 'make_office_member_columns_sortable');

// Gérer le tri alphabétique de la colonne Position
function handle_office_member_position_orderby($query): void
{
    if (!is_admin() || !$query->is_main_query()) {
        return;
    }

    $orderby = $query->get('orderby');

    if ('position' === $orderby) {
        $query->set('meta_key', '_office_position');
        $query->set('orderby', 'meta_value');
    }
}

add_action('pre_get_posts', 'handle_office_member_position_orderby');

// SOLUTION 1: Custom Post Type pour les positions (recommandé)

// Créer le Custom Post Type pour les positions
function create_office_positions_post_type()
{
    register_post_type('office_position', [
            'labels' => [
                    'name' => 'Postes du bureau',
                    'singular_name' => 'Poste',
                    'add_new' => 'Ajouter un poste',
                    'add_new_item' => 'Ajouter un nouveau poste',
                    'edit_item' => 'Modifier le poste',
                    'all_items' => 'Tous les postes',
            ],
            'public' => false,
            'show_ui' => true,
            'show_in_menu' => 'edit.php?post_type=office_member', // Sous-menu des membres
            'menu_icon' => 'dashicons-businessman',
            'supports' => ['title'],
            'has_archive' => false,
            'hierarchical' => false,
    ]);
}

add_action('init', 'create_office_positions_post_type');

// Ajouter un champ pour l'ordre hiérarchique
function add_position_hierarchy_meta_box()
{
    add_meta_box(
            'position_hierarchy',
            'Ordre hiérarchique',
            'render_position_hierarchy_meta_box',
            'office_position',
            'side'
    );
}

add_action('add_meta_boxes', 'add_position_hierarchy_meta_box');

function render_position_hierarchy_meta_box($post)
{
    wp_nonce_field('position_hierarchy_nonce', 'position_hierarchy_nonce');
    $hierarchy = get_post_meta($post->ID, '_position_hierarchy', true) ?: 50;
    ?>
    <label for="position_hierarchy">Ordre (1 = plus haut niveau):</label>
    <input type="number" id="position_hierarchy" name="position_hierarchy"
           value="<?php echo esc_attr($hierarchy); ?>" min="1" max="100" style="width: 100%;">
    <p><small>1 = Président, 2 = Vice-président, etc.</small></p>
    <?php
}

// Sauvegarder l'ordre hiérarchique
function save_position_hierarchy_meta($post_id)
{
    if (!isset($_POST['position_hierarchy_nonce']) ||
            !wp_verify_nonce($_POST['position_hierarchy_nonce'], 'position_hierarchy_nonce')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (isset($_POST['position_hierarchy'])) {
        update_post_meta($post_id, '_position_hierarchy', absint($_POST['position_hierarchy']));
    }
}

add_action('save_post_office_position', 'save_position_hierarchy_meta');

// Nouvelle fonction pour récupérer les positions depuis la base
function getPositions(): array
{
    $positions = [];

    $query = new WP_Query([
            'post_type' => 'office_position',
            'posts_per_page' => -1,
            'post_status' => 'publish',
            'meta_key' => '_position_hierarchy',
            'orderby' => 'meta_value_num',
            'order' => 'ASC'
    ]);

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $slug = sanitize_title(get_the_title());
            $positions[$slug] = get_the_title();
        }
    }
    wp_reset_postdata();

    // Positions par défaut si aucune n'existe
    if (empty($positions)) {
        return [
                'president' => 'Président(e)',
                'vice_president' => 'Vice-président(e)',
                'secretary' => 'Secrétaire',
                'secretary_assistant' => 'Secrétaire adjoint(e)',
                'treasurer' => 'Trésorier(e)',
                'member' => 'Membre'
        ];
    }

    return $positions;
}

// Nouvelle fonction pour l'ordre hiérarchique
function getPositionHierarchy(): array
{
    $hierarchy = [];

    $query = new WP_Query([
            'post_type' => 'office_position',
            'posts_per_page' => -1,
            'post_status' => 'publish',
            'meta_key' => '_position_hierarchy',
            'orderby' => 'meta_value_num',
            'order' => 'ASC'
    ]);

    if ($query->have_posts()) {
        $order = 1;
        while ($query->have_posts()) {
            $query->the_post();
            $slug = get_post()->post_name;
            $custom_order = get_post_meta(get_the_ID(), '_position_hierarchy', true) ?: $order;
            $hierarchy[$slug] = (int)$custom_order;
            $order++;
        }
    }
    wp_reset_postdata();

    return $hierarchy;
}

// Ajouter des colonnes personnalisées pour les positions
function office_position_custom_columns($columns)
{
    return [
            'cb' => $columns['cb'],
            'title' => 'Nom du poste',
            'hierarchy' => 'Ordre hiérarchique',
            'usage_count' => 'Utilisé par',
            'date' => 'Date'
    ];
}

add_filter('manage_office_position_posts_columns', 'office_position_custom_columns');

function office_position_custom_column_content($column, $post_id)
{
    switch ($column) {
        case 'hierarchy':
            $hierarchy = get_post_meta($post_id, '_position_hierarchy', true) ?: 'Non défini';
            echo $hierarchy;
            break;

        case 'usage_count':
            $slug = sanitize_title(get_the_title($post_id));
            $count = new WP_Query([
                    'post_type' => 'office_member',
                    'meta_query' => [
                            [
                                    'key' => '_office_position',
                                    'value' => $slug,
                                    'compare' => '='
                            ]
                    ],
                    'fields' => 'ids'
            ]);
            echo $count->found_posts . ' membre(s)';
            break;
    }
}

add_action('manage_office_position_posts_custom_column', 'office_position_custom_column_content', 10, 2);

//// Fonction pour créer les positions par défaut (à exécuter une seule fois)
//function create_default_positions()
//{
//    $default_positions = [
//            ['title' => 'Président(e)', 'hierarchy' => 1],
//            ['title' => 'Vice-président(e)', 'hierarchy' => 2],
//            ['title' => 'Secrétaire', 'hierarchy' => 3],
//            ['title' => 'Secrétaire adjoint(e)', 'hierarchy' => 4],
//            ['title' => 'Trésorier(e)', 'hierarchy' => 5],
//            ['title' => 'Membre', 'hierarchy' => 6],
//    ];
//
//    foreach ($default_positions as $position) {
//        $post_id = wp_insert_post([
//                'post_title' => $position['title'],
//                'post_type' => 'office_position',
//                'post_status' => 'publish'
//        ]);
//
//        if ($post_id) {
//            update_post_meta($post_id, '_position_hierarchy', $position['hierarchy']);
//        }
//    }
//}
//
//// Hook pour créer les positions par défaut à l'activation du plugin
//register_activation_hook(__FILE__, function () {
//    // Créer les post types d'abord
//    create_office_positions_post_type();
//    office_members_post_type();
//
//    // Flush les règles de réécriture
//    flush_rewrite_rules();
//
//    // Créer les positions par défaut
//    create_default_positions();
//});

// Bouton de création d'un poste
function add_quick_position_button()
{
    global $current_screen;

    if ($current_screen && $current_screen->post_type === 'office_member') {
        ?>
        <script>
            jQuery(document).ready(function ($) {
                // Ajouter un bouton dans la metabox des positions
                $('#office_position').after('<button type="button" id="add-new-position" class="button" style="margin-left: 10px;">+ Nouveau poste</button>');

                $('#add-new-position').on('click', function () {
                    var newPosition = prompt('Nom du nouveau poste:');
                    if (newPosition) {
                        $.ajax({
                            url: ajaxurl,
                            method: 'POST',
                            data: {
                                action: 'create_new_position',
                                position_name: newPosition,
                                nonce: '<?php echo wp_create_nonce("create_position_nonce"); ?>'
                            },
                            success: function (response) {
                                if (response.success) {
                                    $('#office_position').append('<option value="' + response.data.slug + '" selected>' + response.data.name + '</option>');
                                    alert('Poste créé avec succès!');
                                } else {
                                    alert('Erreur: ' + response.data.message);
                                }
                            }
                        });
                    }
                });
            });
        </script>
        <?php
    }
}

add_action('admin_footer', 'add_quick_position_button');

// Handler AJAX pour créer un nouveau poste
function handle_create_new_position()
{
    if (!wp_verify_nonce($_POST['nonce'], 'create_position_nonce')) {
        wp_die('Nonce invalide');
    }

    $position_name = sanitize_text_field($_POST['position_name']);

    if (empty($position_name)) {
        wp_send_json_error(['message' => 'Le nom du poste ne peut pas être vide']);
    }

    // Créer le poste
    $post_id = wp_insert_post([
            'post_title' => $position_name,
            'post_type' => 'office_position',
            'post_status' => 'publish'
    ]);

    if ($post_id) {
        // Ordre par défaut
        update_post_meta($post_id, '_position_hierarchy', 50);

        $slug = sanitize_title($position_name);
        wp_send_json_success([
                'slug' => $slug,
                'name' => $position_name
        ]);
    } else {
        wp_send_json_error(['message' => 'Erreur lors de la création du poste']);
    }
}

add_action('wp_ajax_create_new_position', 'handle_create_new_position');