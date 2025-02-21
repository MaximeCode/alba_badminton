<?php
/*
Plugin Name: Entertainment et free play schedules
Description: Manage entertainment schedules with time slots and difficulty levels
Version: 1.0
*/

if (!defined('ABSPATH')) {
    exit;
}

// Register Custom Post Type for Schedules
function schedules_post_type(): void
{
    register_post_type('schedule', [
        'labels' => [
            'name' => 'Horaires',
            'singular_name' => 'Horaire',
            'add_new' => 'Ajouter un horaire',
            'add_new_item' => 'Ajouter un nouveau créneau horaire',
            'edit_item' => 'Modifier l\'horaire',
        ],
        'public' => true,
        'menu_icon' => 'dashicons-calendar-alt',
        'supports' => array('title', 'editor', 'thumbnail'), // editor is for description, thumbnail for image
        'has_archive' => true
    ]);
}

add_action('init', 'schedules_post_type');

// Add Meta Boxes for additional fields
function add_schedule_meta_boxes(): void
{
    add_meta_box(
        'schedule_details',
        'Détails de l\'horaire',
        'render_schedule_meta_box',
        'schedule',
        'normal',
        'high'
    );
}

add_action('add_meta_boxes', 'add_schedule_meta_boxes');

// Render Meta Box
function render_schedule_meta_box($post): void
{
    wp_nonce_field('schedule_details_nonce', 'schedule_details_nonce');

    $difficulty = get_post_meta($post->ID, '_schedule_difficulty', true);
    $day = get_post_meta($post->ID, '_schedule_day', true);
    $time_start = get_post_meta($post->ID, '_schedule_time_start', true);
    $time_end = get_post_meta($post->ID, '_schedule_time_end', true);

    $day2 = get_post_meta($post->ID, '_schedule_day2', true);

    $image_id = get_post_meta($post->ID, '_schedule_image_id', true);
    $image_url = wp_get_attachment_image_url($image_id, 'thumbnail'); // Changed to thumbnail

    $days = [
        'monday' => 'Lundi',
        'tuesday' => 'Mardi',
        'wednesday' => 'Mercredi',
        'thursday' => 'Jeudi',
        'friday' => 'Vendredi',
        'saturday' => 'Samedi',
        'sunday' => 'Dimanche'
    ];

    $image_id = get_post_meta($post->ID, '_schedule_image_id', true);
    $image_url = wp_get_attachment_image_url($image_id, 'thumbnail'); // Changed to thumbnail
    ?>

    <div class="schedule-meta-box">
        <div class="schedule-meta-row">
            <!-- Difficulty -->
            <div class="schedule-meta-field">
                <label for="schedule_difficulty">Niveau de difficulté de l'entrainement (1-5) : (0 = jeu libre)</label>
                <input type="number" id="schedule_difficulty" name="schedule_difficulty"
                       value="<?php echo esc_attr($difficulty); ?>"
                       min="0" max="5">
            </div>

            <!-- Day -->
            <div class="schedule-meta-field">
                <label for="schedule_day">Jour:</label>
                <select name="schedule_day" id="schedule_day" required>
                    <option value="">Sélectionner un jour</option>
                    <?php foreach ($days as $key => $label): ?>
                        <option value="<?php echo $key; ?>" <?php selected($day, $key); ?>>
                            <?php echo $label; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <!-- Time Schedule -->
        <div class="schedule-meta-row">
            <div class="schedule-meta-field">
                <label for="schedule_time_start">Heure de début:</label>
                <input type="time" id="schedule_time_start" name="schedule_time_start"
                       value="<?php echo esc_attr($time_start); ?>" required>
            </div>
            <div class="schedule-meta-field">
                <label for="schedule_time_end">Heure de fin:</label>
                <input type="time" id="schedule_time_end" name="schedule_time_end"
                       value="<?php echo esc_attr($time_end); ?>" required>
            </div>
        </div>

        <!-- Image Section -->
        <div class="schedule-image-container">
            <div class="schedule-meta-field">
                <label for="schedule_image_id">Image des participants à la séance (en mode paysage)</label>
                <div id="schedule-image-preview">
                    <?php if ($image_url): ?>
                        <img src="<?php echo esc_url($image_url); ?>"
                             style="max-width: 150px; max-height: 150px; object-fit: cover;" alt="Schedule Image">
                    <?php endif; ?>
                </div>
                <div class="schedule-image-actions">
                    <input type="hidden" name="schedule_image_id" id="schedule_image_id"
                           value="<?php echo esc_attr($image_id); ?>">
                    <button type="button" class="button" id="upload_schedule_image_button">
                        <?php echo $image_id ? 'Modifier l\'image' : 'Ajouter une image'; ?>
                    </button>
                    <?php if ($image_id): ?>
                        <button type="button" class="button" id="remove_schedule_image_button">Supprimer</button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <style>
        .schedule-meta-box {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .schedule-meta-row {
            display: flex;
            gap: 20px;
        }

        .schedule-meta-field {
            display: flex;
            flex-direction: column;
            flex: 1;
        }

        .schedule-meta-field label {
            margin-bottom: 5px;
            font-weight: bold;
        }

        .schedule-meta-field input,
        .schedule-meta-field select {
            width: 100%;
        }

        .schedule-image-container {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .schedule-image-actions {
            display: flex;
            gap: 10px;
        }

        #schedule-image-preview img {
            border: 1px solid #ddd;
            border-radius: 4px;
        }
    </style>

    <script>
        jQuery(document).ready(function ($) {
            let frame;

            $('#upload_schedule_image_button').click(function (e) {
                e.preventDefault();

                if (frame) {
                    frame.open();
                    return;
                }

                frame = wp.media({
                    title: 'Sélectionner une image',
                    button: {
                        text: 'Utiliser cette image'
                    },
                    multiple: false
                });

                frame.on('select', function () {
                    let attachment = frame.state().get('selection').first().toJSON();
                    $('#schedule_image_id').val(attachment.id);
                    $('#schedule-image-preview').html(
                        '<img src="' + attachment.url + '" style="max-width: 150px; max-height: 150px; object-fit: cover;" alt="Image de la séance">'
                    );
                    $('#upload_schedule_image_button').text('Modifier l\'image');
                    if (!$('#remove_schedule_image_button').length) {
                        $('.schedule-image-actions').append(
                            '<button type="button" class="button" id="remove_schedule_image_button">Supprimer</button>'
                        );
                    }
                });

                frame.open();
            });

            $(document).on('click', '#remove_schedule_image_button', function (e) {
                e.preventDefault();
                $('#schedule_image_id').val('');
                $('#schedule-image-preview').empty();
                $('#upload_schedule_image_button').text('Ajouter une image');
                $(this).remove();
            });
        });
    </script>
    <?php
}

// Save Meta Data
function save_schedule_meta($post_id): void
{
    if (!isset($_POST['schedule_details_nonce']) ||
        !wp_verify_nonce($_POST['schedule_details_nonce'], 'schedule_details_nonce')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    $fields = [
        'schedule_difficulty',
        'schedule_day',
        'schedule_time_start',
        'schedule_time_end',
        'schedule_image_id'
    ];

    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            update_post_meta(
                $post_id,
                '_' . $field,
                sanitize_text_field($_POST[$field])
            );
        }
    }
}

