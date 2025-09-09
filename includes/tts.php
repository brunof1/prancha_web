<?php
// includes/tts.php
// Gera áudio MP3 via Google Cloud Text-to-Speech e devolve como audio/mpeg
// Requer: habilitar a API TTS no Google Cloud e criar uma API KEY

// ======= CONFIG =======
const GOOGLE_TTS_API_KEY = 'AIzaSyAhyyxh9uW0HCI7JJJuUBcCZj4Rt_Nyud8'; // <<< troque
const CACHE_DIR = __DIR__ . '/../assets/tts_cache';    // pasta gravável

// ======= SANITIZAÇÃO =======
mb_internal_encoding('UTF-8');
$texto = isset($_POST['texto']) ? trim((string)$_POST['texto']) : '';
$lang  = isset($_POST['lang'])  ? trim((string)$_POST['lang'])  : 'pt-BR';
$voice = isset($_POST['voice']) ? trim((string)$_POST['voice']) : 'pt-BR-Neural2-A';

// Limites básicos (evita abusos)
if ($texto === '' || mb_strlen($texto) > 5000) {
  http_response_code(400);
  header('Content-Type: application/json; charset=utf-8');
  echo json_encode(['ok' => false, 'msg' => 'Texto vazio ou muito longo']);
  exit;
}

// ======= CACHE =======
if (!is_dir(CACHE_DIR)) { @mkdir(CACHE_DIR, 0775, true); }
$hash = sha1($lang . '|' . $voice . '|' . $texto);
$cacheFile = CACHE_DIR . '/' . $hash . '.mp3';

if (is_file($cacheFile)) {
  header('Content-Type: audio/mpeg');
  header('Cache-Control: public, max-age=31536000, immutable');
  readfile($cacheFile);
  exit;
}

// ======= CHAMADA À API GOOGLE TTS =======
$url = 'https://texttospeech.googleapis.com/v1/text:synthesize?key=' . urlencode(GOOGLE_TTS_API_KEY);

$payload = [
  'input' => ['text' => $texto],
  'voice' => [
    'languageCode' => $lang,
    'name'         => $voice,        // ex.: pt-BR-Neural2-A, pt-BR-Wavenet-A, etc.
    'ssmlGender'   => 'NEUTRAL'
  ],
  'audioConfig' => [
    'audioEncoding' => 'MP3',
    'speakingRate'  => 1.0,
    'pitch'         => 0.0
  ]
];

$ch = curl_init($url);
curl_setopt_array($ch, [
  CURLOPT_POST           => true,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_HTTPHEADER     => ['Content-Type: application/json; charset=utf-8'],
  CURLOPT_POSTFIELDS     => json_encode($payload, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES),
  CURLOPT_TIMEOUT        => 15,
]);
$res = curl_exec($ch);
$http = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$err  = curl_error($ch);
curl_close($ch);

if ($res === false || $http < 200 || $http >= 300) {
  http_response_code(500);
  header('Content-Type: application/json; charset=utf-8');
  echo json_encode(['ok' => false, 'msg' => 'Falha no TTS', 'http' => $http, 'err' => $err]);
  exit;
}

$data = json_decode($res, true);
if (!isset($data['audioContent'])) {
  http_response_code(500);
  header('Content-Type: application/json; charset=utf-8');
  echo json_encode(['ok' => false, 'msg' => 'Resposta inválida do TTS']);
  exit;
}

$audio = base64_decode($data['audioContent']);
if ($audio === false) {
  http_response_code(500);
  header('Content-Type: application/json; charset=utf-8');
  echo json_encode(['ok' => false, 'msg' => 'Decodificação falhou']);
  exit;
}

// Salva cache
@file_put_contents($cacheFile, $audio);

// Responde MP3
header('Content-Type: audio/mpeg');
header('Cache-Control: public, max-age=31536000, immutable');
echo $audio;
