(function () {
  const synth = window.speechSynthesis;

  // Ajustes globais
  const DEFAULTS = {
    lang: "pt-BR",
    rate: 1.0,     // 0.1–10
    pitch: 1.0,    // 0–2
    volume: 1.0    // 0–1
  };

  let voicesCache = [];
  let preferredVoice = null;
  let voicesReady = false;
  let waitingResolvers = [];

  function resolveWaiters() {
    voicesReady = true;
    waitingResolvers.forEach(r => r());
    waitingResolvers = [];
  }

  function loadVoices() {
    voicesCache = synth.getVoices() || [];
    if (voicesCache.length > 0) resolveWaiters();
  }

  // Carrega já e também quando o Chrome avisar
  loadVoices();
  if (typeof speechSynthesis !== "undefined" && speechSynthesis.onvoiceschanged !== undefined) {
    speechSynthesis.onvoiceschanged = function () {
      loadVoices();
    };
  }

  function waitForVoices() {
    if (voicesReady || (voicesCache && voicesCache.length > 0)) {
      voicesReady = true;
      return Promise.resolve();
    }
    return new Promise(resolve => waitingResolvers.push(resolve));
  }

  function isPtBR(v) {
    return v.lang && v.lang.toLowerCase().startsWith("pt-br");
  }
  function isPtAny(v) {
    return v.lang && v.lang.toLowerCase().startsWith("pt-");
  }
  function isGoogle(v) {
    return /google/i.test(v.name || "");
  }
  function isMicrosoft(v) {
    return /microsoft/i.test(v.name || "");
  }

  function pickPreferredVoice() {
    // 1) Google pt-BR
    let v = voicesCache.find(v => isGoogle(v) && isPtBR(v));
    if (v) return v;

    // 2) Google pt-*
    v = voicesCache.find(v => isGoogle(v) && isPtAny(v));
    if (v) return v;

    // 3) Microsoft pt-BR (fallback bom no Windows 11)
    v = voicesCache.find(v => isMicrosoft(v) && isPtBR(v));
    if (v) return v;

    // 4) Qualquer pt-BR
    v = voicesCache.find(v => isPtBR(v));
    if (v) return v;

    // 5) Qualquer pt-*
    v = voicesCache.find(v => isPtAny(v));
    if (v) return v;

    // 6) Primeira disponível
    return voicesCache[0] || null;
  }

  async function ensurePreferredVoice() {
    await waitForVoices();
    if (!preferredVoice) preferredVoice = pickPreferredVoice();
    return preferredVoice;
  }

  function makeUtterance(texto, voice) {
    const utter = new SpeechSynthesisUtterance(texto);
    utter.lang = DEFAULTS.lang;
    utter.rate = DEFAULTS.rate;
    utter.pitch = DEFAULTS.pitch;
    utter.volume = DEFAULTS.volume;
    if (voice) utter.voice = voice;
    return utter;
  }

  // API pública
  window.falar = async function (texto) {
    if (!texto) return;
    if (synth.speaking || synth.pending) synth.cancel(); // evita sobreposição
    const voice = await ensurePreferredVoice();
    const utter = makeUtterance(texto, voice);
    synth.speak(utter);
  };

  window.falarListaDeCartoes = async function (listaTextos) {
    if (!Array.isArray(listaTextos) || listaTextos.length === 0) return;
    if (synth.speaking || synth.pending) synth.cancel();

    const voice = await ensurePreferredVoice();
    let i = 0;

    const falarProximo = () => {
      if (i >= listaTextos.length) return;
      const utter = makeUtterance(String(listaTextos[i] || ""), voice);
      utter.onend = () => { i++; falarProximo(); };
      synth.speak(utter);
    };

    falarProximo();
  };

  // Opcional: listar vozes no console para checar nomes/idiomas
  window._listarVozes = () => (voicesCache || []).map(v => ({ name: v.name, lang: v.lang, default: v.default }));
})();
