<?php

// Button used a lot of times
function primaryButton(int $idPage, string $text, ?string $paramName = null, ?string $paramValue = null, ?string $classSup = null): string
{
    global $alba_theme_variables;
    $class = $alba_theme_variables['classBtn'] . ($classSup ? " $classSup" : '');
    $url = get_permalink($idPage) . ($paramName && $paramValue ? "?$paramName=$paramValue" : '');
    return "<a class=\"$class\" href=\"$url\">$text</a>";
}

// Toutes les variables globales de mon thème sont déclarées ici
function alba_theme_variables(): array
{
    return [
        'h3' => "mb-4 text-3xl underline decoration-primary-blue",
        'animCardNews' => "transform transition duration-200 ease-in-out hover:bg-primary-blue hover:bg-opacity-10 hover:scale-105",
        'animRotateArrow' => "transform transition-transform duration-500 group-hover:rotate-180",
        'animBase' => "transform transition duration-200 ease-in-out",
        'classLi' => "block py-2 px-3 rounded transform transition duration-200 ease-in-out hover:bg-white hover:text-primary-blue md:py-3",
        'classDivDropdown' => "z-10 hidden font-normal bg-primary-blue rounded-lg shadow-box-dropdown w-44 border-white border-6",
        'classLiDropdown' => "flex items-center justify-between w-full py-2 px-3 rounded transform transition duration-200 ease-in-out group-hover:bg-white group-hover:text-primary-blue lg:w-auto lg:py-3 uppercase",
        'classLiSubDropdown' => "flex items-center justify-between w-full px-4 py-2 leading-7 hover:bg-white hover:text-primary-blue",
        'classBtn' => "text-lg md:text-xl text-white bg-secondary-blue hover:bg-secondary-blue/75 rounded-lg px-5 py-3 transform transition duration-100 ease-in-out",
        'seeAllThings' => "block text-center py-1 border-2 leading-7 hover:text-white hover:bg-primary-blue border-primary-blue text-primary-blue rounded-full text-base",
        'subLi' => "block px-4 py-2 leading-7 hover:bg-white hover:text-primary-blue",
    ];
}

add_action('wp_head', function () {
    global $alba_theme_variables;
    $alba_theme_variables = alba_theme_variables();
});


// Désactiver la barre d'administration pour tous les utilisateurs
add_filter('show_admin_bar', '__return_false');

function alba_theme_enqueue_styles(): void
{
    // Enregistrer le fichier CSS personnalisé de votre thème
    wp_enqueue_style('alba-style', get_stylesheet_directory_uri() . './style.css');
}

add_action('wp_enqueue_scripts', 'alba_theme_enqueue_styles');

// Support des balises <title>
add_theme_support('title-tag');


function alba_theme_enqueue_scripts(): void
{
    // Enqueue Flowbite JS
    wp_enqueue_script('flowbite', get_template_directory_uri() . '/assets/js/flowbite.min.js', array(), null, true);

    // Enqueue Slick Slider CSS et JS
    wp_enqueue_style('slick-css', 'https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css', array(), '1.8.1');
    wp_enqueue_style('slick-theme-css', 'https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css', array(), '1.8.1');
    wp_enqueue_script('slick-js', 'https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js', array('jquery'), '1.8.1', true);

    // Enqueue votre script personnalisé pour initialiser Slick Slider
    wp_enqueue_script('custom-slick-init', get_template_directory_uri() . '/assets/js/slick-init.js', array(
        'jquery',
        'slick-js'
    ), null, true);

    // Equeue Lightbox JS (agrandissement des images au clic)
    wp_enqueue_script('custom-lightbox-js', get_template_directory_uri() . '/assets/js/lightBox.js', array(), null, true);

    // Enqueue JQuery (si nécessaire)
    wp_enqueue_script('jquery');
}

add_action('wp_enqueue_scripts', 'alba_theme_enqueue_scripts');

