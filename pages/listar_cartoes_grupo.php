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

include '../includes/cabecalho.php';
require_once '../includes/modelo_cartoes.php';

$id_grupo = intval($_GET['id']);
$cartoes = listarCartoesPorGrupo($id_grupo);
?>

<h2>Cartões do Grupo</h2>

<?php if (count($cartoes) > 0): ?>
  <div class="lista-cartoes">
    <?php foreach ($cartoes as $cartao): ?>
      <div class="cartao-item">
        <img src="../imagens/cartoes/<?php echo htmlspecialchars($cartao['imagem'] ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?>"
             alt="<?php echo htmlspecialchars($cartao['texto_alternativo'] ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?>"><br>
        <strong><?php echo htmlspecialchars($cartao['titulo'] ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?></strong><br>
        <button class="botao-acao" type="button"
                onclick='falar(<?php echo json_encode($cartao["titulo"] ?? "", JSON_UNESCAPED_UNICODE); ?>)'>
          <span aria-hidden="true">🗣️</span> Falar
        </button>
      </div>
    <?php endforeach; ?>
  </div>
<?php else: ?>
  <p>Não há cartões neste grupo ainda.</p>
<?php endif; ?>

<script src="../assets/js/falar.js"></script>

<?php include '../includes/rodape.php'; ?>
