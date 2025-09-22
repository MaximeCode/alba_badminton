<?php
get_header();
$search = get_search_query();

// Requêtes personnalisées pour séparer les pages et les articles
$pages_query = new WP_Query([
        'post_type' => 'page',
        's' => $search,
        'posts_per_page' => -1,
]);

$posts_query = new WP_Query([
        'post_type' => 'post',
        's' => $search,
        'posts_per_page' => -1,
]);

$has_results = ($pages_query->have_posts() || $posts_query->have_posts());
?>

<main>
    <section class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-4">Résultats de recherche pour :
            <mark class="bg-yellow-200"><?= $search ?></mark>
        </h1>

        <?php if ($has_results) : ?>
            <?php if ($pages_query->have_posts()) : ?>
                <h2 class="text-2xl font-bold mt-6 mb-4 text-primary-blue underline">Pages</h2>
                <ul class="space-y-4">
                    <?php while ($pages_query->have_posts()) : $pages_query->the_post(); ?>
                        <?php
                        $title = get_the_title();
                        $excerpt = get_the_excerpt();

                        if (!empty($search)) {
                            $title = preg_replace('/(' . preg_quote($search, '/') . ')/i', '<mark class="bg-yellow-200">$1</mark>', $title);
                            $excerpt = preg_replace('/(' . preg_quote($search, '/') . ')/i', '<mark class="bg-yellow-200">$1</mark>', $excerpt);
                        }

                        if (htmlspecialchars($excerpt) === htmlspecialchars(get_the_excerpt())) {
                            // Replace the last word of the excerpt with <mark>[...]</mark>
                            $words = explode(' ', $excerpt);
                            if (count($words) > 0) {
                                $words[count($words) - 1] = '[<mark class="bg-yellow-200">...</mark>]';
                                $excerpt = implode(' ', $words);
                            }
                        }
                        ?>
                        <li>
                            <a href="<?php the_permalink(); ?>" class="text-xl text-blue-500 hover:underline">
                                <?= $title ?>
                            </a>
                            <p class="text-gray-600"><?= $excerpt ?></p>
                        </li>
                    <?php endwhile; ?>
                    <?php wp_reset_postdata(); ?>
                </ul>
            <?php endif; ?>

            <?php if ($posts_query->have_posts()) : ?>
                <h2 class="text-2xl font-bold mt-6 mb-4 text-primary-blue underline">Articles</h2>
                <ul class="space-y-4">
                    <?php while ($posts_query->have_posts()) : $posts_query->the_post(); ?>
                        <?php
                        $title = get_the_title();
                        $excerpt = get_the_excerpt();

                        if (!empty($search)) {
                            $title = preg_replace('/(' . preg_quote($search, '/') . ')/i', '<mark class="bg-yellow-200">$1</mark>', $title);
                            $excerpt = preg_replace('/(' . preg_quote($search, '/') . ')/i', '<mark class="bg-yellow-200">$1</mark>', $excerpt);
                        }
                        ?>
                        <li>
                            <a href="<?php the_permalink(); ?>" class="text-xl text-blue-500 hover:underline">
                                <?= $title ?>
                            </a>
                            <p class="text-gray-600"><?= $excerpt ?></p>
                        </li>
                    <?php endwhile; ?>
                    <?php wp_reset_postdata(); ?>
                </ul>
            <?php endif; ?>
        <?php else : ?>
            <p class="text-gray-600 mb-6">Aucun résultat trouvé. Essayez une autre recherche.</p>
            <div class="max-w-md">
                <?= get_search_form() ?>
            </div>
        <?php endif; ?>
    </section>
</main>

<?php get_footer(); ?>
