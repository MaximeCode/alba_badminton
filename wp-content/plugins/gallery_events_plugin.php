<?php
/*
Plugin Name: Gestion de la galerie du club
Description: Plugin pour gérer les événements de la galerie du club
Version: 1.0
Author: M.B.
*/

if (!defined('ABSPATH')) {
    exit;
}

// Register Custom Post Type for Events
// Register Custom Post Type for Events
function gallery_events_post_type(): void
{
    register_post_type('gallery_event', [
        'labels' => [
            'name' => 'Galerie du club',
            'singular_name' => 'Événement',
            'add_new' => 'Ajouter un nouvel événement',
            'add_new_item' => 'Ajouter un nouvel événement',
            'edit_item' => 'Modifier l\'événement',
            'all_items' => 'Tous les événements',
        ],
        'public' => true,
        'menu_icon' => 'dashicons-format-gallery',
        'supports' => ['title'],
        'has_archive' => true,
        'rewrite' => ['slug' => 'gallery-events'],
        'show_in_rest' => true,
    ]);

    // Register Season Taxonomy
    register_taxonomy('event_season', 'gallery_event', [
        'labels' => [
            'name' => 'Les saisons',
            'singular_name' => 'la Saison',
            'add_new_item' => 'Ajouter une nouvelle saison',
            'new_item_name' => 'Nom de la nouvelle saison',
            'edit_item' => 'Modifier la saison',
        ],
        'hierarchical' => true,
        'show_admin_column' => true,
        'show_in_rest' => true,
        'rewrite' => ['slug' => 'season'],
    ]);
}

add_action('init', 'gallery_events_post_type');

// Add Meta Box for Images
function add_gallery_event_meta_boxes(): void
{
    add_meta_box(
        'gallery_event_images',
        'Event Images',
        'render_gallery_images_meta_box',
        'gallery_event',
        'normal',
        'high'
    );
}

add_action('add_meta_boxes', 'add_gallery_event_meta_boxes');

// Render Images Meta Box
function render_gallery_images_meta_box($post): void
{
    wp_nonce_field('gallery_event_images_nonce', 'gallery_event_images_nonce');

    $image_ids = get_post_meta($post->ID, '_gallery_event_images', true);
    $image_ids = $image_ids ? explode(',', $image_ids) : [];
    ?>
    <div class="gallery-images-container">
        <div id="gallery-images-preview" style="margin-bottom: 10px;">
            <?php
            foreach ($image_ids as $image_id) {
                $image_url = wp_get_attachment_image_url($image_id, 'thumbnail');
                if ($image_url) {
                    echo '<div class="gallery-image-item" data-id="' . esc_attr($image_id) . '">';
                    echo wp_get_attachment_image($image_id, 'thumbnail');
                    echo '<button type="button" class="remove-image">×</button>';
                    echo '</div>';
                }
            }
            ?>
        </div>
        <input type="hidden"
               id="gallery_event_images"
               name="gallery_event_images"
               value="<?php echo esc_attr(implode(',', $image_ids)); ?>">
        <button type="button" class="button-primary select-images">
            Sélectionner des images
        </button>
        <button type="button"
                class="button remove-all-images"
                style="color: #a00;">
            Supprimer toutes les images
        </button>
    </div>

    <style>
        .gallery-image-item {
            display: inline-block;
            margin: 5px;
            position: relative;
        }

        .gallery-image-item img {
            max-width: 150px;
            height: auto;
        }

        .remove-image {
            position: absolute;
            top: 5px;
            right: 5px;
            background: rgba(255, 0, 0, 0.7);
            color: white;
            border: none;
            border-radius: 50%;
            padding: 2px 6px;
            cursor: pointer;
        }
    </style>

    <script>
        jQuery(document).ready(function ($) {
            // Initialize media uploader
            let mediaUploader = wp.media({
                title: 'Sélectionner des images pour l\'événement',
                button: {
                    text: 'Utiliser ces images'
                },
                multiple: true
            });

            const inputImg = $('input#gallery_event_images');
            const preview = $('div#gallery-images-preview');

            $('.select-images').on('click', function (e) {
                e.preventDefault();

                mediaUploader.open();
                mediaUploader.on('select', function () {
                    const selection = mediaUploader.state().get('selection');
                    let imageIds = inputImg.val() ?
                        inputImg.val().split(',') :
                        [];

                    selection.each(function (attachment) {
                        const id = attachment.id;

                        if (!imageIds.includes(id.toString())) {
                            imageIds.push(id);

                            const img = attachment.attributes.sizes.thumbnail ||
                                attachment.attributes.sizes.full;

                            preview.append(`
                                <div class="gallery-image-item" data-id="${id}">
                                    <img src="${img.url}"  alt="Image de l'évènement"/>
                                    <button type="button" class="remove-image">×</button>
                                </div>
                            `);
                        }
                    });

                    inputImg.val(imageIds.join(','));
                });
            });

            // Remove single image
            preview.on('click', '.remove-image', function () {
                const item = $(this).parent();
                const id = item.data('id').toString();
                let imageIds = inputImg.val().split(',');

                imageIds = imageIds.filter(imageId => imageId !== id);
                inputImg.val(imageIds.join(','));
                item.remove();
            });

            // Remove all images
            $('.remove-all-images').on('click', function () {
                if (confirm('Êtes-vous sûr de vouloir supprimer toutes les images ?')) {
                    preview.empty();
                    inputImg.val('');
                }
            });
        });
    </script>
    <?php
}

