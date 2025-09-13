// ../assets/js/falar.js
(function () {
  const synth = window.speechSynthesis;

  // Ajustes globais de prosódia
  const DEFAULTS = {
    lang: "pt-BR",
    rate: 1.0,
    pitch: 1.0,
    volume: 1.0
  };

  // ===== Detecção de plataforma =====
  const UA = (navigator.userAgent || navigator.vendor || "").toLowerCase();
  const isAndroid = /android/.test(UA);
  const isIOS = /iphone|ipad|ipod/.test(UA);
  const isWindows = /windows nt/.test(UA);
  const isLinux = !isAndroid && /linux/.test(UA);

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

  // Carrega agora e na troca de vozes
  loadVoices();
  if (typeof speechSynthesis !== "undefined" && speechSynthesis.onvoiceschanged !== undefined) {
    speechSynthesis.onvoiceschanged = loadVoices;
  }

  function waitForVoices() {
    if (voicesReady || (voicesCache && voicesCache.length > 0)) {
      voicesReady = true;
      return Promise.resolve();
    }
    return new Promise(resolve => waitingResolvers.push(resolve));
  }

  // ===== Helpers de filtragem =====
  const lower = s => (s || "").toLowerCase();

  function isPtBR(v)  { return lower(v.lang).startsWith("pt-br"); }
  function isPtAny(v) { return lower(v.lang).startsWith("pt-"); }

  function isGoogle(v)    { return /google/.test(lower(v.name)); }
  function isMicrosoft(v) { return /microsoft/.test(lower(v.name)); }

  // Em iOS/Safari as vozes são “Apple”, mas raramente vêm rotuladas como “Siri”.
  // Lista de nomes comuns de vozes pt-BR em iOS (pode variar por versão):
  const APPLE_PT_NAMES = [
    "luciana", "joana", "yara", "fernanda", "catarina" // manter flexível
  ];
  function isApplePt(v) {
    const nm = lower(v.name);
    return isPtAny(v) && (APPLE_PT_NAMES.some(n => nm.includes(n)) || (!isGoogle(v) && !isMicrosoft(v)));
  }

  // ===== Estratégia por plataforma =====
  function pickPreferredVoiceByPlatform() {
    let v;

    if (isAndroid) {
      // Android → priorizar Google
      v = voicesCache.find(v => isGoogle(v) && isPtBR(v));
      if (v) return v;
      v = voicesCache.find(v => isGoogle(v) && isPtAny(v));
      if (v) return v;
    }

    if (isIOS) {
      // iOS → priorizar Apple/Siri
      v = voicesCache.find(v => isApplePt(v) && isPtBR(v));
      if (v) return v;
      v = voicesCache.find(v => isApplePt(v) && isPtAny(v));
      if (v) return v;
    }

    if (isWindows) {
      // Windows → priorizar Microsoft
      v = voicesCache.find(v => isMicrosoft(v) && isPtBR(v));
      if (v) return v;
      v = voicesCache.find(v => isMicrosoft(v) && isPtAny(v));
      if (v) return v;
    }

    // Linux (ou qualquer um) → melhores pt-* disponíveis
    v = voicesCache.find(isPtBR);
    if (v) return v;

    v = voicesCache.find(isPtAny);
    if (v) return v;

    // Último recurso
    return voicesCache[0] || null;
  }

  async function ensurePreferredVoice() {
    await waitForVoices();
    if (!preferredVoice) preferredVoice = pickPreferredVoiceByPlatform();
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

  // ===== API pública =====
  window.falar = async function (texto) {
    if (!texto) return;
    if (synth.speaking || synth.pending) synth.cancel();

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

  // Depuração/diagnóstico
  window._listarVozes = () => (voicesCache || []).map(v => ({
    name: v.name, lang: v.lang, default: v.default
  }));

  window._ttsInfo = () => ({
    platform: { isAndroid, isIOS, isWindows, isLinux, UA: navigator.userAgent },
    chosenVoice: preferredVoice ? { name: preferredVoice.name, lang: preferredVoice.lang } : null,
    availableCount: (voicesCache || []).length
  });
})();