// Allow SVG
add_filter('wp_check_filetype_and_ext', function ($data, $file, $filename, $mimes) {

    global $wp_version;
    if ($wp_version !== '4.7.1') {
        return $data;
    }

    $filetype = wp_check_filetype($filename, $mimes);

    return [
        'ext' => $filetype['ext'],
        'type' => $filetype['type'],
        'proper_filename' => $data['proper_filename']
    ];

}, 10, 4);

function wppln_mime_types($mimes)
{
    $mimes['svg'] = 'image/svg+xml';

    return $mimes;
}

add_filter('upload_mimes', 'wppln_mime_types');

function wppln_fix_svg(): void
{
    echo '<style>
        .attachment-266x266, .thumbnail img {
             width: 100% !important;
             height: auto !important;
        }
        </style>';
}

add_action('admin_head', 'wppln_fix_svg');

function my_custom_comment_form($args)
{
    $args['class_submit'] = 'bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600'; // Bouton personnalisé
    $args['comment_field'] = '<p class="comment-form-comment">
        <textarea id="comment" name="comment" class="w-full border border-gray-300 rounded p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" rows="5" required></textarea>
    </p>';
    return $args;
}

add_filter('comment_form_defaults', 'my_custom_comment_form');

// fonction d'affichage de la grille des membres du bureau ou de la ligue
function showGridBureau(array $members): void
{
    echo '<div class="bg-white rounded-2xl p-5 grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 divide-y divide-primary-blue sm:divide-none">';
    foreach ($members as $bureau) {
        echo sprintf(
            '<div class="grid grid-rows-[auto_2fr_auto] gap-4 justify-center items-center text-center text-lg p-3">
                            <p class="underline font-bold text-xl">%s</p>
                            <div class="row-span-1">%s</div>
                            <p class="row-span-1 italic text-xl">%s</p>
                        </div>',
            ucwords($bureau['position']),
            wp_get_attachment_image(151, '', false, array(
                'loading' => 'lazy',
                'class' => "w-1/2 max-w-56 m-auto rounded-2xl transform transition duration-300 ease-in-out hover:scale-105 row-span-2",
            )),
            ucwords($bureau['name'])
        );
    }
    echo '</div>';
}

// fonction de génération du breadcrumb sur chaque page (ajouté ds le header.php)
function generate_breadcrumbs(): void
{
    if (!is_front_page() && !is_404() && !is_search()) {
        $mb = !is_404() ? 'mb-8' : '';
        $breadcrumb = '<nav class="' . $mb . ' max-w-max text-md flex justify-center items-center px-5 py-3 text-primary-blue border border-primary-blue/50 rounded-xl bg-gray-50 dark:bg-gray-800 dark:border-gray-700" aria-label="Breadcrumb">';
        // Lien vers la page d'accueil >> svg = Home
        $breadcrumb .= '<li class="inline-flex items-center">
            <a href="/" title="Accueil" class="inline-flex items-center font-medium hover:text-secondary-blue dark:text-gray-400 dark:hover:text-white">
                <svg class="w-5 h-5 md:w-6 md:h-6 me-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 1 1 1-1h2a1 1 0 1 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"/>
                        </svg>
            </a>
        </li>';
        $breadcrumb .= '<ol class="inline-flex flex-wrap flex-1 items-center gap-1 md:gap-2 rtl:space-x-reverse">';

        // Ajout des pages parentes
        if (is_page()) {
            global $post;
            $ancestors = get_post_ancestors($post);
            $ancestors = array_reverse($ancestors);
            foreach ($ancestors as $ancestor) {
                $breadcrumb .= '<li>
                    <div class="flex items-center">
                        <svg class="rtl:rotate-180 block w-4 h-4 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                            <path fill-rule="evenodd" d="M3 4a1 1 0 0 0-.822 1.57L6.632 12l-4.454 6.43A1 1 0 0 0 3 20h13.153a1 1 0 0 0 .822-.43l4.847-7a1 1 0 0 0 0-1.14l-4.847-7a1 1 0 0 0-.822-.43H3Z" clip-rule="evenodd"/>
                        </svg>

                        <a href="' . get_permalink($ancestor) . '" class="flex-1 whitespace-normal break-words ms-1 font-medium hover:text-secondary-blue md:ms-2 dark:text-gray-400 dark:hover:text-white">'
                    . get_the_title($ancestor) . '
                        </a>
                    </div>
                </li>';
            }
        }

        // Si c'est un article, ajouter "Actualit&eacute;s" comme parent dans le breadcrumb
        if (is_single()) {
            $breadcrumb .= '<li>
        <div class="flex items-center">
            <svg class="rtl:rotate-180 block w-4 h-4 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                <path fill-rule="evenodd" d="M3 4a1 1 0 0 0-.822 1.57L6.632 12l-4.454 6.43A1 1 0 0 0 3 20h13.153a1 1 0 0 0 .822-.43l4.847-7a1 1 0 0 0 0-1.14l-4.847-7a1 1 0 0 0-.822-.43H3Z" clip-rule="evenodd"/>
            </svg>
            <a href="' . get_permalink(26) . '" class="flex-1 whitespace-normal break-words ms-1 font-medium hover:text-secondary-blue md:ms-2 dark:text-gray-400 dark:hover:text-white">Les actualit&eacute;s du club</a>
        </div>
    </li>';
        }

        // Ajout de la page enfant
        $breadcrumb .= '<li aria-current="page">
            <div class="flex items-center">
                <svg class="rtl:rotate-180 block w-4 h-4 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                    <path fill-rule="evenodd" d="M3 4a1 1 0 0 0-.822 1.57L6.632 12l-4.454 6.43A1 1 0 0 0 3 20h13.153a1 1 0 0 0 .822-.43l4.847-7a1 1 0 0 0 0-1.14l-4.847-7a1 1 0 0 0-.822-.43H3Z" clip-rule="evenodd"/>
                </svg>
                <span class="flex-1 whitespace-normal break-words ms-1 font-medium text-gray-500 md:ms-2 dark:text-gray-400">'
            . get_the_title() . '
                </span>
            </div>
        </li>';

        $breadcrumb .= '</ol>';
        $breadcrumb .= '</nav>';

        echo $breadcrumb;
    }
}

