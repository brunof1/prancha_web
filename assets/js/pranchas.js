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
