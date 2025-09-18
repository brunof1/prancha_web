<?php
include '../includes/cabecalho.php';
require_once '../includes/controle_pranchas.php'; // entrega $lista_pranchas e $lista_grupos_pranchas

$isAdmin = ($_SESSION['tipo_usuario'] === 'admin');

// Contagem por nome de grupo (respeita as pranchas que o usuário pode ver)
$contagemPorGrupoNome = [];
foreach ($lista_pranchas as $p) {
    $g = (string)($p['grupo'] ?? '');
    if ($g === '') continue;
    $contagemPorGrupoNome[$g] = ($contagemPorGrupoNome[$g] ?? 0) + 1;
}

// Helper de pluralização simples
function pluralizarPrancha(int $n): string {
    return $n === 1 ? "1 prancha" : "{$n} pranchas";
}
?>

<!-- CSS específico desta página -->
<link rel="stylesheet" href="../assets/css/pranchas.css">

<h2>Gerenciar Pranchas</h2>

<?php if ($isAdmin): ?>
  <p style="display:flex; gap:8px; flex-wrap:wrap;">
    <a class="botao-acao" href="criar_prancha.php">➕ Nova prancha</a>
    <a class="botao-acao" href="criar_grupo_prancha.php">🗂️ Novo grupo</a>
  </p>
<?php endif; ?>

<?php
// Se não houver grupos cadastrados ainda
if (empty($lista_grupos_pranchas)):
?>
  <div class="lista-pranchas--vazia">Nenhum grupo de pranchas cadastrado.</div>
<?php
else:
  // Renderiza cada grupo, com ícone + contagem + ações do grupo
  foreach ($lista_grupos_pranchas as $g):
    $gid   = (int)$g['id'];
    $gnome = (string)$g['nome'];
    $qtd   = (int)($contagemPorGrupoNome[$gnome] ?? 0);

    // Filtra as pranchas deste grupo pelo NOME do grupo retornado em $lista_pranchas
    $pranchasDoGrupo = array_values(array_filter($lista_pranchas, function($p) use ($gnome){
      return isset($p['grupo']) && (string)$p['grupo'] === $gnome;
    }));
?>
  <section class="grupo-prancha" aria-labelledby="grp-<?php echo $gid; ?>">
    <div class="grupo-prancha__header">
      <h3 id="grp-<?php echo $gid; ?>" class="grupo-prancha__titulo">
        <span class="grupo-prancha__icone" aria-hidden="true">🗂️</span>
        <span><?php echo htmlspecialchars($gnome, ENT_QUOTES, 'UTF-8'); ?></span>
        <span class="grupo-prancha__contagem">(<?php echo htmlspecialchars(pluralizarPrancha($qtd), ENT_QUOTES, 'UTF-8'); ?>)</span>
      </h3>

      <?php if ($isAdmin): ?>
        <div class="grupo-prancha__acoes" role="group" aria-label="Ações do grupo">
          <a class="botao-acao" href="editar_grupo_prancha.php?id=<?php echo $gid; ?>">✏️ Editar grupo</a>
          <a class="botao-acao excluir"
             href="../includes/controle_excluir_grupo_prancha.php?id=<?php echo $gid; ?>"
             data-action="excluir-grupo-prancha"
             data-id="<?php echo $gid; ?>"
             data-nome="<?php echo htmlspecialchars($gnome, ENT_QUOTES, 'UTF-8'); ?>">
            🗑️ Excluir grupo
          </a>
        </div>
      <?php endif; ?>
    </div>

    <?php if (empty($pranchasDoGrupo)): ?>
      <div class="lista-pranchas--vazia">Nenhuma prancha neste grupo.</div>
    <?php else: ?>
      <div class="lista-pranchas">
        <?php foreach ($pranchasDoGrupo as $pr): ?>
          <?php
            $pid   = (int)$pr['id'];
            $pnome = (string)$pr['nome'];
          ?>
          <article class="prancha-item" aria-label="Prancha: <?php echo htmlspecialchars($pnome, ENT_QUOTES, 'UTF-8'); ?>">
            <strong class="prancha-item__nome"><?php echo htmlspecialchars($pnome, ENT_QUOTES, 'UTF-8'); ?></strong>
            <span class="prancha-item__grupo"><?php echo htmlspecialchars($gnome, ENT_QUOTES, 'UTF-8'); ?></span>

            <div class="prancha-item__acoes" role="group" aria-label="Ações da prancha">
              <a href="#"
                 class="botao-acao"
                 data-action="falar-prancha"
                 data-texto="<?php echo htmlspecialchars($pnome, ENT_QUOTES, 'UTF-8'); ?>">🗣️ Falar</a>

              <a href="visualizar_prancha.php?id=<?php echo $pid; ?>" class="botao-acao">👁️ Visualizar</a>

              <?php if ($isAdmin): ?>
                <a href="editar_prancha.php?id=<?php echo $pid; ?>" class="botao-acao">✏️ Editar</a>
                <a href="../includes/controle_excluir_prancha.php?id=<?php echo $pid; ?>"
                   class="botao-acao excluir"
                   data-action="excluir-prancha"
                   data-id="<?php echo $pid; ?>"
                   data-nome="<?php echo htmlspecialchars($pnome, ENT_QUOTES, 'UTF-8'); ?>">🗑️ Excluir</a>
              <?php endif; ?>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </section>
<?php
  endforeach;
endif;
?>

<!-- JS necessários (sem inline) -->
<script src="../assets/js/falar.js"></script>
<script src="../assets/js/pranchas.js"></script>

<?php include '../includes/rodape.php'; ?>
