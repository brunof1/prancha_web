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
