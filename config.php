<?php
define('USER', 'blutonium');
define('PASSWORD', 'train');
define('HOST', 'localhost');
define('DATABASE', 'blutopiaca');

try {
    $connection = new PDO("pgsql:host=".HOST.";dbname=".DATABASE, USER, PASSWORD);
} catch (PDOException $e) {
    exit("Error: " . $e->getMessage());
}
?>