// fonction qui retourne si la clé entrée en paramètre est la première clé du tableau
function isFirstKey(string $key, array $array): bool
{
    return array_key_first($array) == $key;
}

// fonction qui retourne si la clé entrée en paramètre est la dernière clé du tableau
function isLastKey(string $key, array $array): bool
{
    return array_key_last($array) == $key;
}

////// Custom Meta Box //////

function custom_meta_box(): void
{
//    Ajout de la méta box pour les événements
    if (get_the_ID() == 166) {
        add_meta_box(
            'custom_events_meta_box', // ID de la meta box
            'Événements',            // Titre
            'events_meta_box_callback', // Fonction de rappel
            'page',                  // Type de contenu (ici : page)
            'normal',                // Position
        );
    }
}

add_action('add_meta_boxes', 'custom_meta_box');

function events_meta_box_callback($post): void
{
    wp_nonce_field('save_custom_meta_box', 'custom_meta_box_nonce'); // Sécurité

    // Récupérer les anciennes valeurs
    $seasons = get_post_meta($post->ID, 'custom_seasons', true);
    ?>

    <div id="custom-seasons-container">
        <?php if (!empty($seasons) && is_array($seasons)): ?>
            <?php foreach ($seasons as $season_index => $season): ?>
                <div class="season-row" style="margin-bottom: 20px; border: 1px solid #555; padding: 10px;">
                    <label for="custom_seasons[<?= $season_index; ?>][title]">Saison :</label>
                    <input type="text" name="custom_seasons[<?= $season_index; ?>][title]"
                           id="custom_seasons[<?= $season_index; ?>][title]"
                           value="<?= esc_attr($season['title']); ?>" style="width: 100%; margin-bottom: 10px;"/>

                    <div class="events-container">
                        <?php if (!empty($season['events']) && is_array($season['events'])): ?>
                            <?php foreach ($season['events'] as $event_index => $event): ?>
                                <div class="event-row"
                                     style="margin-bottom: 10px; border: 1px solid #ddd; padding: 10px;">
                                    <label for="custom_seasons[<?= $season_index; ?>][events][<?= $event_index; ?>][title]">
                                        Événement :</label>
                                    <input type="text"
                                           name="custom_seasons[<?= $season_index; ?>][events][<?= $event_index; ?>][title]"
                                           id="custom_seasons[<?= $season_index; ?>][events][<?= $event_index; ?>][title]"
                                           value="<?= esc_attr($event['title']); ?>"
                                           style="width: 100%; margin-bottom: 10px;"/>

                                    <label>Images :</label>
                                    <div class="image-wrapper">
                                        <input type="hidden" class="image-field"
                                               name="custom_seasons[<?= $season_index; ?>][events][<?= $event_index; ?>][images]"
                                               value="<?= esc_attr(implode(',', $event['images'] ?? [])); ?>"/>
                                        <button class="button select-images">Choisir des images</button>
                                        <button class="button remove-images"
                                                style="display: <?= !empty($event['images']) ? 'inline-block' : 'none'; ?>; background-color: #f6f7f7; margin-left: 10px; color: red; border: 1px solid red;">
                                            Supprimer les images
                                        </button>
                                        <div class="image-preview" style="margin-top: 10px;">
                                            <?php if (!empty($event['images'])): ?>
                                                <?php foreach ($event['images'] as $image_id): ?>
                                                    <img src="<?= wp_get_attachment_image_url($image_id, 'thumbnail'); ?>"
                                                         alt="Image"
                                                         style="max-width: 100px; height: auto; margin-right: 5px;"/>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <button class="button remove-event"
                                            style="background-color: #ff4d4d; color: white; margin-top: 10px; border: none;">
                                        Supprimer cet événement
                                    </button>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <button class="button add-event" style="margin-top: 10px;">Ajouter un événement</button>
                    <button class="button remove-season"
                            style="background-color: #ff4d4d; color: white; margin-top: 10px; border: none;">
                        Supprimer cette saison
                    </button>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <button id="add-season" class="button">Ajouter une saison</button>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const container = document.getElementById('custom-seasons-container');
            const addSeasonButton = document.getElementById('add-season');

            // Ajouter une nouvelle saison
            addSeasonButton.addEventListener('click', function (e) {
                e.preventDefault();
                const seasonIndex = container.children.length;

                const seasonRow = document.createElement('div');
                seasonRow.classList.add('season-row');
                seasonRow.style.marginBottom = '20px';
                seasonRow.style.border = '1px solid #555';
                seasonRow.style.padding = '10px';
                seasonRow.innerHTML = `
                    <label for="custom_seasons[${seasonIndex}][title]">Saison : (saisir juste l&#39;ann&eacute;e au format 20xx - 20xx)</label>
                    <input type="text" name="custom_seasons[${seasonIndex}][title]" style="width: 100%; margin-bottom: 10px;" />

                    <div class="events-container"></div>
                    <button class="button add-event" style="margin-top: 10px;">Ajouter un événement</button>
                    <button class="button remove-season" style="background-color: #ff4d4d; color: white; margin-top: 10px;">
                        Supprimer cette saison
                    </button>
                `;
                container.appendChild(seasonRow);

                initializeSeasonRow(seasonRow);
            });

            // Initialiser une saison
            function initializeSeasonRow(seasonRow) {
                const addEventButton = seasonRow.querySelector('.add-event');
                const removeSeasonButton = seasonRow.querySelector('.remove-season');
                const eventsContainer = seasonRow.querySelector('.events-container');

                // Ajouter un événement à une saison
                addEventButton.addEventListener('click', function (e) {
                    e.preventDefault();
                    const seasonIndex = [...container.children].indexOf(seasonRow);
                    const eventIndex = eventsContainer.children.length;

                    const eventRow = document.createElement('div');
                    eventRow.classList.add('event-row');
                    eventRow.style.marginBottom = '10px';
                    eventRow.style.border = '1px solid #ddd';
                    eventRow.style.padding = '10px';
                    eventRow.innerHTML = `
                        <label for="custom_seasons[${seasonIndex}][events][${eventIndex}][title]">Événement :</label>
                        <input type="text" name="custom_seasons[${seasonIndex}][events][${eventIndex}][title]" style="width: 100%; margin-bottom: 10px;" />
                        <label>Images :</label>
                        <div class="image-wrapper">
                            <input type="hidden" class="image-field" name="custom_seasons[${seasonIndex}][events][${eventIndex}][images]" />
                            <button class="button select-images">Choisir des images</button>
                            <button class="button remove-images" style="display: none; margin-left: 10px;">Supprimer les images</button>
                            <div class="image-preview" style="margin-top: 10px;"></div>
                        </div>
                        <button class="button remove-event" style="background-color: #ff4d4d; color: white; margin-top: 10px;">
                            Supprimer cet événement
                        </button>
                    `;
                    eventsContainer.appendChild(eventRow);

                    initializeEventRow(eventRow);
                });

                // Supprimer une saison
                removeSeasonButton.addEventListener('click', function (e) {
                    e.preventDefault();
                    if (confirm('Êtes-vous sûr de vouloir supprimer cette saison ?')) {
                        seasonRow.remove();
                    }
                });
            }

            // Fonction pour initialiser une ligne d'événement
            function initializeEventRow(row) {
                const selectImagesButton = row.querySelector('.select-images');
                const removeImagesButton = row.querySelector('.remove-images');
                const imageField = row.querySelector('.image-field');
                const imagePreview = row.querySelector('.image-preview');
                const removeEventButton = row.querySelector('.remove-event');

                let mediaUploader;

                // Ouvrir la Media Library
                selectImagesButton.addEventListener('click', function (e) {
                    e.preventDefault();

                    if (mediaUploader) {
                        mediaUploader.open();
                        return;
                    }

                    mediaUploader = wp.media({
                        title: 'Choisir des images',
                        button: {text: 'Ajouter des images'},
                        multiple: true,
                    });

                    mediaUploader.on('select', function () {
                        const selection = mediaUploader.state().get('selection').toJSON();
                        const imageIDs = selection.map(image => image.id);

                        imageField.value = imageIDs.join(',');
                        imagePreview.innerHTML = selection.map(image =>
                            `<img src="${image.url}" style="max-width: 100px; height: auto; margin-right: 5px;" alt="an img" />`
                        ).join('');
                        removeImagesButton.style.display = 'inline-block';
                    });

                    mediaUploader.open();
                });

                // Supprimer les images
                removeImagesButton.addEventListener('click', function (e) {
                    e.preventDefault();
                    imageField.value = '';
                    imagePreview.innerHTML = '';
                    removeImagesButton.style.display = 'none';
                });

                // Supprimer l'événement
                removeEventButton.addEventListener('click', function (e) {
                    e.preventDefault();
                    row.remove();
                });
            }

            // Initialiser les saisons existantes
            container.querySelectorAll('.season-row').forEach(initializeSeasonRow);
        });
    </script>
    <?php
}

