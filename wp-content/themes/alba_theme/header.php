<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php wp_title(); ?></title>
    <!-- link:css fait grâce à wp_head() -->
    <?php wp_head();

    $seasons = get_post_meta(166, 'custom_seasons', true);

    global $alba_theme_variables;

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
            focus:outline-none hover:ring-2 hover:ring-white focus:ring-2 focus:ring-gray-200 <?= $alba_theme_variables['animBase'] ?>"
                aria-controls="navbar-dropdown" aria-expanded="false">
            <span class="sr-only text-white">Open main menu</span>
            <svg class="w-5 h-5 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                 viewBox="0 0 17 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M1 1h15M1 7h15M1 13h15"/>
            </svg>
        </button>

        <!-- Mobile menu (hidden by default) -->
        <div class="hidden absolute top-24 left-0 w-full bg-primary-blue lg:static lg:w-auto lg:block z-50 transform transition-all duration-300 ease-in-out"
             id="navbar-dropdown">
            <ul class="w-10/12 mx-auto lg:w-full flex flex-col font-medium mt-4 mb-4 lg:mb-0 xl:px-4 xl:text-lg text-white rounded-lg uppercase xl:space-x-8 lg:flex-row lg:mt-0">
                <li>
                    <a href="/"
                       class="<?= $alba_theme_variables['classLi'] ?>"
                       aria-current="page">Accueil</a>
                </li>
                <li>
                    <a href="<?= get_permalink(26); ?>"
                       class="<?= $alba_theme_variables['classLi'] ?>">Actualit&eacute;s</a>
                </li>
                <!-- Dropdown Le club -->
                <li class="group">
                    <button id="dropdownClub" data-dropdown-toggle="dropdownNavbarClub"
                            data-dropdown-trigger="hover"
                            class="<?= $alba_theme_variables['classLiDropdown'] ?>">
                        Le club
                        <svg class="w-2.5 h-2.5 ms-2.5 <?= $alba_theme_variables['animRotateArrow'] ?>"
                             aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                             fill="none" viewBox="0 0 10 6">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                  stroke-width="2"
                                  d="m1 1 4 4 4-4"/>
                        </svg>
                    </button>
                    <!-- Dropdown menu -->
                    <div id="dropdownNavbarClub"
                         class="<?= $alba_theme_variables['classDivDropdown'] ?> border border-white">
                        <ul class="xl:text-lg normal-case divide-y" aria-labelledby="dropdownLargeButton">
                            <li>
                                <a href="<?= get_permalink(149); ?>"
                                   class="<?= $alba_theme_variables['subLi'] ?> rounded-t-lg">Pr&eacute;sentation</a>
                            </li>
                            <?php
                            // Répertoire des pages enfants
                            $children = get_pages('child_of=149');
                            if (!empty($children) && is_array($children)) {
                                foreach ($children as $child) {
                                    $menu_title = get_post_meta($child->ID, 'menu_title', true);
                                    ?>
                                    <li>
                                        <a href="<?= get_permalink($child->ID); ?>"
                                           class="<?= $alba_theme_variables['subLi'] ?>">
                                            <?= $menu_title ?: $child->post_title ?>
                                        </a>
                                    </li>
                                    <?php
                                }
                            } else {
                                echo "<li class='block px-4 py-2 leading-7 rounded-lg hover:bg-white hover:text-primary-blue'>Aucune page enfant disponible !</li>";
                            }
                            ?>
                        </ul>
                    </div>
                </li>
                <li>
                    <a href="<?= get_permalink(209); ?>"
                       class="<?= $alba_theme_variables['classLi'] ?>">Calendrier</a>
                </li>
                <!-- Dropdown Galerie -->
                <li class="group">
                    <button id="dropdownGalerie" data-dropdown-toggle="dropdownNavbarGalerie"
                            data-dropdown-placement="bottom"
                            data-dropdown-trigger="hover"
                            class="<?= $alba_theme_variables['classLiDropdown'] ?>">
                        Galerie
                        <svg class="w-2.5 h-2.5 ms-2.5 <?= $alba_theme_variables['animRotateArrow'] ?>"
                             aria-hidden="true"
                             xmlns="http://www.w3.org/2000/svg"
                             fill="none" viewBox="0 0 10 6">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="m1 1 4 4 4-4"/>
                        </svg>
                    </button>
                    <!-- Dropdown menu -->
                    <div id="dropdownNavbarGalerie"
                         class="<?= $alba_theme_variables['classDivDropdown'] ?> border border-white">
                        <ul class="xl:text-lg divide-y normal-case" aria-labelledby="dropdownLargeButton">
                            <?php
                            if (!empty($seasons) && is_array($seasons)) {
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
                                        <button id="doubleDropdownButton<?= $id ?>"
                                                data-dropdown-toggle="doubleDropdown<?= $id ?>"
                                                type="button"
                                                data-dropdown-placement="right-start"
                                                data-dropdown-trigger="hover"
                                                class="<?= $alba_theme_variables['classLiSubDropdown'] . " " . $rounded ?>">
                                            <?= str_replace('-', ' - ', $key); ?>
                                            <svg class="w-2.5 h-2.5 ms-3 rtl:rotate-180" aria-hidden="true"
                                                 xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                                <path stroke="currentColor" stroke-linecap="round"
                                                      stroke-linejoin="round"
                                                      stroke-width="2" d="m1 9 4-4-4-4"/>
                                            </svg>
                                        </button>
                                        <!-- Sub dropdown menu -->
                                        <div id="doubleDropdown<?= $id ?>"
                                             class="<?= $alba_theme_variables['classDivDropdown'] ?> border border-white">
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
                                                           class="<?= $alba_theme_variables['classLiSubDropdown'] . ' ' . $rounded_ ?>"><?= $event['title'] ?></a>
                                                    </li>
                                                <?php endforeach; ?>
                                                <li class="bg-white text-primary-blue p-1 rounded-lg">
                                                    <a href="<?= get_permalink(166); ?>#<?= $key ?>"
                                                       class="<?= $alba_theme_variables['seeAllThings'] ?>">
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
                                       class="<?= $alba_theme_variables['seeAllThings'] ?>">
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
                       class="<?= $alba_theme_variables['classLi'] ?>">Contact</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<script>
    // Fonction pour ajuster la position du dropdown
    function adjustDropdownPlacement() {
        const ddGallery = document.getElementById('dropdownGalerie');
        // Si la taille de l'écran est inférieure à 768px, le placement est "bottom"
        if (window.innerWidth < 768) {
            ddGallery.setAttribute('data-dropdown-offset-skidding', '-100');
        } else {
            // Sinon, le placement est "right-end"
            ddGallery.setAttribute('data-dropdown-offset-skidding', '0');
        }
    }

    // Écouteur pour charger la bonne position au chargement de la page
    document.addEventListener('DOMContentLoaded', adjustDropdownPlacement);

    // Écouteur pour ajuster la position lorsque la taille de l'écran change
    window.addEventListener('resize', adjustDropdownPlacement);
</script>

<div class="xs:container mx-auto w-full sm:w-10/12 p-2 sm:p-0">
    <?php generate_breadcrumbs(); ?>
