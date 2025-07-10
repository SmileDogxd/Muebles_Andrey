<?php
// carrito/agregar.php

session_start();
require_once '../config/database.php';

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

// Verificar si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario_id = $_SESSION['usuario_id'];
    $producto_id = $_POST['producto_id'];
    $cantidad = intval($_POST['cantidad']);

    // Validar datos
    if ($cantidad < 1) {
        $_SESSION['error'] = "La cantidad debe ser al menos 1.";
        header("Location: ../productos/index.php");
        exit();
    }

    // Obtener información del producto
    $stmt = $pdo->prepare("SELECT * FROM productos WHERE id = ?");
    $stmt->execute([$producto_id]);
    $producto = $stmt->fetch();

    if (!$producto) {
        $_SESSION['error'] = "Producto no encontrado.";
        header("Location: ../productos/index.php");
        exit();
    }

    if ($producto['stock'] < $cantidad) {
        $_SESSION['error'] = "No hay suficiente stock disponible.";
        header("Location: ../productos/index.php");
        exit();
    }

    // Verificar si el producto ya está en el carrito
    $stmt = $pdo->prepare("SELECT * FROM carrito WHERE usuario_id = ? AND producto_id = ?");
    $stmt->execute([$usuario_id, $producto_id]);
    $carrito_item = $stmt->fetch();

    if ($carrito_item) {
        // Actualizar la cantidad en el carrito
        $nuevo_total = $carrito_item['cantidad'] + $cantidad;
        if ($producto['stock'] < $nuevo_total) {
            $_SESSION['error'] = "No hay suficiente stock disponible para aumentar la cantidad.";
            header("Location: ../productos/index.php");
            exit();
        }

        $stmt = $pdo->prepare("UPDATE carrito SET cantidad = cantidad + ? WHERE id = ?");
        if ($stmt->execute([$cantidad, $carrito_item['id']])) {
            // Actualizar el stock en la base de datos
            $stmtStock = $pdo->prepare("UPDATE productos SET stock = stock - ? WHERE id = ?");
            $stmtStock->execute([$cantidad, $producto_id]);

            $_SESSION['mensaje'] = "Producto actualizado en el carrito exitosamente.";
            header("Location: ../productos/index.php");
            exit();
        } else {
            $_SESSION['error'] = "Error al actualizar el carrito.";
            header("Location: ../productos/index.php");
            exit();
        }
    } else {
        // Insertar un nuevo ítem en el carrito
        $stmt = $pdo->prepare("INSERT INTO carrito (usuario_id, producto_id, cantidad) VALUES (?, ?, ?)");
        if ($stmt->execute([$usuario_id, $producto_id, $cantidad])) {
            // Actualizar el stock en la base de datos
            $stmtStock = $pdo->prepare("UPDATE productos SET stock = stock - ? WHERE id = ?");
            $stmtStock->execute([$cantidad, $producto_id]);

            $_SESSION['mensaje'] = "Producto agregado al carrito exitosamente.";
            header("Location: ../productos/index.php");
            exit();
        } else {
            $_SESSION['error'] = "Error al agregar el producto al carrito.";
            header("Location: ../productos/index.php");
            exit();
        }
    }
} else {
    // Acceso directo sin enviar el formulario
    header("Location: ../productos/index.php");
    exit();
}
?>
