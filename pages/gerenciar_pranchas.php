<?php
// pages/gerenciar_pranchas.php
include '../includes/cabecalho.php';
require_once '../includes/controle_pranchas.php';

// Sinalizadores de permissões
$isAdmin = (isset($_SESSION['tipo_usuario']) && $_SESSION['tipo_usuario'] === 'admin');

// $lista_pranchas vem de includes/controle_pranchas.php
// Para admin, também recebemos $lista_grupos_pranchas (id, nome)

// Agrupa pranchas por nome de grupo (apenas as visíveis ao usuário atual)
$pranchasPorGrupo = [];
foreach (($lista_pranchas ?? []) as $p) {
  $nomeGrupo = $p['grupo'] ?? 'Sem grupo';
  if (!isset($pranchasPorGrupo[$nomeGrupo])) $pranchasPorGrupo[$nomeGrupo] = [];
  $pranchasPorGrupo[$nomeGrupo][] = $p;
}

// Monta lista de grupos a iterar
// - Admin: usa $lista_grupos_pranchas (id, nome) para habilitar botões de editar/excluir grupo
// - Usuário comum: deriva dos grupos presentes nas pranchas visíveis (sem id)
$gruposParaExibir = [];
if ($isAdmin && !empty($lista_grupos_pranchas)) {
  foreach ($lista_grupos_pranchas as $g) {
    $nome = $g['nome'] ?? 'Sem grupo';
    $gruposParaExibir[] = ['id' => (int)$g['id'], 'nome' => $nome];
    // Garante a chave no agrupamento mesmo que esteja vazia
    if (!isset($pranchasPorGrupo[$nome])) $pranchasPorGrupo[$nome] = [];
  }
} else {
  // Somente grupos com pranchas visíveis ao usuário
  foreach (array_keys($pranchasPorGrupo) as $nome) {
    $gruposParaExibir[] = ['id' => null, 'nome' => $nome];
  }
  // Ordena alfabeticamente
  usort($gruposParaExibir, function($a, $b){ return strcasecmp($a['nome'], $b['nome']); });
}

// Mensagens de feedback (ex.: ?sucesso=1 / ?erro=1 ao excluir)
$sucesso  = isset($_GET['sucesso']) ? (int)$_GET['sucesso'] : 0;
$erro     = isset($_GET['erro'])    ? (int)$_GET['erro']    : 0;
?>
<link rel="stylesheet" href="../assets/css/pranchas.css">

<h2>Gerenciar Pranchas</h2>

<?php if ($sucesso): ?>
  <div class="alert alert--success" role="alert" aria-live="polite">Operação realizada com sucesso.</div>
<?php endif; ?>
<?php if ($erro): ?>
  <div class="alert alert--danger" role="alert" aria-live="polite">Não foi possível concluir a operação.</div>
<?php endif; ?>

<div class="barra-acoes-topo">
  <?php if ($isAdmin): ?>
    <a class="botao-acao" href="criar_prancha.php">➕ Nova prancha</a>
    <a class="botao-acao" href="criar_grupo_prancha.php">🗂️ Novo grupo</a>
  <?php endif; ?>
</div>

<?php if (empty($gruposParaExibir)): ?>
  <p class="help">Nenhuma prancha encontrada.</p>
<?php else: ?>
  <div class="grupos">
    <?php foreach ($gruposParaExibir as $g): ?>
      <?php
        $nomeGrupo = $g['nome'];
        $itens = $pranchasPorGrupo[$nomeGrupo] ?? [];
        $qtd   = count($itens);
      ?>
      <section class="grupo" aria-labelledby="titulo-grupo-<?php echo htmlspecialchars(md5($nomeGrupo), ENT_QUOTES, 'UTF-8'); ?>">
        <header class="grupo-cabecalho">
          <h3 id="titulo-grupo-<?php echo htmlspecialchars(md5($nomeGrupo), ENT_QUOTES, 'UTF-8'); ?>" class="grupo-titulo">
            <span class="grupo-icone" aria-hidden="true">🗂️</span>
            <span class="grupo-nome"><?php echo htmlspecialchars($nomeGrupo, ENT_QUOTES, 'UTF-8'); ?></span>
            <span class="grupo-contador" aria-label="Quantidade neste grupo"><?php echo (int)$qtd; ?></span>
          </h3>

          <?php if ($isAdmin && !empty($g['id'])): ?>
            <div class="grupo-acoes" aria-label="Ações do grupo">
              <a class="botao-acao" href="editar_grupo_prancha.php?id=<?php echo (int)$g['id']; ?>">✏️ Editar grupo</a>
              <a class="botao-acao excluir"
                 href="../includes/controle_excluir_grupo_prancha.php?id=<?php echo (int)$g['id']; ?>"
                 onclick="return confirmarExclusaoGrupo(<?php echo (int)$g['id']; ?>);">🗑️ Excluir grupo</a>
            </div>
          <?php endif; ?>
        </header>

        <?php if ($qtd === 0): ?>
          <p class="help">Sem pranchas neste grupo.</p>
        <?php else: ?>
          <ul class="lista-pranchas">
            <?php foreach ($itens as $p): ?>
              <li class="prancha-item">
                <div class="prancha-nome" title="Prancha #<?php echo (int)$p['id']; ?>">
                  📋 <?php echo htmlspecialchars($p['nome'], ENT_QUOTES, 'UTF-8'); ?>
                </div>
                <div class="prancha-acoes">
                  <?php if ($isAdmin): ?>
                    <a class="botao-acao" href="editar_prancha.php?id=<?php echo (int)$p['id']; ?>">✏️ Editar</a>
                    <a class="botao-acao excluir"
                       href="../includes/controle_excluir_prancha.php?id=<?php echo (int)$p['id']; ?>"
                       onclick="return confirmarExclusaoPrancha(<?php echo (int)$p['id']; ?>);">🗑️ Excluir</a>
                  <?php endif; ?>
                </div>
              </li>
            <?php endforeach; ?>
          </ul>
        <?php endif; ?>
      </section>
    <?php endforeach; ?>
  </div>
<?php endif; ?>

<script src="../assets/js/pranchas_admin.js"></script>
<?php include '../includes/rodape.php'; ?>
