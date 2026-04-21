<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

try {
    // Connect without database name
    $dsn = "mysql:host=$host;charset=$charset";
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);

    // Drop and Recreate Database for a clean slate
    $pdo->exec("DROP DATABASE IF EXISTS ecommerce_db");
    $pdo->exec("CREATE DATABASE ecommerce_db");
    echo "Database 'ecommerce_db' reset successfully.<br>";

    // Switch to database
    $pdo->exec("USE ecommerce_db");

    // Read and execute schema.sql
    $sql = file_get_contents(__DIR__ . '/database/schema.sql');
    
    // The schema.sql might contain multiple statements, PDO::exec only does one.
    // However, since schema.sql is likely multiple statements, we should split or use a different method.
    // But actually, many MySQL drivers allow multiple statements if configured.
    // Let's do it safely by splitting.
    
    // Remove comments
    $sql = preg_replace('/--.*?\n/', '', $sql);
    $queries = explode(';', $sql);

    foreach ($queries as $query) {
        $query = trim($query);
        if (!empty($query)) {
            $pdo->exec($query);
        }
    }

    echo "Database schema imported successfully!<br>";
    echo "<strong>You can now delete this file and start using the app.</strong><br>";
    echo "<a href='index.php'>Go to Homepage</a>";

} catch (\PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
