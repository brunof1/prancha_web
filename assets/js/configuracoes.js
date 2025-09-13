// Página: Configurações (sem PHP embutido)

// Atualiza mostradores dos sliders (rate/pitch/volume)
(function initSliders(){
  ['tts_rate','tts_pitch','tts_volume'].forEach(function(id){
    var input = document.getElementById(id);
    var out = document.getElementById(id + '_val');
    if (input && out){
      input.addEventListener('input', function(){
        var v = parseFloat(input.value || '0');
        out.textContent = v.toFixed(2);
      });
    }
  });
})();

// Popula vozes (Web Speech API) e pré-seleciona a salva (do data-atribute)
(function initVozes(){
  var select = document.getElementById('voz_selecao');
  if (!select) return;

  var vozSalva = select.dataset.vozSalva || '';

  function preencher(voices){
    select.innerHTML = '';
    var optPadrao = document.createElement('option');
    optPadrao.value = '';
    optPadrao.textContent = 'Usar voz padrão do sistema';
    select.appendChild(optPadrao);

    (voices || []).forEach(function(v){
      var o = document.createElement('option');
      o.value = v.voiceURI;
      o.textContent = (v.name || v.voiceURI) + (v.lang ? ' ('+v.lang+')' : '');
      if (vozSalva && v.voiceURI === vozSalva) { o.selected = true; }
      select.appendChild(o);
    });
  }

  function carregar(){
    var voices = (window.speechSynthesis && window.speechSynthesis.getVoices) ? window.speechSynthesis.getVoices() : [];
    preencher(voices || []);
  }

  if ('speechSynthesis' in window){
    window.speechSynthesis.onvoiceschanged = carregar;
    carregar();
  } else {
    preencher([]);
  }
})();

// Testar voz (usa valores atuais do formulário)
(function initTesteVoz(){
  var btn = document.getElementById('btn-testar-voz');
  if (!btn) return;
  btn.addEventListener('click', function(){
    if (!('speechSynthesis' in window)) { alert('Seu navegador não suporta síntese de voz.'); return; }
    var texto = 'Testando a voz escolhida.';
    var u = new SpeechSynthesisUtterance(texto);

    var select = document.getElementById('voz_selecao');
    var vozUri = select ? select.value : '';
    var rate = parseFloat((document.getElementById('tts_rate')||{}).value || '1');
    var pitch= parseFloat((document.getElementById('tts_pitch')||{}).value || '1');
    var vol  = parseFloat((document.getElementById('tts_volume')||{}).value || '1');

    var voces = speechSynthesis.getVoices ? speechSynthesis.getVoices() : [];
    if (vozUri){
      var v = (voces || []).find(function(vv){ return vv.voiceURI === vozUri; });
      if (v) u.voice = v;
    }
    u.rate = rate; u.pitch = pitch; u.volume = vol;
    speechSynthesis.cancel();
    speechSynthesis.speak(u);
  });
})();

// Salvar tema via endpoint existente (../includes/atualizar_tema.php)
(function initSalvarTema(){
  var btn = document.getElementById('btn-salvar-tema');
  if (!btn) return;
  btn.addEventListener('click', function(){
    var select = document.getElementById('tema_escolha');
    if (!select) return;
    var tema = select.value;
    fetch('../includes/atualizar_tema.php', {
      method: 'POST',
      body: new URLSearchParams({ tema: tema }),
      credentials: 'same-origin'
    })
    .then(function(r){ return r.json(); })
    .then(function(data){
      if (data && data.ok){
        document.body.setAttribute('data-theme', tema);
        alert('Tema salvo com sucesso.');
      } else {
        alert('Não foi possível salvar o tema.');
      }
    })
    .catch(function(){ alert('Erro ao salvar o tema.'); });
  });
})();