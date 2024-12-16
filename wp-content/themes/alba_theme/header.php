<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php wp_title(); ?></title>
    <!-- link:css fait grâce à wp_head() -->
    <?php wp_head();

    // Class title page
    global $classTitle;
    $classTitle = "text-4xl font-bold mb-8";

    // Animation cartes d'actualités
    global $animCardNews;
    global $animBase;
    $animCardNews = "transform transition duration-200 ease-in-out hover:bg-primary-blue hover:bg-opacity-10 hover:scale-105";
    $animRotateArrow = "transform transition-transform duration-500 group-hover:rotate-180";
    // <li> animation on hover
    $animBase = "transform transition duration-200 ease-in-out";
    // Class of each <li> in the navbar
    $classLi = "block py-2 px-3 rounded $animBase hover:bg-white hover:text-primary-blue md:py-3";
    // Class of div of each dropdown in the navbar
    $classDivDropdown = "z-10 hidden font-normal bg-primary-blue rounded-lg shadow-box-dropdown w-44 border-white border-6";
    // Class of each dropdown <li> in the navbar
    $classLiDropdown = "flex items-center justify-between w-full py-2 px-3 rounded $animBase group-hover:bg-white 
	group-hover:text-primary-blue lg:w-auto lg:py-3 uppercase";
    // Class of each sub dropdown <li> in the navbar
    $classLiSubDropdown = "flex items-center justify-between w-full px-4 py-2 leading-7 hover:bg-white hover:text-primary-blue";

    // Classes of all btn (sauf "envoyer" du form de contact)
    global $classBtn;
    $classBtn = "text-lg md:text-xl text-white bg-secondary-blue hover:bg-secondary-blue/75 rounded-lg px-5 py-3 transform transition duration-100 ease-in-out";

    global $members;
    // bureau actuel
    $members = array(
        'pr&eacute;sident' => array(
            'name' => 'Jean Dupont',
            'img' => 151,
        ),
        'vice-pr&eacute;sident' => array(
            'name' => 'Jeanne Dupont',
            'img' => 151,
        ),
        'tr&eacute;sorier' => array(
            'name' => 'Jean Dupont',
            'img' => 151,
        ),
        'secr&eacute;taire' => array(
            'name' => 'Jeanne Dupont',
            'img' => 151,
        ),
        'membre' => array(
            'name' => 'Jean Dupont',
            'img' => 151,
        ),
        'membre 2' => array(
            'name' => 'MaximE bauDe',
            'img' => 151,
        ),
    );

    $seasons = get_post_meta(166, 'custom_seasons', true);


    // Préparation des données pour le menu Galerie
    $gallery = [];
    if (isset($seasons) && is_array($seasons)) {
        foreach ($seasons as $season) {
            $gallery[$season['title']] = []; // Initialise la saison

            if (isset($season['events']) && is_array($season['events'])) {
                foreach ($season['events'] as $eventKey => $event) {
                    $eventAnchor = isset($event['title']) ? sanitize_title(str_replace(' ', '-', $event['title'])) : 'event-' . $eventKey;
                    $gallery[$season['title']][$eventKey] = [
                        'title' => $event['title'],
                        'anchor' => $eventAnchor,
                    ];
                }
            }
        }
    }
    ?>
</head>

<body class="bg-back-blue font-crimson selection:bg-primary-blue selection:text-white">

