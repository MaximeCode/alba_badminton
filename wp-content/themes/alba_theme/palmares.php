<?php
/* Template Name: Palmares */
get_header();

global $alba_theme_variables;
?>

    <section>
        <?= display_titlePage() ?>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <?php
            $args = array(
                'post_type' => 'post',
                'posts_per_page' => -1,
                'orderby' => 'date',
                'order' => 'DESC',
                'category_name' => 'palmares'
            );

            $query = new WP_Query($args);

            if ($query->have_posts()) {
                while ($query->have_posts()) {
                    $query->the_post();
                    ?>
                    <a href="<?= get_the_permalink() ?>"
                       class="bg-white rounded-2xl shadow-2xl p-3 sm:p-5 grid grid-cols-1 gap-4 sm:grid-cols-2 place-content-between hover:scale-105 <?= $alba_theme_variables['animBase'] ?>">
                        <div>
                            <h2 class="text-2xl mb-4 text-primary-blue underline decoration-primary-blue"><?= get_the_title() ?></h2>
                            <p><?= get_the_excerpt() ?></p>
                        </div>
                        <div class="flex justify-end">
                            <?= wp_get_attachment_image(get_post_thumbnail_id(), 'medium', false, [
                                'loading' => 'lazy',
                                'class' => 'rounded-xl max-h-48 object-cover object-center'
                            ]) ?>
                        </div>
                    </a>
                    <?php
                }
                wp_reset_postdata();
            } else {
                echo 'Aucun article concernant le palmarès des équipes d\'interclubs n\'a été trouvé.';
            }
            ?>
        </div>
    </section>

<?php
get_footer();
