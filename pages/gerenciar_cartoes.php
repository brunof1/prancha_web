<?php
// pages/gerenciar_cartoes.php

include '../includes/cabecalho.php';

// Qualquer usuário logado pode ver esta página.
// Apenas escondemos ações administrativas na UI quando não for admin.
$isAdmin = (isset($_SESSION['tipo_usuario']) && $_SESSION['tipo_usuario'] === 'admin');

// Carrega modelos usando caminho absoluto (evita problemas de include)
require_once __DIR__ . '/../includes/modelo_grupos.php';
require_once __DIR__ . '/../includes/modelo_cartoes.php';

// Dados
$grupos = listarGrupos(); // grupos_cartoes
?>
<h1>🖼️ Gerenciar Cartões</h1>

<?php if (!$isAdmin): ?>
  <div class="alert" role="status" aria-live="polite" style="margin-bottom:12px;">
    Você pode visualizar e <strong>ouvir</strong> os cartões. Ações de criar, editar e excluir ficam disponíveis apenas para administradores.
  </div>
<?php endif; ?>

<?php if ($isAdmin): ?>
  <p style="display:flex; gap:8px; flex-wrap:wrap;">
    <a class="botao-acao" href="criar_grupo_cartao.php">➕ Criar grupo de cartões</a>
    <a class="botao-acao" href="criar_cartao.php">🖼️ Criar cartão</a>
  </p>
<?php endif; ?>

<?php
// feedbacks simples via querystring (usado após ações de admin)
$flags = [
  'sucesso'         => 'Cartão criado com sucesso.',
  'sucesso_excluir' => 'Registro excluído com sucesso.',
  'erro_excluir'    => 'Não foi possível excluir.',
  'gsucesso'        => 'Grupo criado com sucesso.',
  'sucesso_editar'  => 'Grupo atualizado.',
  'edit_ok'         => 'Cartão atualizado.'
];
foreach ($flags as $k => $msg) {
    if (isset($_GET[$k]) && $isAdmin) {
        echo '<div class="alert alert--success" role="alert" aria-live="polite">'
           . htmlspecialchars($msg, ENT_QUOTES, 'UTF-8')
           . '</div>';
    }
}
?>

<?php if (empty($grupos)): ?>
  <div class="alert alert--danger" role="alert" aria-live="polite">
    Nenhum grupo de cartões encontrado.
    <?php if ($isAdmin): ?>
      <a class="botao-acao ml-8" href="criar_grupo_cartao.php">➕ Criar grupo</a>
    <?php endif; ?>
  </div>
<?php else: ?>

  <?php foreach ($grupos as $g): ?>
    <?php
      $gid = (int)$g['id'];
      $gnm = (string)($g['nome'] ?? '');
      $cartoesDoGrupo = listarCartoesPorGrupo($gid);
    ?>

    <section aria-labelledby="grupo-<?php echo $gid; ?>" style="margin:22px 0;">
      <div style="display:flex; align-items:center; justify-content:space-between; gap:8px; flex-wrap:wrap;">
        <h3 id="grupo-<?php echo $gid; ?>" style="margin:0;">
          🗂️ <?php echo htmlspecialchars($gnm, ENT_QUOTES, 'UTF-8'); ?>
          <small class="help" style="margin-left:8px; opacity:.8;">
            (<?php echo count($cartoesDoGrupo); ?> cartão(ões))
          </small>
        </h3>
        <?php if ($isAdmin): ?>
          <div style="display:flex; gap:8px;">
            <a class="botao-acao" href="editar_grupo_cartao.php?id=<?php echo $gid; ?>">✏️ Editar grupo</a>
            <a class="botao-acao excluir" href="../includes/controle_excluir_grupo.php?id=<?php echo $gid; ?>"
               onclick="return confirm('Tem certeza que deseja excluir o grupo &quot;<?php echo htmlspecialchars($gnm, ENT_QUOTES, 'UTF-8'); ?>&quot;? Os cartões permanecerão no banco, mas sem grupo.');">
              🗑️ Excluir grupo
            </a>
          </div>
        <?php endif; ?>
      </div>

      <?php if (empty($cartoesDoGrupo)): ?>
        <p class="help" style="margin:10px 0;">Nenhum cartão neste grupo.</p>
      <?php else: ?>
        <div class="lista-cartoes" style="margin-top:12px;">
          <?php foreach ($cartoesDoGrupo as $c): ?>
            <?php
              $cid   = (int)$c['id'];
              $tit   = (string)($c['titulo'] ?? '');
              $img   = (string)($c['imagem'] ?? '');
              $alt   = (string)($c['texto_alternativo'] ?? $tit);
              $src   = '../imagens/cartoes/' . rawurlencode($img);
              $textoParaFalar = $tit !== '' ? $tit : $alt;
            ?>
            <div class="cartao-item">
              <?php if ($img !== ''): ?>
                <img src="<?php echo $src; ?>" alt="<?php echo htmlspecialchars($alt, ENT_QUOTES, 'UTF-8'); ?>">
              <?php else: ?>
                <div style="height:120px; display:flex; align-items:center; justify-content:center; border:1px dashed var(--border); border-radius:8px;">
                  <span class="help">Sem imagem</span>
                </div>
              <?php endif; ?>

              <strong style="display:block; margin-top:8px;"><?php echo htmlspecialchars($tit, ENT_QUOTES, 'UTF-8'); ?></strong>

              <!-- 🔊 Falar: liberado para todos os usuários -->
              <div style="display:flex; gap:8px; justify-content:center; margin-top:8px;">
                <button
                  type="button"
                  class="botao-acao"
                  onclick='falar(<?php echo json_encode($textoParaFalar, JSON_UNESCAPED_UNICODE); ?>)'
                  aria-label="Falar: <?php echo htmlspecialchars($textoParaFalar, ENT_QUOTES, 'UTF-8'); ?>">
                  🗣️ Falar
                </button>

                <?php if ($isAdmin): ?>
                  <a class="botao-acao" href="editar_cartao.php?id=<?php echo $cid; ?>">✏️ Editar</a>
                  <a class="botao-acao excluir"
                     href="../includes/controle_excluir_cartao.php?id=<?php echo $cid; ?>"
                     onclick="return confirm('Excluir o cartão #<?php echo $cid; ?> &quot;<?php echo htmlspecialchars($tit, ENT_QUOTES, 'UTF-8'); ?>&quot;?');">
                     🗑️ Excluir
                  </a>
                <?php endif; ?>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </section>

    <hr aria-hidden="true">
  <?php endforeach; ?>

<?php endif; ?>

<!-- TTS / falar -->
<script src="../assets/js/falar.js"></script>

<?php include '../includes/rodape.php'; ?>
