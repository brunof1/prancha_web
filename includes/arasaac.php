<?php
// includes/arasaac.php
// Pequena camada para buscar e importar pictogramas da ARASAAC com preferência por SVG.
// Não requer libs externas. Usa cURL (mais robusto que allow_url_fopen).

require_once __DIR__ . '/acl.php';
require_once __DIR__ . '/../config/config.php';

if (!function_exists('arasaac_http_get_json')) {
    function arasaac_http_get_json(string $url, int $timeout = 10): ?array {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_CONNECTTIMEOUT => $timeout,
            CURLOPT_TIMEOUT => $timeout,
            CURLOPT_HTTPHEADER => ['Accept: application/json'],
            CURLOPT_USERAGENT => 'PranchaWeb/1.0 (+arasaac-integration)'
        ]);
        $body = curl_exec($ch);
        $code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($code >= 200 && $code < 300 && $body !== false) {
            $data = json_decode($body, true);
            return is_array($data) ? $data : null;
        }
        return null;
    }
}

if (!function_exists('arasaac_try_fetch_binary')) {
    function arasaac_try_fetch_binary(string $url, int $timeout = 15): ?array {
        // Retorna ['ok'=>true, 'data'=>string, 'ctype'=>string] em sucesso
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_CONNECTTIMEOUT => $timeout,
            CURLOPT_TIMEOUT => $timeout,
            CURLOPT_HTTPHEADER => ['Accept: image/svg+xml,image/png;q=0.9,*/*;q=0.1'],
            CURLOPT_USERAGENT => 'PranchaWeb/1.0 (+arasaac-integration)'
        ]);
        $bin = curl_exec($ch);
        $code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $ctype = (string) curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
        curl_close($ch);

        if ($code >= 200 && $code < 300 && $bin !== false && strlen($bin) > 0) {
            return ['ok'=>true, 'data'=>$bin, 'ctype'=>$ctype];
        }
        return null;
    }
}

if (!function_exists('arasaac_search')) {
    function arasaac_search(string $q, string $lang = 'pt', int $limit = 30): array {
        $q = trim($q);
        if ($q === '') return [];
        // Endpoint público de busca por idioma/termo:
        $url = 'https://api.arasaac.org/api/pictograms/' . rawurlencode($lang) . '/search/' . rawurlencode($q);
        $arr = arasaac_http_get_json($url);
        if (!is_array($arr)) return [];
        // Normaliza estrutura: muitos retornam {id, keywords:[{keyword, ...}]}
        $out = [];
        foreach ($arr as $item) {
            $id = $item['id'] ?? ($item['_id'] ?? null);
            if ($id === null) continue;
            $preview_svg = "https://static.arasaac.org/pictograms/{$id}/{$id}.svg";
            $preview_png = "https://static.arasaac.org/pictograms/{$id}/{$id}_300.png";

            // Extrai palavras-chave (se vierem)
            $keywords = [];
            if (isset($item['keywords']) && is_array($item['keywords'])) {
                foreach ($item['keywords'] as $kw) {
                    if (is_array($kw) && !empty($kw['keyword'])) {
                        $keywords[] = (string)$kw['keyword'];
                    } elseif (is_string($kw)) {
                        $keywords[] = $kw;
                    }
                }
            }
            $out[] = [
                'id' => (int)$id,
                'keywords' => array_values(array_unique($keywords)),
                'preview' => ['svg' => $preview_svg, 'png' => $preview_png],
            ];
            if (count($out) >= $limit) break;
        }
        return $out;
    }
}

if (!function_exists('arasaac_candidate_urls')) {
    function arasaac_candidate_urls(int $id, string $lang = 'pt'): array {
        $idS = (string)$id;
        return [
            // SVG preferido
            "https://static.arasaac.org/pictograms/{$idS}/{$idS}.svg",
            // PNGs comuns no CDN
            "https://static.arasaac.org/pictograms/{$idS}/{$idS}_300.png",
            "https://static.arasaac.org/pictograms/{$idS}/{$idS}.png",
        ];
    }
}

if (!function_exists('arasaac_safe_basename')) {
    function arasaac_safe_basename(string $name): string {
        $name = preg_replace('/[^A-Za-z0-9._-]/', '_', $name);
        return $name !== '' ? $name : ('arasaac_' . uniqid());
    }
}

if (!function_exists('arasaac_import')) {
    function arasaac_import(int $id, string $lang = 'pt'): array {
        $candidates = arasaac_candidate_urls($id, $lang);
        $found = null;
        foreach ($candidates as $url) {
            $res = arasaac_try_fetch_binary($url);
            if ($res && !empty($res['ctype'])) {
                $ct = strtolower($res['ctype']);
                $isSvg = (strpos($ct, 'image/svg') !== false) || (strpos($url, '.svg') !== false);
                $isPng = (strpos($ct, 'image/png') !== false) || (strpos($url, '.png') !== false);

                if ($isSvg || $isPng) {
                    $ext = $isSvg ? 'svg' : 'png';
                    $base = "arasaac_{$id}_{$lang}_" . uniqid();
                    $fname = arasaac_safe_basename($base) . '.' . $ext;

                    $dir = __DIR__ . '/../imagens/cartoes/';
                    if (!is_dir($dir)) { @mkdir($dir, 0775, true); }

                    $ok = @file_put_contents($dir . $fname, $res['data']);
                    if ($ok !== false) {
                        $found = ['ok' => true, 'filename' => $fname, 'mime' => $ct, 'ext' => $ext];
                        break;
                    }
                }
            }
        }
        return $found ?: ['ok' => false, 'error' => 'Nenhuma fonte disponível para este pictograma.'];
    }
}
?>