<?php

/* Template Name: allBureaux */

get_header();

$members_23_24 = array(
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

$oldBureaux = array(
    '2024-2025' => $members_23_24,
    '2023-2024' => $members_23_24,
    '2022-2023' => $members_23_24,
    '2021-2022' => $members_23_24,
);

?>
    <style>
        .thefixe {
            position: sticky;
            top: 20%;
        }
    </style>
    <section>
        <h2 class="text-4xl font-bold mb-8"><?php the_title(); ?></h2>

        <div class="flex gap-6 relative "> <!--flex-col sm:flex-row-->
            <!--Date (col fixe)-->
            <div class="basis-2/6 md:basis-1/6">
                <!-- Les dates se positionnent ici -->
                <div class="sticky top-[15%] relative flex flex-col justify-center">
                    <!-- Ligne continue verticale passant derrière les liens -->
                    <div class="absolute inset-0 left-[32px] z-40 m-0 w-0.5 h-full bg-black/50 transform -translate-x-1/2"></div>

                    <!-- Liens avec espace entre eux -->
                    <div class="space-y-12 z-50">
                        <?php foreach ($oldBureaux as $key => $bureau) {
                            echo sprintf('
        <a class="flex items-center bg-white rounded-xl p-3" href="#%s">
            <div class="w-10 h-10 rounded-full bg-primary-blue relative"></div>
            <div class="ms-3">%s</div>
        </a>
        ', $key, $key);
                        } ?>
                    </div>
                </div>

            </div>
            <!--Grille des informations-->
            <div class="basis-4/6 md:basis-5/6">
                <?php foreach ($oldBureaux as $key => $bureau) {
                    echo sprintf('
                <div id="%s" class="mb-16">
                    <h3 class="text-2xl font-bold mb-4">%s</h3>
                    <div class="bg-white rounded-2xl p-5 grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 divide-y divide-primary-blue sm:divide-none">
                        ', $key, $key);
                    foreach ($bureau as $keyB => $member) {
                        echo sprintf(
                            '<div class="grid grid-rows-[auto_2fr_auto] gap-4 justify-center text-center text-lg p-4">
                            <p class="underline font-bold text-xl">%s</p>
                            <div class="row-span-1">%s</div>
                            <p class="row-span-1 italic text-xl">%s</p>
                        </div>',
                            ucwords(strtolower($keyB)),
                            wp_get_attachment_image($member['img'], '', false, array(
                                'loading' => 'lazy',
                                'class' => "w-1/2 max-w-56 m-auto rounded-2xl transform transition duration-300 ease-in-out hover:scale-105 row-span-2",
                            )),
                            ucwords(strtolower($member['name']))
                        );
                    }
                    echo '</div></div>';
                } ?>
            </div>
        </div>
    </section>


<?php
get_footer();
