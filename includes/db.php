<?php
require_once __DIR__ . '/config.php';

/** Cliente privado de la API REST de Supabase. Nunca se ejecuta en el navegador. */
function supabase_request(string $method, string $resource, ?array $payload = null, array $extraHeaders = []): array
{
    if (!function_exists('curl_init')) throw new RuntimeException('La extensión cURL de PHP debe estar habilitada en el hosting.');
    if (str_contains(SUPABASE_URL, 'TU-PROYECTO') || str_contains(SUPABASE_SECRET_KEY, 'REEMPLAZAR')) throw new RuntimeException('Configurá SUPABASE_URL y SUPABASE_SECRET_KEY en includes/config.php.');
    $responseHeaders = [];
    $curl = curl_init(rtrim(SUPABASE_URL, '/') . '/rest/v1/' . ltrim($resource, '/'));
    curl_setopt_array($curl, [
        CURLOPT_CUSTOMREQUEST => strtoupper($method), CURLOPT_RETURNTRANSFER => true, CURLOPT_TIMEOUT => 15,
        CURLOPT_HTTPHEADER => array_merge(['apikey: ' . SUPABASE_SECRET_KEY, 'Authorization: Bearer ' . SUPABASE_SECRET_KEY, 'Content-Type: application/json'], $extraHeaders),
        CURLOPT_HEADERFUNCTION => static function ($handle, string $header) use (&$responseHeaders): int { if (str_contains($header, ':')) { [$name, $value] = explode(':', $header, 2); $responseHeaders[strtolower(trim($name))] = trim($value); } return strlen($header); },
    ]);
    if ($payload !== null) curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($payload, JSON_UNESCAPED_UNICODE));
    $body = curl_exec($curl);
    if ($body === false) { $error = curl_error($curl); curl_close($curl); throw new RuntimeException('No se pudo conectar con Supabase: ' . $error); }
    $status = (int) curl_getinfo($curl, CURLINFO_RESPONSE_CODE); curl_close($curl);
    $data = $body === '' ? [] : json_decode($body, true);
    if ($status < 200 || $status >= 300) { $message = is_array($data) ? ($data['message'] ?? $data['hint'] ?? 'Error desconocido de Supabase.') : 'Error desconocido de Supabase.'; throw new RuntimeException('Supabase respondió ' . $status . ': ' . $message); }
    return ['status' => $status, 'data' => is_array($data) ? $data : [], 'headers' => $responseHeaders];
}

/** Cuenta registros con la cabecera Content-Range de PostgREST. */
function supabase_count(string $resource): int
{
    $result = supabase_request('GET', $resource, null, ['Prefer: count=exact', 'Range: 0-0']);
    return (int) substr(strrchr($result['headers']['content-range'] ?? '0-0/0', '/'), 1);
}