function save_custom_meta_box($post_id): void
{
    // Vérification du nonce pour la sécurité
    if (!isset($_POST['custom_meta_box_nonce']) || !wp_verify_nonce($_POST['custom_meta_box_nonce'], 'save_custom_meta_box')) {
        return;
    }

    // Éviter les sauvegardes automatiques
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Vérification des permissions de l'utilisateur
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // Validation et nettoyage des données
    if (isset($_POST['custom_seasons']) && is_array($_POST['custom_seasons'])) {
        $cleaned_data = array_map(function ($season) {
            // Nettoyage des données de la saison
            $cleaned_season = [
                'title' => isset($season['title']) ? sanitize_text_field($season['title']) : '',
                'events' => [],
            ];

            // Si des événements sont présents, les nettoyer également
            if (isset($season['events']) && is_array($season['events'])) {
                $cleaned_season['events'] = array_map(function ($event) {
                    return [
                        'title' => isset($event['title']) ? ucwords(sanitize_text_field($event['title'])) : '',
                        'images' => isset($event['images']) ? array_filter(array_map('absint', explode(',', $event['images']))) : [], // Convertir les IDs d'images en tableau
                    ];
                }, $season['events']);
            }

            return $cleaned_season;
        }, $_POST['custom_seasons']);

        // Sauvegarder les données nettoyées dans les meta données
        update_post_meta($post_id, 'custom_seasons', $cleaned_data);
    } else {
        // Si aucune donnée n'est fournie, supprimer la meta donnée
        delete_post_meta($post_id, 'custom_seasons');
    }
}

