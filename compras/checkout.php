<?php
// compras/checkout.php
session_start();
require_once '../config/database.php';

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

$usuario_id = $_SESSION['usuario_id'];

// Obtener los ítems del carrito
$stmt = $pdo->prepare("
    SELECT c.id, p.id AS producto_id, p.nombre, p.precio, c.cantidad, p.stock 
    FROM carrito c
    JOIN productos p ON c.producto_id = p.id
    WHERE c.usuario_id = ?
");
$stmt->execute([$usuario_id]);
$carrito_items = $stmt->fetchAll();

// Calcular el total
$total = 0;
foreach ($carrito_items as $item) {
    $total += $item['precio'] * $item['cantidad'];
}

// Procesar la compra
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        // Iniciar transacción
        $pdo->beginTransaction();

        // Insertar en la tabla compras
        $stmt = $pdo->prepare("INSERT INTO compras (usuario_id, total) VALUES (?, ?)");
        $stmt->execute([$usuario_id, $total]);
        $compra_id = $pdo->lastInsertId();

        // Insertar cada ítem en compra_items y actualizar el stock
        foreach ($carrito_items as $item) {
            // Verificar stock nuevamente
            if ($item['stock'] < $item['cantidad']) {
                throw new Exception("Stock insuficiente para el producto: " . htmlspecialchars($item['nombre']));
            }

            // Insertar en compra_items
            $stmt = $pdo->prepare("INSERT INTO compra_items (compra_id, producto_id, cantidad, precio) VALUES (?, ?, ?, ?)");
            $stmt->execute([$compra_id, $item['producto_id'], $item['cantidad'], $item['precio']]);

            // Actualizar el stock en productos
            $stmt = $pdo->prepare("UPDATE productos SET stock = stock - ? WHERE id = ?");
            $stmt->execute([$item['cantidad'], $item['producto_id']]);
        }

        // Vaciar el carrito
        $stmt = $pdo->prepare("DELETE FROM carrito WHERE usuario_id = ?");
        $stmt->execute([$usuario_id]);

        // Confirmar transacción
        $pdo->commit();

        $_SESSION['mensaje'] = "Compra realizada exitosamente.";
        header("Location: ../facturas/ver_factura.php?id=" . $compra_id);
        exit();
    } catch (Exception $e) {
        // Revertir transacción
        $pdo->rollBack();
        $_SESSION['error'] = "Error al procesar la compra: " . $e->getMessage();
        header("Location: checkout.php");
        exit();
    }
}
?>

<?php include('../includes/header.php'); ?>
<?php include('../includes/navbar.php'); ?>

<div class="container mt-5">
    <h2>Checkout</h2>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($_SESSION['error']); ?></div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <?php if (count($carrito_items) > 0): ?>
        <h4>Resumen de la Compra</h4>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Precio Unitario ($)</th>
                    <th>Cantidad</th>
                    <th>Subtotal ($)</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($carrito_items as $item): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item['nombre']); ?></td>
                        <td><?php echo number_format($item['precio'], 2); ?></td>
                        <td><?php echo htmlspecialchars($item['cantidad']); ?></td>
                        <td><?php echo number_format($item['precio'] * $item['cantidad'], 2); ?></td>
                    </tr>
                <?php endforeach; ?>
                <tr>
                    <td colspan="3" class="text-end"><strong>Total:</strong></td>
                    <td><strong>$<?php echo number_format($total, 2); ?></strong></td>
                </tr>
            </tbody>
        </table>
        <form method="post" action="checkout.php">
            <button type="submit" class="btn btn-success">Confirmar Compra</button>
            <a href="../carrito/index.php" class="btn btn-secondary">Cancelar</a>
        </form>
    <?php else: ?>
        <p>No tienes productos en tu carrito.</p>
        <a href="../index.php" class="btn btn-primary">Volver a Productos</a>
    <?php endif; ?>
</div>

<?php include('../includes/footer.php'); ?>
