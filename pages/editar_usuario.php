<?php
// pages/editar_usuario.php
include '../includes/cabecalho.php';
require_once '../includes/acl.php';
require_admin();

require_once '../includes/modelo_usuarios.php';

$erro = '';
$ok   = '';
$id   = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
  http_response_code(400);
  echo "<p>ID inválido.</p>";
  include '../includes/rodape.php';
  exit;
}

$usuario = buscarUsuarioPorId($id);
if (!$usuario) {
  http_response_code(404);
  echo "<p>Usuário não encontrado.</p>";
  include '../includes/rodape.php';
  exit;
}

/* POST: editar dados básicos */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['acao'] ?? '') === 'editar') {
  $nome  = trim($_POST['nome'] ?? '');
  $email = trim($_POST['email'] ?? '');
  $tipo  = $_POST['tipo'] ?? 'user';
  $tema  = $_POST['tema'] ?? 'light';

  if ($nome === '' || $email === '') {
    $erro = 'Nome e e-mail são obrigatórios.';
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $erro = 'E-mail inválido.';
  } elseif (emailJaExiste($email, $id)) {
    $erro = 'Já existe um usuário com este e-mail.';
  } elseif (!tipoValido($tipo)) {
    $erro = 'Tipo de usuário inválido.';
  } else {
    // Protege rebaixar o ÚLTIMO admin
    $eraAdmin = ($usuario['tipo'] === 'admin');
    if ($eraAdmin && $tipo !== 'admin' && contarAdmins() <= 1) {
      $erro = 'Não é possível rebaixar o último administrador.';
    } else {
      if (atualizarUsuario($id, $nome, $email, $tipo, $tema)) {
        $ok = 'Usuário atualizado com sucesso.';
        $usuario = buscarUsuarioPorId($id); // recarrega
      } else {
        $erro = 'Falha ao atualizar usuário.';
      }
    }
  }
}

/* POST: trocar senha */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['acao'] ?? '') === 'senha') {
  $nova = trim($_POST['nova_senha'] ?? '');
  $conf = trim($_POST['confirma_senha'] ?? '');
  if ($nova === '' || $conf === '') {
    $erro = 'Preencha a nova senha e a confirmação.';
  } elseif ($nova !== $conf) {
    $erro = 'A confirmação não confere.';
  } elseif (strlen($nova) < 6) {
    $erro = 'A nova senha deve ter pelo menos 6 caracteres.';
  } else {
    if (alterarSenhaUsuario($id, $nova)) {
      $ok = 'Senha alterada com sucesso.';
    } else {
      $erro = 'Não foi possível alterar a senha.';
    }
  }
}

/* POST: atualizar bateria social */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['acao'] ?? '') === 'bateria') {
  $nivel = isset($_POST['nivel']) ? (int)$_POST['nivel'] : -1;
  if ($nivel < 0 || $nivel > 5) {
    $erro = 'Nível inválido (0–5).';
  } else {
    if (atualizarBateriaSocial($id, $nivel)) {
      $ok = 'Bateria social atualizada.';
    } else {
      $erro = 'Falha ao salvar o nível de bateria.';
    }
  }
}

/* Dados atuais da bateria para preencher o form */
$bat = obterBateriaSocial($id);
$batNivel = (int)($bat['nivel'] ?? 3);
$batQuando = $bat['atualizado_em'] ? date('d/m/Y H:i', strtotime($bat['atualizado_em'])) : 'nunca';
$labels = [
  0 => '0 – Esgotado',
  1 => '1 – Baixíssimo',
  2 => '2 – Baixo',
  3 => '3 – Neutro',
  4 => '4 – Bom',
  5 => '5 – Cheio',
];
?>
<link rel="stylesheet" href="../assets/css/form-usuarios.css">
<link rel="stylesheet" href="../assets/css/bateria.css">

<h2>Editar Usuário</h2>

<?php if ($erro): ?>
  <div class="alerta erro" role="alert"><?= htmlspecialchars($erro, ENT_QUOTES, 'UTF-8') ?></div>
<?php endif; ?>
<?php if ($ok): ?>
  <div class="alerta ok" role="status"><?= htmlspecialchars($ok, ENT_QUOTES, 'UTF-8') ?></div>
<?php endif; ?>

<section class="card">
  <h3>Dados do usuário</h3>
  <form method="post" class="form-grid" novalidate>
    <input type="hidden" name="acao" value="editar">
    <div>
      <label for="nome">Nome</label>
      <input id="nome" name="nome" type="text" required value="<?= htmlspecialchars($usuario['nome'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
    </div>
    <div>
      <label for="email">E-mail</label>
      <input id="email" name="email" type="email" required value="<?= htmlspecialchars($usuario['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
    </div>
    <div>
      <label for="tipo">Tipo</label>
      <select id="tipo" name="tipo" required>
        <option value="user"  <?= ($usuario['tipo'] ?? '') === 'user'  ? 'selected' : '' ?>>Usuário</option>
        <option value="admin" <?= ($usuario['tipo'] ?? '') === 'admin' ? 'selected' : '' ?>>Admin</option>
      </select>
    </div>
    <div>
      <label for="tema">Tema</label>
      <select id="tema" name="tema" required>
        <option value="light" <?= ($usuario['tema_preferido'] ?? 'light') === 'light' ? 'selected' : '' ?>>Claro</option>
        <option value="dark"  <?= ($usuario['tema_preferido'] ?? 'light') === 'dark'  ? 'selected' : '' ?>>Escuro</option>
      </select>
    </div>
    <div class="linha-acoes">
      <button type="submit" class="botao-acao">💾 Salvar</button>
      <a class="botao-acao" href="gerenciar_usuarios.php">↩️ Voltar</a>
    </div>
  </form>
</section>

<section class="card" id="senha">
  <h3>Alterar senha</h3>
  <form method="post" class="form-grid" novalidate>
    <input type="hidden" name="acao" value="senha">
    <div>
      <label for="nova_senha">Nova senha</label>
      <input id="nova_senha" name="nova_senha" type="password" minlength="6" required autocomplete="new-password">
    </div>
    <div>
      <label for="confirma_senha">Confirmar nova senha</label>
      <input id="confirma_senha" name="confirma_senha" type="password" minlength="6" required autocomplete="new-password">
    </div>
    <div class="linha-acoes">
      <button type="submit" class="botao-acao">🔑 Alterar senha</button>
    </div>
  </form>
</section>

<section class="card" id="bateria">
  <h3>Bateria social</h3>
  <form method="post" class="form-grid" novalidate>
    <input type="hidden" name="acao" value="bateria">
    <div>
      <label for="nivel">Nível</label>
      <select id="nivel" name="nivel" required>
        <?php foreach ($labels as $v => $txt): ?>
          <option value="<?= $v ?>" <?= $batNivel === $v ? 'selected' : '' ?>><?= htmlspecialchars($txt, ENT_QUOTES, 'UTF-8') ?></option>
        <?php endforeach; ?>
      </select>
      <p class="help">Última atualização: <?= htmlspecialchars($batQuando, ENT_QUOTES, 'UTF-8') ?></p>
    </div>
    <div class="linha-acoes">
      <button type="submit" class="botao-acao">⚡ Salvar nível</button>
    </div>
  </form>
</section>

<?php include '../includes/rodape.php'; ?>
