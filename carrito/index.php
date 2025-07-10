<?php
// carrito/index.php

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
    SELECT c.id AS carrito_id, p.id AS producto_id, p.nombre, p.precio, p.imagen, c.cantidad, p.stock 
    FROM carrito c
    JOIN productos p ON c.producto_id = p.id
    WHERE c.usuario_id = ?
");
$stmt->execute([$usuario_id]);
$carrito_items = $stmt->fetchAll();

// Calcular el total
$total = 0;
foreach ($carrito_items as $item) {
    $subtotal = $item['precio'] * $item['cantidad'];
    $total += $subtotal;
}
?>

<?php
include '../includes/header.php';
include '../includes/navbar.php';
?>

<div class="container mt-5">
    <h2>Tu Carrito de Compras</h2>

    <?php if (isset($_SESSION['mensaje'])): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($_SESSION['mensaje']); ?></div>
        <?php unset($_SESSION['mensaje']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($_SESSION['error']); ?></div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <?php if (count($carrito_items) > 0): ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Imagen</th>
                    <th>Producto</th>
                    <th>Precio Unitario ($)</th>
                    <th>Cantidad</th>
                    <th>Subtotal ($)</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($carrito_items as $item): ?>
                    <tr>
                        <td>
                            <?php if ($item['imagen'] && file_exists("../uploads/" . $item['imagen'])): ?>
                                <img src="../uploads/<?php echo htmlspecialchars($item['imagen']); ?>" alt="<?php echo htmlspecialchars($item['nombre']); ?>" width="50">
                            <?php else: ?>
                                <img src="https://via.placeholder.com/50x50.png?text=Sin+Imagen" alt="Sin Imagen" width="50">
                            <?php endif; ?>
                        </td>
                        <td><?php echo htmlspecialchars($item['nombre']); ?></td>
                        <td><?php echo number_format($item['precio'], 2); ?></td>
                        <td><?php echo htmlspecialchars($item['cantidad']); ?></td>
                        <td><?php echo number_format($item['precio'] * $item['cantidad'], 2); ?></td>
                        <td>
                            <a href="eliminar.php?id=<?php echo $item['carrito_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de eliminar este ítem del carrito?');">Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <tr>
                    <td colspan="4" class="text-end"><strong>Total:</strong></td>
                    <td colspan="2"><strong>$<?php echo number_format($total, 2); ?></strong></td>
                </tr>
            </tbody>
        </table>
        <a href="../compras/checkout.php" class="btn btn-success">Proceder al Pago</a>
    <?php else: ?>
        <p>No tienes productos en tu carrito.</p>
        <a href="../productos/index.php" class="btn btn-primary">Volver a Productos</a>
    <?php endif; ?>
</div>

<?php
include '../includes/footer.php';
?>
