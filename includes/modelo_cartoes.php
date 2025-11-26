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

require_once '../config/config.php';

function cx_cartoes(): mysqli {
    $cx = new mysqli(DB_HOST, DB_USUARIO, DB_SENHA, DB_NOME);
    if ($cx->connect_error) { die('Erro de conexão: ' . $cx->connect_error); }
    $cx->set_charset('utf8mb4');
    @$cx->query("SET collation_connection = 'utf8mb4_unicode_ci'");
    return $cx;
}

function listarCartoesPorGrupo($id_grupo) {
    $conexao = cx_cartoes();
    $cartoes = [];

    $sql = "SELECT id, titulo, imagem, texto_alternativo FROM cartoes WHERE id_grupo = ? ORDER BY titulo";
    $comando = $conexao->prepare($sql);
    $comando->bind_param("i", $id_grupo);
    $comando->execute();
    $comando->store_result();
    $comando->bind_result($id, $titulo, $imagem, $texto_alt);

    while ($comando->fetch()) {
        $cartoes[] = [
            'id' => $id,
            'titulo' => $titulo,
            'imagem' => $imagem,
            'texto_alternativo' => $texto_alt
        ];
    }

    $comando->close();
    $conexao->close();

    return $cartoes;
}

function criarCartao($titulo, $texto_alt, $nome_arquivo_imagem, $id_grupo) {
    $conexao = cx_cartoes();

    $sql = "INSERT INTO cartoes (titulo, imagem, texto_alternativo, id_grupo) VALUES (?, ?, ?, ?)";
    $comando = $conexao->prepare($sql);
    $comando->bind_param("sssi", $titulo, $nome_arquivo_imagem, $texto_alt, $id_grupo);

    $resultado = $comando->execute();

    $comando->close();
    $conexao->close();

    return $resultado;
}

function buscarCartaoPorId(int $id_cartao): ?array {
    $cx = cx_cartoes();

    $st = $cx->prepare("SELECT id, titulo, imagem, texto_alternativo, id_grupo FROM cartoes WHERE id = ? LIMIT 1");
    $st->bind_param("i", $id_cartao);
    $st->execute();
    $st->bind_result($id, $titulo, $imagem, $texto_alt, $id_grupo);

    $out = null;
    if ($st->fetch()) {
        $out = [
            'id'                 => (int)$id,
            'titulo'             => $titulo,
            'imagem'             => $imagem,
            'texto_alternativo'  => $texto_alt,
            'id_grupo'           => (int)$id_grupo,
        ];
    }
    $st->close();
    $cx->close();
    return $out;
}

function atualizarCartao(int $id_cartao, string $titulo, string $texto_alt, ?string $novo_nome_imagem, int $id_grupo): bool {
    $cx = cx_cartoes();

    if ($novo_nome_imagem === null) {
        $sql = "UPDATE cartoes SET titulo = ?, texto_alternativo = ?, id_grupo = ? WHERE id = ?";
        $st  = $cx->prepare($sql);
        $st->bind_param("ssii", $titulo, $texto_alt, $id_grupo, $id_cartao);
    } else {
        $sql = "UPDATE cartoes SET titulo = ?, texto_alternativo = ?, imagem = ?, id_grupo = ? WHERE id = ?";
        $st  = $cx->prepare($sql);
        $st->bind_param("sssii", $titulo, $texto_alt, $novo_nome_imagem, $id_grupo, $id_cartao);
    }

    $ok = $st->execute();
    $st->close();
    $cx->close();
    return $ok;
}

function excluirCartao($id_cartao) {
    $conexao = cx_cartoes();

    // Primeiro, buscar o nome da imagem para excluir do servidor
    $sql = "SELECT imagem FROM cartoes WHERE id = ?";
    $comando = $conexao->prepare($sql);
    $comando->bind_param("i", $id_cartao);
    $comando->execute();
    $comando->store_result();
    $comando->bind_result($imagem);

    if ($comando->num_rows > 0) {
        $comando->fetch();
        $caminho_imagem = "../imagens/cartoes/" . $imagem;
        if (is_file($caminho_imagem)) {
            @unlink($caminho_imagem);
        }
    }

    $comando->close();

    // Agora deletar o cartão do banco
    $sql = "DELETE FROM cartoes WHERE id = ?";
    $comando = $conexao->prepare($sql);
    $comando->bind_param("i", $id_cartao);
    $resultado = $comando->execute();

    $comando->close();
    $conexao->close();

    return $resultado;
}

function listarTodosCartoes() {
    $conexao = cx_cartoes();
    $cartoes = [];

    $sql = "SELECT id, titulo, imagem, texto_alternativo FROM cartoes ORDER BY titulo";
    $resultado = $conexao->query($sql);

    if ($resultado) {
        while ($linha = $resultado->fetch_assoc()) {
            $cartoes[] = $linha;
        }
        $resultado->close();
    }

    $conexao->close();
    return $cartoes;
}
?>
