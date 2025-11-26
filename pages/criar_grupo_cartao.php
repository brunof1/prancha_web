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

if ($_SESSION['tipo_usuario'] !== 'admin') {
    http_response_code(403); echo "<p>Acesso restrito ao administrador.</p>";
    include '../includes/rodape.php'; exit;
}

?>

<h2>Criar Novo Grupo de Cartões</h2>
<form action="../includes/controle_criar_grupo.php" method="post">
    <fieldset>
        <legend>Informações do novo grupo de cartão(ões)</legend>
        <label>Nome do Grupo:</label><br>
        <input type="text" name="nome_grupo" required><br><br>
    </fieldset>
    <br>
        <!--
        <button type="submit">Salvar Grupo</button>
        -->
        <button type="submit" class="botao-acao">💾 Salvar Novo Grupo de Cartão(ões)</button>
        <a class="botao-acao" href="gerenciar_cartoes.php">↩️ Voltar</a>
</form>

<?php include '../includes/rodape.php'; ?>
