<?php
/* Template Name: pdc */
get_header();
?>

<h2 class="<?= $classTitle ?>"><?php the_title(); ?></h2>

  <div class="prose">
	  <?php the_content(); ?>
  </div>

<?php
get_footer();
