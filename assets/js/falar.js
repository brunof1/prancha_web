function falar(texto) {
    const synth = window.speechSynthesis;
    const utter = new SpeechSynthesisUtterance(texto);
    synth.speak(utter);
}
