// assets/js/bateria.js
(function(){
  function init(form){
    const radios = form.querySelectorAll('input[name="bateria_social"]');
    const status = form.querySelector('.bat-status');
    const container = form;

    const rotulos = {
      0: 'Esgotado',
      1: 'Baixíssimo',
      2: 'Baixo',
      3: 'Neutro',
      4: 'Bom',
      5: 'Cheio'
    };

    function setVal(v, whenText){
      var nivel = Math.max(0, Math.min(5, Number(v)));
      container.style.setProperty('--bat-val', (nivel/5*100) + '%');
      if (status) {
        status.textContent = 'Seu nível: ' + rotulos[nivel] + ' (' + nivel + '/5)' + (whenText ? ' • ' + whenText : '');
      }
      // marcar visualmente
      const r = form.querySelector('input[name="bateria_social"][value="'+nivel+'"]');
      if (r) r.checked = true;
    }

    // carrega valor atual
    fetch('../includes/bateria_api.php', {credentials: 'same-origin'})
      .then(r => r.ok ? r.json() : {ok:false})
      .then(j => {
        if (j && j.ok) {
          setVal(j.nivel, j.atualizado_em_human ? ('atualizado em ' + j.atualizado_em_human) : '');
        }
      })
      .catch(()=>{ /* silencioso */ });

    // ao alterar, salva
    radios.forEach(r => {
      r.addEventListener('change', function(){
        const v = Number(r.value);
        setVal(v, 'salvando...');
        fetch('../includes/bateria_api.php', {
          method: 'POST',
          credentials: 'same-origin',
          headers: {'Content-Type': 'application/x-www-form-urlencoded'},
          body: 'nivel=' + encodeURIComponent(v)
        })
        .then(res => res.ok ? res.json() : {ok:false})
        .then(j => {
          if (j && j.ok) {
            setVal(j.nivel, 'atualizado em ' + (j.atualizado_em_human || 'agora'));
          } else {
            if (status) status.textContent = 'Não foi possível salvar agora.';
          }
        })
        .catch(()=>{ if (status) status.textContent = 'Erro de rede ao salvar.'; });
      });
    });
  }

  document.addEventListener('DOMContentLoaded', function(){
    document.querySelectorAll('[data-role="bateria-social"]').forEach(init);
  });
})();
