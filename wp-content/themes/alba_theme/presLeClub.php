<?php
/* Template Name: presLeClub */

get_header();

?>

    <section>
        <h2 class="text-4xl font-bold mb-8"><?php the_title(); ?></h2>
        <div class="container w-full md:w-3/4 m-auto">
            <h2 class="text-center italic text-3xl font-bold mb-12 text-balance">
                <span class="text-primary-blue">A</span>micale de <span class="text-primary-blue">L</span>uc&eacute; de
                <span class="text-primary-blue">BA</span>dminton
            </h2>

            <div class="mb-12 w-full h-48 md:h-72 bg-gray-900/50 rounded-2xl grid place-content-center">
                Photo du club
                <!--                <img src="--><?php //echo get_the_post_thumbnail_url(); ?><!--" alt="-->
                <?php //the_title(); ?><!--"-->
                <!--                     class="w-full h-auto">-->
            </div>

            <!--Paragraphe de présentation-->
            <p class="mb-8 text-justify text-lg text-balance">
                Situ&eacute; en plein cœur de la ville de Luc&eacute;, le
                club ALBA a &eacute;t&eacute;
                fond&eacute; en 1987 pour permettre aux habitants de
                vivre leur passion du badminton et encourager le d&eacute;veloppement de la pratique de ce sport mill&eacute;naire.<br><br>

                L’ALBA est compos&eacute;e d’une centaine d’adh&eacute;rents de tous &acirc;ges (des d&eacute;butants
                aux v&eacute;t&eacute;rans)
                qui ont plaisir
                &agrave;
                se retrouver chaque semaine pour des sessions d’entra&icirc;nements et des tournois organis&eacute;s
                &agrave; travers
                la
                r&eacute;gion
                Centre-Val de Loire.<br><br>

                Le club est r&eacute;sident du gymnase Jean Boudrie qui comprend 7 terrains de jeux (simple et double)
                pr&eacute;sentant
                un sol combin&eacute; multisports de type Taraflex. Chaque terrain dispose du mat&eacute;riel ad&eacute;quat
                (poteaux,
                filets)
                pour la pratique du badminton. Le joueur b&eacute;n&eacute;ficie donc de conditions de jeu id&eacute;ales
                pour s’adonner &agrave; sa
                passion &excl;
            </p>

            <!--Le tournoi annuel-->
            <h3 class="mb-4 text-2xl underline decoration-primary-blue">Notre tournoi annuel</h3>
            <p class="mb-12 text-justify text-lg text-balance">
                Organis&eacute; tous les ans au mois de novembre, le tournoi inter-r&eacute;gional du club r&eacute;unit
                pas moins de 200
                joueurs sur le weekend dans une ambiance conviviale et comp&eacute;titive.
            </p>

            <!--Le bureau-->
            <h3 class="mb-4 text-2xl underline decoration-primary-blue">Le bureau 2024 - 2025</h3>
            <!--Tous les membres-->
            <div class="grid grid-cols-1 gap-4 divide-y divide-primary-blue">
                <!-- Président -->
                <div class="grid grid-rows-[auto_2fr_auto] gap-4 justify-center text-center p-4">
                    <p class="underline">Pr&eacute;sident</p>
                    <div class="row-span-1">
                        <?php echo wp_get_attachment_image(151, '', false, array(
                            'loading' => 'lazy',
                            'class' => "w-1/2 m-auto rounded-2xl transform transition duration-300 ease-in-out hover:scale-105 row-span-2",
                        )); ?>
                    </div>
                    <p class="row-span-1">Jean Dupont</p>
                </div>
                <!-- Vice-président -->
                <div class="grid grid-rows-[auto_2fr_auto] gap-4 justify-center text-center p-4">
                    <p class="underline">Vice-Pr&eacute;sident</p>
                    <div class="row-span-1">
                        <?php echo wp_get_attachment_image(151, '', false, array(
                            'loading' => 'lazy',
                            'class' => "w-1/2 m-auto rounded-2xl transform transition duration-300 ease-in-out hover:scale-105 row-span-2",
                        )); ?>
                    </div>
                    <p class="row-span-1">Jean Dupont</p>
                </div>
                <!-- Trésorier -->
                <div class="grid grid-rows-[auto_2fr_auto] gap-4 justify-center text-center p-4">
                    <p class="underline">Tr&eacute;sorier</p>
                    <div class="row-span-1">
                        <?php echo wp_get_attachment_image(151, '', false, array(
                            'loading' => 'lazy',
                            'class' => "w-1/2 m-auto rounded-2xl transform transition duration-300 ease-in-out hover:scale-105 row-span-2",
                        )); ?>
                    </div>
                    <p class="row-span-1">Jean Dupont</p>
                </div>
                <!-- Secrétaire -->
                <div class="grid grid-rows-[auto_2fr_auto] gap-4 justify-center text-center p-4">
                    <p class="underline">Secr&eacute;taire</p>
                    <div class="row-span-1">
                        <?php echo wp_get_attachment_image(151, '', false, array(
                            'loading' => 'lazy',
                            'class' => "w-1/2 m-auto rounded-2xl transform transition duration-300 ease-in-out hover:scale-105 row-span-2",
                        )); ?>
                    </div>
                    <p class="row-span-1">Jean Dupont</p>
                </div>
                <!-- Référent Jeunes -->
                <div class="grid grid-rows-[auto_2fr_auto] gap-4 justify-center text-center p-4">
                    <p class="underline">R&eacute;f&eacute;rent Jeunes</p>
                    <div class="row-span-1">
                        <?php echo wp_get_attachment_image(151, '', false, array(
                            'loading' => 'lazy',
                            'class' => "w-1/2 m-auto rounded-2xl transform transition duration-300 ease-in-out hover:scale-105 row-span-2",
                        )); ?>
                    </div>
                    <p class="row-span-1">Jean Dupont</p>
                </div>
                <!-- Référent Adultes -->
                <div class="grid grid-rows-[auto_2fr_auto] gap-4 justify-center text-center p-4">
                    <p class="underline">R&eacute;f&eacute;rent Adultes</p>
                    <div class="row-span-1">
                        <?php echo wp_get_attachment_image(151, '', false, array(
                            'loading' => 'lazy',
                            'class' => "w-1/2 m-auto rounded-2xl transform transition duration-300 ease-in-out hover:scale-105 row-span-2",
                        )); ?>
                    </div>
                    <p class="row-span-1">Jean Dupont</p>
                </div>
                <!-- Un membre -->
                <div class="grid grid-rows-[auto_2fr_auto] gap-4 justify-center text-center p-4">
                    <p class="underline">Pr&eacute;sident</p>
                    <div class="row-span-1">
                        <?php echo wp_get_attachment_image(151, '', false, array(
                            'loading' => 'lazy',
                            'class' => "w-1/2 m-auto rounded-2xl transform transition duration-300 ease-in-out hover:scale-105 row-span-2",
                        )); ?>
                    </div>
                    <p class="row-span-1">Jean Dupont</p>
                </div>
                <!-- Un membre -->
                <div class="grid grid-rows-[auto_2fr_auto] gap-4 justify-center text-center p-4">
                    <p class="underline">Pr&eacute;sident</p>
                    <div class="row-span-1">
                        <?php echo wp_get_attachment_image(151, '', false, array(
                            'loading' => 'lazy',
                            'class' => "w-1/2 m-auto rounded-2xl transform transition duration-300 ease-in-out hover:scale-105 row-span-2",
                        )); ?>
                    </div>
                    <p class="row-span-1">Jean Dupont</p>
                </div>
            </div>

            <!--Btn voir all bureaux-->
            <div class="grid place-items-center">
                <button type="button"
                        class="<?= $classBtn ?> my-16">
                    <a href="<?php the_permalink(151); ?>" class="flex items-center">Voir les bureaux des années précédentes
                        <svg class="w-[30px] h-[30px]" aria-hidden="true"
                             xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                  stroke-width="2.5" d="M19 12H5m14 0-4 4m4-4-4-4"/>
                        </svg>
                    </a>
                </button>
            </div>
        </div>
    </section>

<?php
get_footer();
