/**
 * Prancha Web
 * Plataforma Web de Comunicação Alternativa e Aumentativa (CAA)
 *
 * Copyright (c) 2025 Bruno Silva da Silva
 *
 * Este arquivo faz parte do projeto Prancha Web.
 *
 * Licenciamento duplo:
 * - Apache License 2.0
 * - GNU General Public License v3.0 (GPLv3)
 *
 * Você pode redistribuir e/ou modificar este arquivo sob os termos de
 * qualquer uma das licenças, a seu critério, desde que cumpra integralmente
 * os respectivos requisitos.
 *
 * Você deve ter recebido uma cópia das licenças junto com este programa.
 * Caso contrário, veja:
 * - https://www.apache.org/licenses/LICENSE-2.0
 * - https://www.gnu.org/licenses/gpl-3.0.html
 */

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
