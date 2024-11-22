<?php

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
    foreach ($members as $key => $member) {

        // Afficher un membre du bureau
        echo sprintf(
            '<div class="grid grid-rows-[auto_2fr_auto] gap-4 justify-center text-center text-lg p-4">
                    <p class="underline font-bold text-xl">%s</p>
                    <div class="row-span-1">%s</div>
                    <p class="row-span-1 italic text-xl">%s</p>
                </div>',
            ucwords(strtolower($key)),
            wp_get_attachment_image($member['img'], '', false, array(
                'loading' => 'lazy',
                'class' => "w-1/2 max-w-56 m-auto rounded-2xl transform transition duration-300 ease-in-out hover:scale-105 row-span-2",
            )),
            ucwords(strtolower($member['name'])));
    }
    echo '</div>';
}

// fonction de génération du breadcrumb sur chaque page (ajouté ds le header.php)
function generate_breadcrumbs(): void
{
    if (!is_front_page()) {
        $breadcrumb = '<nav class="max-w-max text-md mb-8 flex justify-center items-center px-5 py-3 text-primary-blue border border-primary-blue/50 rounded-xl bg-gray-50 dark:bg-gray-800 dark:border-gray-700" aria-label="Breadcrumb">';
        // Lien vers la page d'accueil >> svg = Home
        $breadcrumb .= '<li class="inline-flex items-center">
            <a href="' . home_url() . '" title="Accueil" class="inline-flex items-center font-medium hover:text-secondary-blue dark:text-gray-400 dark:hover:text-white">
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