add_action('save_post_schedule', 'save_schedule_meta');

// Enqueue necessary scripts
function schedule_admin_scripts($hook): void
{
    global $post_type;

    if ($post_type !== 'schedule') {
        return;
    }

    wp_enqueue_media();
}

add_action('admin_enqueue_scripts', 'schedule_admin_scripts');

// Custom Columns for Schedule List
function schedule_custom_columns($columns): array
{
    return array(
        'cb' => $columns['cb'],
        'title' => 'Horaires',
        'image' => 'Image',
        'description' => 'Description',
        'difficulty' => 'Difficulté',
        'day' => 'Jour',
        'time' => 'Horaires',
        'date' => 'Date'
    );
}

add_filter('manage_schedule_posts_columns', 'schedule_custom_columns');

// Populate Custom Columns
function schedule_custom_column_content($column, $post_id): void
{
    switch ($column) {
        case 'image':
            $image_id = get_post_meta($post_id, '_schedule_image_id', true);
            if ($image_id) {
                echo wp_get_attachment_image($image_id, 'thumbnail');
            } else {
                echo '—';
            }
            break;
        case 'description':
            echo wp_trim_words(get_the_excerpt($post_id), 20);
            break;
        case 'difficulty':
            $difficulty = get_post_meta($post_id, '_schedule_difficulty', true);
            echo esc_html($difficulty ? $difficulty . '/5' : 'Non défini');
            break;
        case 'day':
            $day = get_post_meta($post_id, '_schedule_day', true);
            echo esc_html(get_french_day_name($day) ?: 'Non défini');
            break;
        case 'time':
            $start = get_post_meta($post_id, '_schedule_time_start', true);
            $end = get_post_meta($post_id, '_schedule_time_end', true);
            if ($start && $end) {
                echo esc_html(date('H:i', strtotime($start)) . ' - ' . date('H:i', strtotime($end)));
            } else {
                echo 'Non défini';
            }
            break;
    }
}

add_action('manage_schedule_posts_custom_column', 'schedule_custom_column_content', 10, 2);

// Function to get schedules by day
function get_schedules(): array
{
    // args order by difficulty
    $args = [
        'post_type' => 'schedule',
        'posts_per_page' => -1,
        'orderby' => 'meta_value_num',
        'meta_key' => '_schedule_difficulty',
        'order' => 'ASC'
    ];

    $schedules = [];
    $query = new WP_Query($args);

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();

            $schedule = [
                'id' => get_the_ID(),
                'title' => get_the_title(),
                'desc' => get_the_content(),
                'difficulty' => get_post_meta(get_the_ID(), '_schedule_difficulty', true),
                'day' => get_post_meta(get_the_ID(), '_schedule_day', true),
                'day2' => get_post_meta(get_the_ID(), '_schedule_day2', true),
                'time_start' => get_post_meta(get_the_ID(), '_schedule_time_start', true),
                'time_end' => get_post_meta(get_the_ID(), '_schedule_time_end', true),
                'image_id' => get_post_meta(get_the_ID(), '_schedule_image_id', true)
            ];

            $schedules[] = $schedule;
        }
    }
    wp_reset_postdata();

    return $schedules;
}

function get_french_day_name($day_key): string
{
    $french_days = [
        'monday' => 'Lundi',
        'tuesday' => 'Mardi',
        'wednesday' => 'Mercredi',
        'thursday' => 'Jeudi',
        'friday' => 'Vendredi',
        'saturday' => 'Samedi',
        'sunday' => 'Dimanche'
    ];

    return $french_days[$day_key] ?? $day_key;
}