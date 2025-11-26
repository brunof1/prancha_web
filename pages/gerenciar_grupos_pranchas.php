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
require_once '../includes/controle_grupos_pranchas.php';

if ($_SESSION['tipo_usuario'] !== 'admin') {
    http_response_code(403); echo "<p>Acesso restrito ao administrador.</p>";
    include '../includes/rodape.php'; exit;
}
?>

<h2>Grupos de Pranchas</h2>

<?php if (count($lista_grupos_pranchas) > 0): ?>
    <ul>
        <?php foreach ($lista_grupos_pranchas as $grupo): ?>
            <li>
                <?php echo htmlspecialchars($grupo['nome']); ?> - 
                <a href="editar_grupo_prancha.php?id=<?php echo $grupo['id']; ?>">✏️ Editar</a> |
                <a href="../includes/controle_excluir_grupo_prancha.php?id=<?php echo $grupo['id']; ?>" onclick="return confirm('Tem certeza que deseja excluir este grupo?');">🗑️ Excluir</a>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>Nenhum grupo de prancha cadastrado ainda.</p>
<?php endif; ?>

<p><a href="criar_grupo_prancha.php">➕ Criar novo grupo de prancha</a></p>

<?php include '../includes/rodape.php'; ?>
