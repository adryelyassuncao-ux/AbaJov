<?php

$envFile = __DIR__ . '/../.env';
if (is_file($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#')) {
            continue;
        }

        [$name, $value] = array_pad(explode('=', $line, 2), 2, '');
        $_ENV[$name] = $value;
        putenv("{$name}={$value}");
    }
}

$host = getenv('DB_HOST') ?: 'localhost';
$porta = getenv('DB_PORT') ?: '3306';
$banco = getenv('DB_NAME') ?: 'atv_php';
$usuario = getenv('DB_USER') ?: 'root';
$pass = getenv('DB_PASS');

return new PDO(
    "mysql:host={$host};port={$porta};dbname={$banco};charset=utf8mb4",
    $usuario,
    $pass,
    [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]
);
