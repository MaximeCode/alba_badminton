<?php /** @noinspection ALL */

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
    <section>
        <h2 class="<?= $classTitle ?>"><?php the_title(); ?></h2>

        <div class="flex gap-3 sm:gap-6 md:gap-10 xl:gap-16 relative">
            <!--Date (col fixe)-->
            <div class="basis-2/6 lg:basis-3/12">
                <!-- Les dates se positionnent ici -->
                <div class="sticky top-[10%] relative flex flex-col justify-center">
                    <!-- Ligne continue verticale passant derrière les liens -->
                    <div class="absolute inset-0 left-10 z-40 m-0 w-[1px] h-full bg-black/50 transform -translate-x-1/2"></div>

                    <!-- Liens avec espace entre eux -->
                    <div class="space-y-12 z-50">
                        <?php foreach ($oldBureaux as $key => $bureau) {
                            echo sprintf('
        <a class="flex items-center bg-white rounded-xl shadow-2xl p-1 sm:p-2 md:p-3 space-x-3 lg:space-x-5 border hover:border-primary-blue focus:border-primary-blue" href="#%s" onclick="goToOldManagers(this, \'%s\')">
            <div class="w-5 h-5 sm:w-7 sm:h-7 lg:w-10 lg:h-10 rounded-full bg-primary-blue relative"></div>
            <div>%s</div>
        </a>
        ', $key, $key, str_replace('-', ' - ', $key));
                        } ?>
                    </div>
                </div>

            </div>
            <!--Grille des informations-->
            <div class="basis-4/6 md:basis-5/6 space-y-10">
                <?php foreach ($oldBureaux as $key => $bureau) {
                    echo sprintf('
                <div id="%s">
                    <h3 class="text-2xl font-bold mb-4">%s</h3>
                    <div class="bg-white rounded-2xl shadow-2xl p-3 sm:p-5 grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 divide-y divide-primary-blue sm:divide-none">
                        ', $key, str_replace('-', ' - ', $key));
                    foreach ($bureau as $keyB => $member) {
                        echo sprintf(
                            '<div class="grid grid-rows-[auto_2fr_auto] gap-4 justify-center items-center text-center text-lg p-3">
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
