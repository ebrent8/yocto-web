<?php get_template_part('templates/page', 'header'); ?>

<div class="container">
  <div class="content row page-content">
    <div class="col-xs-12">
      <div class="content-block">

<?php if (!have_posts()) : ?>
  <div class="alert alert-warning">
    <?php _e('Sorry, no results were found.', 'sage'); ?>
  </div>
  <?php get_search_form(); ?>
<?php endif; ?>

<?php while (have_posts()) : the_post(); ?>
  <?php get_template_part('templates/content', 'search'); ?>
<?php endwhile; ?>

<?php the_posts_navigation(); ?>
	  </div>
	</div>
  </div>
</div>