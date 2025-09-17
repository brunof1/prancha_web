<?php
include '../includes/cabecalho.php';
require_once '../includes/controle_usuarios_admin.php';
?>
<link rel="stylesheet" href="../assets/css/usuarios.css">

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

    <label class="sr-only" for="novo_nome">Nome</label>
    <input id="novo_nome" type="text" name="nome" placeholder="Nome" required aria-required="true">

    <label class="sr-only" for="novo_email">Email</label>
    <input id="novo_email" type="email" name="email" placeholder="Email" required aria-required="true">

    <label class="sr-only" for="novo_senha">Senha</label>
    <input id="novo_senha" type="password" name="senha" placeholder="Senha (min. 6)" minlength="6" required aria-required="true" autocomplete="new-password">

    <label class="sr-only" for="novo_tipo">Tipo</label>
    <select id="novo_tipo" name="tipo" required aria-required="true">
      <option value="user">user</option>
      <option value="admin">admin</option>
    </select>

    <label class="sr-only" for="novo_tema">Tema</label>
    <select id="novo_tema" name="tema_preferido" aria-label="Tema preferido">
      <option value="light">Claro</option>
      <option value="dark">Escuro</option>
    </select>

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
      <!-- Larguras fixas para manter alinhamento perfeito -->
      <colgroup>
        <col class="id"><col class="nome"><col class="email"><col class="tipo"><col class="tema"><col class="acoes"><col class="salvar">
      </colgroup>
      <thead>
        <tr role="row">
          <th scope="col">ID</th>
          <th scope="col">Nome</th>
          <th scope="col">Email</th>
          <th scope="col">Tipo</th>
          <th scope="col">Tema</th>
          <th scope="col">Ações</th>
          <th scope="col">Salvar</th> <!-- NOVA COLUNA -->
        </tr>
      </thead>
      <tbody>
        <?php foreach ($lista_usuarios as $u): ?>
          <?php $fid = 'form_editar_' . (int)$u['id']; ?>
          <tr>
            <td><?php echo (int)$u['id']; ?></td>

            <!-- Cada campo fica na sua coluna e aponta para o formulário da última coluna -->
            <td>
              <label class="sr-only" for="nome_<?php echo (int)$u['id']; ?>">Nome</label>
              <input id="nome_<?php echo (int)$u['id']; ?>" type="text" name="nome"
                     value="<?php echo htmlspecialchars($u['nome'], ENT_QUOTES, 'UTF-8'); ?>"
                     required aria-required="true" form="<?php echo $fid; ?>">
            </td>

            <td>
              <label class="sr-only" for="email_<?php echo (int)$u['id']; ?>">Email</label>
              <input id="email_<?php echo (int)$u['id']; ?>" type="email" name="email"
                     value="<?php echo htmlspecialchars($u['email'], ENT_QUOTES, 'UTF-8'); ?>"
                     required aria-required="true" form="<?php echo $fid; ?>">
            </td>

            <td>
              <label class="sr-only" for="tipo_<?php echo (int)$u['id']; ?>">Tipo</label>
              <select id="tipo_<?php echo (int)$u['id']; ?>" name="tipo" aria-label="Tipo"
                      form="<?php echo $fid; ?>">
                <option value="user"  <?php echo $u['tipo']==='user'  ? 'selected' : ''; ?>>user</option>
                <option value="admin" <?php echo $u['tipo']==='admin' ? 'selected' : ''; ?>>admin</option>
              </select>
            </td>

            <td>
              <label class="sr-only" for="tema_<?php echo (int)$u['id']; ?>">Tema</label>
              <select id="tema_<?php echo (int)$u['id']; ?>" name="tema_preferido" aria-label="Tema preferido"
                      form="<?php echo $fid; ?>">
                <option value="light" <?php echo $u['tema_preferido']==='light' ? 'selected' : ''; ?>>Claro</option>
                <option value="dark"  <?php echo $u['tema_preferido']==='dark'  ? 'selected' : ''; ?>>Escuro</option>
              </select>
            </td>

            <td class="celula-acao">
              <details>
                <summary class="botao-acao" aria-label="Abrir ações">⚙️ Ações</summary>
                <div style="margin-top:8px;">
                  <!-- Redefinir senha -->
                  <form method="post" class="form-inline" aria-label="Redefinir senha do usuário #<?php echo (int)$u['id']; ?>">
                    <input type="hidden" name="acao" value="senha">
                    <input type="hidden" name="id" value="<?php echo (int)$u['id']; ?>">
                    <input type="password" name="nova_senha" placeholder="Nova senha (min. 6)" minlength="6" required aria-required="true" autocomplete="new-password">
                    <input type="password" name="confirma_senha" placeholder="Confirmar senha" minlength="6" required aria-required="true" autocomplete="new-password">
                    <button type="submit" class="botao-acao">🔒 Redefinir</button>
                  </form>

                  <!-- Excluir -->
                  <form method="post" class="form-inline" onsubmit="return confirmarExclusao(<?php echo (int)$u['id']; ?>);" aria-label="Excluir usuário #<?php echo (int)$u['id']; ?>">
                    <input type="hidden" name="acao" value="excluir">
                    <input type="hidden" name="id" value="<?php echo (int)$u['id']; ?>">
                    <?php
                      $ultimoAdmin = ($u['tipo']==='admin' && contarAdmins() <= 1);
                      $ehProprio   = ((int)$u['id'] === (int)$_SESSION['id_usuario']);
                    ?>
                    <button type="submit" class="botao-acao excluir"
                            <?php echo ($ultimoAdmin || $ehProprio) ? 'disabled aria-disabled="true" title="Ação indisponível"' : ''; ?>>
                      🗑️ Excluir
                    </button>
                  </form>
                </div>
              </details>
            </td>

            <!-- NOVA COLUNA: formulário que reúne os campos da linha -->
            <td class="celula-salvar">
              <form id="<?php echo $fid; ?>" method="post" aria-label="Salvar alterações do usuário #<?php echo (int)$u['id']; ?>">
                <input type="hidden" name="acao" value="editar">
                <input type="hidden" name="id" value="<?php echo (int)$u['id']; ?>">
                <button type="submit" class="botao-acao">💾 Salvar</button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
</section>

<script src="../assets/js/usuarios_admin.js"></script>

<?php include '../includes/rodape.php'; ?>
