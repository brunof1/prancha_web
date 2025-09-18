<?php
require_once 'modelo_cartoes.php';
require_once 'funcoes.php';
require_once __DIR__ . '/acl.php';
require_admin();

$mensagem = "";
$cartao = null;
$lista_grupos = listarGrupos();

function nomeSeguroArquivo(string $nome): string {
    // remove caracteres problemáticos do nome original
    $nome = preg_replace('/[^A-Za-z0-9._-]/', '_', $nome);
    return $nome ?: ('img_' . uniqid());
}

if (!isset($_GET['id']) || !($id_cartao = (int)$_GET['id'])) {
    die("ID do cartão não informado.");
}

$cartao = buscarCartaoPorId($id_cartao);
if (!$cartao) {
    die("Cartão não encontrado.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo            = trim($_POST['titulo'] ?? '');
    $texto_alternativo = trim($_POST['texto_alternativo'] ?? '');
    $id_grupo          = (int)($_POST['id_grupo'] ?? 0);

    if ($titulo === '' || $id_grupo <= 0) {
        $mensagem = "Preencha o título e selecione um grupo.";
    } else {
        $novo_nome_imagem = null;

        // Se veio arquivo novo e sem erro, valida e prepara upload
        if (isset($_FILES['imagem']) && is_array($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
            $permitidas = ['jpg','jpeg','png','gif','webp'];
            $ext = strtolower(pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION));
            if (!in_array($ext, $permitidas, true)) {
                $mensagem = "Formato de imagem não suportado. Use: " . implode(', ', $permitidas);
            } elseif ($_FILES['imagem']['size'] > (4 * 1024 * 1024)) {
                $mensagem = "Imagem muito grande (máx. 4MB).";
            } else {
                $dir = "../imagens/cartoes/";
                $base = nomeSeguroArquivo(pathinfo($_FILES['imagem']['name'], PATHINFO_FILENAME));
                $novo_nome_imagem = uniqid() . '_' . $base . '.' . $ext;
                $destino = $dir . $novo_nome_imagem;

                if (!move_uploaded_file($_FILES['imagem']['tmp_name'], $destino)) {
                    $mensagem = "Falha ao salvar a nova imagem no servidor.";
                    $novo_nome_imagem = null; // cancela troca
                }
            }
        }

        if ($mensagem === "") {
            $ok = atualizarCartao($id_cartao, $titulo, $texto_alternativo, $novo_nome_imagem, $id_grupo);
            if ($ok) {
                // se imagem trocou com sucesso, apaga a antiga (evita lixo no disco)
                if ($novo_nome_imagem !== null && !empty($cartao['imagem'])) {
                    $antigo = "../imagens/cartoes/" . $cartao['imagem'];
                    if (is_file($antigo)) { @unlink($antigo); }
                }
                header('Location: ../pages/gerenciar_cartoes.php?edit_ok=1');
                exit;
            } else {
                // se falhou atualizar e já havíamos feito upload novo, remove o arquivo novo
                if ($novo_nome_imagem !== null) {
                    $recem = "../imagens/cartoes/" . $novo_nome_imagem;
                    if (is_file($recem)) { @unlink($recem); }
                }
                $mensagem = "Erro ao atualizar o cartão.";
            }
        }
    }

    // Recarrega dados atuais para reexibir o formulário com os valores tentados
    $cartao = buscarCartaoPorId($id_cartao);
}
?>
