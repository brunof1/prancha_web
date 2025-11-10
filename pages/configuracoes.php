<?php
include '../includes/cabecalho.php';
require_once '../includes/controle_configuracoes.php';
$isAdmin = ($_SESSION['tipo_usuario'] === 'admin');
?>

<!-- CSS específico desta página -->
<link rel="stylesheet" href="../assets/css/configuracoes.css">

<h1>⚙️ Configurações</h1>

<div class="grid-2" role="region" aria-label="Configurações do usuário">

  <!-- Perfil -->
  <section aria-labelledby="titulo-perfil">
    <h3 id="titulo-perfil">Meu perfil</h3>

    <?php if ($mensagem_perfil): ?>
      <div class="alert <?php echo $classe_msg_perfil; ?>" role="alert" aria-live="polite">
        <?php echo htmlspecialchars($mensagem_perfil, ENT_QUOTES, 'UTF-8'); ?>
      </div>
    <?php endif; ?>

    <form method="post" novalidate>
      <input type="hidden" name="acao" value="perfil">
      <div class="campo">
        <label for="nome">Nome</label><br>
        <input id="nome" name="nome" type="text" value="<?php echo htmlspecialchars($_SESSION['nome_usuario'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required aria-required="true">
      </div>
      <div class="campo">
        <label for="email">Email</label><br>
        <input id="email" name="email" type="email" value="<?php echo htmlspecialchars($_SESSION['email_usuario'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required aria-required="true" aria-describedby="ajuda-email">
        <div id="ajuda-email" class="help">Seu email é único na plataforma.</div>
      </div>
      <button type="submit" class="botao-acao">💾 Salvar perfil</button>
    </form>

    <hr aria-hidden="true">

    <?php if ($mensagem_senha): ?>
      <div class="alert <?php echo $classe_msg_senha; ?>" role="alert" aria-live="polite">
        <?php echo htmlspecialchars($mensagem_senha, ENT_QUOTES, 'UTF-8'); ?>
      </div>
    <?php endif; ?>

    <form method="post" novalidate>
      <input type="hidden" name="acao" value="senha">
      <fieldset>
        <legend>Alterar senha</legend>
        <div class="campo">
          <label for="senha_atual">Senha atual</label><br>
          <input id="senha_atual" name="senha_atual" type="password" required aria-required="true" autocomplete="current-password">
        </div>
        <div class="campo">
          <label for="nova_senha">Nova senha</label><br>
          <input id="nova_senha" name="nova_senha" type="password" required aria-required="true" minlength="6" autocomplete="new-password">
        </div>
        <div class="campo">
          <label for="confirma_senha">Confirmar nova senha</label><br>
          <input id="confirma_senha" name="confirma_senha" type="password" required aria-required="true" minlength="6" autocomplete="new-password">
        </div>
        <input type="submit" class="botao-acao" value="🔒 Alterar senha">
      </fieldset>
    </form>
  </section>

  <!-- Preferências -->
  <section aria-labelledby="titulo-preferencias">
    <h3 id="titulo-preferencias">Preferências</h3>

    <?php if ($mensagem_preferencias): ?>
      <div class="alert <?php echo $classe_msg_prefs; ?>" role="alert" aria-live="polite">
        <?php echo htmlspecialchars($mensagem_preferencias, ENT_QUOTES, 'UTF-8'); ?>
      </div>
    <?php endif; ?>

    <form method="post" id="form-preferencias" novalidate>
      <input type="hidden" name="acao" value="preferencias">

      <fieldset style="margin-bottom: 14px;">
        <legend>Aparência</legend>
        <div class="help">O tema claro/escuro também pode ser alternado no topo da página.</div>
        <div class="campo">
          <label for="tema_escolha">Tema</label><br>
          <select id="tema_escolha" name="tema" aria-describedby="ajuda-tema">
            <option value="light" <?php echo ($tema === 'light') ? 'selected' : ''; ?>>Claro</option>
            <option value="dark"  <?php echo ($tema === 'dark')  ? 'selected' : ''; ?>>Escuro</option>
          </select>
          <button type="button" id="btn-salvar-tema" class="botao-acao ml-8">💾 Salvar tema</button>
        </div>
      </fieldset>

      <fieldset style="margin-bottom: 14px;">
        <legend>Leitura em voz alta (TTS)</legend>

        <div class="campo">
          <label for="voz_selecao">Voz</label><br>
          <select id="voz_selecao" name="voz_uri" aria-describedby="ajuda-voz"
                  data-voz-salva="<?php echo htmlspecialchars($preferencias['voz_uri'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"></select>
          <div id="ajuda-voz" class="help">As vozes dependem do seu navegador/sistema.</div>
        </div>

        <div class="campo">
          <label for="tts_rate">Velocidade: <span id="tts_rate_val"><?php echo htmlspecialchars(number_format($preferencias['tts_rate'], 2), ENT_QUOTES, 'UTF-8'); ?></span></label><br>
          <input type="range" id="tts_rate" name="tts_rate" min="0.5" max="2" step="0.05" value="<?php echo (float)$preferencias['tts_rate']; ?>" aria-valuemin="0.5" aria-valuemax="2" aria-valuenow="<?php echo (float)$preferencias['tts_rate']; ?>">
        </div>

        <div class="campo">
          <label for="tts_pitch">Tom: <span id="tts_pitch_val"><?php echo htmlspecialchars(number_format($preferencias['tts_pitch'], 2), ENT_QUOTES, 'UTF-8'); ?></span></label><br>
          <input type="range" id="tts_pitch" name="tts_pitch" min="0" max="2" step="0.05" value="<?php echo (float)$preferencias['tts_pitch']; ?>">
        </div>

        <div class="campo">
          <label for="tts_volume">Volume: <span id="tts_volume_val"><?php echo htmlspecialchars(number_format($preferencias['tts_volume'], 2), ENT_QUOTES, 'UTF-8'); ?></span></label><br>
          <input type="range" id="tts_volume" name="tts_volume" min="0" max="1" step="0.05" value="<?php echo (float)$preferencias['tts_volume']; ?>">
        </div>

        <div class="campo">
          <button type="button" id="btn-testar-voz" class="botao-acao" aria-label="Testar a voz escolhida">🗣️ Testar voz</button>
        </div>
      </fieldset>
      
      <fieldset class="card" style="margin-top:8px;">
        <legend>Aparência</legend>

        <div class="campo">
          <label for="font_base_px">Tamanho da fonte</label><br>
          <input id="font_base_px" name="font_base_px" type="range" min="14" max="24" step="1"
                 value="<?php echo htmlspecialchars((string)($preferencias['font_base_px'] ?? 16), ENT_QUOTES, 'UTF-8'); ?>">
          <div class="help">Atual: <strong id="font_base_px_val"></strong> (aplica no site inteiro)</div>
        </div>
        <!--
        <div class="campo">
          <label>
            <input type="checkbox" name="falar_ao_clicar" value="1" <?php echo !empty($preferencias['falar_ao_clicar']) ? 'checked' : ''; ?>>
            Falar o texto do cartão ao clicar nele
          </label>
          <div class="help">Quando ativo, ao tocar em um cartão, a voz lê o rótulo dele.</div>
        </div>
        -->
      </fieldset>

      <div class="campo" style="margin-top:14px;">
        <button type="submit" class="botao-acao">💾 Salvar preferências</button>
      </div>
    </form>

    <?php if ($isAdmin): ?>
      <hr>
      <section aria-labelledby="atalhos-admin">
        <h4 id="atalhos-admin">Atalhos de administração</h4>
        <p class="help">Acesso rápido às telas administrativas.</p>
        <p>
          <a class="botao-acao" href="gerenciar_pranchas.php">📋 Gerenciar Pranchas</a>
          <a class="botao-acao" href="gerenciar_cartoes.php">🖼️ Gerenciar Cartões</a>
          <a class="botao-acao" href="gerenciar_grupos_pranchas.php">🗂️ Grupos de Pranchas</a>
        </p>
      </section>
    <?php endif; ?>
  </section>
</div>

<!-- JS específico desta página -->
<script src="../assets/js/configuracoes.js"></script>

<?php include '../includes/rodape.php'; ?>
