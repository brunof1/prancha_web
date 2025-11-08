<?php
// includes/controle_salvar_cartao.php
require_once __DIR__ . '/acl.php';
require_admin();

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/modelo_cartoes.php';

$mensagem = "";

function caminho_imagens_dir(): string {
    return __DIR__ . '/../imagens/cartoes/';
}

function nomeSeguroArquivo(string $nome): string {
    $nome = preg_replace('/[^A-Za-z0-9._-]/', '_', $nome);
    return $nome ?: ('img_' . uniqid());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo            = trim($_POST['titulo'] ?? '');
    $texto_alternativo = trim($_POST['texto_alternativo'] ?? '');
    $id_grupo          = (int)($_POST['id_grupo'] ?? 0);
    $img_remota        = trim($_POST['imagem_remota'] ?? '');

    if ($titulo === '' || $id_grupo <= 0) {
        $mensagem = "Preencha o título e selecione um grupo.";
    } else {
        $dir = caminho_imagens_dir();
        if (!is_dir($dir)) { @mkdir($dir, 0775, true); }

        $nome_arquivo = null;

        // A) Imagem remota já baixada
        if ($img_remota !== '') {
            $path = $dir . basename($img_remota);
            if (is_file($path)) {
                $nome_arquivo = basename($img_remota);
            } else {
                $mensagem = "A imagem importada não foi encontrada no servidor.";
            }
        }

        // B) Upload normal
        if ($nome_arquivo === null && (!isset($_FILES['imagem']) || $_FILES['imagem']['error'] !== UPLOAD_ERR_OK)) {
            $mensagem = "Envie uma imagem válida ou use a importação ARASAAC.";
        } elseif ($nome_arquivo === null) {
            $permitidas = ['svg','jpg','jpeg','png','gif','webp'];
            $ext = strtolower(pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION));
            if (!in_array($ext, $permitidas, true)) {
                $mensagem = "Formato não suportado. Use: " . implode(', ', $permitidas);
            } elseif ($_FILES['imagem']['size'] > (4 * 1024 * 1024)) {
                $mensagem = "Imagem muito grande (máx. 4MB).";
            } else {
                $base = nomeSeguroArquivo(pathinfo($_FILES['imagem']['name'], PATHINFO_FILENAME));
                $nome_arquivo = uniqid() . '_' . $base . '.' . $ext;
                $destino_fs   = $dir . $nome_arquivo;

                if (!move_uploaded_file($_FILES['imagem']['tmp_name'], $destino_fs)) {
                    $mensagem = "Erro ao mover o arquivo da imagem.";
                    $nome_arquivo = null;
                }
            }
        }

        if ($mensagem === "" && $nome_arquivo !== null) {
            if (criarCartao($titulo, $texto_alternativo, $nome_arquivo, $id_grupo)) {
                header('Location: ../pages/gerenciar_cartoes.php?sucesso=1');
                exit;
            }
            // rollback de arquivo se falhar no banco (apenas para upload; remota já é final também)
            @unlink($dir . $nome_arquivo);
            $mensagem = "Erro ao salvar o cartão no banco.";
        }
    }
}
?>