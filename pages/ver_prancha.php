<?php
include '../includes/cabecalho.php';
require_once '../includes/modelo_pranchas.php';

$id_prancha = intval($_GET['id']);
$isAdmin = ($_SESSION['tipo_usuario'] === 'admin');
if (!usuarioPodeVerPrancha($id_prancha, (int)$_SESSION['id_usuario'], $isAdmin)) {
    http_response_code(403);
    echo "<p style='color:red;'>Você não tem acesso a esta prancha.</p>";
    include '../includes/rodape.php'; exit;
}
$prancha = buscarPranchaPorId($id_prancha);
$cartoes_ids = buscarCartoesDaPrancha($id_prancha);
$cartoes = buscarCartoesPorIds($cartoes_ids);
?>

<h2>Prancha: <?php echo htmlspecialchars($prancha['nome'] ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?></h2>
<p><?php echo htmlspecialchars($prancha['descricao'] ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?></p>

<script>
    const listaDeCartoes = <?php echo json_encode(array_column($cartoes, 'titulo'), JSON_UNESCAPED_UNICODE); ?>;
</script>
    
<button class="botao-falar-tudo botao-acao" type="button" onclick="falarListaDeCartoes(listaDeCartoes)"><span aria-hidden="true">🗣️</span> Falar Tudo</button>

<?php if (count($cartoes) > 0): ?>
    <div class="lista-cartoes">
        <?php foreach ($cartoes as $cartao): ?>
            <div class="cartao-item">
                <img src="../imagens/cartoes/<?php echo htmlspecialchars($cartao['imagem'] ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?>"
                     alt="<?php echo htmlspecialchars($cartao['texto_alternativo'] ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?>"><br>
                <strong><?php echo htmlspecialchars($cartao['titulo'] ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?></strong><br>
                <button class="botao-acao" type="button"
                        onclick='falar(<?php echo json_encode($cartao["titulo"] ?? "", JSON_UNESCAPED_UNICODE); ?>)'><span aria-hidden="true">🗣️</span> Falar</button>
            </div>
        <?php endforeach; ?>
    </div>

    <br>

<?php else: ?>
    <p>Nenhum cartão nesta prancha.</p>
<?php endif; ?>
<script src="../assets/js/falar.js"></script>
<?php include '../includes/rodape.php'; ?>
