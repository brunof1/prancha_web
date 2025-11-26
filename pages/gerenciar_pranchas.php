<?php

/**
 * Prancha Web
 * Plataforma Web de Comunicação Alternativa e Aumentativa (CAA)
 *
 * Copyright (c) 2025 Bruno Silva da Silva
 *
 * Este arquivo faz parte do projeto Prancha Web.
 *
 * Licenciamento duplo:
 * - Apache License 2.0
 * - GNU General Public License v3.0 (GPLv3)
 *
 * Você pode redistribuir e/ou modificar este arquivo sob os termos de
 * qualquer uma das licenças, a seu critério, desde que cumpra integralmente
 * os respectivos requisitos.
 *
 * Você deve ter recebido uma cópia das licenças junto com este programa.
 * Caso contrário, veja:
 * - https://www.apache.org/licenses/LICENSE-2.0
 * - https://www.gnu.org/licenses/gpl-3.0.html
 */

// pages/gerenciar_pranchas.php
include '../includes/cabecalho.php';
require_once '../includes/controle_pranchas.php';
require_once '../includes/modelo_pranchas.php';

$isAdmin = (isset($_SESSION['tipo_usuario']) && $_SESSION['tipo_usuario'] === 'admin');

/**
 * Retorna os títulos (ou texto alternativo) dos cartões de uma prancha, na ordem.
 */
