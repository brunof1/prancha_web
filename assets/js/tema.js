// ../assets/js/tema.js
(function () {
  const btn = document.getElementById('toggle-tema');
  if (!btn) return;

  function labelDoTema(theme) {
    return theme === 'dark' ? '🌞 Modo claro' : '🌙 Modo escuro';
  }

  function temaAtual() {
    return document.body.getAttribute('data-theme') === 'dark' ? 'dark' : 'light';
  }

  function aplicarA11y(theme) {
    btn.textContent = labelDoTema(theme);
    btn.setAttribute('aria-pressed', theme === 'dark' ? 'true' : 'false');
    btn.setAttribute('aria-label', btn.textContent);
  }

  aplicarA11y(temaAtual());

  btn.addEventListener('click', async () => {
    const novo = temaAtual() === 'dark' ? 'light' : 'dark';
    document.body.setAttribute('data-theme', novo);
    aplicarA11y(novo);

    try {
      const res = await fetch('../includes/atualizar_tema.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'tema=' + encodeURIComponent(novo)
      });
      // opcional: validar res.ok
    } catch (e) {
      // se falhar, não quebre a UX
    }
  });
})();
