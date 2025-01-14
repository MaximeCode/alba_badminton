<?php
/*
Plugin Name: Club Office Members
Description: Gestion des anciens membres du bureau
Version: 1.0
*/

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

// Save Position Meta Data
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

// Enqueue Scripts for Media Upload
function old_members_admin_scripts($hook): void
{
    global $post_type;

    if ($post_type !== 'office_member') {
        return;
    }

    wp_enqueue_media();
//    wp_enqueue_script('sports-team-media-upload', plugin_dir_url(__FILE__) . 'js/team-media-upload.js', array('jquery'), '1.0', true);
}

add_action('admin_enqueue_scripts', 'old_members_admin_scripts');

// Custom Columns for Old Members
function old_members_custom_columns($columns): array
{
    return array(
        'cb' => $columns['cb'],
        'title' => 'Membre',
        'position' => 'Poste',
        'year' => 'Ann&eacute;e(s)',
        'date' => 'Date'
    );
}

add_filter('manage_office_member_posts_columns', function ($columns) {
    return old_members_custom_columns($columns);
});


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
//            echo esc_html($year);
            break;
        case 'position':
            $position = get_post_meta($post_id, '_office_position', true);
            $positions = getPositions();
            echo isset($positions[$position]) ? esc_html($positions[$position]) : 'Non défini';
            break;
    }
}

add_action('manage_office_member_posts_custom_column', 'old_members_custom_column_content', 10, 2);

//function set_default_office_member_query_ordering($query): void
//{
//    // trie par défaut par année
//    if (is_admin() && $query->get('post_type') === 'office_member') {
//        $query->set('meta_key', '_office_year');
//        $query->set('orderby', 'meta_value');
//        $query->set('order', 'DESC');
//    }
//}

//add_action('pre_get_posts', 'set_default_office_member_query_ordering');

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

            if (!$year) {
                foreach ($year_slug as $year_oui) {
                    $members[$year_oui][] = [
                        'id' => get_the_ID(),
                        'name' => get_the_title(),
                        'position' => $position,
                    ];
                }
            } else {
                $members[] = [
                    'id' => get_the_ID(),
                    'name' => get_the_title(),
                    'position' => $position,
                ];
            }
        }
    }
    wp_reset_postdata();

    return $members;
}

// Example usage:
/*
$members = get_office_members_by_year('2024');
// Or get all years:
$all_members = get_office_members_by_year();
*/

function getPositions(): array
{
    return [
        'president' => 'Pr&eacute;sident(e)',
        'vice_president' => 'Vice-pr&eacute;sident(e)',
        'treasurer' => 'Tr&eacute;sorier(e)',
        'secretary' => 'Secr&eacute;taire',
        'member' => 'Membre'
    ];
}