<?php
// compras/procesar_compra.php
require_once '../config/database.php';
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: /dashboard_usuario/auth/login.php");
    exit();
}

$usuario_id = $_SESSION['usuario_id'];

// Obtener los ítems en el carrito
$stmt = $pdo->prepare("
    SELECT carrito.id, productos.id AS producto_id, productos.nombre, productos.precio, carrito.cantidad, productos.stock
    FROM carrito
    JOIN productos ON carrito.producto_id = productos.id
    WHERE carrito.usuario_id = ?
");
$stmt->execute([$usuario_id]);
$carrito_items = $stmt->fetchAll();

if (count($carrito_items) == 0) {
    $_SESSION['error'] = "Tu carrito está vacío.";
    header("Location: /dashboard_usuario/carrito/index.php");
    exit();
}

// Verificar que hay suficiente stock para cada producto
foreach ($carrito_items as $item) {
    if ($item['cantidad'] > $item['stock']) {
        $_SESSION['error'] = "No hay suficiente stock para el producto: " . htmlspecialchars($item['nombre']);
        header("Location: /dashboard_usuario/carrito/index.php");
        exit();
    }
}

try {
    // Iniciar transacción
    $pdo->beginTransaction();

    // Insertar en la tabla compras
    $stmtCompra = $pdo->prepare("INSERT INTO compras (usuario_id, total, fecha_compra) VALUES (?, ?, NOW())");
    $total = 0;
    foreach ($carrito_items as $item) {
        $total += $item['precio'] * $item['cantidad'];
    }
    $stmtCompra->execute([$usuario_id, $total]);
    $compra_id = $pdo->lastInsertId();

    // Insertar en la tabla compra_items y actualizar el stock
    $stmtCompraItem = $pdo->prepare("INSERT INTO compra_items (compra_id, producto_id, cantidad, precio_unitario) VALUES (?, ?, ?, ?)");
    $stmtActualizarStock = $pdo->prepare("UPDATE productos SET cantidad = cantidad - ? WHERE id = ?");

    foreach ($carrito_items as $item) {
        $stmtCompraItem->execute([$compra_id, $item['producto_id'], $item['cantidad'], $item['precio']]);
        $stmtActualizarStock->execute([$item['cantidad'], $item['producto_id']]);
    }

    // Insertar en la tabla facturas
    $stmtFactura = $pdo->prepare("INSERT INTO facturas (compra_id, usuario_id, total, fecha_factura) VALUES (?, ?, ?, NOW())");
    $stmtFactura->execute([$compra_id, $usuario_id, $total]);

    // Limpiar el carrito
    $stmtClearCart = $pdo->prepare("DELETE FROM carrito WHERE usuario_id = ?");
    $stmtClearCart->execute([$usuario_id]);

    // Commit de la transacción
    $pdo->commit();

    $_SESSION['success'] = "Compra realizada exitosamente.";
    header("Location: /dashboard_usuario/facturas/ver_factura.php?id=$compra_id");
    exit();
} catch (Exception $e) {
    // Rollback en caso de error
    $pdo->rollBack();
    $_SESSION['error'] = "Error al procesar la compra: " . $e->getMessage();
    header("Location: /dashboard_usuario/carrito/index.php");
    exit();
}
?>
