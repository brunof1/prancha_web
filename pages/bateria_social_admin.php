<?php
// pages/bateria_social_admin.php
require_once '../includes/cabecalho.php';
require_once '../includes/acl.php';
require_admin();

require_once '../includes/modelo_usuarios.php';

function h($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
function bat_emoji(int $n){
  $map = ['😵','😞','😕','😐','🙂','😄'];
  return $map[max(0,min(5,$n))];
}
function human_dt(?string $s){ return $s ? date('d/m/Y H:i', strtotime($s)) : 'nunca'; }

$usuarios = listarUsuariosComBateria(); // id, nome, email, tipo, tema_preferido, bateria_social, bateria_atualizado_em
?>
<link rel="stylesheet" href="../assets/css/usuarios.css">
<link rel="stylesheet" href="../assets/css/bateria.css">
<link rel="stylesheet" href="../assets/css/bateria_admin.css">

<h1>📊 Bateria Social — Administração</h1>
<p class="help">Use o seletor para ajustar o nível (0–5) dos outros usuários.</p>

<section class="lista-usuarios lista-bateria-admin">
  <!-- Cabeçalho (só aparece no desktop pelo usuarios.css) -->
  <div class="usuarios-head" role="row">
    <div role="columnheader">ID</div>
    <div role="columnheader">Usuário</div>
    <div role="columnheader">Bateria</div>
    <div role="columnheader">Ações</div>
  </div>

  <div class="usuarios-rows">
    <?php foreach ($usuarios as $u): 
      $id   = (int)$u['id'];
      $nome = $u['nome'];
      $email= $u['email'];
      $niv  = isset($u['bateria_social']) ? (int)$u['bateria_social'] : 3;
      $quando = $u['bateria_atualizado_em'] ?? null;
    ?>
    <article class="usuario-row" role="row" data-id="<?= $id ?>">
      <!-- Coluna: ID -->
      <div class="campo" role="cell">
        <div class="label">ID</div>
        <div class="valor"><?= $id ?></div>
      </div>

      <!-- Coluna: Usuário (nome + email) -->
      <div class="campo" role="cell">
        <div class="label">Usuário</div>
        <div class="valor">
          <strong><?= h($nome) ?></strong><br>
          <small><?= h($email) ?></small>
        </div>
      </div>

      <!-- Coluna: Bateria (pill + meta) -->
      <div class="campo" role="cell">
        <div class="label">Bateria</div>
        <div class="valor">
          <span class="bat-pill lvl-<?= $niv ?>" aria-label="Nível de bateria atual">
            <span class="bat-pill__emoji"><?= bat_emoji($niv) ?></span>
            <span class="bat-pill__text"><?= $niv ?>/5</span>
          </span>
          <div class="bat-meta">Atualizado: <?= h(human_dt($quando)) ?></div>
        </div>
      </div>

      <!-- Coluna: Ações (selecionar novo nível e salvar) -->
      <div class="acao" role="cell">
        <form class="form-bateria-admin" data-id="<?= $id ?>" onsubmit="return false">
          <label class="sr-only" for="bat-<?= $id ?>">Novo nível</label>
          <select id="bat-<?= $id ?>" name="nivel">
            <option value="0" <?= $niv===0?'selected':'' ?>>0 — Esgotado</option>
            <option value="1" <?= $niv===1?'selected':'' ?>>1 — Baixíssimo</option>
            <option value="2" <?= $niv===2?'selected':'' ?>>2 — Baixo</option>
            <option value="3" <?= $niv===3?'selected':'' ?>>3 — Neutro</option>
            <option value="4" <?= $niv===4?'selected':'' ?>>4 — Bom</option>
            <option value="5" <?= $niv===5?'selected':'' ?>>5 — Cheio</option>
          </select>
          <button type="button" class="botao-acao" data-action="salvar-bateria">Salvar</button>
        </form>
      </div>
    </article>
    <?php endforeach; ?>
  </div>
</section>

<script src="../assets/js/bateria_admin.js"></script>

<?php require_once '../includes/rodape.php'; ?>
