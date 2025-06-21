function falar(texto) {
    const synth = window.speechSynthesis;
    const utter = new SpeechSynthesisUtterance(texto);
    synth.speak(utter);
}

function falarListaDeCartoes(listaTextos) {
    let synth = window.speechSynthesis;
    let indice = 0;

    function falarProximo() {
        if (indice < listaTextos.length) {
            let utter = new SpeechSynthesisUtterance(listaTextos[indice]);
            utter.onend = falarProximo;
            synth.speak(utter);
            indice++;
        }
    }

    falarProximo();
}
