<?php get_header(); ?>

<main>
    <section class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-4">Résultats de recherche pour : <?= get_search_query(); ?></h1>

        <?php if (have_posts()) : ?>
            <ul class="space-y-4">
                <?php while (have_posts()) : the_post(); ?>
                    <li>
                        <a href="<?php the_permalink(); ?>" class="text-xl text-blue-500 hover:underline">
                            <?php the_title(); ?>
                        </a>
                        <p class="text-gray-600"><?= get_the_excerpt(); ?></p>
                    </li>
                <?php endwhile; ?>
            </ul>
        <?php else : ?>
            <p class="text-gray-600 mb-6">Aucun résultat trouvé. Essayez une autre recherche.</p>
            <div class="max-w-md">
                <?= get_search_form() ?>
            </div>
        <?php endif; ?>
    </section>
</main>

<?php get_footer(); ?>