<!-- Navbar wrapper -->
<nav class="bg-primary-blue mb-10 z-50 font-personal relative shadow-xl">
    <div class="container mx-auto w-10/12 flex flex-wrap items-center justify-between z-50">
        <a href="/" class="flex items-center space-x-3">
            <?php echo wp_get_attachment_image(140, 'thumbnail', false, array('class' => 'h-24 w-auto')); ?>
        </a>
        <!-- Btn open navbar in mobile -->
        <button data-collapse-toggle="navbar-dropdown" type="button"
                class="inline-flex items-center p-2 w-10 h-10 justify-center lg:text-lg text-gray-500 rounded-lg lg:hidden
            focus:outline-none hover:ring-2 hover:ring-white focus:ring-2 focus:ring-gray-200 <?= $animBase ?>"
                aria-controls="navbar-dropdown" aria-expanded="false">
            <span class="sr-only text-white">Open main menu</span>
            <svg class="w-5 h-5 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                 viewBox="0 0 17 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M1 1h15M1 7h15M1 13h15"/>
            </svg>
        </button>

        <!-- Mobile menu (hidden by default) -->
        <div
                class="hidden absolute top-24 left-0 w-full bg-primary-blue lg:static lg:w-auto lg:block z-50 transform transition-all duration-300 ease-in-out"
                id="navbar-dropdown">
            <ul
                    class="w-10/12 mx-auto lg:w-full flex flex-col font-medium mt-4 xl:px-4 xl:text-lg text-white rounded-lg uppercase xl:space-x-8 lg:flex-row lg:mt-0">
                <li>
                    <a href="/"
                       class="<?= $classLi ?>"
                       aria-current="page">Accueil</a>
                </li>
                <li>
                    <a href="<?= get_permalink(26); ?>"
                       class="<?= $classLi ?>">Actualit&eacute;s</a>
                </li>
                <!-- Dropdown Le club -->
                <li class="group">
                    <button id="dropdownClub" data-dropdown-toggle="dropdownNavbarClub"
                            data-dropdown-trigger="hover"
                            class="<?= $classLiDropdown ?>">
                        Le club
                        <svg class="w-2.5 h-2.5 ms-2.5 <?= $animRotateArrow ?>"
                             aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                             fill="none" viewBox="0 0 10 6">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                  stroke-width="2"
                                  d="m1 1 4 4 4-4"/>
                        </svg>
                    </button>
                    <!-- Dropdown menu -->
                    <div id="dropdownNavbarClub"
                         class="<?= $classDivDropdown ?>">
                        <ul class="xl:text-lg normal-case divide-y" aria-labelledby="dropdownLargeButton">
                            <li>
                                <a href="<?= get_permalink(149); ?>"
                                   class="block px-4 py-2 leading-7 rounded-t-lg hover:bg-white hover:text-primary-blue">Pr&eacute;sentation</a>
                            </li>
                            <li>
                                <a href="<?= get_permalink(153); ?>"
                                   class="block px-4 py-2 leading-7 hover:bg-white hover:text-primary-blue">
                                    Historique du Bureau
                                </a>
                            </li>
                            <li>
                                <a href="<?= get_permalink(190); ?>"
                                   class="block px-4 py-2 leading-7 hover:bg-white hover:text-primary-blue">Interclubs</a>
                            </li>
                            <li>
                                <a href="<?= get_permalink(164); ?>"
                                   class="block px-4 py-2 leading-7 hover:bg-white hover:text-primary-blue">Horaires</a>
                            </li>
                            <li>
                                <a href="#"
                                   class="block px-4 py-2 leading-7 rounded-b-lg hover:bg-white hover:text-primary-blue">Palmar&egrave;s</a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li>
                    <a href="#"
                       class="<?= $classLi ?>">Calendrier</a>
                </li>
                <!-- Dropdown Galerie -->
                <li class="group">
                    <button id="dropdownGalerie" data-dropdown-toggle="dropdownNavbarGalerie"
                            data-dropdown-trigger="hover"
                            class="<?= $classLiDropdown ?>">
                        Galerie
                        <svg class="w-2.5 h-2.5 ms-2.5 <?= $animRotateArrow ?>" aria-hidden="true"
                             xmlns="http://www.w3.org/2000/svg"
                             fill="none" viewBox="0 0 10 6">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="m1 1 4 4 4-4"/>
                        </svg>
                    </button>
                    <!-- Dropdown menu -->
                    <div id="dropdownNavbarGalerie"
                         class="<?= $classDivDropdown ?>">
                        <ul class="xl:text-lg divide-y normal-case" aria-labelledby="dropdownLargeButton">
                            <?php
                            if (isset($seasons) && !empty($seasons) && is_array($seasons)) {
                                $id = 0; // ID pour les boutons de dropdown
                                foreach ($gallery as $key => $season) :
                                    if (isFirstKey($key, $gallery) && isLastKey($key, $gallery)) { // Vérifie s'il n'y a qu'une seule saison
                                        $rounded = "rounded-lg"; // rounded top and bottom
                                    } else {
                                        if (isFirstKey($key, $gallery)) { // Vérifie si la clé est la première
                                            $rounded = "rounded-t-lg"; // rounded top
                                        } elseif (isLastKey($key, $gallery)) { // Vérifie si la clé est la dernière
                                            $rounded = "rounded-b-lg"; // rounded bottom
                                        } else {
                                            $rounded = ""; // rounded none
                                        }
                                    }
                                    ?>
                                    <li class="<?php /*= isLastKey($key, $gallery) ? 'mb-2' : ''; */
                                    ?>">
                                        <a href="<?= get_permalink(166); ?>#<?= sanitize_title(str_replace(' ', '-', $key)) ?>"
                                           id="doubleDropdownButton<?= $id ?>"
                                           data-dropdown-toggle="doubleDropdown<?= $id ?>"
                                           type="button"
                                           data-dropdown-placement="right-start" data-dropdown-trigger="hover"
                                           class="<?= "$classLiSubDropdown $rounded" ?>">
                                            <?= str_replace('-', ' - ', $key); ?>
                                            <svg class="w-2.5 h-2.5 ms-3 rtl:rotate-180" aria-hidden="true"
                                                 xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                                <path stroke="currentColor" stroke-linecap="round"
                                                      stroke-linejoin="round"
                                                      stroke-width="2" d="m1 9 4-4-4-4"/>
                                            </svg>
                                        </a>
                                        <!-- Sub dropdown menu -->
                                        <div id="doubleDropdown<?= $id ?>"
                                             class="<?= $classDivDropdown ?>">
                                            <ul class="xl:text-lg divide-y normal-case"
                                                aria-labelledby="doubleDropdownButton">
                                                <?php foreach ($season as $theKey => $event) :
                                                    if (isFirstKey($theKey, $season)) { // Vérifie si la clé est la première
                                                        $rounded_ = "rounded-t-lg"; // rounded top
                                                    } else {
                                                        $rounded_ = ""; // rounded none
                                                    }
                                                    ?>
                                                    <li>
                                                        <a href="<?= get_permalink(166); ?>#<?= strtolower($event['anchor']) ?>"
                                                           class="<?= "$classLiSubDropdown $rounded_" ?>"><?= $event['title'] ?></a>
                                                    </li>
                                                <?php endforeach; ?>
                                                <li class="bg-white text-primary-blue p-1 rounded-lg">
                                                    <a href="<?= get_permalink(166); ?>#<?= $key ?>"
                                                       class="block text-center py-1 border-2 leading-7 hover:text-white hover:bg-primary-blue border-primary-blue text-primary-blue rounded-full text-base">
                                                        Voir toutes les photos
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                    <?php $id++;
                                endforeach; ?>
                                <li class="bg-white text-primary-blue p-1 rounded-lg">
                                    <a href="<?= get_permalink(166); ?>"
                                       class="block text-center py-1 border-2 leading-7 hover:text-white hover:bg-primary-blue border-primary-blue text-primary-blue rounded-full text-base">
                                        Voir toute la galerie
                                    </a>
                                </li>
                                <?php
                            } else {
                                echo "<li class='block px-4 py-2 leading-7 rounded-lg hover:bg-white hover:text-primary-blue'>Aucune galerie disponible !</li>";
                            } ?>
                        </ul>
                    </div>
                </li>
                <li>
                    <a href="<?= get_permalink(134); ?>"
                       class="<?= $classLi ?>">Contact</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mx-auto w-full sm:w-10/12 p-2 sm:p-0">
    <?php generate_breadcrumbs();

    //    echo "<pre>";
    //    var_dump($gallery);
    //    echo "</pre>";
    ?>

