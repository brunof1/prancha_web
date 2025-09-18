<?php
require_once 'modelo_cartoes.php';
require_once __DIR__ . '/acl.php';
require_admin();

$mensagem_erro = "";

function caminho_imagens_dir(): string {
    return __DIR__ . '/../imagens/cartoes/';
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $titulo   = trim($_POST['titulo'] ?? '');
    $texto_alt= trim($_POST['texto_alt'] ?? '');
    $id_grupo = (int)($_POST['id_grupo'] ?? 0);
    $img_remota = trim($_POST['imagem_remota'] ?? '');

    if ($titulo === '' || $id_grupo <= 0) {
        $mensagem_erro = "Preencha título e selecione um grupo.";
    } else {
        $nome_arquivo_final = null;

        // 1) Se veio imagem remota (ARASAAC já baixada)
        if ($img_remota !== '') {
            $path = caminho_imagens_dir() . basename($img_remota);
            if (is_file($path)) {
                $nome_arquivo_final = basename($img_remota);
            } else {
                $mensagem_erro = "A imagem importada não foi encontrada no servidor.";
            }
        }

        // 2) Senão, tenta upload normal (opcional)
        if ($nome_arquivo_final === null && isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
            $permitidas = ['svg','jpg','jpeg','png','gif','webp'];
            $ext = strtolower(pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION));
            if (!in_array($ext, $permitidas, true)) {
                $mensagem_erro = "Formato não suportado. Use: " . implode(', ', $permitidas);
            } elseif ($_FILES['imagem']['size'] > (4 * 1024 * 1024)) {
                $mensagem_erro = "Imagem muito grande (máx. 4MB).";
            } else {
                $dir = caminho_imagens_dir();
                if (!is_dir($dir)) { @mkdir($dir, 0775, true); }
                $base = preg_replace('/[^A-Za-z0-9._-]/', '_', pathinfo($_FILES['imagem']['name'], PATHINFO_FILENAME));
                $nome_arquivo_final = uniqid() . '_' . ($base ?: 'img') . '.' . $ext;
                if (!move_uploaded_file($_FILES['imagem']['tmp_name'], $dir . $nome_arquivo_final)) {
                    $mensagem_erro = "Falha ao salvar a imagem enviada.";
                    $nome_arquivo_final = null;
                }
            }
        }

        if ($nome_arquivo_final === null && $mensagem_erro === "") {
            $mensagem_erro = "Envie uma imagem ou importe da ARASAAC.";
        }

        if ($mensagem_erro === "" && $nome_arquivo_final !== null) {
            if (criarCartao($titulo, $texto_alt, $nome_arquivo_final, $id_grupo)) {
                header("Location: ../pages/gerenciar_cartoes.php?sucesso=1");
                exit;
            } else {
                $mensagem_erro = "Erro ao salvar no banco.";
            }
        }
    }
}
?>