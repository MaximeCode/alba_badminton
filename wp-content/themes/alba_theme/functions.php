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

function showVar($var): void
{
    echo '<pre>';
    print_r($var);
    echo '</pre>';
    die();
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
    wp_enqueue_style('alba-style', get_stylesheet_directory_uri() . '/style.css');
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

add_filter('comment_form_default_fields', function ($fields) {
    // Supprime le champ site web
    if (isset($fields['url'])) {
        unset($fields['url']);
    }

    if (isset($fields['cookies'])) {
        $fields['cookies'] = str_replace(
            'Enregistrer mon nom, mon e-mail et mon site dans le navigateur pour mon prochain commentaire.',
            'Enregistrer mon nom et mon e-mail dans le navigateur pour mon prochain commentaire.',
            $fields['cookies']
        );
    }
    return $fields;
});

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

//////// Menu in Admin panel to settings details like number of courts, members...

// Add the menu page (club settings)
add_action('admin_menu', function () {
    add_menu_page(
        esc_html__('Paramètres du club', 'alba_theme'),
        esc_html__('Paramètres du club', 'alba_theme'),
        'manage_options',
        'club-settings',
        'render_club_settings_page',
        'dashicons-admin-settings',
        30
    );
});

// Register settings (club settings)
add_action('admin_init', function () {
    //// Homepage section ///////////////////////////////////////
    add_settings_section(
        'club_homepage_settings',
        "",
        'render_title_section',
        'club-settings',
        ['theTitle' => "Page d'accueil"]
    );

    // 1st title of homepage
    add_settings_field(
        'club_title_homepage',
        esc_html__('Titre principal', 'alba_theme'),
        'render_text_field',
        'club-settings',
        'club_homepage_settings',
        ['field_name' => 'club_title_homepage']
    );

    // 1st paragraph in homepage
    add_settings_field(
        'club_paragraph_homepage',
        esc_html__('Paragraphe de pr&eacute;sentation', 'alba_theme'),
        'render_textarea_field',
        'club-settings',
        'club_homepage_settings',
        ['field_name' => 'club_paragraph_homepage']
    );

    // Nb of members
    add_settings_field(
        'club_members_count',
        esc_html__('Nombre de membres', 'alba_theme'),
        'render_number_field',
        'club-settings',
        'club_homepage_settings',
        ['field_name' => 'club_members_count']
    );

    //// PresLeClub section ///////////////////////////////////////
    add_settings_section(
        'club_pres_settings',
        "",
        'render_title_section',
        'club-settings',
        ['theTitle' => "Page de pr&eacute;sentation du club"]
    );

    // Add image field
    add_settings_field(
        'club_family_img',
        esc_html__('Photo de famille ', 'alba_theme'),
        'render_image_field',
        'club-settings',
        'club_pres_settings',
        ['field_name' => 'club_family_img']
    );

    // caption below image
    add_settings_field(
        'club_legend_img',
        esc_html__("L&eacute;gende sous l'image", 'alba_theme'),
        'render_textarea_field',
        'club-settings',
        'club_pres_settings',
        ['field_name' => 'club_legend_img']
    );

    //// Calendar section ///////////////////////////////////////
    add_settings_section(
        'club_calendar_settings',
        "",
        'render_title_section',
        'club-settings',
        ['theTitle' => "Calendrier du CODEP"]
    );

    // Link of calendar in Google Sheets
    add_settings_field(
        'club_link_calendar',
        esc_html__('Lien vers le calendrier avec Google Sheets', 'alba_theme'),
        'render_text_field',
        'club-settings',
        'club_calendar_settings',
        ['field_name' => 'club_link_calendar']
    );

    //// Contact section ///////////////////////////////////////
    add_settings_section(
        'club_contact_settings',
        "",
        'render_title_section',
        'club-settings',
        ['theTitle' => "Page de contact"]
    );
    // Small title (questions)
    add_settings_field(
        'club_contact_questions',
        esc_html__("Titre secondaire", 'alba_theme'),
        'render_textarea_field',
        'club-settings',
        'club_contact_settings',
        ['field_name' => 'club_contact_questions']
    );

    // Mail
    add_settings_field(
        'club_mail',
        esc_html__('Mail', 'alba_theme'),
        'render_email_field',
        'club-settings',
        'club_contact_settings',
        [
            'field_name' => 'club_mail',
            'desc' => esc_html__('Email où recevoir les demandes / questions des visiteurs', 'alba_theme')
        ]
    );

    // Tel
    add_settings_field(
        'club_tel',
        esc_html__('Téléphone', 'alba_theme'),
        'render_tel_field',
        'club-settings',
        'club_contact_settings',
        [
            'field_name' => 'club_tel',
            'std' => '+33',
            'pattern' => '\+[0-9]{2}[0-9\s]*'
        ]
    );

    // Adresse postale
    add_settings_field(
        'club_address',
        esc_html__('Adresse postale du gymnase', 'alba_theme'),
        'render_text_field',
        'club-settings',
        'club_contact_settings',
        ['field_name' => 'club_address']
    );


    // Register the settings [save]
    //// Homepage section
    register_setting('club_settings', 'club_title_homepage');
    register_setting('club_settings', 'club_paragraph_homepage');
    register_setting('club_settings', 'club_members_count');

    //// presLeClub
    register_setting('club_settings', 'club_family_img');
    register_setting('club_settings', 'club_legend_img');

    /// Calendar
    register_setting('club_settings', 'club_link_calendar');

    //// Contact
    register_setting('club_settings', 'club_contact_questions');
    register_setting('club_settings', 'club_mail');
    register_setting('club_settings', 'club_tel');
    register_setting('club_settings', 'club_address');
});

function render_title_section($args): void
{
    $title = $args['theTitle'];
    ?>
    <style>
        .section-divider {
            margin: 3em 0 1em 0;
            border-top: 2px solid #2271b1;
        }

        .section-title {
            color: #2271b1;
            font-size: 1.3em;
            margin: 1em 0;
        }
    </style>
    <hr class="section-divider">
    <h2 class="section-title"><?= esc_html__($title, 'alba_theme'); ?></h2>
    <?php
}

// Fonction pour rendre le champ email
function render_email_field($args): void
{
    $value = get_option($args['field_name']);
    ?>
    <input
            type="email"
            name="<?php echo esc_attr($args['field_name']); ?>"
            value="<?php echo esc_attr($value); ?>"
            class="regular-text"
            size="60"
    >
    <?php if (isset($args['desc'])): ?>
    <p class="description"><?php echo esc_html($args['desc']); ?></p>
<?php endif; ?>
    <?php
}

// Fonction pour rendre le champ téléphone
function render_tel_field($args): void
{
    $value = get_option($args['field_name']) ?: $args['std'];
    ?>
    <input
            type="text"
            name="<?php echo esc_attr($args['field_name']); ?>"
            value="<?php echo esc_attr($value); ?>"
            class="regular-text"
            pattern="<?php echo $args['pattern']; ?>"
            max="12"
    >
    <?php
}

// Render text field
function render_text_field($args): void
{
    $value = get_option($args['field_name']);
    ?>
    <input
            type="text"
            name="<?php echo esc_attr($args['field_name']); ?>"
            value="<?php echo esc_attr($value); ?>"
            class="large-text"
            min="0"
    >
    <?php
}

// Render number field
function render_number_field($args): void
{
    $value = get_option($args['field_name']);
    ?>
    <input
            type="number"
            name="<?php echo esc_attr($args['field_name']); ?>"
            value="<?php echo esc_attr($value); ?>"
            class="small-text"
            min="0"
    >
    <?php
}

// Render textarea field
function render_textarea_field($args): void
{
    $value = get_option($args['field_name']);
    ?>
    <textarea
            name="<?php echo esc_attr($args['field_name']); ?>"
            class="large-text"
            rows="5"
    ><?php echo esc_html($value); ?></textarea>
    <?php
}

// Render the settings page
function render_club_settings_page(): void
{
    if (!current_user_can('manage_options')) {
        return;
    }
    ?>
    <div class="wrap">
        <h1><?php echo esc_html__('Param&eacute;trage des infos du club', 'alba_theme'); ?></h1>
        <form action="options.php" method="post">
            <?php
            settings_fields('club_settings');
            do_settings_sections('club-settings');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

// First, enqueue the WordPress media scripts
add_action('admin_enqueue_scripts', function ($hook) {
    if ('toplevel_page_club-settings' !== $hook) {
        return;
    }
    wp_enqueue_media();
});

// Render image field function
function render_image_field($args): void
{
    $image_id = get_option($args['field_name']);
    $image_url = $image_id ? wp_get_attachment_url($image_id) : '';
    ?>
    <div class="image-upload-wrap">
        <input type="hidden" name="<?php echo esc_attr($args['field_name']); ?>"
               id="<?php echo esc_attr($args['field_name']); ?>"
               value="<?php echo esc_attr($image_id); ?>">

        <div class="image-preview">
            <?php if ($image_url): ?>
                <img src="<?php echo esc_url($image_url); ?>" style="max-width: 250px;" alt="">
            <?php endif; ?>
        </div>

        <input type="button" class="button upload-image-button"
               value="<?php esc_attr_e('Insérer une image', 'alba_theme'); ?>"/>

        <?php if ($image_url): ?>
            <input type="button" class="button remove-image-button"
                   value="<?php esc_attr_e("Supprimer l'image", 'alba_theme'); ?>"/>
        <?php endif; ?>
    </div>

    <script>
        jQuery(document).ready(function ($) {
            // Upload image
            $('.upload-image-button').click(function (e) {
                e.preventDefault();
                var button = $(this);
                var imageWrap = button.closest('.image-upload-wrap');
                var imageInput = imageWrap.find('input[type="hidden"]');
                var imagePreview = imageWrap.find('.image-preview');

                var image = wp.media({
                    title: '<?php esc_html_e('Sélectionner ou insérer une image', 'alba_theme'); ?>',
                    multiple: false
                }).open().on('select', function () {
                    var uploadedImage = image.state().get('selection').first().toJSON();
                    imageInput.val(uploadedImage.id);

                    // Update preview
                    imagePreview.html('<img src="' + uploadedImage.url + '" style="max-width: 150px;" alt="">');

                    // Show remove button if not already present
                    if (imageWrap.find('.remove-image-button').length === 0) {
                        imageWrap.append('<input type="button" class="button remove-image-button" value="<?php esc_attr_e("Supprimer l'image", 'alba_theme'); ?>" />');
                    }
                });
            });

            // Remove image
            $(document).on('click', '.remove-image-button', function (e) {
                e.preventDefault();
                var button = $(this);
                var imageWrap = button.closest('.image-upload-wrap');
                var imageInput = imageWrap.find('input[type="hidden"]');
                var imagePreview = imageWrap.find('.image-preview');

                imageInput.val('');
                imagePreview.empty();
                button.remove();
            });
        });
    </script>
    <?php
}