<?php
// include este arquivo onde quiser exibir a bateria social do usuário atual.
?>
<link rel="stylesheet" href="../assets/css/bateria.css">
<section class="bat-card" aria-labelledby="bat-title">
  <h2 id="bat-title">Bateria Social</h2>
  <p class="help" id="bat-desc">Como você está para interagir agora?</p>

  <form class="bateria" data-role="bateria-social" aria-describedby="bat-desc">
    <!--
    <fieldset>
      -->
      <legend class="sr-only">Nível de bateria social</legend>
      <div class="bat-levels" role="radiogroup" aria-label="Nível de bateria social">
        <input class="sr-only" type="radio" id="bat-0" name="bateria_social" value="0" />
        <label class="bat-level lvl-0" data-level="0" for="bat-0"><span class="bat-emoji" aria-hidden="true">😵</span><span class="bat-text">Esgotado</span></label>

        <input class="sr-only" type="radio" id="bat-1" name="bateria_social" value="1" />
        <label class="bat-level lvl-1" data-level="1" for="bat-1"><span class="bat-emoji" aria-hidden="true">😟</span><span class="bat-text">Baixíssimo</span></label>

        <input class="sr-only" type="radio" id="bat-2" name="bateria_social" value="2" />
        <label class="bat-level lvl-2" data-level="2" for="bat-2"><span class="bat-emoji" aria-hidden="true">🙁</span><span class="bat-text">Baixo</span></label>

        <input class="sr-only" type="radio" id="bat-3" name="bateria_social" value="3" />
        <label class="bat-level lvl-3" data-level="3" for="bat-3"><span class="bat-emoji" aria-hidden="true">😐</span><span class="bat-text">Neutro</span></label>

        <input class="sr-only" type="radio" id="bat-4" name="bateria_social" value="4" />
        <label class="bat-level lvl-4" data-level="4" for="bat-4"><span class="bat-emoji" aria-hidden="true">🙂</span><span class="bat-text">Bom</span></label>

        <input class="sr-only" type="radio" id="bat-5" name="bateria_social" value="5" />
        <label class="bat-level lvl-5" data-level="5" for="bat-5"><span class="bat-emoji" aria-hidden="true">😄</span><span class="bat-text">Cheio</span></label>
      </div>
      <!--
    </fieldset>
    -->

    <div class="bat-meter" aria-hidden="true"><div class="bat-meter__fill"></div></div>
    <div class="bat-status help" aria-live="polite"></div>
  </form>
</section>
<script src="../assets/js/bateria.js"></script>
