<?php
require_once '../config/config.php';
require_once 'modelo_cartoes.php';
require_once 'modelo_grupos.php';
require_once 'funcoes.php';

require_once __DIR__ . '/acl.php';
require_admin();

$mensagem = "";
$lista_grupos = listarGrupos();

function caminho_imagens_dir(): string {
    return __DIR__ . '/../imagens/cartoes/';
}

function nomeSeguroArquivo($n){ $n = preg_replace('/[^A-Za-z0-9._-]/','_',$n); return $n ?: ('img_'.uniqid()); }

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $titulo = limparEntrada($_POST['titulo'] ?? '');
    $texto_alternativo = limparEntrada($_POST['texto_alternativo'] ?? '');
    $id_grupo = (int)($_POST['id_grupo'] ?? 0);
    $img_remota = trim($_POST['imagem_remota'] ?? '');

    if ($titulo === '' || $id_grupo <= 0) {
        $mensagem = "Preencha título e selecione um grupo.";
    } else {
        $nome_arquivo_final = null;

        if ($img_remota !== '') {
            $path = caminho_imagens_dir() . basename($img_remota);
            if (is_file($path)) {
                $nome_arquivo_final = basename($img_remota);
            } else {
                $mensagem = "A imagem importada não foi encontrada no servidor.";
            }
        }

        if ($nome_arquivo_final === null && isset($_FILES['imagem']) && $_FILES['imagem']['error'] == 0) {
            $permitidas = ['svg','jpg','jpeg','png','gif','webp'];
            $ext = strtolower(pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION));
            if (!in_array($ext, $permitidas, true)) {
                $mensagem = "Formato não suportado. Use: " . implode(', ', $permitidas);
            } elseif ($_FILES['imagem']['size'] > (4 * 1024 * 1024)) {
                $mensagem = "Imagem muito grande (máx. 4MB).";
            } else {
                $destinoDir = caminho_imagens_dir();
                if (!is_dir($destinoDir)) { @mkdir($destinoDir, 0775, true); }
                $base = nomeSeguroArquivo(pathinfo($_FILES['imagem']['name'], PATHINFO_FILENAME));
                $nome_arquivo_final = uniqid() . '_' . $base . '.' . $ext;
                if (!move_uploaded_file($_FILES['imagem']['tmp_name'], $destinoDir . $nome_arquivo_final)) {
                    $mensagem = "Erro ao mover o arquivo da imagem.";
                    $nome_arquivo_final = null;
                }
            }
        }

        if ($mensagem === "" && $nome_arquivo_final !== null) {
            if (criarCartao($titulo, $texto_alternativo, $nome_arquivo_final, $id_grupo)) {
                header('Location: ../pages/gerenciar_cartoes.php?sucesso=1');
                exit;
            } else {
                $mensagem = "Erro ao salvar o cartão no banco.";
            }
        } elseif ($mensagem === "") {
            $mensagem = "Envie uma imagem ou importe da ARASAAC.";
        }
    }
}
?>