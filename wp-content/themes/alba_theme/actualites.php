<?php
/* Template Name: actualites */

global $alba_theme_variables;
get_header();

$category = $_GET['categoryName'] ?? null;
//var_dump($category);
//echo '<pre>';
//var_dump(get_categories());
//echo '</pre>';
// get_categories()[0]['name']
$actif = ' bg-primary-blue text-white ';
?>

    <section>
        <?= display_titlePage() ?>

        <h2 class="text-2xl underline decoration-primary-blue">Filtrer les actualités par catégories :</h2>

        <div class="my-8 font-semibold">
            <?php foreach (get_categories() as $cat) :
                if ($cat->slug == 'non-classe')  {
                    continue;
                }
                    ?>
                <a href="<?= get_permalink(26) ?>?categoryName=<?= $cat->slug ?>"
                   class="<?php echo $alba_theme_variables['animBase'];
                   echo $category == $cat->slug ? $actif : '' ?>
                   rounded-lg px-5 py-2.5 text-center me-2 text-primary-blue hover:text-white border border-primary-blue hover:bg-primary-blue focus:ring-4 focus:outline-none focus:ring-blue-300 dark:border-blue-500 dark:text-blue-500 dark:hover:text-white dark:hover:bg-blue-500 dark:focus:ring-primary-blue"
                   title="<?= $cat->category_description ?>">
                    <?= $cat->name ?>
                </a>
            <?php endforeach; ?>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php
            $args = array(
                'post_type' => 'post',
                'posts_per_page' => 10,
                'category_name' => $category,
            );
            $query = new WP_Query($args);

            if ($query->have_posts()) :
                $i = 0;
                while ($query->have_posts()) : $query->the_post(); ?>
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden <?= $alba_theme_variables['animCardNews'] ?>">
                        <a href="<?php the_permalink(); ?>">
                            <?php if (has_post_thumbnail()) : ?>
                                <img src="<?php the_post_thumbnail_url(); ?>" alt="<?php the_title(); ?>"
                                     class="object-cover object-center w-full h-64 md:h-96" loading="lazy">
                            <?php endif; ?>
                            <div class="p-4">
                                <h2 class="text-xl font-bold text-primary-blue underline"><?php the_title(); ?></h2>
                                <h6 class="subtitle"><?= get_the_date(); ?></h6>
                                <p class="text-gray-700 text-center"><?php the_excerpt(); ?></p>
                            </div>
                        </a>
                    </div>
                <?php endwhile;
                wp_reset_postdata();
            else : ?>
                <p>Aucun article trouvé.</p>
            <?php endif; ?>
        </div>
    </section>
<?php get_footer(); ?>