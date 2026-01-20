<?php
// =============================================
// config.php - Conexión a Supabase
// =============================================

// Zona horaria
date_default_timezone_set('America/La_Paz');

// Supabase credentials
define('SUPABASE_URL', 'https://ikajregqluhbqwogtaiq.supabase.co');
define('SUPABASE_KEY', 'sb_publishable_82ZBhRFYW4H1ziu-D08HZg_tgBWMfpM');

// Headers comunes para CURL
function supabaseHeaders() {
    return [
        'apikey: ' . SUPABASE_KEY,
        'Authorization: Bearer ' . SUPABASE_KEY,
        'Content-Type: application/json'
    ];
}

// =============================================
// FUNCIONES PRINCIPALES
// =============================================

// Obtener datos de una tabla
function supabaseGet($tabla, $filtros = []) {
    $url = SUPABASE_URL . '/rest/v1/' . $tabla;
    if ($filtros) {
        $params = [];
        foreach ($filtros as $key => $value) {
            $params[] = $key . '=eq.' . urlencode($value);
        }
        $url .= '?' . implode('&', $params);
    }

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, supabaseHeaders());
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    if ($response === false) {
        die('Error CURL: ' . curl_error($ch));
    }
    curl_close($ch);

    return json_decode($response, true);
}

// Insertar datos en una tabla
function supabaseInsert($tabla, $data) {
    $url = SUPABASE_URL . '/rest/v1/' . $tabla;

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, supabaseHeaders());
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    return ['httpCode' => $httpCode, 'response' => json_decode($response, true)];
}
?>
