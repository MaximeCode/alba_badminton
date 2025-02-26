<?php
if (post_password_required()) {
    return;
}

function my_custom_comment_callback($comment, $args, $depth): void
{
    $tag = ($args['style'] === 'div') ? 'div' : 'li';
    ?>
    <<?php echo $tag; ?> id="comment-<?php comment_ID(); ?>" <?php comment_class('mb-6 border-b border-primary-blue pb-4'); ?>>
    <div>
        <?php if ($args['avatar_size'] != 0) echo get_avatar($comment, $args['avatar_size'], '', '', ['class' => 'rounded-full']); ?>
        <div>
            <p class="font-bold mt-2"><?php comment_author_link(); ?></p>
            <p class="text-sm text-gray-500"><?php comment_date(); ?> à <?php comment_time(); ?></p>
            <div class="mt-2">
                <?php comment_text(); ?>
            </div>
        </div>
    </div>
    </<?php echo $tag; ?>>
    <?php
}

$user_identity = wp_get_current_user()->display_name;

?>


<div id="comments">
    <?php if (have_comments()) : ?>
        <h2 class="comments-title text-2xl font-bold mb-6">
            <?php
            printf(
                esc_html(_n('%1$s Commentaire', '%1$s Commentaires', get_comments_number(), 'textdomain')),
                number_format_i18n(get_comments_number())
            );
            ?>
        </h2>

        <ol class="comment-list">
            <?php
            wp_list_comments(array(
                'style' => 'ol',
                'short_ping' => true,
                'avatar_size' => 60,
                'callback' => 'my_custom_comment_callback',
            ));
            ?>
        </ol>

        <?php the_comments_navigation(); ?>

    <?php endif; ?>

    <?php if (!comments_open() && get_comments_number() && post_type_supports(get_post_type(), 'comments')) : ?>
        <p class="no-comments"><?php esc_html_e('Les commentaires sont fermés.', 'textdomain'); ?></p>
    <?php endif; ?>

    <div class="mb-2 flex items-center gap-6">
        <?php if (is_user_logged_in()) : ?>
            <div>
                Connecté en tant que <span
                        class="underline decoration-primary-blue"><?php echo $user_identity; ?></span>
            </div>
            <a href="<?php echo wp_logout_url(get_permalink()); ?>" title="Se déconnecter"
               class="hover:text-white border border-primary-blue hover:bg-primary-blue focus:ring-4 focus:outline-none focus:ring-blue-300
               font-medium rounded-lg text-sm px-3 py-2 text-center transition">
                Se déconnecter
            </a>
        <?php endif; ?>
    </div>

    <?php
    comment_form(array(
        'title_reply' => '<span class="text-xl font-bold">Laisser un commentaire</span>',
        'class_submit' => 'bg-primary-blue text-white px-4 py-2 rounded-lg hover:bg-primary-dark transition',
        'comment_notes_before' => '<p class="text-sm text-gray-500">Votre adresse e-mail ne sera pas publiée.</p>',
        'comment_field' => '<textarea id="comment" name="comment" class="w-full border border-gray-300 rounded-lg p-2 mt-2" rows="5" required></textarea>',
    ));
    ?>
</div>
