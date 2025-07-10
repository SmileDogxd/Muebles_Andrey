<?php
// facturas/ver_factura.php
session_start();
require_once '../config/database.php';

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

$usuario_id = $_SESSION['usuario_id'];

// Verificar si se ha pasado un ID de compra
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['error'] = "Factura no válida.";
    header("Location: index.php");
    exit();
}

$compra_id = $_GET['id'];

// Obtener la compra y verificar que pertenece al usuario
$stmt = $pdo->prepare("SELECT * FROM compras WHERE id = ? AND usuario_id = ?");
$stmt->execute([$compra_id, $usuario_id]);
$compra = $stmt->fetch();

if (!$compra) {
    $_SESSION['error'] = "Factura no encontrada o no autorizada.";
    header("Location: index.php");
    exit();
}

// Obtener los ítems de la compra
$stmt = $pdo->prepare("
    SELECT ci.*, p.nombre AS producto_nombre
    FROM compra_items ci
    JOIN productos p ON ci.producto_id = p.id
    WHERE ci.compra_id = ?
");
$stmt->execute([$compra_id]);
$compra_items = $stmt->fetchAll();
?>

<?php include('../includes/header.php'); ?>
<?php include('../includes/navbar.php'); ?>

<div class="container mt-5">
    <h2>Detalle de la Factura</h2>
    <div class="card mb-4">
        <div class="card-body">
            <p><strong>ID de Compra:</strong> <?php echo $compra['id']; ?></p>
            <p><strong>Total:</strong> $<?php echo number_format($compra['total'], 2); ?></p>
            <p><strong>Fecha de Compra:</strong> <?php echo htmlspecialchars($compra['fecha_compra']); ?></p>
        </div>
    </div>

    <?php if (count($compra_items) > 0): ?>
        <h4>Productos Adquiridos</h4>
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
                <?php foreach ($compra_items as $item): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item['producto_nombre']); ?></td>
                        <td><?php echo number_format($item['precio'], 2); ?></td>
                        <td><?php echo htmlspecialchars($item['cantidad']); ?></td>
                        <td><?php echo number_format($item['precio'] * $item['cantidad'], 2); ?></td>
                    </tr>
                <?php endforeach; ?>
                <tr>
                    <td colspan="3" class="text-end"><strong>Total:</strong></td>
                    <td><strong>$<?php echo number_format($compra['total'], 2); ?></strong></td>
                </tr>
            </tbody>
        </table>
    <?php else: ?>
        <p>No hay productos en esta factura.</p>
    <?php endif; ?>

    <a href="index.php" class="btn btn-secondary">Volver al Historial de Facturas</a>
    <a href="factura.php" class="btn btn-primary">Descargar Factura en PDF</a>
</div>

<?php include('../includes/footer.php'); ?>
