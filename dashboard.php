<?php
// index.php
session_start();
require_once 'config/database.php';

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario_id'])) {
    header("Location: auth/login.php");
    exit();
}

// Obtener productos desde el Dashboard de Administración
$stmt = $pdo->prepare("SELECT * FROM productos WHERE stock > 0");
$stmt->execute();
$productos = $stmt->fetchAll();
?>

<?php include('includes/header.php'); ?>
<?php include('includes/navbar.php'); ?>

<div class="container mt-5">
    <h2>Productos Disponibles</h2>
    <div class="row">
        <?php foreach ($productos as $producto): ?>
            <div class="col-md-4">
                <div class="card mb-4">
                    <!-- Verificar si el producto tiene una imagen y mostrarla -->
                    <?php if (!empty($producto['imagen'])): ?>
                        <img src="../uploads/<?php echo htmlspecialchars($producto['imagen']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($producto['nombre']); ?>">
                    <?php else: ?>
                        <img src="../uploads/default.png" class="card-img-top" alt="Sin imagen disponible">
                    <?php endif; ?>
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($producto['nombre']); ?></h5>
                        <p class="card-text"><?php echo htmlspecialchars($producto['descripcion']); ?></p>
                        <p class="card-text"><strong>Precio:</strong> $<?php echo number_format($producto['precio'], 2); ?></p>
                        <p class="card-text"><strong>Stock:</strong> <?php echo htmlspecialchars($producto['stock']); ?></p>
                        <form method="post" action="carrito/agregar.php">
                            <input type="hidden" name="producto_id" value="<?php echo $producto['id']; ?>">
                            <div class="mb-3">
                                <label for="cantidad_<?php echo $producto['id']; ?>" class="form-label">Cantidad</label>
                                <input type="number" class="form-control" id="cantidad_<?php echo $producto['id']; ?>" name="cantidad" value="1" min="1" max="<?php echo $producto['stock']; ?>" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Agregar al Carrito</button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php include('includes/footer.php'); ?>
