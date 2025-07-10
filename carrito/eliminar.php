<?php
// carrito/eliminar.php

session_start();
require_once '../config/database.php';

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

// Verificar si se ha pasado el ID del ítem del carrito
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['error'] = "Ítem del carrito no válido.";
    header("Location: index.php");
    exit();
}

$carrito_id = $_GET['id'];
$usuario_id = $_SESSION['usuario_id'];

// Obtener el ítem del carrito
$stmt = $pdo->prepare("SELECT * FROM carrito WHERE id = ? AND usuario_id = ?");
$stmt->execute([$carrito_id, $usuario_id]);
$carrito_item = $stmt->fetch();

if (!$carrito_item) {
    $_SESSION['error'] = "Ítem del carrito no encontrado.";
    header("Location: index.php");
    exit();
}

// Obtener la cantidad y el producto_id para actualizar el stock
$cantidad = $carrito_item['cantidad'];
$producto_id = $carrito_item['producto_id'];

// Eliminar el ítem del carrito
$stmt = $pdo->prepare("DELETE FROM carrito WHERE id = ?");
if ($stmt->execute([$carrito_id])) {
    // Actualizar el stock del producto
    $stmtStock = $pdo->prepare("UPDATE productos SET stock = stock + ? WHERE id = ?");
    $stmtStock->execute([$cantidad, $producto_id]);

    $_SESSION['mensaje'] = "Ítem eliminado del carrito correctamente.";
    header("Location: index.php");
    exit();
} else {
    $_SESSION['error'] = "Error al eliminar el ítem del carrito.";
    header("Location: index.php");
    exit();
}
?>
