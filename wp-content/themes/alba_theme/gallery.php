<?php
/* Template Name: gallery */
get_header();

// ID de la page => 166

//$theTitle = rwmb_meta('titre_de_l_evenement');
//$theImages = rwmb_meta('les_images');
?>
    <style>
        p {
            font-size: 1.25rem /* 20px */;
            line-height: 1.75rem /* 28px */;
        }
    </style>

    <h2 class="<?= $classTitle ?>"><?php the_title(); ?></h2>

<?php
$seasons = get_post_meta(get_the_ID(), 'custom_seasons', true);

if (isset($seasons) && !empty($seasons) && is_array($seasons)) {
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
                            'class' => 'w-48 sm:w-96 md:w-1/6 rounded-2xl',
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

<?php
//// Vérifiez si ACF est activé
//if (function_exists('get_field')) {
//    // Récupérer les données du champ ACF
//    $categories_evenements = get_field('categories_evenements');
//
//    if (is_array($categories_evenements)) {
//        // Récupération du titre de la catégorie
//        $titre_categorie = $categories_evenements['titre_categorie'] ?? 'Titre non défini';
//        echo '<h3>' . esc_html($titre_categorie) . '</h3>';
//
//        // Récupération des images de la catégorie
//        $image_id = $categories_evenements['images_categorie'];
//        if ($image_id) {
//            echo wp_get_attachment_image($image_id, 'large');
//        } else {
//            echo 'Les images de la catégorie ne sont pas disponibles.';
//        }
//    } else {
//        echo 'Les données de la catégorie ne sont pas disponibles.';
//    }
//}

get_footer();