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

require_once __DIR__ . '/../config/config.php';

function obterPreferenciasUsuario(int $id_usuario): array {
    $cx = new mysqli(DB_HOST, DB_USUARIO, DB_SENHA, DB_NOME);
    if ($cx->connect_error) { return preferenciasPadrao(); }

    // Garante charset correto
    $cx->set_charset('utf8mb4');

    $sql = "SELECT voz_uri, tts_rate, tts_pitch, tts_volume, font_base_px, falar_ao_clicar
              FROM preferencias_usuarios
             WHERE id_usuario = ?
             LIMIT 1";
    $st = $cx->prepare($sql);
    $st->bind_param("i", $id_usuario);
    $st->execute();
    $st->store_result();

    $prefs = preferenciasPadrao();

    $st->bind_result($voz_uri, $rate, $pitch, $volume, $font_px, $falar_click);
    if ($st->num_rows > 0 && $st->fetch()) {
        $prefs = [
            'voz_uri'         => $voz_uri,
            'tts_rate'        => (float)$rate,
            'tts_pitch'       => (float)$pitch,
            'tts_volume'      => (float)$volume,
            'font_base_px'    => (int)$font_px,
            'falar_ao_clicar' => (int)$falar_click
        ];
    } else {
        // cria linha padrão se não existir
        $ins = $cx->prepare("INSERT INTO preferencias_usuarios (id_usuario) VALUES (?)");
        $ins->bind_param("i", $id_usuario);
        $ins->execute();
        $ins->close();
    }

    $st->close();
    $cx->close();
    return $prefs;
}

function salvarPreferenciasUsuario(
    int $id_usuario,
    ?string $voz_uri,
    float $tts_rate,
    float $tts_pitch,
    float $tts_volume,
    int $font_base_px,
    int $falar_ao_clicar
): bool {
    // saneamento/limites seguros
    $voz_uri        = $voz_uri !== null ? trim($voz_uri) : null;
    $tts_rate       = max(0.5, min(2.0, $tts_rate));   // intervalo amigável
    $tts_pitch      = max(0.0, min(2.0, $tts_pitch));  // 0–2
    $tts_volume     = max(0.0, min(1.0, $tts_volume)); // 0–1
    $font_base_px   = max(14,  min(24, $font_base_px)); // acessível
    $falar_ao_clicar = $falar_ao_clicar ? 1 : 0;

    $cx = new mysqli(DB_HOST, DB_USUARIO, DB_SENHA, DB_NOME);
    if ($cx->connect_error) { return false; }
    $cx->set_charset('utf8mb4');

    $sql = "INSERT INTO preferencias_usuarios
              (id_usuario, voz_uri, tts_rate, tts_pitch, tts_volume, font_base_px, falar_ao_clicar)
            VALUES
              (?, ?, ?, ?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE
              voz_uri = VALUES(voz_uri),
              tts_rate = VALUES(tts_rate),
              tts_pitch = VALUES(tts_pitch),
              tts_volume = VALUES(tts_volume),
              font_base_px = VALUES(font_base_px),
              falar_ao_clicar = VALUES(falar_ao_clicar)";
    $st = $cx->prepare($sql);
    $st->bind_param(
        "isdddii",
        $id_usuario,
        $voz_uri,
        $tts_rate,
        $tts_pitch,
        $tts_volume,
        $font_base_px,
        $falar_ao_clicar
    );
    $ok = $st->execute();
    $st->close();
    $cx->close();
    return $ok;
}

function preferenciasPadrao(): array {
    return [
        'voz_uri'         => null,
        'tts_rate'        => 1.00,
        'tts_pitch'       => 1.00,
        'tts_volume'      => 1.00,
        'font_base_px'    => 16,
        'falar_ao_clicar' => 0
    ];
}
?>