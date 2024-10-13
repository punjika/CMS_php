<?php
$host = 'localhost';
$dbname = 'cms_project';
$username = 'root';
$password = 'Mysql@123';

try {
    // Try to establish a connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // If connected successfully
    // echo "Database connected successfully!<br>";

} catch (PDOException $e) {
    // If there is any error in the connection
    echo "Connection failed: " . $e->getMessage();
}
?>

