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
