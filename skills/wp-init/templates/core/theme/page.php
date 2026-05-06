<?php if (!defined('ABSPATH')) { exit; } get_header(); ?>
<article class="wrap page">
  <?php while (have_posts()) : the_post(); ?>
    <header><h1 class="page-title"><?php the_title(); ?></h1></header>
    <div class="page-content"><?php the_content(); ?></div>
  <?php endwhile; ?>
</article>
<?php get_footer(); ?>
