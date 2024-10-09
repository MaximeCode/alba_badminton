<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php wp_title(); ?></title>
    <!-- link:css fait grâce à wp_head() -->
    <!--    <link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.css" rel="stylesheet"/>-->
	<?php wp_head(); ?>
</head>

<body class="container mx-auto w-10/12 bg-back-blue font-personal">

<nav class="bg-primary-blue mb-10">
    <div class="flex max-w-screen-xl flex-wrap items-center justify-between mx-auto px-10">
        <a href="#" class="flex items-center space-x-3 rtl:space-x-reverse">
            <?php echo wp_get_attachment_image( 18, 'thumbnail', false, array( 'class' => 'h-24 w-auto' )); ?>
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
        <div class="hidden w-full lg:block lg:w-auto" id="navbar-dropdown">
            <ul class="flex flex-col font-medium p-4 mt-4 lg:px-4 xl:text-lg text-white border-gray-100 rounded-lg uppercase lg:space-x-8 rtl:space-x-reverse lg:flex-row lg:mt-0 border-0">
                <li>
                    <a href="#"
                       class="block py-2 px-3 rounded hover:bg-white hover:text-primary-blue md:py-3"
                       aria-current="page">Accueil</a>
                </li>
                <li>
                    <a href="#"
                       class="block py-2 px-3 rounded hover:bg-white hover:text-primary-blue md:py-3">Actualit&eacute;s</a>
                </li>
                <!-- Dropdown Le club -->
                <li>
                    <button id="dropdownClub" data-dropdown-toggle="dropdownNavbarClub"
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
                        <ul class="py-2 xl:text-lg capitalize divide-y" aria-labelledby="dropdownLargeButton">
                            <li>
                                <a href="#"
                                   class="block px-4 py-2 hover:bg-white hover:text-primary-blue">Pr&eacute;sentation</a>
                            </li>
                            <li>
                                <a href="#"
                                   class="block px-4 py-2 hover:bg-white hover:text-primary-blue">Historique du
                                    bureau</a>
                            </li>
                            <li>
                                <a href="#"
                                   class="block px-4 py-2 hover:bg-white hover:text-primary-blue">Interclubs</a>
                            </li>
                            <li>
                                <a href="#"
                                   class="block px-4 py-2 hover:bg-white hover:text-primary-blue">Horaires</a>
                            </li>
                            <li>
                                <a href="#"
                                   class="block px-4 py-2 hover:bg-white hover:text-primary-blue">Palmar&egrave;s</a>
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
                            class="flex items-center justify-between w-full py-2 px-3 rounded hover:bg-white hover:text-primary-blue lg:w-auto lg:py-3 uppercase focus:bg-white focus:text-primary-blue">
                        Galerie
                        <svg class="w-2.5 h-2.5 ms-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                             fill="none" viewBox="0 0 10 6">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="m1 1 4 4 4-4"/>
                        </svg>
                    </button>
                    <!-- Dropdown menu -->
                    <div id="dropdownNavbarGalerie"
                         class="z-10 hidden font-normal bg-primary-blue divide-y divide-gray-800 rounded-lg shadow-box-dd w-44 border-white border-6">
                        <ul class="py-2 xl:text-lg capitalize divide-y" aria-labelledby="dropdownLargeButton">
                            <li>
                                <a href="#"
                                   class="block px-4 py-2 hover:bg-white hover:text-primary-blue">Soir&eacute;e
                                    Blackminton</a>
                            </li>
                            <li>
                                <a href="#"
                                   class="block px-4 py-2 hover:bg-white hover:text-primary-blue">Tournoi Annuel</a>
                            </li>
                            <li>
                                <a href="#"
                                   class="block px-4 py-2 hover:bg-white hover:text-primary-blue">Nouvelle
                                    Génération</a>
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