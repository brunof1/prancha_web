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
      <input id="c_nome"  name="nome"  type="text"    required autocomplete="name">
    </div>

    <div>
      <label for="c_email">Email</label>
      <input id="c_email" name="email" type="email"   required autocomplete="email">
    </div>

    <div>
      <label for="c_senha">Senha</label>
      <input id="c_senha" name="senha" type="password" minlength="6" required autocomplete="new-password">
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
  <table class="tabela tabela--usuarios" aria-labelledby="titulo-lista">
    <colgroup>
      <col class="col-id">
      <col class="col-nome">
      <col class="col-email">
      <col class="col-acoes">
      <col class="col-salvar">
    </colgroup>
    <thead>
      <tr>
        <th>ID</th>
        <th>Nome</th>
        <th class="col-email">Email</th>
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

          <!-- Form de EXCLUSÃO (isolado, sem aninhar) -->
          <td class="col-acoes">
            <div class="btn-group" role="group" aria-label="Ações do usuário <?= htmlspecialchars($u['nome'],ENT_QUOTES,'UTF-8') ?>">
              <a class="botao-acao" href="editar_usuario.php?id=<?= $uid ?>"
                aria-label="Editar usuário <?= htmlspecialchars($u['nome'],ENT_QUOTES,'UTF-8') ?>">✏️ Editar</a>

              <a class="botao-acao" href="editar_usuario.php?id=<?= $uid ?>#bateria"
                aria-label="Abrir bateria social de <?= htmlspecialchars($u['nome'],ENT_QUOTES,'UTF-8') ?>">⚡ Bateria</a>

              <form method="post" class="inline"
                    data-action="excluir-usuario"
                    data-confirm="Excluir o usuário '<?= htmlspecialchars($u['nome'],ENT_QUOTES,'UTF-8') ?>'?">
                <input type="hidden" name="acao" value="excluir">
                <input type="hidden" name="id"   value="<?= $uid ?>">
                <button type="submit" class="botao-acao excluir"
                        aria-label="Excluir usuário <?= htmlspecialchars($u['nome'],ENT_QUOTES,'UTF-8') ?>">🗑️ Excluir</button>
              </form>
            </div>
          </td>

          <!-- Form de EDIÇÃO (fica no TD, inputs apontam via form="...") -->
          <td class="celula-salvar col-salvar">
            <form id="fedit_<?= $uid ?>" method="post" action="gerenciar_usuarios.php">
              <input type="hidden" name="acao" value="editar">
              <input type="hidden" name="id" value="<?= $uid ?>">
              <button type="submit" class="botao-acao" aria-label="Salvar alterações de <?= htmlspecialchars($u['nome'],ENT_QUOTES,'UTF-8') ?>">Salvar</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<?php require_once '../includes/rodape.php'; ?>