add_action('save_post', 'save_custom_meta_box');

// Main function to register all meta boxes
add_filter('rwmb_meta_boxes', 'alba_register_all_meta_boxes');

function alba_register_all_meta_boxes($meta_boxes): array
{
    if (is_admin() && isset($_GET['post'])) {
        $post_id = (int)$_GET['post'];
        // post id of contact page : 134
        if ($post_id === 134) {
            // Meta Box for contact page
            $prefix_contact = 'infos_';
            $meta_boxes[] = [
                'title' => esc_html__('Informations de la page contact', 'alba_theme'),
                'id' => $prefix_contact . 'contact',
                'post_types' => ['page'],
                'show' => [
                    'template' => ['contact.php'],
                ],
                'context' => 'normal',
                'priority' => 'high',
                'fields' => [
                    [
                        'type' => 'email',
                        'name' => esc_html__('Email du bureau', 'alba_theme'),
                        'id' => $prefix_contact . 'email_bureau',
                        'desc' => esc_html__('Email où recevoir les demandes / questions des visiteurs', 'alba_theme'),
                        'size' => 60,
                    ],
                    [
                        'type' => 'text',
                        'name' => esc_html__('Numéro de téléphone', 'alba_theme'),
                        'id' => $prefix_contact . 'num_tel',
                        'std' => '+33',
                        'size' => 60,
                        'pattern' => '\+[0-9]{2}[0-9\s]*',
                    ],
                    [
                        'type' => 'text',
                        'name' => esc_html__('Adresse postale', 'alba_theme'),
                        'id' => $prefix_contact . 'adresse_postale',
                        'placeholder' => esc_html__('Adresse du gymnase', 'alba_theme'),
                        'size' => 60,
                    ],
                ],
            ];
        }

        // post id of judges : 341
        if ($post_id === 341) {
            // Meta Box for judges
            $prefix_judge = 'judge_';

            // Retrieve judge types dynamically
            $types_juges = get_post_meta(341, 'judge_type_field', true);

            // Check if there are any judge types saved
            $options = [];
            if (!empty($types_juges)) {
                foreach ($types_juges as $type) {
                    $options[$type] = esc_html__($type, 'alba_theme');
                }
            } else {
                $options['non'] = esc_html__('Ca marche pas...', 'alba_theme');
            }

            // Single Meta Box with two columns
            $meta_boxes[] = [
                'title' => esc_html__('Ajout des juges officiels du club', 'alba_theme'),
                'id' => $prefix_judge . 'info',
                'post_types' => ['page'],
                'show' => [
                    'template' => ['judges.php'],
                ],
                'context' => 'normal',
                'priority' => 'high',
                'fields' => [
                    [
                        'type' => 'text',
                        'name' => esc_html__('Nom du Juge', 'alba_theme'),
                        'id' => 'lePtnDeNom',
                        'placeholder' => esc_html__('NOM Prénom', 'alba_theme'),
                        'size' => 40,
                        'clone' => true,
                    ],
                    [
                        'type' => 'select_advanced',
                        'name' => esc_html__('Type de juge', 'alba_theme'),
                        'id' => 'lePtnDeType',
                        'options' => $options,
                        'multiple' => true,
                        'clone' => true,
                    ],
                ],
            ];

            // Add the meta box for types of judges
            $meta_boxes[] = [
                'title' => esc_html__('Ajout des types de juges', 'alba_theme'),
                'id' => $prefix_judge . 'type_meta_box',
                'post_types' => ['page'],
                'show' => [
                    'template' => ['judges.php'],
                ],
                'context' => 'normal',
                'priority' => 'high',
                'fields' => [
                    [
                        'type' => 'text',
                        'name' => esc_html__('Type de Juge', 'alba_theme'),
                        'id' => 'judge_type_field',
                        'placeholder' => esc_html__('Juge Arbitre', 'alba_theme'),
                        'size' => 50,
                        'clone' => true,
                    ],
                ],
            ];
            // Add custom CSS for the admin
            add_action('admin_head', 'alba_add_judge_admin_styles');
        }

        // post id of Homepage page : 53
        if ($post_id === 53) {
            // Meta Box for Homepage
            $prefix_home = 'home_';
            $meta_boxes[] = [
                'title' => esc_html__('Les partenaires du club pour la saison 2024 - 2025', 'alba_theme'),
                'id' => $prefix_home . 'info',
                'post_types' => ['page'],
                'show' => [
                    'template' => ['homePage.php'],
                ],
                'context' => 'normal',
                'priority' => 'high',
                'fields' => [
                    [
                        'type' => 'image_advanced',
                        'name' => __('Logo des partenaires', 'alba_theme'),
                        'id' => $prefix_home . 'img_id',
                        'clone' => true,
                    ],
                ],
            ];
        }
    }
    return $meta_boxes;
}

