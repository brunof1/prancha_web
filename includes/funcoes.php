<?php
function limparEntrada($valor) {
    // Higieniza sem quebrar Unicode e sem “escapar” manualmente (usamos prepared statements)
    $valor = trim((string)$valor);
    $valor = strip_tags($valor);
    return $valor;
}
?>
