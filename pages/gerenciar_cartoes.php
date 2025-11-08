<?php
// pages/gerenciar_cartoes.php
include '../includes/cabecalho.php';

// Qualquer usuário logado pode ver esta página.
// Apenas escondemos ações administrativas na UI quando não for admin.
$isAdmin = (isset($_SESSION['tipo_usuario']) && $_SESSION['tipo_usuario'] === 'admin');

// Modelos
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

  <!-- barra de chips de grupos -->
  <div class="grupos-wrap" role="tablist" aria-label="Grupos de cartões">
    <?php foreach ($grupos as $g): ?>
      <?php
        $gid = (int)$g['id'];
        $gnm = (string)($g['nome'] ?? '');
        $cartoesDoGrupo = listarCartoesPorGrupo($gid);
        $count = count($cartoesDoGrupo);
        $painelId = 'painel-cartoes-' . $gid;
      ?>
      <details class="grupo-exp" role="group">
        <summary
          class="grupo-chip"
          role="tab"
          aria-controls="<?= $painelId ?>"
          aria-expanded="false">
          <span aria-hidden="true">🗂️</span>
          <span><?= htmlspecialchars($gnm, ENT_QUOTES, 'UTF-8') ?></span>
          <span class="grupo-chip__count" aria-label="<?= $count ?> cartão(ões)">
            (<?= $count ?>)
          </span>
        </summary>

        <div id="<?= $painelId ?>" class="grupo-exp__painel" role="tabpanel" tabindex="-1">
          <?php if ($isAdmin): ?>
              <div class="grupo-exp__acoes" aria-label="Ações do grupo">
                <details class="acoes-drop">
                  <summary class="botao-acao botao-icone" aria-label="Ações do grupo <?= htmlspecialchars($gnm, ENT_QUOTES, 'UTF-8') ?>" aria-expanded="false">
                    <span aria-hidden="true">⋮</span>
                    <span class="sr-only">Ações do grupo <?= htmlspecialchars($gnm, ENT_QUOTES, 'UTF-8') ?></span>
                  </summary>
                  <div class="acoes-drop__panel" role="menu">
                    <a class="botao-acao menu-link" role="menuitem" href="editar_grupo_cartao.php?id=<?= $gid ?>">✏️ Editar grupo</a>
                    <a class="botao-acao excluir menu-link" role="menuitem"
                      href="../includes/controle_excluir_grupo.php?id=<?= $gid ?>"
                      onclick="return confirm('Tem certeza que deseja excluir o grupo &quot;<?= htmlspecialchars($gnm, ENT_QUOTES, 'UTF-8') ?>&quot;?');">
                      🗑️ Excluir grupo
                    </a>
                  </div>
                </details>
              </div>
            <?php endif; ?>


          <?php if (empty($cartoesDoGrupo)): ?>
            <div class="grupo-exp__vazio">Nenhum cartão neste grupo.</div>
          <?php else: ?>
            <div class="lista-cartoes">
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
                    <img src="<?= $src ?>" alt="<?= htmlspecialchars($alt, ENT_QUOTES, 'UTF-8') ?>">
                  <?php else: ?>
                    <div style="height:120px; display:flex; align-items:center; justify-content:center; border:1px dashed var(--border); border-radius:8px;">
                      <span class="help">Sem imagem</span>
                    </div>
                  <?php endif; ?>

                  <strong style="display:block; margin-top:8px;"><?= htmlspecialchars($tit, ENT_QUOTES, 'UTF-8') ?></strong>

                  <div style="display:flex; gap:8px; justify-content:center; margin-top:8px;">
                    <button
                      type="button"
                      class="botao-acao"
                      onclick='falar(<?= json_encode($textoParaFalar, JSON_UNESCAPED_UNICODE) ?>)'
                      aria-label="Falar: <?= htmlspecialchars($textoParaFalar, ENT_QUOTES, 'UTF-8') ?>">
                      🗣️ Falar
                    </button>

                    <?php if ($isAdmin): ?>
                      <details class="acoes-drop">
                        <summary class="botao-acao botao-icone"
                                aria-label="Ações do cartão <?= htmlspecialchars($c['titulo'], ENT_QUOTES, 'UTF-8') ?>"
                                aria-expanded="false">
                          <span aria-hidden="true">⋮</span>
                          <span class="sr-only">Ações do cartão <?= htmlspecialchars($c['titulo'], ENT_QUOTES, 'UTF-8') ?></span>
                        </summary>
                        <div class="acoes-drop__panel" role="menu">
                          <a class="botao-acao menu-link" role="menuitem"
                            href="editar_cartao.php?id=<?= (int)$c['id'] ?>">✏️ Editar</a>

                          <a class="botao-acao excluir menu-link" role="menuitem"
                            href="../includes/controle_excluir_cartao.php?id=<?= (int)$c['id'] ?>"
                            onclick="return confirm('Excluir o cartão &quot;<?= htmlspecialchars($c['titulo'], ENT_QUOTES, 'UTF-8') ?>&quot;?');">
                            🗑️ Excluir
                          </a>
                        </div>
                      </details>
                    <?php endif; ?>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
        </div>
      </details>
    <?php endforeach; ?>
  </div>

<?php endif; ?>

<!-- TTS / falar e grupos -->
<script src="../assets/js/falar.js"></script>
<script src="../assets/js/grupos.js"></script>

<?php include '../includes/rodape.php'; ?>