function titulosDaPranchaParaFalar(int $idPrancha): array {
    $ids = buscarCartoesDaPrancha($idPrancha);
    if (empty($ids)) return [];
    $cartoes = buscarCartoesPorIds($ids); // mantém ordem
    $out = [];
    foreach ($cartoes as $c) {
        $t = trim($c['titulo'] ?? '');
        if ($t === '') { $t = trim($c['texto_alternativo'] ?? ''); }
        if ($t !== '') $out[] = $t;
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
uksort($grupos, fn($a,$b)=> strcasecmp($a,$b));

// Mapa nome->id dos grupos de pranchas (para links)
$mapaGrupos = [];
if (isset($lista_grupos_pranchas) && is_array($lista_grupos_pranchas)) {
    foreach ($lista_grupos_pranchas as $g) {
        $mapaGrupos[(string)($g['nome'] ?? '')] = (int)$g['id'];
    }
}
?>

<link rel="stylesheet" href="../assets/css/pranchas.css">

<h1>📋 Gerenciar Pranchas</h1>

<?php if ($isAdmin): ?>
  <p>
    <a href="criar_grupo_prancha.php" class="botao-acao">➕ Criar grupo de pranchas</a>
    <a href="criar_prancha.php" class="botao-acao">📋 Criar prancha</a>
  </p>
<?php endif; ?>

<?php if (empty($lista_pranchas)): ?>
  <div class="lista-pranchas--vazia">Nenhuma prancha encontrada.</div>
<?php else: ?>

  <!-- chips de grupos -->
  <div class="grupos-wrap" role="tablist" aria-label="Grupos de pranchas">
    <?php foreach ($grupos as $nomeGrupo => $pranchasDoGrupo): ?>
      <?php
        $grupoId = $mapaGrupos[$nomeGrupo] ?? null;
        $count = count($pranchasDoGrupo);
        $painelId = 'painel-pranchas-' . md5($nomeGrupo);
      ?>
      <details class="grupo-exp" role="group">
        <summary
          class="grupo-chip"
          role="tab"
          aria-controls="<?= $painelId ?>"
          aria-expanded="false">
          <span aria-hidden="true">🗂️</span>
          <span><?= htmlspecialchars($nomeGrupo ?? 'Sem grupo', ENT_QUOTES, 'UTF-8') ?></span>
          <span class="grupo-chip__count" aria-label="<?= $count ?> prancha(s)">(<?= $count ?>)</span>
        </summary>

        <div id="<?= $painelId ?>" class="grupo-exp__painel" role="tabpanel" tabindex="-1">
          <?php if ($isAdmin && $grupoId): ?>
            <div class="grupo-exp__acoes" aria-label="Ações do grupo">
              <details class="acoes-drop">
                <summary class="botao-acao botao-icone"
                        aria-label="Ações do grupo <?= htmlspecialchars($nomeGrupo, ENT_QUOTES, 'UTF-8') ?>"
                        aria-expanded="false">
                  <span aria-hidden="true">⋮</span>
                  <span class="sr-only">Ações do grupo <?= htmlspecialchars($nomeGrupo, ENT_QUOTES, 'UTF-8') ?></span>
                </summary>
                <div class="acoes-drop__panel" role="menu">
                  <a class="botao-acao menu-link" role="menuitem"
                    href="editar_grupo_prancha.php?id=<?= (int)$grupoId ?>">✏️ Editar grupo</a>
                  <a class="botao-acao excluir menu-link" role="menuitem"
                    href="../includes/controle_excluir_grupo_prancha.php?id=<?= (int)$grupoId ?>"
                    onclick="return confirm('Tem certeza que deseja excluir o grupo &quot;<?= htmlspecialchars($nomeGrupo, ENT_QUOTES, 'UTF-8') ?>&quot;?');">
                    🗑️ Excluir grupo
                  </a>
                </div>
              </details>
            </div>
          <?php endif; ?>

          <div class="lista-pranchas" role="list">
            <?php foreach ($pranchasDoGrupo as $pr): ?>
              <?php
                $id   = (int)($pr['id'] ?? 0);
                $nome = (string)($pr['nome'] ?? '');
                $listaTitulos = titulosDaPranchaParaFalar($id);
                $listaJson    = htmlspecialchars(json_encode($listaTitulos, JSON_UNESCAPED_UNICODE), ENT_QUOTES, 'UTF-8');
              ?>
              <article class="prancha-item" role="listitem">
                <div class="prancha-item__nome">📋 <?= htmlspecialchars($nome, ENT_QUOTES, 'UTF-8') ?></div>
                <div class="prancha-item__grupo"><?= htmlspecialchars($pr['descricao'] ?? '', ENT_QUOTES, 'UTF-8') ?></div>

                <div class="prancha-item__acoes" aria-label="Ações da prancha">
                  <a class="botao-acao" href="ver_prancha.php?id=<?= $id ?>">🔎 Abrir</a>
                  <!-- Falar todos os cartões da prancha -->
                  <a class="botao-acao"
                     href="#"
                     data-action="falar-prancha"
                     data-texto="<?= htmlspecialchars($nome, ENT_QUOTES, 'UTF-8') ?>"
                     data-lista="<?= $listaJson ?>">🗣️ Falar Tudo</a>
                  <!-- Botões de ações da prancha -->
                  <?php if ($isAdmin): ?>
                    <details class="acoes-drop">
                      <summary class="botao-acao botao-icone"
                              aria-label="Ações da prancha <?= htmlspecialchars($pr['nome'], ENT_QUOTES, 'UTF-8') ?>"
                              aria-expanded="false">
                        <span aria-hidden="true">⋮</span>
                        <span class="sr-only">Ações da prancha <?= htmlspecialchars($pr['nome'], ENT_QUOTES, 'UTF-8') ?></span>
                      </summary>
                      <div class="acoes-drop__panel" role="menu">
                        <a class="botao-acao menu-link" role="menuitem"
                          href="editar_prancha.php?id=<?= (int)$pr['id'] ?>">✏️ Editar</a>

                        <a class="botao-acao excluir menu-link" role="menuitem"
                          href="../includes/controle_excluir_prancha.php?id=<?= (int)$pr['id'] ?>"
                          data-action="excluir-prancha"
                          data-id="<?= (int)$pr['id'] ?>"
                          data-nome="<?= htmlspecialchars($pr['nome'], ENT_QUOTES, 'UTF-8') ?>">
                          🗑️ Excluir
                        </a>
                      </div>
                    </details>
                  <?php endif; ?>
                </div>
              </article>
            <?php endforeach; ?>
          </div>
        </div>
      </details>
    <?php endforeach; ?>
  </div>

<?php endif; ?>

<script src="../assets/js/falar.js"></script>
<script src="../assets/js/pranchas.js"></script>
<script src="../assets/js/grupos.js"></script>

<?php include '../includes/rodape.php'; ?>
