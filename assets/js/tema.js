// assets/js/tema.js
(function () {
  function getCurrentTheme() {
    return document.body.getAttribute('data-theme') === 'dark' ? 'dark' : 'light';
  }

  function updateButtonLabel(theme) {
    const btn = document.getElementById('toggle-tema');
    if (!btn) return;
    if (theme === 'dark') {
      btn.textContent = '☀️ Modo claro';
      btn.setAttribute('aria-pressed', 'true');
    } else {
      btn.textContent = '🌙 Modo escuro';
      btn.setAttribute('aria-pressed', 'false');
    }
  }

  function applyTheme(theme) {
    document.body.setAttribute('data-theme', theme);
    updateButtonLabel(theme);
  }

  async function saveTheme(theme) {
    try {
      const resp = await fetch('../includes/atualizar_tema.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' },
        body: 'tema=' + encodeURIComponent(theme)
      });
      const data = await resp.json();
      if (!resp.ok || !data.ok) throw new Error(data.msg || 'Erro ao salvar tema');
      return true;
    } catch (e) {
      console.error(e);
      return false;
    }
  }

  document.addEventListener('DOMContentLoaded', () => {
    // Ajusta o rótulo do botão ao carregar
    updateButtonLabel(getCurrentTheme());

    const btn = document.getElementById('toggle-tema');
    if (!btn) return;

    btn.addEventListener('click', async () => {
      const current = getCurrentTheme();
      const next = current === 'dark' ? 'light' : 'dark';

      // Aplica imediatamente para resposta visual rápida
      applyTheme(next);

      // Persiste no banco
      const ok = await saveTheme(next);
      if (!ok) {
        // Reverte se falhar
        applyTheme(current);
        alert('Não foi possível salvar sua preferência de tema agora.');
      }
    });
  });
})();
