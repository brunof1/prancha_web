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

// assets/js/pranchas.js
// - Confirmações de exclusão (prancha e grupo) sem JS inline

document.addEventListener('DOMContentLoaded', () => {
  // Exclusão de PRANCHA
  document.body.addEventListener('click', (ev) => {
    const link = ev.target.closest('a[data-action="excluir-prancha"]');
    if (!link) return;

    ev.preventDefault();
    const id = link.dataset.id || '';
    const nome = link.dataset.nome || '';
    const ok = confirm(`Tem certeza que deseja excluir a prancha #${id}${nome ? ` (${nome})` : ''}? Esta ação é irreversível.`);
    if (ok) window.location.href = link.getAttribute('href');
  });

  // Exclusão de GRUPO DE PRANCHA (se existir botão na tela)
  document.body.addEventListener('click', (ev) => {
    const link = ev.target.closest('a[data-action="excluir-grupo-prancha"]');
    if (!link) return;

    ev.preventDefault();
    const id = link.dataset.id || '';
    const nome = link.dataset.nome || '';
    const ok = confirm(`Excluir o grupo de pranchas #${id}${nome ? ` (${nome})` : ''}?`);
    if (ok) window.location.href = link.getAttribute('href');
  });
});
