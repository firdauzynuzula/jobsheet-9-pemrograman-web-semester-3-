<?php
$envFile = dirname(__DIR__) . '/.env';
if (!is_readable($envFile)) {
    http_response_code(500);
    die('File konfigurasi .env tidak ditemukan.');
}

foreach (file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
    $line = trim($line);
    if ($line === '' || str_starts_with($line, '#') || !str_contains($line, '=')) {
        continue;
    }

    [$name, $value] = explode('=', $line, 2);
    $name = trim($name);
    $value = trim($value);
    if ($value !== '' && (($value[0] === '"' && substr($value, -1) === '"') || ($value[0] === "'" && substr($value, -1) === "'"))) {
        $value = substr($value, 1, -1);
    }
    putenv($name . '=' . $value);
}

$required = ['DB_HOST', 'DB_PORT', 'DB_NAME', 'DB_USER', 'DB_PASSWORD'];
foreach ($required as $name) {
    if (getenv($name) === false) {
        http_response_code(500);
        die('Konfigurasi database di .env belum lengkap.');
    }
}

$host = getenv('DB_HOST');
$port = getenv('DB_PORT');
$db = getenv('DB_NAME');
$user = getenv('DB_USER');
$pass = getenv('DB_PASSWORD');

try {
    $dsn = "pgsql:host=$host;port=$port;dbname=$db";
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (Throwable $e) {
    error_log('Koneksi database gagal: ' . $e->getMessage());
    http_response_code(503);
    die('Koneksi database gagal. Pastikan PostgreSQL aktif dan konfigurasi .env benar.');
}
