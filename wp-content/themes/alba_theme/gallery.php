<?php
/* Template Name: gallery */
get_header();

$seasons = get_gallery_events(); // $seasons => [2024-2025] -> $events => [id, title, image_ids (array)]

//// Structure de $seasons
// [2024-2025] => Array
//        (
//            [0] => Array
//                (
//                    [id] => 498
//                    [title] => Tournoi annuel
//                    [image_ids] => Array
//                        (
//                            [0] => 331
//                            ...
//                        )
//                )
//        )
//showVar($seasons);
?>
    <style>
        p {
            font-size: 1.25rem /* 20px */;
            line-height: 1.75rem /* 28px */;
        }
    </style>

    <section>

        <?= display_titlePage() ?>

        <?php

        if (!empty($seasons) && is_array($seasons)) {
            foreach ($seasons as $key => $season) {
                echo "<div class='mb-4'>";
                echo "<h2 id='$key' class='text-3xl font-bold my-12 underline decoration-primary-blue'>Saison " . $key . "</h2>";

                if (!empty($season)) {
                    foreach ($season as $event) {
                        echo "<div>";
                        echo "<h3 class='text-2xl font-bold mt-12 mb-6 underline text-primary-blue' id=" . sanitize_title(str_replace(' ', '-', $event['title'])) . ">{$event['title']}</h3>";

                        if (!empty($event['image_ids'])) {
                            echo '<div class="flex flex-wrap justify-around gap-2 md:gap-6 gap-y-4 lg:gap-y-12 items-center">';
                            foreach ($event['image_ids'] as $id_img) {
                                echo wp_get_attachment_image($id_img, '', false, array(
                                    'loading' => 'lazy',
                                    'class' => 'w-48 sm:w-96 md:w-1/6 rounded-2xl lightbox-trigger cursor-pointer',
                                    'data-full-size' => wp_get_attachment_image_src($id_img, 'large')[0]
                                ));
                            }
                            echo '</div>';
                        } else {
                            echo "<p>Aucune image pour l'événement {$event['title']}</p>";
                        }
                        echo "</div>";
                    }
                } else {
                    echo "<p>Aucun événement pour la saison {$key}</p>";
                }
                echo "</div>";
            }
        } else {
            echo "<p>Aucune saison n'a été définie pour le moment. <span class='italic'>Patience... 😁</span></p>";
        } ?>

    </section>

<?php

get_footer();