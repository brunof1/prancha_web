// assets/js/menu.js
(function(){
  const drop = document.querySelector('.menu-drop');
  if (!drop) return;

  const summary = drop.querySelector('summary');

  // Sincroniza aria-expanded com o estado do <details>
  function syncAria(){
    summary?.setAttribute('aria-expanded', drop.hasAttribute('open') ? 'true' : 'false');
  }
  drop.addEventListener('toggle', syncAria);
  syncAria();

  // Fecha com ESC e clique fora (qualquer layout)
  document.addEventListener('keydown', (ev) => {
    if (ev.key === 'Escape' && drop.hasAttribute('open')) {
      drop.removeAttribute('open');
      summary?.focus();
    }
  });
  document.addEventListener('click', (ev) => {
    if (!drop.hasAttribute('open')) return;
    if (!drop.contains(ev.target)) {
      drop.removeAttribute('open');
      syncAria();
    }
  });
})();
