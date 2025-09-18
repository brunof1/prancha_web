// assets/js/arasaac.js
document.addEventListener('DOMContentLoaded', () => {
  const q          = document.getElementById('arasaac-q');
  const langSel    = document.getElementById('arasaac-lang');
  const btnBuscar  = document.getElementById('arasaac-buscar');
  const statusBox  = document.getElementById('arasaac-status');
  const ulResults  = document.getElementById('arasaac-resultados');
  const inputOculto= document.getElementById('imagem_remota');

  if (!btnBuscar || !ulResults) return; // página sem o box

  const setStatus = (msg) => { if (statusBox) { statusBox.textContent = msg; statusBox.setAttribute('aria-live','polite'); } };

  const buildCandidates = (id) => {
  id = String(id);
  return [
    `https://static.arasaac.org/pictograms/${id}/${id}.svg`,
    `https://static.arasaac.org/pictograms/${id}/${id}_300.png`,
    `https://static.arasaac.org/pictograms/${id}/${id}.png`
  ];
};

  const attachFallback = (img, urls) => {
    img.dataset.idx = '0';
    img.dataset.urls = urls.join('|');
    img.src = urls[0];
    img.onerror = function () {
      const list = this.dataset.urls.split('|');
      let i = parseInt(this.dataset.idx || '0', 10) + 1;
      if (i < list.length) {
        this.dataset.idx = String(i);
        this.src = list[i];
      } else {
        this.onerror = null;
        this.alt = 'Pré-visualização indisponível';
        this.style.opacity = '0.4';
      }
    };
  };

  async function buscar() {
    const termo = (q?.value || '').trim();
    const lang  = (langSel?.value || 'pt').trim();
    if (!termo) { setStatus('Digite um termo para buscar.'); return; }

    setStatus('Buscando...');
    ulResults.innerHTML = '';

    try {
      const resp = await fetch(`../includes/arasaac_buscar.php?q=${encodeURIComponent(termo)}&lang=${encodeURIComponent(lang)}`, { credentials: 'same-origin' });
      const data = await resp.json();
      if (!data.ok) { setStatus('Não foi possível buscar agora.'); return; }
      const results = Array.isArray(data.results) ? data.results : [];
      if (results.length === 0) { setStatus('Nenhum resultado.'); return; }

      setStatus(`Encontrados ${results.length} resultado(s). Clique em "Importar" para usar.`);

      results.forEach(item => {
        const li = document.createElement('li');
        li.className = 'arasaac-item';
        li.style.display = 'grid';
        li.style.gridTemplateColumns = '96px 1fr auto';
        li.style.alignItems = 'center';
        li.style.gap = '10px';
        li.style.padding = '8px';
        li.style.border = '1px solid #ddd';
        li.style.borderRadius = '10px';
        li.style.background = 'var(--bg, #fff)';

        // Figura
        const fig = document.createElement('figure');
        fig.style.margin = '0';
        fig.style.width = '96px';
        fig.style.height = '96px';
        fig.style.display = 'flex';
        fig.style.alignItems = 'center';
        fig.style.justifyContent = 'center';
        fig.style.overflow = 'hidden';
        fig.style.borderRadius = '12px';
        fig.style.background = '#f6f6f6';

        const img = document.createElement('img');
        img.alt = (item.keywords?.[0] || termo) + ` (ARASAAC ${item.id})`;
        img.style.maxWidth = '96px';
        img.style.maxHeight = '96px';
        img.style.objectFit = 'contain';
        attachFallback(img, buildCandidates(item.id));
        fig.appendChild(img);

        // Texto
        const info = document.createElement('div');
        const title = document.createElement('div');
        title.style.fontWeight = '600';
        title.textContent = item.keywords?.[0] || termo;
        const meta = document.createElement('div');
        meta.style.fontSize = '12px';
        meta.style.opacity = '0.75';
        meta.textContent = `ID: ${item.id}${item.keywords?.length ? ' • ' + item.keywords.slice(0,4).join(', ') : ''}`;
        info.appendChild(title);
        info.appendChild(meta);

        // Botão importar
        const btnImp = document.createElement('button');
        btnImp.className = 'botao-acao';
        btnImp.type = 'button';
        btnImp.textContent = '⬇️ Importar';
        btnImp.addEventListener('click', async () => {
          btnImp.disabled = true;
          const originalTxt = btnImp.textContent;
          btnImp.textContent = 'Importando...';
          setStatus(`Importando ARASAAC ${item.id}...`);
          try {
            const body = new URLSearchParams({ id: String(item.id), lang });
            const r = await fetch('../includes/arasaac_importar.php', { method: 'POST', body, credentials: 'same-origin' });
            const j = await r.json();
            if (j.ok && j.filename) {
              inputOculto.value = j.filename;
              setStatus('Imagem importada! Agora é só salvar o cartão.');
              // marca o selecionado
              ulResults.querySelectorAll('.arasaac-item').forEach(el => { el.style.outline = 'none'; });
              li.style.outline = '2px solid #2e7d32';
              btnImp.textContent = '✅ Selecionado';
            } else {
              setStatus(j.msg || 'Falha ao importar.');
              btnImp.disabled = false;
              btnImp.textContent = originalTxt;
            }
          } catch (e) {
            setStatus('Erro de rede ao importar.');
            btnImp.disabled = false;
            btnImp.textContent = originalTxt;
          }
        });

        li.appendChild(fig);
        li.appendChild(info);
        li.appendChild(btnImp);
        ulResults.appendChild(li);
      });

    } catch (e) {
      setStatus('Erro ao buscar.');
    }
  }

  btnBuscar.addEventListener('click', buscar);
  q?.addEventListener('keydown', (ev) => { if (ev.key === 'Enter') { ev.preventDefault(); buscar(); } });
});
