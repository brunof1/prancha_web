// assets/js/arasaac.js
// UI mínima para pesquisar e importar pictogramas da ARASAAC.
// Requer que a página tenha os elementos com IDs usados abaixo.

(function () {
  const $ = (sel) => document.querySelector(sel);

  // Em "criar/editar cartão":
  const box = $('#arasaac-box');
  if (!box) return;

  const input = $('#arasaac-q');
  const langSel = $('#arasaac-lang');
  const btn = $('#arasaac-buscar');
  const list = $('#arasaac-resultados');
  const status = $('#arasaac-status');
  const hidden = $('#imagem_remota'); // <input type="hidden" name="imagem_remota">

  function limparResultados() {
    list.innerHTML = '';
    status.textContent = '';
  }

  async function buscar() {
    limparResultados();
    const q = (input.value || '').trim();
    const lang = (langSel && langSel.value) || 'pt';
    if (!q) return;
    status.textContent = 'Buscando...';
    try {
      const r = await fetch(`../includes/arasaac_buscar.php?q=${encodeURIComponent(q)}&lang=${encodeURIComponent(lang)}`, {
        credentials: 'same-origin'
      });
      const j = await r.json();
      if (!j.ok) throw new Error(j.msg || 'Falha na busca');
      if (!Array.isArray(j.results) || j.results.length === 0) {
        status.textContent = 'Nenhum resultado.';
        return;
      }
      status.textContent = `Encontrados ${j.results.length} resultados`;

      // Render simples (sem imagem preview, para evitar dependência de padrões do CDN)
      list.innerHTML = j.results.map(item => {
        const id = item.id;
        const kws = (item.keywords || []).slice(0, 5).join(', ');
        return `
          <li class="arasaac-item">
            <div>
              <strong>ID #${id}</strong><br>
              <small>${kws}</small>
            </div>
            <div>
              <button type="button" class="botao-acao" data-id="${id}" data-lang="${lang}">⬇️ Importar</button>
            </div>
          </li>
        `;
      }).join('');

      list.querySelectorAll('button[data-id]').forEach(btn => {
        btn.addEventListener('click', importar);
      });

    } catch (e) {
      console.error(e);
      status.textContent = 'Erro na busca.';
    }
  }

  async function importar(ev) {
    const b = ev.currentTarget;
    const id = b.getAttribute('data-id');
    const lang = b.getAttribute('data-lang') || 'pt';
    b.disabled = true;
    b.textContent = 'Importando...';
    status.textContent = 'Baixando arquivo (SVG > PNG)...';
    try {
      const fd = new FormData();
      fd.append('id', id);
      fd.append('lang', lang);
      const r = await fetch('../includes/arasaac_importar.php', {
        method: 'POST',
        body: fd,
        credentials: 'same-origin'
      });
      const j = await r.json();
      if (!j.ok) throw new Error(j.msg || 'Falha ao importar');

      // Salva o nome do arquivo baixado no hidden; o backend usará isso no salvar/atualizar
      hidden.value = j.filename;
      status.textContent = `Imagem importada: ${j.filename}. Você já pode salvar o cartão.`;
      b.textContent = 'Importado ✓';
    } catch (e) {
      console.error(e);
      status.textContent = 'Não foi possível importar este pictograma.';
      b.textContent = 'Tentar novamente';
      b.disabled = false;
    }
  }

  btn && btn.addEventListener('click', buscar);
  input && input.addEventListener('keydown', (ev) => { if (ev.key === 'Enter') { ev.preventDefault(); buscar(); } });
})();