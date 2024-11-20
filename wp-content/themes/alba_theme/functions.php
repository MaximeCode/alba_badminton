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


function alba_theme_enqueue_scripts()
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

function wppln_fix_svg()
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