// Add custom CSS to style the meta box layout
function alba_add_judge_admin_styles()
{
    ?>
    <style>
        #judge_info .inside .rwmb-meta-box {
            display: flex;
        }

        #judge_info .inside .rwmb-meta-box .rwmb-field {
            flex: 1;
            margin-right: 20px;
        }
    </style>
    <?php
}

// Custom dropdown title page //
// Add custom field for menu title only on child pages
function add_menu_title_meta_box(): void
{
    // Get the current post ID
    $post_id = isset($_GET['post']) ? $_GET['post'] : null;

    // Only proceed if we have a post ID
    if ($post_id) {
        // Get the post's parent ID
        $post_parent = wp_get_post_parent_id($post_id);

        // Only add the meta box if this is a child page (has a parent)
        if ($post_parent > 0) {
            add_meta_box(
                'menu_title_meta_box',
                'Menu Title',
                'menu_title_meta_box_html',
                'page'
            );
        }
    } else {
        // For new pages, we'll add the meta box and hide it with JavaScript if it's not a child page
        add_meta_box(
            'menu_title_meta_box',
            'Menu Title',
            'menu_title_meta_box_html',
            'page'
        );
        add_action('admin_footer', 'menu_title_visibility_script');
    }
}

add_action('add_meta_boxes', 'add_menu_title_meta_box');

