<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php wp_title(); ?></title>
    <!-- link:css fait grâce à wp_head() -->
	<?php wp_head(); ?>
</head>

<body class="bg-back-blue font-crimson">

<!-- Navbar wrapper -->
<nav class="bg-primary-blue mb-10 z-50 font-personal relative">
    <div class="container mx-auto w-10/12 flex flex-wrap items-center justify-between z-50">
        <a href="/" class="flex items-center space-x-3 rtl:space-x-reverse">
			<?php echo wp_get_attachment_image( 18, 'thumbnail', false, array( 'class' => 'h-24 w-auto' ) ); ?>
        </a>
        <!-- Btn open navbar in mobile -->
        <button data-collapse-toggle="navbar-dropdown" type="button"
                class="inline-flex items-center p-2 w-10 h-10 justify-center lg:text-lg text-gray-500 rounded-lg lg:hidden focus:outline-none focus:ring-2 focus:ring-gray-200"
                aria-controls="navbar-dropdown" aria-expanded="false">
            <span class="sr-only text-white">Open main menu</span>
            <svg class="w-5 h-5 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                 viewBox="0 0 17 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M1 1h15M1 7h15M1 13h15"/>
            </svg>
        </button>

        <!-- Mobile menu (hidden by default) -->
        <div class="hidden absolute top-24 left-0 w-full bg-primary-blue lg:static lg:w-auto lg:block z-50"
             id="navbar-dropdown">
            <ul class="w-10/12 mx-auto lg:w-full flex flex-col font-medium mt-4 xl:px-4 xl:text-lg text-white rounded-lg uppercase xl:space-x-8 lg:flex-row lg:mt-0">
                <li>
                    <a href="/"
                       class="block py-2 px-3 rounded hover:bg-white hover:text-primary-blue md:py-3"
                       aria-current="page">Accueil</a>
                </li>
                <li>
                    <a href="<?= get_permalink( 26 ); ?>"
                       class="block py-2 px-3 rounded hover:bg-white hover:text-primary-blue md:py-3">Actualit&eacute;s</a>
                </li>
                <!-- Dropdown Le club -->
                <li>
                    <button id="dropdownClub" data-dropdown-toggle="dropdownNavbarClub" data-dropdown-trigger="hover"
                            class="flex items-center justify-between w-full py-2 px-3 rounded hover:bg-white hover:text-primary-blue lg:w-auto lg:py-3 uppercase focus:bg-white focus:text-primary-blue">
                        Le club
                        <svg class="w-2.5 h-2.5 ms-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                             fill="none" viewBox="0 0 10 6">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="m1 1 4 4 4-4"/>
                        </svg>
                    </button>
                    <!-- Dropdown menu -->
                    <div id="dropdownNavbarClub"
                         class="z-10 hidden font-normal bg-primary-blue rounded-lg shadow-box-dd w-44 border-white border-6">
                        <ul class="xl:text-lg normal-case divide-y" aria-labelledby="dropdownLargeButton">
                            <li>
                                <a href="#"
                                   class="block px-4 py-2 leading-7 rounded-t-lg hover:bg-white hover:text-primary-blue">Pr&eacute;sentation</a>
                            </li>
                            <li>
                                <a href="#"
                                   class="block px-4 py-2 leading-7 hover:bg-white hover:text-primary-blue">Historique
                                    du
                                    Bureau</a>
                            </li>
                            <li>
                                <a href="#"
                                   class="block px-4 py-2 leading-7 hover:bg-white hover:text-primary-blue">Interclubs</a>
                            </li>
                            <li>
                                <a href="#"
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
                       class="block py-2 px-3 rounded hover:bg-white hover:text-primary-blue md:py-3">Calendrier</a>
                </li>
                <!-- Dropdown Galerie -->
                <li>
                    <button id="dropdownGalerie" data-dropdown-toggle="dropdownNavbarGalerie"
                            data-dropdown-trigger="hover"
                            class="flex items-center justify-between w-full py-2 px-3 rounded hover:bg-white hover:text-primary-blue lg:w-auto lg:py-3 uppercase focus:bg-white focus:text-primary-blue">
                        Galerie 2024
                        <svg class="w-2.5 h-2.5 ms-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                             fill="none" viewBox="0 0 10 6">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="m1 1 4 4 4-4"/>
                        </svg>
                    </button>
                    <!-- Dropdown menu -->
                    <div id="dropdownNavbarGalerie"
                         class="z-10 hidden font-normal bg-primary-blue divide-y divide-gray-800 rounded-lg shadow-box-dd w-44 border-white border-6">
                        <ul class="xl:text-lg divide-y normal-case" aria-labelledby="dropdownLargeButton">
                            <li>
                                <a href="#"
                                   class="block px-4 py-2 leading-7 rounded-t-lg hover:bg-white hover:text-primary-blue">
                                    Soir&eacute;e Blackminton
                                </a>
                            </li>
                            <li>
                                <a href="#"
                                   class="block px-4 py-2 leading-7 hover:bg-white hover:text-primary-blue">Tournoi
                                    Annuel</a>
                            </li>
                            <li>
                                <a href="#"
                                   class="block px-4 py-2 leading-7 hover:bg-white hover:text-primary-blue">Soirée
                                    d'Intégration</a>
                            </li>
                            <li>
                                <a href="#"
                                   class="block px-4 py-2 leading-7 hover:bg-white hover:text-primary-blue">Nouvelle
                                    Génération</a>
                            </li>
                            <li class="bg-white text-primary-blue p-1 rounded-lg">
                                <a href="#"
                                   class="block text-center py-1 border-2 leading-7 hover:text-white hover:bg-primary-blue border-primary-blue text-primary-blue rounded-full text-base">
                                    Voir toute la galerie
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li>
                    <a href="#"
                       class="block py-2 px-3 rounded hover:bg-white hover:text-primary-blue md:py-3">Contact</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mx-auto w-10/12">