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
