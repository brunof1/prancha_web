<?php
// includes/controle_salvar_cartao.php
require_once __DIR__ . '/acl.php';
require_admin();

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/modelo_cartoes.php';

$mensagem = "";

function nomeSeguroArquivo(string $nome): string {
    // base “limpa” para compor o nome final
    $nome = preg_replace('/[^A-Za-z0-9._-]/', '_', $nome);
    return $nome ?: ('img_' . uniqid());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo            = trim($_POST['titulo'] ?? '');
    $texto_alternativo = trim($_POST['texto_alternativo'] ?? '');
    $id_grupo          = (int)($_POST['id_grupo'] ?? 0);

    if ($titulo === '' || $id_grupo <= 0) {
        $mensagem = "Preencha o título e selecione um grupo.";
    } elseif (!isset($_FILES['imagem']) || $_FILES['imagem']['error'] !== UPLOAD_ERR_OK) {
        $mensagem = "Envie uma imagem válida.";
    } else {
        // validação de imagem
        $permitidas = ['jpg','jpeg','png','gif','webp'];
        $ext = strtolower(pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $permitidas, true)) {
            $mensagem = "Formato não suportado. Use: " . implode(', ', $permitidas);
        } elseif ($_FILES['imagem']['size'] > (4 * 1024 * 1024)) {
            $mensagem = "Imagem muito grande (máx. 4MB).";
        } else {
            $dir  = __DIR__ . '/../imagens/cartoes/';
            $base = nomeSeguroArquivo(pathinfo($_FILES['imagem']['name'], PATHINFO_FILENAME));
            $nome_arquivo = uniqid() . '_' . $base . '.' . $ext;
            $destino_fs   = $dir . $nome_arquivo;

            if (!is_dir($dir)) { @mkdir($dir, 0775, true); }

            if (move_uploaded_file($_FILES['imagem']['tmp_name'], $destino_fs)) {
                if (criarCartao($titulo, $texto_alternativo, $nome_arquivo, $id_grupo)) {
                    header('Location: ../pages/gerenciar_cartoes.php?sucesso=1');
                    exit;
                }
                // rollback de arquivo se falhar no banco
                @unlink($destino_fs);
                $mensagem = "Erro ao salvar o cartão no banco.";
            } else {
                $mensagem = "Erro ao mover o arquivo da imagem.";
            }
        }
    }
}
