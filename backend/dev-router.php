<?php
require_once __DIR__ . '/config.php';

$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$basePath = rtrim(parse_url(ROOT_URL, PHP_URL_PATH) ?: '/banhang', '/');
$relativePath = $path;
if ($basePath !== '' && str_starts_with($relativePath, $basePath)) {
    $relativePath = substr($relativePath, strlen($basePath));
}

$file = realpath(__DIR__ . '/' . ltrim($relativePath, '/'));
$root = realpath(__DIR__);
if ($file && $root && str_starts_with($file, $root . DIRECTORY_SEPARATOR) && is_file($file)) {
    $types = [
        'css' => 'text/css; charset=utf-8',
        'js' => 'application/javascript; charset=utf-8',
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'png' => 'image/png',
        'webp' => 'image/webp',
        'svg' => 'image/svg+xml',
    ];
    $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
    header('Content-Type: ' . ($types[$extension] ?? 'application/octet-stream'));
    readfile($file);
    return true;
}

require __DIR__ . '/index.php';
