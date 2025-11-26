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

// assets/js/falar.js
(function(){
  if (!('speechSynthesis' in window) || typeof window.SpeechSynthesisUtterance !== 'function') {
    window.falar = function(){};
    window.falarListaDeCartoes = function(){};
    return;
  }

  var base = (window.location.pathname.indexOf('/pages/') !== -1) ? '..' : '.';

  // ===== utils =====
  function waitForVoices(timeoutMs){
    return new Promise(function(resolve){
      var done = false;
      function finish(){ if (!done){ done = true; resolve(); } }
      var tries = 0;
      var iv = setInterval(function(){
        if (window.speechSynthesis.getVoices().length){
          clearInterval(iv); clearTimeout(t); finish();
        } else if (++tries > 40) {
          clearInterval(iv); clearTimeout(t); finish();
        }
      }, 50);
      var t = setTimeout(function(){ clearInterval(iv); finish(); }, timeoutMs || 2000);
      window.speechSynthesis.onvoiceschanged = function(){
        if (window.speechSynthesis.getVoices().length){ clearInterval(iv); clearTimeout(t); finish(); }
      };
    });
  }

  function makeVoiceKey(v){
    var uri  = (v && v.voiceURI) || '';
    var name = (v && v.name) || '';
    var lang = (v && v.lang) || '';
    return [uri, name, lang].join('||');
  }
  function parseVoiceKey(key){
    key = key || '';
    var parts = key.split('||');
    return { uri: parts[0] || '', name: parts[1] || '', lang: parts[2] || '' };
  }
  function findVoiceByKey(voices, key){
    if (!key) return null;
    var k = parseVoiceKey(key);
    var v = voices.find(function(x){ return (x.voiceURI||'') === k.uri && k.uri; });
    if (v) return v;
    v = voices.find(function(x){ return (x.name||'') === k.name && k.name; });
    if (v) return v;
    v = voices.find(function(x){ return (x.lang||'').toLowerCase() === (k.lang||'').toLowerCase() && k.lang; });
    if (v) return v;
    return null;
  }

  // “resume hack” para Android/Chrome
  function withResumeWorkaround(cb){
    var iv = null;
    try {
      if ('speechSynthesis' in window) {
        iv = setInterval(function(){
          try { window.speechSynthesis.resume(); } catch(_) {}
          if (!window.speechSynthesis.speaking) { clearInterval(iv); iv = null; }
        }, 300);
      }
    } catch(_) {}
    try { cb(); } finally {
      // o clearInterval acontece quando speaking=false
    }
  }

  // ===== estado =====
  var preferencias = {
    voz_uri: null,       // pode ser URI antigo, nome, lang ou a chave composta
    tts_rate: 1.0,
    tts_pitch: 1.0,
    tts_volume: 1.0,
    falar_ao_clicar: 0
  };

  var carregamentoPreferenciasEmAndamento = null;
  function fetchPreferenciasFresh(){
    if (carregamentoPreferenciasEmAndamento) return carregamentoPreferenciasEmAndamento;
    var url = base + '/includes/preferencias_api.php?t=' + Date.now();
    carregamentoPreferenciasEmAndamento = fetch(url, {
      credentials: 'same-origin',
      cache: 'no-store'
    })
    .then(function(r){ return r.ok ? r.json() : {ok:false}; })
    .then(function(data){
      carregamentoPreferenciasEmAndamento = null;
      if (data && data.ok && data.preferencias) {
        preferencias = Object.assign(preferencias, data.preferencias);
        aplicarFalarAoClicar();
      }
      return preferencias;
    })
    .catch(function(){
      carregamentoPreferenciasEmAndamento = null;
      return preferencias;
    });
    return carregamentoPreferenciasEmAndamento;
  }

  function escolherVoz(vozKey) {
    var voces = speechSynthesis.getVoices() || [];
    // tenta por nossa chave composta
    var v = vozKey ? findVoiceByKey(voces, vozKey) : null;
    if (!v && vozKey){
      // compat com valores antigos: voiceURI, name ou lang
      v = voces.find(function(x){ return x.voiceURI === vozKey || x.name === vozKey || x.lang === vozKey; }) || null;
    }
    if (v) return v;

    // fallback amigável
    var nav = (navigator.language || '').toLowerCase();
    return voces.find(function(x){ return (x.lang||'').toLowerCase().startsWith('pt'); })
        || voces.find(function(x){ return (x.lang||'').toLowerCase().startsWith(nav); })
        || null;
  }

  async function falar(texto){
    if (!texto) return;
    await waitForVoices(2000);
    await fetchPreferenciasFresh();

    var u = new SpeechSynthesisUtterance(String(texto));
    var v = escolherVoz(preferencias.voz_uri);

    if (v) {
      u.voice = v;
      if (v.lang) u.lang = v.lang; // **crítico no Android**
    } else {
      // sem voz definida: force um idioma razoável
      u.lang = 'pt-BR';
    }

    u.rate   = Number(preferencias.tts_rate || 1.0);
    u.pitch  = Number(preferencias.tts_pitch || 1.0);
    u.volume = Number(preferencias.tts_volume || 1.0);

    try { speechSynthesis.cancel(); } catch(_) {}

    withResumeWorkaround(function(){
      speechSynthesis.speak(u);
    });
  }

  async function falarListaDeCartoes(lista){
    if (!Array.isArray(lista) || !lista.length) return;

    await waitForVoices(2000);
    await fetchPreferenciasFresh();

    var i = 0;
    function proximo(){
      if (i >= lista.length) return;
      var u = new SpeechSynthesisUtterance(String(lista[i++]));
      var v = escolherVoz(preferencias.voz_uri);
      if (v) { u.voice = v; if (v.lang) u.lang = v.lang; } else { u.lang = 'pt-BR'; }
      u.rate   = Number(preferencias.tts_rate || 1.0);
      u.pitch  = Number(preferencias.tts_pitch || 1.0);
      u.volume = Number(preferencias.tts_volume || 1.0);
      u.onend = function(){ setTimeout(proximo, 250); };
      withResumeWorkaround(function(){
        speechSynthesis.speak(u);
      });
    }
    try { speechSynthesis.cancel(); } catch(_) {}
    proximo();
  }

  function aplicarFalarAoClicar(){
    if (!preferencias.falar_ao_clicar) return;
    if (aplicarFalarAoClicar._ligado) return;
    aplicarFalarAoClicar._ligado = true;
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
      } catch(e){}
    }
    var texto = btn.getAttribute('data-texto') || '';
    if (texto) falar(texto);
  });

  window.falar = falar;
  window.falarListaDeCartoes = falarListaDeCartoes;
})();
