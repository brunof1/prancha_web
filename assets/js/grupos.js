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

// assets/js/grupos.js
(function(){
  const grupos = Array.from(document.querySelectorAll('.grupo-exp'));
  if (!grupos.length) return;

  grupos.forEach((det) => {
    det.addEventListener('toggle', () => {
      if (!det.open) return;
      // fecha os demais
      grupos.forEach(d => { if (d !== det && d.open) d.open = false; });
      // sincroniza aria-expanded no summary
      const s = det.querySelector('summary.grupo-chip');
      if (s) s.setAttribute('aria-expanded', 'true');
    });
    const s = det.querySelector('summary.grupo-chip');
    if (s) s.setAttribute('aria-expanded', det.open ? 'true' : 'false');
  });

  // Clique fora fecha o aberto
  document.addEventListener('click', (ev) => {
    const aberto = grupos.find(d => d.open);
    if (!aberto) return;
    if (!aberto.contains(ev.target)) aberto.open = false;
  });

  // ESC fecha o aberto e devolve o foco para o chip
  document.addEventListener('keydown', (ev) => {
    if (ev.key !== 'Escape') return;
    const aberto = grupos.find(d => d.open);
    if (!aberto) return;
    const chip = aberto.querySelector('summary.grupo-chip');
    aberto.open = false;
    if (chip) chip.focus();
  });
})();
