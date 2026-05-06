<?php if (!defined('ABSPATH')) { exit; } ?>
</main>

<footer class="site-footer">
  <div class="site-footer__inner">
    <span>&copy; <?php echo esc_html(date('Y')); ?> <?php bloginfo('name'); ?></span>
    <?php wp_nav_menu([
        'theme_location' => 'footer',
        'menu_class'     => 'site-footer__menu',
        'container'      => false,
        'fallback_cb'    => '__return_empty_string',
    ]); ?>
  </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
