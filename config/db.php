<?php
$host = 'localhost';
$db   = 'ecommerce_db';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
     $pdo = new PDO($dsn, $user, $pass, $options);
     
     // Define BASE_URL for consistent pathing
     $base_dir = str_replace('\\', '/', dirname(__DIR__));
     $doc_root = str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']);
     $relative_path = str_replace($doc_root, '', $base_dir);
     $relative_path = '/' . trim($relative_path, '/') . '/';
     $relative_path = str_replace('//', '/', $relative_path); // Ensure only one slash if it was empty
     
     if (!defined('BASE_URL')) {
         define('BASE_URL', $relative_path);
     }
} catch (\PDOException $e) {
     throw new \PDOException($e->getMessage(), (int)$e->getCode());
}
?>
