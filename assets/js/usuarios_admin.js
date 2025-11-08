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
