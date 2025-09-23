<?php
require_once '../includes/cabecalho.php';
require_once '../includes/controle_usuarios_admin.php'; // popula $lista_usuarios, mensagens
?>
<link rel="stylesheet" href="../assets/css/usuarios.css">
<link rel="stylesheet" href="../assets/css/tabela_responsiva.css">
<script src="../assets/js/usuarios_admin.js" defer></script>

<h1 id="titulo-lista">Usuários cadastrados</h1>

<?php if (!empty($mensagem_usuarios)): ?>
  <p class="alert <?= htmlspecialchars($classe_msg_usuarios,ENT_QUOTES,'UTF-8') ?>">
    <?= htmlspecialchars($mensagem_usuarios,ENT_QUOTES,'UTF-8') ?>
  </p>
<?php endif; ?>

<!-- ====== CRIAR NOVO USUÁRIO ====== -->
<section class="card" aria-labelledby="titulo-criar">
  <h2 id="titulo-criar" style="margin-top:0">Criar novo usuário</h2>
  <form method="post" action="gerenciar_usuarios.php" class="form-grid" autocomplete="off">
    <input type="hidden" name="acao" value="criar">

    <div>
      <label for="c_nome">Nome</label>
      <input id="c_nome" name="nome" type="text" required>
    </div>

    <div>
      <label for="c_email">Email</label>
      <input id="c_email" name="email" type="email" required>
    </div>

    <div>
      <label for="c_senha">Senha</label>
      <input id="c_senha" name="senha" type="password" minlength="6" required>
      <div class="help">Mínimo de 6 caracteres.</div>
    </div>

    <div>
      <label for="c_tipo">Tipo</label>
      <select id="c_tipo" name="tipo" required>
        <option value="user" selected>user</option>
        <option value="admin">admin</option>
      </select>
    </div>

    <div>
      <label for="c_tema">Tema</label>
      <select id="c_tema" name="tema_preferido">
        <option value="light" selected>light</option>
        <option value="dark">dark</option>
      </select>
    </div>

    <div>
      <label for="c_bat">Bateria social</label>
      <select id="c_bat" name="bateria_social">
        <?php for($i=0;$i<=5;$i++): ?>
          <option value="<?= $i ?>" <?= $i===3?'selected':'' ?>><?= $i ?></option>
        <?php endfor; ?>
      </select>
      <div class="help">0 (esgotado) a 5 (cheio)</div>
    </div>

    <div class="linha-acoes">
      <button type="submit" class="botao-acao">➕ Criar usuário</button>
    </div>
  </form>
</section>

<!-- ====== LISTA/EDIÇÃO DE USUÁRIOS ====== -->
<div class="tabela-wrap">
  <table class="tabela tabela--usuarios" aria-describedby="titulo-lista">
    <colgroup>
      <col class="id">
      <col class="nome">
      <col class="email col-email">
      <col class="tipo">
      <col class="tema col-tema">
      <col class="bateria col-bateria">
      <col class="acoes col-acoes">
      <col class="salvar col-salvar">
    </colgroup>
    <thead>
      <tr>
        <th>ID</th>
        <th>Nome</th>
        <th class="col-email">Email</th>
        <th>Tipo</th>
        <th class="col-tema">Tema</th>
        <th class="col-bateria">Bateria</th>
        <th class="col-acoes">Ações</th>
        <th class="col-salvar">Salvar</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($lista_usuarios as $u): ?>
        <?php $uid = (int)$u['id']; $bat = isset($u['bateria_social']) ? (int)$u['bateria_social'] : 3; ?>
        <tr>
          <!-- Inputs pertencem ao form de edição via atributo form="fedit_<?= $uid ?>" -->
          <td><?= $uid ?></td>

          <td>
            <label class="sr-only" for="nome_<?= $uid ?>">Nome</label>
            <input id="nome_<?= $uid ?>" name="nome" type="text"
                   value="<?= htmlspecialchars($u['nome'],ENT_QUOTES,'UTF-8') ?>"
                   form="fedit_<?= $uid ?>">
          </td>

          <td class="col-email">
            <label class="sr-only" for="email_<?= $uid ?>">Email</label>
            <input id="email_<?= $uid ?>" name="email" type="email"
                   value="<?= htmlspecialchars($u['email'],ENT_QUOTES,'UTF-8') ?>"
                   form="fedit_<?= $uid ?>">
          </td>

          <td>
            <label class="sr-only" for="tipo_<?= $uid ?>">Tipo</label>
            <select id="tipo_<?= $uid ?>" name="tipo" form="fedit_<?= $uid ?>">
              <option value="user"  <?= $u['tipo']==='user'  ? 'selected' : '' ?>>user</option>
              <option value="admin" <?= $u['tipo']==='admin' ? 'selected' : '' ?>>admin</option>
            </select>
          </td>

          <td class="col-tema">
            <label class="sr-only" for="tema_<?= $uid ?>">Tema</label>
            <select id="tema_<?= $uid ?>" name="tema_preferido" form="fedit_<?= $uid ?>">
              <option value="light" <?= ($u['tema_preferido'] ?? 'light')==='light' ? 'selected' : '' ?>>light</option>
              <option value="dark"  <?= ($u['tema_preferido'] ?? 'light')==='dark'  ? 'selected' : '' ?>>dark</option>
            </select>
          </td>

          <td class="col-bateria">
            <label class="sr-only" for="bat_<?= $uid ?>">Bateria social (0–5)</label>
            <select id="bat_<?= $uid ?>" name="bateria_social" form="fedit_<?= $uid ?>">
              <?php for($i=0;$i<=5;$i++): ?>
                <option value="<?= $i ?>" <?= $bat===$i ? 'selected':'' ?>><?= $i ?></option>
              <?php endfor; ?>
            </select>
          </td>

          <!-- Form de EXCLUSÃO (isolado, sem aninhar) -->
          <td class="celula-acao col-acoes">
            <form method="post"
                  action="gerenciar_usuarios.php"
                  data-action="excluir-usuario"
                  data-confirm="Excluir o usuário #<?= $uid ?>?">
              <input type="hidden" name="acao" value="excluir">
              <input type="hidden" name="id" value="<?= $uid ?>">
              <button type="submit" class="botao-acao excluir">Excluir</button>
            </form>
          </td>

          <!-- Form de EDIÇÃO (fica no TD, inputs apontam via form="...") -->
          <td class="celula-salvar col-salvar">
            <form id="fedit_<?= $uid ?>" method="post" action="gerenciar_usuarios.php">
              <input type="hidden" name="acao" value="editar">
              <input type="hidden" name="id" value="<?= $uid ?>">
              <button type="submit" class="botao-acao">Salvar</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<?php require_once '../includes/rodape.php'; ?>
