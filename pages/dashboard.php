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

?>

<h1>🏠 Início</h1>
<?php
    $nome_exibir = isset($_SESSION['nome_usuario']) ? $_SESSION['nome_usuario'] : '';
    $tipo_exibir = isset($_SESSION['tipo_usuario']) ? $_SESSION['tipo_usuario'] : '';
?>
<p>Bem-vindo, <?php echo htmlspecialchars($nome_exibir, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?>!</p>
<p>Tipo de usuário: <?php echo htmlspecialchars($tipo_exibir, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?></p>

<p>Escolha uma opção no menu acima para começar.</p>

<?php include '../includes/widget_bateria.php'; ?>

<?php include '../includes/rodape.php'; ?>
