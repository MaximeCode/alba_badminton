<?php

function alba_theme_enqueue_styles(): void {

	// Enregistrer le fichier CSS personnalisé de votre thème
	wp_enqueue_style( 'alba-style', get_stylesheet_directory_uri() . './style.css' );
}

add_action( 'wp_enqueue_scripts', 'alba_theme_enqueue_styles' );

// Support des balises <title>
add_theme_support( 'title-tag' );


function alba_theme_enqueue_scripts() {
	// Enqueue Flowbite JS
	wp_enqueue_script( 'flowbite', get_template_directory_uri() . '/assets/js/flowbite.min.js', array(), null, true );
}

add_action( 'wp_enqueue_scripts', 'alba_theme_enqueue_scripts' );

// Allow SVG
add_filter( 'wp_check_filetype_and_ext', function ( $data, $file, $filename, $mimes ) {

	global $wp_version;
	if ( $wp_version !== '4.7.1' ) {
		return $data;
	}

	$filetype = wp_check_filetype( $filename, $mimes );

	return [
		'ext'             => $filetype['ext'],
		'type'            => $filetype['type'],
		'proper_filename' => $data['proper_filename']
	];

}, 10, 4 );

function wppln_mime_types( $mimes ) {
	$mimes['svg'] = 'image/svg+xml';

	return $mimes;
}

add_filter( 'upload_mimes', 'wppln_mime_types' );

function wppln_fix_svg() {
	echo '<style>
        .attachment-266x266, .thumbnail img {
             width: 100% !important;
             height: auto !important;
        }
        </style>';
}

add_action( 'admin_head', 'wppln_fix_svg' );