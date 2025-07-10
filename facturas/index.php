<?php
// facturas/index.php
session_start();
require_once '../config/database.php';

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

$usuario_id = $_SESSION['usuario_id'];

// Obtener todas las compras del usuario
$stmt = $pdo->prepare("SELECT * FROM compras WHERE usuario_id = ? ORDER BY fecha_compra DESC");
$stmt->execute([$usuario_id]);
$compras = $stmt->fetchAll();
?>

<?php include('../includes/header.php'); ?>
<?php include('../includes/navbar.php'); ?>

<div class="container mt-5">
    <h2>Mis Facturas</h2>

    <?php if (isset($_SESSION['mensaje'])): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($_SESSION['mensaje']); ?></div>
        <?php unset($_SESSION['mensaje']); ?>
    <?php endif; ?>

    <?php if (count($compras) > 0): ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID de Compra</th>
                    <th>Total ($)</th>
                    <th>Fecha de Compra</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($compras as $compra): ?>
                    <tr>
                        <td><?php echo $compra['id']; ?></td>
                        <td><?php echo number_format($compra['total'], 2); ?></td>
                        <td><?php echo htmlspecialchars($compra['fecha_compra']); ?></td>
                        <td>
                            <a href="ver_factura.php?id=<?php echo $compra['id']; ?>" class="btn btn-info btn-sm">Ver Factura</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No has realizado ninguna compra todavía.</p>
        <a href="../index.php" class="btn btn-primary">Explorar Productos</a>
    <?php endif; ?>
</div>

<?php include('../includes/footer.php'); ?>
