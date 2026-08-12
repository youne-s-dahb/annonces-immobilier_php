<?php
// db.php

// 1. Fonction manual bach n-qraw .env (hit ma-3ndnach Composer)
function loadEnv($path) {
    if (!file_exists($path)) {
        return false;
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        // Ignori l-commentaires (li fihom #)
        if (strpos(trim($line), '#') === 0) continue;

        // Parse l-line (KEY=VALUE)
        list($name, $value) = explode('=', $line, 2);
        
        // Nettoyer l-values (remove spaces and quotes)
        $name = trim($name);
        $value = trim($value, " \t\n\r\0\x0B\"");

        // Stocker f $_ENV w putenv
        putenv(sprintf('%s=%s', $name, $value));
        $_ENV[$name] = $value;
    }
}

// 2. Appel dial l-fonction
loadEnv(__DIR__ . '/.env');

// 3. Récupération dial les variables
$host   = getenv('DB_HOST');
$dbname = getenv('DB_NAME');
$user   = getenv('DB_USER');
$pass   = getenv('DB_PASS');

try {
    $db = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}