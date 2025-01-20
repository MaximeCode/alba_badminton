<?php
/* Template Name: pdc */
get_header();
?>

<?= display_titlePage() ?>

  <div class="prose text-justify">
	  <?php the_content(); ?>
  </div>

<?php
get_footer();
