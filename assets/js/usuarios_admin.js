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

// assets/js/usuarios_admin.js
function confirmarExclusao(id){
  return confirm('Tem certeza que deseja excluir o usuário #' + id + '? Esta ação é irreversível.');
}

document.addEventListener('DOMContentLoaded', () => {
  // Intercepta qualquer form com data-action="excluir-usuario"
  document.body.addEventListener('submit', (ev) => {
    const form = ev.target.closest('form[data-action="excluir-usuario"]');
    if (!form) return;

    const idInput = form.querySelector('input[name="id"]');
    const id = idInput ? idInput.value : '';
    const msg = form.getAttribute('data-confirm') || `Excluir o usuário #${id}?`;
    if (!confirm(msg)) {
      ev.preventDefault();
      ev.stopPropagation();
    }
  });
});