// Meta box HTML
function menu_title_meta_box_html($post): void
{
    $value = get_post_meta($post->ID, 'menu_title', true);
    ?>
    <label for="menu_title">Court titre pour le menu de la barre de navigation</label>
    <input type="text" id="menu_title" name="menu_title" value="<?= esc_attr($value) ?>" class="widefat">
    <p class="description">Laissez vide pour utiliser le titre complet de la page</p>
    <?php
}

// JavaScript to hide/show meta box based on parent selection
function menu_title_visibility_script()
{
    ?>
    <script type="text/javascript">
        jQuery(document).ready(function ($) {
            const theparentId = $('#parent_id');

            // Function to toggle meta box visibility
            function toggleMenuTitleMetaBox() {
                var parentId = theparentId.val();
                if (parentId && parentId > 0) {
                    $('#menu_title_meta_box').show();
                } else {
                    $('#menu_title_meta_box').hide();
                }
            }

            // Initial check
            toggleMenuTitleMetaBox();

            // Watch for changes to the parent dropdown
            theparentId.on('change', toggleMenuTitleMetaBox);
        });
    </script>
    <?php
}

// Save meta box data
function save_menu_title_meta_box($post_id): void
{
    // Only save if this is a child page
    if (wp_get_post_parent_id($post_id) > 0) {
        if (array_key_exists('menu_title', $_POST)) {
            update_post_meta(
                $post_id,
                'menu_title',
                sanitize_text_field($_POST['menu_title'])
            );
        }
    }
}

add_action('save_post', 'save_menu_title_meta_box');

///////// function which displays the title in <h2> tag with a specific class //////////
function display_titlePage(): string
{
    return '<h2 class="text-4xl font-bold mb-8">' . get_the_title() . '</h2>';
}