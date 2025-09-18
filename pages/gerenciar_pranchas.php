<?php
// pages/gerenciar_pranchas.php
include '../includes/cabecalho.php';
require_once '../includes/controle_pranchas.php'; // entrega $lista_grupos_pranchas e pranchas visíveis
require_once '../includes/acl.php';

$isAdmin = (isset($_SESSION['tipo_usuario']) && $_SESSION['tipo_usuario'] === 'admin');

/**
 * Utiliza a conexão já aberta em cabecalho.php ($conn)
 * para montar um mapa id_grupo => total de pranchas
 */
$contagens = [];
if (isset($conn) && $conn instanceof mysqli) {
  $sqlCount = "SELECT id_grupo, COUNT(*) AS total FROM pranchas GROUP BY id_grupo";
  if ($rs = $conn->query($sqlCount)) {
    while ($r = $rs->fetch_assoc()) {
      $contagens[(int)$r['id_grupo']] = (int)$r['total'];
    }
    $rs->close();
  }
}

/**
 * Busca pranchas de um grupo específico (somente admins veem todas;
 * usuários veem apenas as vinculadas a eles)
 */
function pranchasDoGrupo(mysqli $cx, int $idGrupo, int $idUsuario, bool $isAdmin): array {
  if ($isAdmin) {
    $sql = "SELECT p.id, p.nome FROM pranchas p WHERE p.id_grupo = ? ORDER BY p.nome";
    $st  = $cx->prepare($sql);
    $st->bind_param("i", $idGrupo);
  } else {
    $sql = "SELECT p.id, p.nome
              FROM pranchas p
              JOIN pranchas_usuarios pu ON pu.id_prancha = p.id
             WHERE p.id_grupo = ? AND pu.id_usuario = ?
             ORDER BY p.nome";
    $st  = $cx->prepare($sql);
    $st->bind_param("ii", $idGrupo, $idUsuario);
  }
  $out = [];
  if ($st && $st->execute()) {
    $st->bind_result($id, $nome);
    while ($st->fetch()) { $out[] = ['id' => (int)$id, 'nome' => $nome]; }
  }
  if ($st) $st->close();
  return $out;
}

$idUsuario = (int)($_SESSION['id_usuario'] ?? 0);

// mensagens de feedback
$alert = '';
if (isset($_GET['sucesso'])) { $alert = '<div class="alert alert--success" role="alert">Operação realizada com sucesso.</div>'; }
if (isset($_GET['erro']))    { $alert = '<div class="alert alert--danger" role="alert">Não foi possível concluir a operação.</div>'; }
?>

<style>
  /* Ajustes visuais para espelhar gerenciar_cartoes.php */
  .toolbar-superior {
    display:flex; gap:8px; flex-wrap:wrap; align-items:center; margin-bottom:10px;
  }
  .grupo-bloco {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 12px;
    margin: 14px 0;
    box-shadow: var(--shadow-sm);
  }
  .grupo-cabeca {
    display:flex; align-items:center; gap:12px; justify-content:space-between; margin-bottom:10px;
  }
  .grupo-titulo {
    display:flex; align-items:center; gap:10px; margin:0;
    font-size:1.05rem;
  }
  .grupo-titulo .icone { font-size:1.15rem; }
  .grupo-meta {
    color: var(--muted);
    font-weight:600;
    font-size:.95rem;
  }
  .grupo-acoes { display:flex; gap:8px; }
  /* Reaproveita grid/card dos cartões para listar pranchas */
  .lista-pranchas {
    display:grid;
    grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
    gap: 12px;
  }
  .prancha-item {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 10px;
    box-shadow: var(--shadow-sm);
    transition: transform .12s ease, box-shadow .18s ease, border-color .18s ease;
  }
  .prancha-item:hover {
    transform: translateY(-2px);
    border-color: var(--primary);
    box-shadow: var(--shadow-md);
  }
  .prancha-nome { display:block; font-weight:700; margin-bottom:8px; }
  .prancha-acoes { display:flex; gap:8px; flex-wrap:wrap; }
</style>

<h2>Gerenciar Pranchas</h2>

<?php echo $alert; ?>

<div class="toolbar-superior" role="toolbar" aria-label="Ações principais">
  <?php if ($isAdmin): ?>
    <a href="criar_prancha.php" class="botao-acao">➕ Nova prancha</a>
    <a href="criar_grupo_prancha.php" class="botao-acao">🗂️ Novo grupo</a>
  <?php endif; ?>
  <a href="dashboard.php" class="botao-acao">↩️ Voltar</a>
</div>

<?php if (empty($lista_grupos_pranchas)): ?>
  <div class="alert" role="status">Nenhum grupo de pranchas cadastrado ainda.</div>
<?php else: ?>
  <?php foreach ($lista_grupos_pranchas as $g): ?>
    <?php
      $gid = (int)$g['id'];
      $qtd = $contagens[$gid] ?? 0;
      $pranchas = pranchasDoGrupo($conn, $gid, $idUsuario, $isAdmin);
    ?>
    <section class="grupo-bloco" aria-labelledby="grupo-<?php echo $gid; ?>">
      <div class="grupo-cabeca">
        <h3 id="grupo-<?php echo $gid; ?>" class="grupo-titulo">
          <span class="icone" aria-hidden="true">🗂️</span>
          <span><?php echo htmlspecialchars($g['nome'] ?? '', ENT_QUOTES, 'UTF-8'); ?></span>
          <span class="grupo-meta">• <?php echo $qtd; ?> prancha<?php echo $qtd === 1 ? '' : 's'; ?></span>
        </h3>
        <?php if ($isAdmin): ?>
          <div class="grupo-acoes">
            <a class="botao-acao" href="editar_grupo_prancha.php?id=<?php echo $gid; ?>">✏️ Editar grupo</a>
            <a class="botao-acao excluir"
               href="../includes/controle_excluir_grupo_prancha.php?id=<?php echo $gid; ?>"
               onclick="return confirm('Tem certeza que deseja excluir este grupo? Essa ação não pode ser desfeita.');">
               🗑️ Excluir grupo
            </a>
          </div>
        <?php endif; ?>
      </div>

      <?php if (empty($pranchas)): ?>
        <p class="help" style="margin:.25rem 0 .5rem;">Nenhuma prancha neste grupo.</p>
      <?php else: ?>
        <div class="lista-pranchas">
          <?php foreach ($pranchas as $p): ?>
            <article class="prancha-item" aria-label="Prancha <?php echo htmlspecialchars($p['nome'], ENT_QUOTES, 'UTF-8'); ?>">
              <strong class="prancha-nome"><?php echo htmlspecialchars($p['nome'], ENT_QUOTES, 'UTF-8'); ?></strong>
              <div class="prancha-acoes">
                <a class="botao-acao" href="editar_prancha.php?id=<?php echo (int)$p['id']; ?>">✏️ Editar</a>
                <?php if ($isAdmin): ?>
                  <a class="botao-acao excluir"
                     href="../includes/controle_excluir_prancha.php?id=<?php echo (int)$p['id']; ?>"
                     onclick="return confirm('Excluir a prancha &quot;<?php echo htmlspecialchars($p['nome'], ENT_QUOTES, 'UTF-8'); ?>&quot;? Esta ação é irreversível.');">
                     🗑️ Excluir
                  </a>
                <?php endif; ?>
              </div>
            </article>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </section>
  <?php endforeach; ?>
<?php endif; ?>

<?php include '../includes/rodape.php'; ?>
