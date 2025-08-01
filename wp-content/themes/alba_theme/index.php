<?php get_header() ?>

<?php
if (have_posts()) {
  while (have_posts()) {
    the_post();
    echo '<h2 class="text-4xl font-bold mb-12">' . get_the_title() . '</h2>';
    echo "<div class='prose text-lg lg:text-xl'>";
    the_content();
    echo "</div>";
  }
}
?>
<?php get_footer() ?>