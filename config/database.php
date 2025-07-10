<?php
// config/database.php

$host = 'localhost:3306';
$db   = 'ibatechh_muebles_andrey'; // Reemplaza con el nombre real de tu base de datos
$user = 'ibatechh_muebles';           // Reemplaza con tu usuario de la base de datos
$pass = 'yulian848andrey';               // Reemplaza con tu contraseña de la base de datos
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Mostrar errores como excepciones
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Modo de fetch
    PDO::ATTR_EMULATE_PREPARES   => false,                  // Desactivar emulación de prepared statements
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {

    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}
?>
