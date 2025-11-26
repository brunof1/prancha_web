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

// Atualiza mostradores dos sliders (rate/pitch/volume)
(function initSliders(){
  ['tts_rate','tts_pitch','tts_volume'].forEach(function(id){
    var input = document.getElementById(id);
    var out = document.getElementById(id + '_val');
    if (input && out){
      out.textContent = Number(input.value || 0).toFixed(2);
      input.addEventListener('input', function(){
        var v = parseFloat(input.value || '0');
        out.textContent = v.toFixed(2);
      });
    }
  });
})();

(function initFontPreview(){
  var input = document.getElementById('font_base_px');
  var out = document.getElementById('font_base_px_val');
  if (!input) return;

  function clamp(n){ n = parseInt(n||16,10); if (isNaN(n)) n=16; return Math.max(14, Math.min(24, n)); }

  var start = clamp(input.value);
  input.value = String(start);
  if (out) out.textContent = start + ' px';
  try {
    document.documentElement.style.setProperty('--base-font-size', start + 'px');
  } catch(_) {}

  input.addEventListener('input', function(){
    var v = clamp(input.value);
    if (out) out.textContent = v + ' px';
    document.documentElement.style.setProperty('--base-font-size', v + 'px');
  });
})();

// ===== Utilitários de voz =====
function waitForVoices(timeoutMs){
  return new Promise(function(resolve){
    var done = false;
    function finish(){ if (!done){ done = true; resolve(); } }
    var tries = 0;
    var iv = setInterval(function(){
      if (window.speechSynthesis && window.speechSynthesis.getVoices().length){
        clearInterval(iv); clearTimeout(t); finish();
      } else if (++tries > 40) { // ~2s
        clearInterval(iv); clearTimeout(t); finish();
      }
    }, 50);
    var t = setTimeout(function(){ clearInterval(iv); finish(); }, timeoutMs || 2000);
    if (window.speechSynthesis){
      window.speechSynthesis.onvoiceschanged = function(){
        if (window.speechSynthesis.getVoices().length){ clearInterval(iv); clearTimeout(t); finish(); }
      };
    }
  });
}

// cria uma chave estável que funciona bem no Android
function makeVoiceKey(v){
  var uri  = (v && v.voiceURI) || '';
  var name = (v && v.name) || '';
  var lang = (v && v.lang) || '';
  return [uri, name, lang].join('||'); // ex.: "com.google.android.tts||Português (Brasil)||pt-BR"
}

function parseVoiceKey(key){
  key = key || '';
  var parts = key.split('||');
  return { uri: parts[0] || '', name: parts[1] || '', lang: parts[2] || '' };
}

function findVoiceByKey(voices, key){
  if (!key) return null;
  var k = parseVoiceKey(key);
  // 1) casa por voiceURI
  var v = voices.find(function(x){ return (x.voiceURI||'') === k.uri && k.uri; });
  if (v) return v;
  // 2) casa por name
  v = voices.find(function(x){ return (x.name||'') === k.name && k.name; });
  if (v) return v;
  // 3) casa por lang
  v = voices.find(function(x){ return (x.lang||'').toLowerCase() === (k.lang||'').toLowerCase() && k.lang; });
  if (v) return v;
  return null;
}

// Normaliza nomes para casar entre engines/sistemas
function normName(s){
  return String(s||'').toLowerCase().normalize('NFKD').replace(/[\u0300-\u036f]/g,'').trim();
}

