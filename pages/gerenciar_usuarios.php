BEGIN FILE: C:\Users\Bruno\OneDrive\Documentos\GitHub\prancha_web\pages\gerenciar_usuarios.php
----------------------------------------------------------------------------------------------------
<?php
include '../includes/cabecalho.php';
require_once '../includes/controle_usuarios_admin.php';
?>

<!-- CSS opcional específico (usa botões 44x44 via style.css) -->
<style>
.tabela { width:100%; border-collapse: collapse; }
.tabela th, .tabela td { border:1px solid #ddd; padding:8px; vertical-align: top; }
.tabela th { background: #f3f3f3; text-align:left; }
.form-inline { display:flex; flex-wrap:wrap; gap:8px; align-items:center; }
@media (max-width: 720px) { .tabela { font-size: 0.95rem; } }
details > summary { cursor:pointer; }
</style>

<h2>Gerenciar Usuários</h2>

<?php if (!empty($mensagem_usuarios)): ?>
  <div class="alert <?php echo $classe_msg_usuarios; ?>" role="alert" aria-live="polite">
    <?php echo htmlspecialchars($mensagem_usuarios, ENT_QUOTES, 'UTF-8'); ?>
  </div>
<?php endif; ?>

<section aria-labelledby="titulo-criar">
  <h3 id="titulo-criar">Adicionar usuário</h3>
  <form method="post" class="form-inline" novalidate>
    <input type="hidden" name="acao" value="criar">

    <label>
      <span class="sr-only">Nome</span>
      <input type="text" name="nome" placeholder="Nome" required aria-required="true">
    </label>

    <label>
      <span class="sr-only">Email</span>
      <input type="email" name="email" placeholder="Email" required aria-required="true">
    </label>

    <label>
      <span class="sr-only">Senha</span>
      <input type="password" name="senha" placeholder="Senha (min. 6)" minlength="6" required aria-required="true" autocomplete="new-password">
    </label>

    <label>
      <span class="sr-only">Tipo</span>
      <select name="tipo" required aria-required="true">
        <option value="user">user</option>
        <option value="admin">admin</option>
      </select>
    </label>

    <label>
      <span class="sr-only">Tema</span>
      <select name="tema_preferido" aria-label="Tema preferido">
        <option value="light">Claro</option>
        <option value="dark">Escuro</option>
      </select>
    </label>

    <button type="submit" class="botao-acao">➕ Criar</button>
  </form>
</section>

<hr aria-hidden="true">

<section aria-labelledby="titulo-lista">
  <h3 id="titulo-lista">Usuários cadastrados</h3>

  <?php if (count($lista_usuarios) === 0): ?>
    <p>Nenhum usuário encontrado.</p>
  <?php else: ?>
    <table class="tabela" role="table">
      <thead>
        <tr role="row">
          <th scope="col">ID</th>
          <th scope="col">Nome</th>
          <th scope="col">Email</th>
          <th scope="col">Tipo</th>
          <th scope="col">Tema</th>
          <th scope="col">Ações</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($lista_usuarios as $u): ?>
          <tr>
            <td><?php echo (int)$u['id']; ?></td>
            <td>
              <form method="post" class="form-inline" aria-label="Editar usuário #<?php echo (int)$u['id']; ?>">
                <input type="hidden" name="acao" value="editar">
                <input type="hidden" name="id" value="<?php echo (int)$u['id']; ?>">
                <input type="text" name="nome" value="<?php echo htmlspecialchars($u['nome'], ENT_QUOTES, 'UTF-8'); ?>" required aria-required="true">
                <input type="email" name="email" value="<?php echo htmlspecialchars($u['email'], ENT_QUOTES, 'UTF-8'); ?>" required aria-required="true">
                <select name="tipo" aria-label="Tipo">
                  <option value="user"  <?php echo $u['tipo']==='user'  ? 'selected' : ''; ?>>user</option>
                  <option value="admin" <?php echo $u['tipo']==='admin' ? 'selected' : ''; ?>>admin</option>
                </select>
                <select name="tema_preferido" aria-label="Tema preferido">
                  <option value="light" <?php echo $u['tema_preferido']==='light' ? 'selected' : ''; ?>>Claro</option>
                  <option value="dark"  <?php echo $u['tema_preferido']==='dark'  ? 'selected' : ''; ?>>Escuro</option>
                </select>
                <button type="submit" class="botao-acao">💾 Salvar</button>
              </form>
            </td>
            <td><?php /* coluna Email já está acima no formulário; manter célula por semântica */ ?></td>
            <td><?php /* coluna Tipo idem */ ?></td>
            <td><?php /* coluna Tema idem */ ?></td>
            <td>
              <details>
                <summary class="botao-acao" aria-label="Abrir ações">⚙️ Ações</summary>
                <div style="margin-top:8px;">
                  <form method="post" class="form-inline" aria-label="Redefinir senha do usuário #<?php echo (int)$u['id']; ?>">
                    <input type="hidden" name="acao" value="senha">
                    <input type="hidden" name="id" value="<?php echo (int)$u['id']; ?>">
                    <input type="password" name="nova_senha" placeholder="Nova senha (min. 6)" minlength="6" required aria-required="true" autocomplete="new-password">
                    <input type="password" name="confirma_senha" placeholder="Confirmar senha" minlength="6" required aria-required="true" autocomplete="new-password">
                    <button type="submit" class="botao-acao">🔒 Redefinir</button>
                  </form>

                  <form method="post" class="form-inline" onsubmit="return confirmarExclusao(<?php echo (int)$u['id']; ?>);" aria-label="Excluir usuário #<?php echo (int)$u['id']; ?>">
                    <input type="hidden" name="acao" value="excluir">
                    <input type="hidden" name="id" value="<?php echo (int)$u['id']; ?>">
                    <?php
                      $ultimoAdmin = ($u['tipo']==='admin' && contarAdmins() <= 1);
                      $ehProprio   = ((int)$u['id'] === (int)$_SESSION['id_usuario']);
                    ?>
                    <button type="submit" class="botao-acao" <?php echo ($ultimoAdmin || $ehProprio) ? 'disabled aria-disabled="true" title="Ação indisponível"' : ''; ?>>🗑️ Excluir</button>
                  </form>
                </div>
              </details>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
</section>

<script src="../assets/js/usuarios_admin.js"></script>

<?php include '../includes/rodape.php'; ?>
END FILE: C:\Users\Bruno\OneDrive\Documentos\GitHub\prancha_web\pages\gerenciar_usuarios.php
