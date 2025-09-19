<?php
// pages/gerenciar_pranchas.php
include '../includes/cabecalho.php';
require_once '../includes/controle_pranchas.php';
require_once '../includes/modelo_pranchas.php';

$isAdmin = (isset($_SESSION['tipo_usuario']) && $_SESSION['tipo_usuario'] === 'admin');

/**
 * Retorna os títulos (ou texto alternativo como fallback) dos cartões de uma prancha, na ordem.
 */
function titulosDaPranchaParaFalar(int $idPrancha): array {
    $ids = buscarCartoesDaPrancha($idPrancha);
    if (empty($ids)) return [];
    $cartoes = buscarCartoesPorIds($ids); // mantém ordem
    $out = [];
    foreach ($cartoes as $c) {
        $t = trim($c['titulo'] ?? '');
        if ($t === '') { $t = trim($c['texto_alternativo'] ?? ''); }
        if ($t !== '') {
            $out[] = $t;
        }
    }
    return $out;
}

// Agrupa por nome de grupo
$grupos = [];
foreach ($lista_pranchas as $p) {
    $g = $p['grupo'] ?? 'Sem grupo';
    if (!isset($grupos[$g])) $grupos[$g] = [];
    $grupos[$g][] = $p;
}
// ✅ Ordena os grupos alfabeticamente (case-insensitive, natural)
uksort($grupos, function($a, $b){ return strcasecmp($a, $b); });

// Mapa nome->id dos grupos de pranchas (para links de editar/excluir)
$mapaGrupos = [];
if (isset($lista_grupos_pranchas) && is_array($lista_grupos_pranchas)) {
    foreach ($lista_grupos_pranchas as $g) {
        $mapaGrupos[(string)($g['nome'] ?? '')] = (int)$g['id'];
    }
}
?>

<link rel="stylesheet" href="../assets/css/pranchas.css">

<h2>Gerenciar Pranchas</h2>

<?php if ($isAdmin): ?>
  <p>
    <a href="criar_grupo_prancha.php" class="botao-acao">➕ Criar grupo de pranchas</a>
    <a href="criar_prancha.php" class="botao-acao">📋 Criar prancha</a>
    
  </p>
<?php endif; ?>

<?php if (empty($lista_pranchas)): ?>
  <div class="lista-pranchas--vazia">Nenhuma prancha encontrada.</div>
<?php else: ?>

  <?php foreach ($grupos as $nomeGrupo => $pranchasDoGrupo): ?>
    <?php
      // Tenta descobrir o ID do grupo para os botões (só renderiza se existir).
      $grupoId = $mapaGrupos[$nomeGrupo] ?? null;
    ?>
    <section class="grupo-prancha" aria-labelledby="grp-<?php echo md5($nomeGrupo); ?>">
      <header class="grupo-prancha__header">
        <h3 id="grp-<?php echo md5($nomeGrupo); ?>" class="grupo-prancha__titulo">
          <span class="grupo-prancha__icone" aria-hidden="true">🗂️</span>
          <span><?php echo htmlspecialchars($nomeGrupo ?? 'Sem grupo', ENT_QUOTES, 'UTF-8'); ?></span>
          <span class="grupo-prancha__contagem">(<?php echo count($pranchasDoGrupo); ?> Prancha(s))</span>
        </h3>

        <div class="grupo-prancha__acoes">
          <?php if ($isAdmin && $grupoId): ?>
            <a class="botao-acao" href="editar_grupo_prancha.php?id=<?php echo (int)$grupoId; ?>">✏️ Editar grupo</a>
            <a
              class="botao-acao excluir"
              href="../includes/controle_excluir_grupo_prancha.php?id=<?php echo (int)$grupoId; ?>"
              data-action="excluir-grupo-prancha"
              data-id="<?php echo (int)$grupoId; ?>"
              data-nome="<?php echo htmlspecialchars($nomeGrupo, ENT_QUOTES, 'UTF-8'); ?>"
            >🗑️ Excluir grupo</a>
          <?php endif; ?>
        </div>
      </header>

      <div class="lista-pranchas" role="list">
        <?php foreach ($pranchasDoGrupo as $pr): ?>
          <?php
            $id   = (int)($pr['id'] ?? 0);
            $nome = (string)($pr['nome'] ?? '');
            $listaTitulos = titulosDaPranchaParaFalar($id);
            $listaJson    = htmlspecialchars(json_encode($listaTitulos, JSON_UNESCAPED_UNICODE), ENT_QUOTES, 'UTF-8');
          ?>
          <article class="prancha-item" role="listitem">
            <div class="prancha-item__nome">📋 <?php echo htmlspecialchars($nome, ENT_QUOTES, 'UTF-8'); ?></div>
            <div class="prancha-item__grupo"><?php echo htmlspecialchars($pr['descricao'] ?? '', ENT_QUOTES, 'UTF-8'); ?></div>

            <div class="prancha-item__acoes" aria-label="Ações da prancha">
              <a class="botao-acao" href="ver_prancha.php?id=<?php echo $id; ?>">🔎 Abrir</a>

              <?php if ($isAdmin): ?>
                <a class="botao-acao" href="editar_prancha.php?id=<?php echo $id; ?>">✏️ Editar</a>
                <a class="botao-acao excluir"
                   href="../includes/controle_excluir_prancha.php?id=<?php echo $id; ?>"
                   data-action="excluir-prancha"
                   data-id="<?php echo $id; ?>"
                   data-nome="<?php echo htmlspecialchars($nome, ENT_QUOTES, 'UTF-8'); ?>">🗑️ Excluir</a>
              <?php endif; ?>

              <!-- Botão FALAR: fala TODOS os cartões da prancha (lista em data-lista) -->
              <a class="botao-acao"
                 href="#"
                 data-action="falar-prancha"
                 data-texto="<?php echo htmlspecialchars($nome, ENT_QUOTES, 'UTF-8'); ?>"
                 data-lista="<?php echo $listaJson; ?>">🗣️ Falar</a>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
    </section>
  <?php endforeach; ?>

<?php endif; ?>

<script src="../assets/js/falar.js"></script>
<script src="../assets/js/pranchas.js"></script>

<?php include '../includes/rodape.php'; ?>
