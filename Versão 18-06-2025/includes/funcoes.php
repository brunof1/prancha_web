<?php
function limparEntrada($valor) {
    $valor = trim($valor);               // Remove espaços antes e depois
    $valor = strip_tags($valor);         // Remove tags HTML e PHP
    $valor = addslashes($valor);         // Escapa aspas simples, duplas e barras
    return $valor;
}
?>