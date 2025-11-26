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

document.addEventListener('DOMContentLoaded', function () {
  const grid = document.querySelector('.lista-cartoes');
  const ordemInput = document.getElementById('ordem_cartoes');
  if (!grid || !ordemInput) return;

  // Todos os checkboxes de cartões
  const checkboxes = grid.querySelectorAll('input[name="cartoes[]"]');
  let ordem = [];

  // Acessibilidade: cartões focáveis com papel de checkbox
  grid.querySelectorAll('.cartao-item').forEach(item => {
    item.setAttribute('tabindex', '0');
    item.setAttribute('role', 'checkbox');
    const cb = item.querySelector('input[name="cartoes[]"]');
    item.setAttribute('aria-checked', cb && cb.checked ? 'true' : 'false');
  });

  // Se a página fornecer ordem inicial (editar_prancha), aplica
  (function aplicarOrdemInicial() {
    const data = grid.getAttribute('data-ordem-inicial');
    if (!data) {
      // Sem ordem inicial: se já vierem marcados, usa a ordem do DOM
      checkboxes.forEach(cb => { if (cb.checked) ordem.push(String(cb.value)); });
      return;
    }
    try {
      const arr = JSON.parse(data);
      if (Array.isArray(arr)) {
        ordem = arr.map(String);
        // garante que os checkboxes respectivos fiquem marcados
        ordem.forEach(id => {
          const cb = grid.querySelector('input[name="cartoes[]"][value="' + id + '"]');
          if (cb) cb.checked = true;
        });
      }
    } catch (_) { /* ignora */ }
  })();

  function ensureBadge(wrapper) {
    let b = wrapper.querySelector('.ordem-badge');
    if (!b) {
      b = document.createElement('span');
      b.className = 'ordem-badge';
      b.setAttribute('aria-hidden', 'true');
      wrapper.appendChild(b);
    }
    return b;
  }

  function setItemAria(item, checked){
    item.setAttribute('aria-checked', checked ? 'true' : 'false');
  }

  function refreshVisuals() {
    // limpa tudo
    grid.querySelectorAll('.cartao-item').forEach(item => {
      item.classList.remove('is-selected');
      const badge = ensureBadge(item);
      badge.textContent = '';
      badge.style.display = 'none';          // esconde quando não selecionado
      item.removeAttribute('data-ordem');

      // sincroniza ARIA com o estado atual do checkbox
      const cb = item.querySelector('input[name="cartoes[]"]');
      setItemAria(item, !!(cb && cb.checked));
    });

    // aplica numeração conforme "ordem"
    ordem.forEach((id, idx) => {
      const cb = grid.querySelector('input[name="cartoes[]"][value="' + id + '"]');
      if (!cb) return;
      const item = cb.closest('.cartao-item');
      if (!item) return;
      const badge = ensureBadge(item);

      item.classList.add('is-selected');
      item.setAttribute('data-ordem', String(idx + 1));
      badge.textContent = String(idx + 1);
      badge.style.display = 'inline-flex';   // mostra e centraliza com flex

      setItemAria(item, true);
    });

    ordemInput.value = ordem.join(',');
  }

  function toggleViaCheckbox(cb) {
    const id = String(cb.value);
    if (cb.checked) {
      if (!ordem.includes(id)) ordem.push(id);
    } else {
      ordem = ordem.filter(x => x !== id);
    }
    // sincroniza ARIA do cartão correspondente
    const item = cb.closest('.cartao-item');
    if (item) setItemAria(item, cb.checked);

    refreshVisuals();
  }

  // Mudança direta no checkbox (inclusive clique no rótulo)
  checkboxes.forEach(cb => {
    cb.addEventListener('change', function () {
      toggleViaCheckbox(cb);
    });
  });

  // Clique em QUALQUER lugar do cartão alterna o checkbox
  grid.addEventListener('click', function (ev) {
    const item = ev.target.closest('.cartao-item');
    if (!item) return;

    // Se clicou no próprio input/label, deixa o evento padrão agir
    if (ev.target.tagName === 'INPUT') return;
    const label = ev.target.closest('label.cartao-checkbox');
    if (label) return;

    const cb = item.querySelector('input[name="cartoes[]"]');
    if (!cb) return;
    ev.preventDefault();
    cb.checked = !cb.checked;
    setItemAria(item, cb.checked);
    toggleViaCheckbox(cb);
  }, false);

  // Teclado (Enter/Espaço no cartão)
  grid.addEventListener('keydown', function (ev) {
    if (ev.key !== 'Enter' && ev.key !== ' ') return;
    const item = ev.target.closest('.cartao-item');
    if (!item) return;
    const cb = item.querySelector('input[name="cartoes[]"]');
    if (!cb) return;
    ev.preventDefault();
    cb.checked = !cb.checked;
    setItemAria(item, cb.checked);
    toggleViaCheckbox(cb);
  });

  // Primeiro desenho
  refreshVisuals();
});
