<?php
require_once '../includes/cabecalho.php';
require_once '../includes/controle_usuarios_admin.php'; // popula $lista_usuarios e mensagens
?>
<link rel="stylesheet" href="../assets/css/form-usuarios.css"><!-- grade do form -->
<link rel="stylesheet" href="../assets/css/usuarios.css"><!-- NOVO layout responsivo -->
<script src="../assets/js/usuarios_admin.js" defer></script>

<?php if (!empty($mensagem_usuarios)): ?>
  <p class="alert <?= htmlspecialchars($classe_msg_usuarios,ENT_QUOTES,'UTF-8') ?>">
    <?= htmlspecialchars($mensagem_usuarios,ENT_QUOTES,'UTF-8') ?>
  </p>
<?php endif; ?>

<!-- ====== CRIAR NOVO USUÁRIO ====== -->
<section class="card" aria-labelledby="titulo-criar">
  <h2 id="titulo-criar" style="margin-top:0">Criar novo usuário</h2>
  <form method="post" action="gerenciar_usuarios.php" class="form-grid" autocomplete="off" novalidate>
    <input type="hidden" name="acao" value="criar">

    <div>
      <label for="c_nome">Nome</label>
      <input id="c_nome"  name="nome"  type="text" required autocomplete="name">
    </div>

    <div>
      <label for="c_email">Email</label>
      <input id="c_email" name="email" type="email" required autocomplete="email">
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

<!-- ====== LISTA/EDIÇÃO DE USUÁRIOS (layout novo) ====== -->

<section class="lista-usuarios" aria-labelledby="titulo-lista">
  <h2 id="titulo-lista">Usuários cadastrados</h2>

  <!-- Cabeçalho só aparece no desktop -->
  <div class="usuarios-head" role="row">
    <div role="columnheader">ID</div>
    <div role="columnheader">Nome</div>
    <div role="columnheader">Email</div>
    <div role="columnheader">Ações</div>
  </div>

  <div class="usuarios-rows" role="table" aria-label="Lista de usuários">
    <?php foreach ($lista_usuarios as $u): ?>
      <?php
        $uid = (int)$u['id'];
        $nome = $u['nome'] ?? '';
        $email = $u['email'] ?? '';
      ?>
      <article class="usuario-row" role="row" aria-label="Usuário <?= htmlspecialchars($nome,ENT_QUOTES,'UTF-8') ?>">
        <div class="campo" role="cell">
          <span class="label">ID</span>
          <span class="valor"><?= $uid ?></span>
        </div>

        <div class="campo" role="cell">
          <span class="label">Nome</span>
          <span class="valor"><?= htmlspecialchars($nome,ENT_QUOTES,'UTF-8') ?></span>
        </div>

        <div class="campo" role="cell">
          <span class="label">Email</span>
          <span class="valor"><?= htmlspecialchars($email,ENT_QUOTES,'UTF-8') ?></span>
        </div>

        <div class="acao" role="cell">
          <div class="acoes" role="group" aria-label="Ações para <?= htmlspecialchars($nome,ENT_QUOTES,'UTF-8') ?>">
            <a class="botao-acao" href="editar_usuario.php?id=<?= $uid ?>"
               aria-label="Editar usuário <?= htmlspecialchars($nome,ENT_QUOTES,'UTF-8') ?>">✏️ Editar</a>

            <a class="botao-acao" href="editar_usuario.php?id=<?= $uid ?>#bateria"
               aria-label="Abrir bateria social de <?= htmlspecialchars($nome,ENT_QUOTES,'UTF-8') ?>">⚡ Bateria</a>

            <form method="post" class="inline"
                  data-action="excluir-usuario"
                  data-confirm="Excluir o usuário '<?= htmlspecialchars($nome,ENT_QUOTES,'UTF-8') ?>'?">
              <input type="hidden" name="acao" value="excluir">
              <input type="hidden" name="id"   value="<?= $uid ?>">
              <button type="submit" class="botao-acao excluir"
                      aria-label="Excluir usuário <?= htmlspecialchars($nome,ENT_QUOTES,'UTF-8') ?>">🗑️ Excluir</button>
            </form>
          </div>
        </div>
      </article>
    <?php endforeach; ?>
  </div>
</section>

<?php require_once '../includes/rodape.php'; ?>
