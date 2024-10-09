<?php get_header() ?>

Content

<!--    <main class="container">-->
<!--        <h1 class="text-3xl font-bold text-center mt-12">Latest Posts</h1>-->
<!--        <div class="grid grid-cols-1 gap-6 mt-6 sm:grid-cols-2 lg:grid-cols-3">-->
<!--			--><?php //if ( have_posts() ) : ?>
<!--				--><?php //while ( have_posts() ) : the_post(); ?>
<!--                    <article class="bg-white dark:bg-gray-800 rounded-lg shadow">-->
<!--                        <a href="--><?php //the_permalink() ?><!--">-->
<!--                            <img src="--><?php //the_post_thumbnail_url() ?><!--" alt="--><?php //the_title() ?><!--"-->
<!--                                 class="object-cover w-full h-48 rounded-t-lg">-->
<!--                        </a>-->
<!--                        <div class="p-6">-->
<!--                            <a href="--><?php //the_permalink() ?><!--"-->
<!--                               class="block text-lg font-semibold text-gray-800 dark:text-gray-100 hover:underline">--><?php //the_title() ?><!--</a>-->
<!--                            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">--><?php //the_excerpt() ?><!--</p>-->
<!--                        </div>-->
<!--                    </article>-->
<!--				--><?php //endwhile; ?>
<!--			--><?php //else : ?>
<!--                <p class="text-center">No posts found</p>-->
<!--			--><?php //endif; ?>
<!--        </div>-->
<!--    </main>-->

<?php get_footer() ?>