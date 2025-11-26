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

// assets/js/drop.js
(function(){
  function setup(detail){
  if (!detail) return;
  const summary = detail.querySelector('summary');

  // sempre começamos alinhando à direita do botão
  detail.classList.add('align-right');

  function markHost(open){
    const host = detail.closest('.prancha-item, .cartao-item');
    if (!host) return;
    host.classList.toggle('is-drop-open', !!open);
  }

  // calcula se o painel está vazando do container e vira a âncora
  function adjustPosition(){
    const panel = detail.querySelector('.acoes-drop__panel');
    if (!panel) return;

    // reset de estilo temporário
    panel.style.maxWidth = '';
    detail.classList.remove('align-left','align-right');
    detail.classList.add('align-right'); // padrão

    // mede após aplicar o padrão
    const cont = detail.closest('.container') || document.body;
    const r  = panel.getBoundingClientRect();
    const rc = cont.getBoundingClientRect();

    const margem = 8; // respiro interno

    // Se passou para fora pela ESQUERDA do container → ancora à esquerda
    if (r.left < rc.left + margem){
      detail.classList.remove('align-right');
      detail.classList.add('align-left');
      // mede de novo já ancorado à esquerda
      const r2 = panel.getBoundingClientRect();
      // se mesmo assim não couber, limita a largura ao container
      if (r2.right > rc.right - margem){
        panel.style.maxWidth = Math.max(180, rc.width - 2*margem) + 'px';
      }
      return;
    }

    // Se estourar pela DIREITA do container mesmo ancorado à direita → limita largura
    if (r.right > rc.right - margem){
      panel.style.maxWidth = Math.max(180, rc.width - 2*margem) + 'px';
    }
  }

  const sync = () => {
    const isOpen = detail.hasAttribute('open');
    if (summary) summary.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
    markHost(isOpen);
    if (isOpen) {
      // aguarda o repaint para medir com o painel visível
      requestAnimationFrame(adjustPosition);
    }
  };

  detail.addEventListener('toggle', sync);
  sync();
}

  function closeAll(except){
    document.querySelectorAll('details.acoes-drop[open]').forEach(d => {
      if (d !== except) {
        d.removeAttribute('open');
        // garantir remoção da classe do host
        const host = d.closest('.prancha-item, .cartao-item');
        host && host.classList.remove('is-drop-open');
      }
    });
  }

  document.addEventListener('click', (ev) => {
    const open = document.querySelector('details.acoes-drop[open]');
    if (!open) return;
    if (!open.contains(ev.target)) {
      open.removeAttribute('open');
      const s = open.querySelector('summary'); s && s.focus();
      const host = open.closest('.prancha-item, .cartao-item');
      host && host.classList.remove('is-drop-open');
    }
  });

  document.addEventListener('keydown', (ev) => {
    if (ev.key !== 'Escape') return;
    const open = document.querySelector('details.acoes-drop[open]');
    if (!open) return;
    open.removeAttribute('open');
    const s = open.querySelector('summary'); s && s.focus();
    const host = open.closest('.prancha-item, .cartao-item');
    host && host.classList.remove('is-drop-open');
  });

  // fecha outros quando abrir um
  document.addEventListener('toggle', (ev) => {
    const det = ev.target;
    if (!(det instanceof HTMLDetailsElement)) return;
    if (!det.classList.contains('acoes-drop')) return;
    if (det.hasAttribute('open')) closeAll(det);
  }, true);

  // inicializa os existentes
  document.querySelectorAll('details.acoes-drop').forEach(setup);

  // observer para elementos criados dinamicamente
  const mo = new MutationObserver((muts) => {
    muts.forEach(m => m.addedNodes && m.addedNodes.forEach(n => {
      if (n.nodeType === 1 && n.matches?.('details.acoes-drop')) setup(n);
      if (n.querySelectorAll) n.querySelectorAll('details.acoes-drop').forEach(setup);
    }));
  });
  mo.observe(document.documentElement, { childList: true, subtree: true });
})();
