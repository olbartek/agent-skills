// Theme entry point. Add behavior here.
document.addEventListener('DOMContentLoaded', () => {
  const toggle = document.querySelector('.site-nav__toggle');
  const menu   = document.querySelector('#site-nav-menu');
  if (!toggle || !menu) return;
  toggle.addEventListener('click', () => {
    const open = toggle.getAttribute('aria-expanded') === 'true';
    toggle.setAttribute('aria-expanded', String(!open));
    menu.style.display = open ? '' : 'flex';
  });
});
