// assets/js/falar.js
(function(){
  if (!('speechSynthesis' in window)) {
    window.falar = function(){};
    window.falarListaDeCartoes = function(){};
    return;
  }

  var preferencias = {
    voz_uri: null,
    tts_rate: 1.0,
    tts_pitch: 1.0,
    tts_volume: 1.0,
    falar_ao_clicar: 0
  };

  // Descobre base relativa (páginas estão em /pages/)
  var base = (window.location.pathname.indexOf('/pages/') !== -1) ? '..' : '.';

  // Busca preferências do usuário autenticado
  fetch(base + '/includes/preferencias_api.php', { credentials: 'same-origin' })
    .then(function(r){ return r.ok ? r.json() : {ok:false}; })
    .then(function(data){
      if (data && data.ok && data.preferencias) {
        preferencias = Object.assign(preferencias, data.preferencias);
        aplicarFalarAoClicar();
      }
    })
    .catch(function(){ /* silencioso */ });

  function escolherVoz(vozUri) {
    var voces = speechSynthesis.getVoices() || [];
    if (!vozUri) return null;
    for (var i=0;i<voces.length;i++) {
      if (voces[i].voiceURI === vozUri) return voces[i];
    }
    return null;
  }

  function falar(texto){
    if (!texto) return;
    var utt = new SpeechSynthesisUtterance(texto);
    var voz = escolherVoz(preferencias.voz_uri);
    if (voz) utt.voice = voz;
    utt.rate   = Number(preferencias.tts_rate || 1.0);
    utt.pitch  = Number(preferencias.tts_pitch || 1.0);
    utt.volume = Number(preferencias.tts_volume || 1.0);
    speechSynthesis.cancel(); // evita fila longa
    speechSynthesis.speak(utt);
  }

  function falarListaDeCartoes(lista){
    if (!Array.isArray(lista) || !lista.length) return;
    var i = 0;
    function proximo(){
      if (i >= lista.length) return;
      var utt = new SpeechSynthesisUtterance(String(lista[i++]));
      var voz = escolherVoz(preferencias.voz_uri);
      if (voz) utt.voice = voz;
      utt.rate   = Number(preferencias.tts_rate || 1.0);
      utt.pitch  = Number(preferencias.tts_pitch || 1.0);
      utt.volume = Number(preferencias.tts_volume || 1.0);
      utt.onend = function(){ setTimeout(proximo, 250); };
      speechSynthesis.speak(utt);
    }
    speechSynthesis.cancel();
    proximo();
  }

  function aplicarFalarAoClicar(){
    if (!preferencias.falar_ao_clicar) return;
    // Delegação: ao clicar num cartão .cartao-item, fala o <strong> se existir
    document.addEventListener('click', function(ev){
      var el = ev.target;
      while (el && el !== document.body && !el.classList.contains('cartao-item')) {
        el = el.parentElement;
      }
      if (el && el.classList.contains('cartao-item')) {
        var titulo = el.querySelector('strong');
        if (titulo && titulo.textContent) {
          falar(titulo.textContent.trim());
        }
      }
    });
  }

  document.addEventListener('click', function(ev){
    var btn = ev.target.closest('a[data-action="falar-prancha"],button[data-action="falar-prancha"]');
    if (!btn) return;
    ev.preventDefault();

    var listaAttr = btn.getAttribute('data-lista');
    if (listaAttr) {
      try {
        var arr = JSON.parse(listaAttr);
        if (Array.isArray(arr) && arr.length > 0) {
          falarListaDeCartoes(arr);
          return;
        }
      } catch(e){ /* fallback abaixo */ }
    }
    var texto = btn.getAttribute('data-texto') || '';
    if (texto) falar(texto);
  });

  // expõe globalmente para quem quiser usar direto
  window.falar = falar;
  window.falarListaDeCartoes = falarListaDeCartoes;
})();
