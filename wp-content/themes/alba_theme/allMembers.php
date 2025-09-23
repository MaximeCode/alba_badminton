<?php

/* Template Name: allMembers */

get_header();

$allMembers = get_office_members_by_year();
//echo '<pre>';
//var_dump($allMembers);
//die();

?>
    <section>
        <?= display_titlePage() ?>

        <div class="flex gap-3 sm:gap-6 md:gap-10 xl:gap-16 relative">
            <!--Dates (col fixe)-->
            <div class="basis-2/6 lg:basis-3/12">
                <!-- Les dates se positionnent ici -->
                <div class="sticky top-[10%] relative flex flex-col justify-center">
                    <!-- Ligne continue verticale passant derrière les liens -->
                    <div class="absolute inset-0 left-10 z-40 m-0 w-[1px] h-full bg-black/50 transform -translate-x-1/2"></div>

                    <!-- Liens avec espace entre eux -->
                    <div class="space-y-12 z-50">
                        <?php foreach ($allMembers as $key => $bureau) {
                            echo sprintf('
        <a class="flex items-center bg-white rounded-xl shadow-2xl p-1 sm:p-2 md:p-3 space-x-3 lg:space-x-5 border hover:border-primary-blue focus:border-primary-blue" href="#%s" onclick="goToOldManagers(this, \'%s\')">
            <div class="w-5 h-5 sm:w-7 sm:h-7 lg:w-10 lg:h-10 rounded-full bg-primary-blue relative"></div>
            <div class="text-xl">%s</div>
        </a>
        ', $key, $key, str_replace('-', ' - ', $key));
                        } ?>
                    </div>
                </div>
            </div>

            <!--Grille des membres-->
            <div class="basis-4/6 md:basis-5/6 space-y-10">
                <?php foreach ($allMembers as $key => $bureau) {
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
                                ucwords($member['position']),
                                wp_get_attachment_image((!empty($member['image_id']) ? $member['image_id'] : 523), '', false, array(
                                        'loading' => 'lazy',
                                        'class' => "w-1/2 max-w-56 m-auto rounded-2xl transform transition-300 ease-in-out hover:scale-105 row-span-2",
                                )),
                                ucwords($member['name'])
                        );
                    }
                    echo '</div></div>';
                } ?>
            </div>
        </div>
    </section>

    <!-- Section d'information contact -->
    <div class="mt-16 flex justify-center">
        <a href="<?= get_permalink(134); ?>" class="group max-w-2xl w-full">
            <div class="bg-primary-blue border border-primary-blue rounded-2xl shadow-2xl p-6 w-full text-center flex flex-col items-center gap-4 cursor-pointer transition-colors duration-200 group-hover:bg-white group-hover:text-primary-blue group-hover:border-primary-blue group-active:scale-95 group-focus:bg-white group-focus:text-primary-blue group-active:border-transparent">
                <svg class="h-10 w-10 text-white group-hover:text-primary-blue group-focus:text-primary-blue mb-2 transition-colors duration-200" fill="none"
                     aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" stroke="currentColor"
                     viewBox="0 0 24 24">
                    <path fill-rule="evenodd" d="M4 3a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1h1v2a1 1 0 0 0 1.707.707L9.414 13H15a1 1 0 0 0 1-1V4a1 1 0 0 0-1-1H4Z"
                          clip-rule="evenodd"/>
                    <path fill-rule="evenodd"
                          d="M8.023 17.215c.033-.03.066-.062.098-.094L10.243 15H15a3 3 0 0 0 3-3V8h2a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1h-1v2a1 1 0 0 1-1.707.707L14.586 18H9a1 1 0 0 1-.977-.785Z"
                          clip-rule="evenodd"/>
                </svg>
                <h4 class="text-xl font-bold text-white group-hover:text-primary-blue group-focus:text-primary-blue transition-colors duration-200">Besoin de contacter un
                    membre du bureau ?</h4>
                <p class="text-lg text-white group-hover:text-primary-blue group-focus:text-primary-blue transition-colors duration-200">Utilisez la page de contact pour
                    joindre la personne de votre choix !</p>
            </div>
        </a>
    </div>

<?php
get_footer();
