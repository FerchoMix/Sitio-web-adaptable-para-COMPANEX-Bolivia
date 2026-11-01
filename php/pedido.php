<?php
declare(strict_types=1);
$cfg = require __DIR__ . '/../config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    header('Allow: POST');
    exit('Método no permitido');
}
function limpiar(mixed $v, int $max): string {
    $v = trim(strip_tags((string) $v));
    $v = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F]/', '', $v) ?? '';
    return preg_match('/^.{0,' . $max . '}/su', $v, $m) ? $m[0] : '';
}

$quiereJson = ($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') === 'fetch';
function responder(bool $json, int $codigo, array $datos): never {
    http_response_code($codigo);
    if ($json) {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($datos, JSON_UNESCAPED_UNICODE);
    } else {
        header('Content-Type: text/plain; charset=utf-8');
        echo $datos['error'] ?? 'Listo';
    }
    exit;
}

/* Campo trampa para bots */
if (!empty($_POST['web'])) {
    responder($quiereJson, 200, ['ok' => true]);
}

$nombre  = limpiar($_POST['nombre']  ?? '', 80);
$negocio = limpiar($_POST['negocio'] ?? '', 80);
$lista   = limpiar($_POST['lista']   ?? '', 1500);

if ($nombre === '' || $lista === '') {
    responder($quiereJson, 422, ['ok' => false, 'error' => 'Faltan el nombre o los productos del pedido.']);
}

/* Guarda el pedido en data/pedidos.csv  */
if (!empty($cfg['guardar_pedidos'])) {
    $archivo = __DIR__ . '/../data/pedidos.csv';
    $fila = [date('Y-m-d H:i:s'), $nombre, $negocio, str_replace(["\r\n", "\r", "\n"], ' | ', $lista)];
    $fila = array_map(fn($c) => preg_match('/^[=+\-@]/', $c) ? "'" . $c : $c, $fila);
    $nuevo = !file_exists($archivo);
    $fh = @fopen($archivo, 'ab');
    if ($fh && flock($fh, LOCK_EX)) {
        if ($nuevo) {
            fputcsv($fh, ['fecha', 'nombre', 'negocio', 'productos'], ',', '"', '\\');
        }
        fputcsv($fh, $fila, ',', '"', '\\');
        flock($fh, LOCK_UN);
    }
    if ($fh) {
        fclose($fh);
    }
}

$mensaje = 'Hola COMPANEX, soy ' . $nombre . ($negocio !== '' ? ' de ' . $negocio : '') . ". Quiero pedir:\n" . $lista;
$url = 'https://wa.me/' . preg_replace('/\D/', '', (string) $cfg['whatsapp']) . '?text=' . rawurlencode($mensaje);

if ($quiereJson) {
    responder(true, 200, ['ok' => true, 'url' => $url]);
}
header('Location: ' . $url, true, 303);
exit;
