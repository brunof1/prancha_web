// assets/js/pranchas.js
// - Confirmações de exclusão (prancha e grupo) sem JS inline
// - Botão "Falar" usando window.falar() (já provido por assets/js/falar.js)

document.addEventListener('DOMContentLoaded', () => {
  // Delegação para exclusão de PRANCHA
  document.body.addEventListener('click', (ev) => {
    const link = ev.target.closest('a[data-action="excluir-prancha"]');
    if (!link) return;

    ev.preventDefault();
    const id = link.dataset.id || '';
    const nome = link.dataset.nome || '';
    const ok = confirm(`Tem certeza que deseja excluir a prancha #${id}${nome ? ` (${nome})` : ''}? Esta ação é irreversível.`);
    if (ok) window.location.href = link.getAttribute('href');
  });

  // Delegação para exclusão de GRUPO
  document.body.addEventListener('click', (ev) => {
    const link = ev.target.closest('a[data-action="excluir-grupo-prancha"]');
    if (!link) return;

    ev.preventDefault();
    const id = link.dataset.id || '';
    const nome = link.dataset.nome || '';
    const ok = confirm(`Excluir o grupo de pranchas #${id}${nome ? ` (${nome})` : ''}? As pranchas continuarão existindo, apenas sem este agrupamento (se sua modelagem assim permitir).`);
    if (ok) window.location.href = link.getAttribute('href');
  });

  // Botão "Falar" do item de prancha
  document.body.addEventListener('click', (ev) => {
    const btn = ev.target.closest('a[data-action="falar-prancha"]');
    if (!btn) return;

    ev.preventDefault();
    const texto = btn.dataset.texto || '';
    if (typeof window.falar === 'function') {
      window.falar(texto);
    }
  });
});
