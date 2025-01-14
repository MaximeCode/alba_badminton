<?php
/* Template Name: gallery */
get_header();

$seasons = get_post_meta(get_the_ID(), 'custom_seasons', true);
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
            foreach ($seasons as $season) {
                echo "<div class='mb-4'>";
                echo "<h2 id='{$season['title']}' class='text-3xl font-bold my-12 underline decoration-primary-blue'>Saison " . str_replace('-', ' - ', $season['title']) . "</h2>";

                if (isset($season['events']) && is_array($season['events'])) {
                    foreach ($season['events'] as $event) {

                        echo "<div>";
                        echo "<h3 class='text-2xl font-bold mt-12 mb-6 underline text-primary-blue' id=" . sanitize_title(str_replace(' ', '-', $event['title'])) . ">{$event['title']}</h3>";

                        if (isset($event['images'])) {
                            echo '<div class="flex flex-wrap justify-around gap-2 md:gap-6 gap-y-4 lg:gap-y-12 items-center">';
                            foreach ($event['images'] as $id_img) {
                                echo wp_get_attachment_image($id_img, 'medium', false, array(
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
                    echo "<p>Aucun événement pour la saison {$season['title']}</p>";
                }
                echo "</div>";
            }
        } else {
            echo "<p>Aucune saison n'a été définie pour cette galerie.</p>";
        }
        ?>

    </section>

<?php

get_footer();