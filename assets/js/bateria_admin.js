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

// assets/js/bateria_admin.js
document.addEventListener('click', function(ev){
  const btn = ev.target.closest('[data-action="salvar-bateria"]');
  if (!btn) return;

  ev.preventDefault();
  const form = btn.closest('.form-bateria-admin');
  if (!form) return;

  const id = form.dataset.id;
  const select = form.querySelector('select[name="nivel"]');
  const nivel = select ? select.value : null;
  if (!id || nivel === null) return;

  btn.disabled = true;
  const original = btn.textContent;
  btn.textContent = 'Salvando...';

  fetch('../includes/bateria_api.php', {
    method: 'POST',
    credentials: 'same-origin',
    headers: {'Content-Type':'application/x-www-form-urlencoded'},
    body: 'id='+encodeURIComponent(id)+'&nivel='+encodeURIComponent(nivel)
  })
  .then(r => r.ok ? r.json() : Promise.reject())
  .then(j => {
    if (!j || !j.ok) throw new Error();

    // Atualiza pill e meta sem recarregar
    const row  = form.closest('.usuario-row');
    const pill = row.querySelector('.bat-pill');
    const txt  = row.querySelector('.bat-pill .bat-pill__text');
    const meta = row.querySelector('.bat-meta');

    // troca classe lvl-*
    pill.className = pill.className.replace(/lvl-\d/g,'').trim() + ' lvl-' + j.nivel;
    if (txt) txt.textContent = `${j.nivel}/5`;

    if (meta) meta.textContent = `Atualizado: ${j.atualizado_em_human || 'agora'}`;

    btn.textContent = '✅ Salvo';
    setTimeout(() => { btn.textContent = original; btn.disabled = false; }, 900);
  })
  .catch(() => {
    alert('Não foi possível salvar agora.');
    btn.textContent = original;
    btn.disabled = false;
  });
});