// Save Images Meta Data
function save_gallery_event_meta($post_id): void
{
    if (!isset($_POST['gallery_event_images_nonce']) ||
        !wp_verify_nonce($_POST['gallery_event_images_nonce'], 'gallery_event_images_nonce')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    if (isset($_POST['gallery_event_images'])) {
        $image_ids = sanitize_text_field($_POST['gallery_event_images']);
        update_post_meta($post_id, '_gallery_event_images', $image_ids);
        error_log('Saved image IDs: ' . $image_ids); // Debugging statement
    }
}

add_action('save_post_gallery_event', 'save_gallery_event_meta');

// Enqueue Scripts
function gallery_events_admin_scripts($hook): void
{
    global $post_type;

    if ($post_type !== 'gallery_event') {
        return;
    }

    wp_enqueue_media();
}

add_action('admin_enqueue_scripts', 'gallery_events_admin_scripts');

// Add Custom Columns
function gallery_events_custom_columns($columns): array
{
    return [
        'cb' => $columns['cb'],
        'title' => 'Nom de l\'évènement',
        'images' => 'Les images',
        'taxonomy-event_season' => 'Les saisons',
        'date' => 'Date'
    ];
}

add_filter('manage_gallery_event_posts_columns', 'gallery_events_custom_columns');

// Populate Custom Columns
function gallery_events_custom_column_content($column, $post_id): void
{
    if ($column === 'images') {
        $image_ids = get_post_meta($post_id, '_gallery_event_images', true);
        if ($image_ids) {
            $image_ids = explode(',', $image_ids);
            $first_images = array_slice($image_ids, 0, 3);
            foreach ($first_images as $image_id) {
                echo wp_get_attachment_image($image_id, [80, 80]);
            }
            $count = count($image_ids);
            if ($count > 3) {
                echo " <span class='image-count'>+" . ($count - 3) . " more</span>";
            }
        } else {
            echo 'Aucune images renseignées';
        }
    }
}

add_action('manage_gallery_event_posts_custom_column', 'gallery_events_custom_column_content', 10, 2);

// Helper function to get events by season
function get_gallery_events(): array
{
    $args = [
        'post_type' => 'gallery_event',
        'posts_per_page' => -1,
        'orderby' => 'date',
        'order' => 'ASC'
    ];

    $events = [];
    $query = new WP_Query($args);

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $image_ids = get_post_meta(get_the_ID(), '_gallery_event_images', true);
            $image_ids = $image_ids ? explode(',', $image_ids) : [];

            $season_terms = wp_get_post_terms(get_the_ID(), 'event_season');
            $season_slug = !empty($season_terms) ? $season_terms[0]->slug : '';

            $events[$season_slug][] = [
                'id' => get_the_ID(),
                'title' => get_the_title(),
                'image_ids' => $image_ids,
            ];
        }
    }
    wp_reset_postdata();

    return $events;
}