(function initVozes(){
  var select = document.getElementById('voz_selecao');
  if (!select) return;

  var vozSalva = select.dataset.vozSalva || ''; // pode ser URI antigo, nome ou já nossa chave composta
  var usuarioEscolheu = false;
  var primeiraCarga = true;

  select.addEventListener('change', function(){ usuarioEscolheu = true; });

  function preencher(voices){
    var escolhaAtual = select.value;
    select.innerHTML = '';

    var optPadrao = document.createElement('option');
    optPadrao.value = '';
    optPadrao.textContent = 'Usar voz padrão do sistema';
    select.appendChild(optPadrao);

    (voices || []).forEach(function(v){
      var o = document.createElement('option');
      o.value = makeVoiceKey(v);
      var label = (v.name || v.voiceURI || 'Voz') + (v.lang ? ' ('+v.lang+')' : '');
      o.textContent = label;
      select.appendChild(o);
    });

    var alvo = '';

    if (usuarioEscolheu && escolhaAtual){
      alvo = escolhaAtual;
    } else if (primeiraCarga && vozSalva){
      // tenta mapear uma chave antiga (só URI ou só nome) para nossa chave composta
      var voces = voices || [];
      var tentativa = findVoiceByKey(voces, vozSalva);
      if (!tentativa) {
        // se vozSalva for nome/uri simples, tente achar por igualdade direta
        tentativa = voces.find(function(v){ return v.voiceURI === vozSalva || v.name === vozSalva || v.lang === vozSalva; }) || null;
      }
      if (tentativa) alvo = makeVoiceKey(tentativa);
    } else {
      var nav = (navigator.language || '').toLowerCase();
      var voces = voices || [];
      var preferida = voces.find(function(v){ return (v.lang || '').toLowerCase().startsWith('pt'); })
                    || voces.find(function(v){ return (v.lang || '').toLowerCase().startsWith(nav); })
                    || null;
      if (preferida) alvo = makeVoiceKey(preferida);
    }

    if (alvo){
      var existe = Array.prototype.some.call(select.options, function(o){ return o.value === alvo; });
      if (existe) select.value = alvo;
    }

    primeiraCarga = false;
  }

  async function carregar(){
    await waitForVoices(2000);
    var voices = (window.speechSynthesis && window.speechSynthesis.getVoices) ? window.speechSynthesis.getVoices() : [];
    preencher(voices || []);
  }

  if ('speechSynthesis' in window){
    carregar();
    window.speechSynthesis.onvoiceschanged = carregar;
  } else {
    preencher([]);
  }
})();

// Testar voz
(function initTesteVoz(){
  var btn = document.getElementById('btn-testar-voz');
  if (!btn) return;

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
      // o clear vem pelo speaking=false acima
    }
  }

  btn.addEventListener('click', async function(){
    if (!('speechSynthesis' in window) || typeof window.SpeechSynthesisUtterance !== 'function') {
      alert('Seu navegador não suporta síntese de voz.');
      return;
    }

    await waitForVoices(2000);

    var texto = 'Testando a voz escolhida.';
    var u = new SpeechSynthesisUtterance(texto);

    var select = document.getElementById('voz_selecao');
    var voiceKey = select ? select.value : '';
    var rate = parseFloat((document.getElementById('tts_rate')||{}).value || '1');
    var pitch= parseFloat((document.getElementById('tts_pitch')||{}).value || '1');
    var vol  = parseFloat((document.getElementById('tts_volume')||{}).value || '1');

    var voces = speechSynthesis.getVoices ? speechSynthesis.getVoices() : [];
    var v = voiceKey ? findVoiceByKey(voces, voiceKey) : null;

    if (!v && voiceKey){
      // compat: se BD tiver só URI/nome/lang antigos
      v = voces.find(function(vv){ return vv.voiceURI === voiceKey || vv.name === voiceKey || vv.lang === voiceKey; }) || null;
    }

    if (v) {
      u.voice = v;
      if (v.lang) u.lang = v.lang; // **importante no Android**
    } else {
      // fallback por idioma
      var nav = (navigator.language || 'pt-BR');
      var f = voces.find(function(x){ return (x.lang||'').toLowerCase().startsWith('pt'); })
           || voces.find(function(x){ return (x.lang||'').toLowerCase().startsWith(nav.toLowerCase()); })
           || null;
      if (f) { u.voice = f; u.lang = f.lang || 'pt-BR'; }
      else   { u.lang = 'pt-BR'; }
    }

    u.rate   = isFinite(rate)  ? rate  : 1;
    u.pitch  = isFinite(pitch) ? pitch : 1;
    u.volume = isFinite(vol)   ? vol   : 1;

    try { speechSynthesis.cancel(); } catch(_) {}

    withResumeWorkaround(function(){
      speechSynthesis.speak(u);
    });
  });
})();

(function initSalvarTema(){
  var btn = document.getElementById('btn-salvar-tema');
  if (!btn) return;

  var base = (window.location.pathname.indexOf('/pages/') !== -1) ? '..' : '.';

  btn.addEventListener('click', function(){
    var select = document.getElementById('tema_escolha');
    if (!select) return;
    var tema = select.value === 'dark' ? 'dark' : 'light';

    fetch(base + '/includes/atualizar_tema.php', {
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
        alert(data && data.msg ? data.msg : 'Não foi possível salvar o tema.');
      }
    })
    .catch(function(){
      alert('Erro ao salvar o tema.');
    });
  });
})();