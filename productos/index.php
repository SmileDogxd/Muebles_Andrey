<?php
// productos/index.php

require_once '../config/database.php';
include '../includes/header.php';
include '../includes/navbar.php';

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

// Obtener todos los productos disponibles (stock > 0)
$stmt = $pdo->prepare("SELECT * FROM productos WHERE stock > 0 ORDER BY creado_en DESC");
$stmt->execute();
$productos = $stmt->fetchAll();
?>

<div class="container mt-5">
    <h2>Productos Disponibles</h2>
    <div class="row">
        <?php foreach ($productos as $producto): ?>
            <div class="col-md-4">
                <div class="card mb-4">
                    <?php if ($producto['imagen'] && file_exists("../uploads/" . $producto['imagen'])): ?>
                        <img src="../uploads/<?php echo htmlspecialchars($producto['imagen']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($producto['nombre']); ?>" style="height:200px; object-fit:cover;">
                    <?php else: ?>
                        <img src="https://via.placeholder.com/200x200.png?text=Sin+Imagen" class="card-img-top" alt="Sin Imagen" style="height:200px; object-fit:cover;">
                    <?php endif; ?>
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($producto['nombre']); ?></h5>
                        <p class="card-text"><?php echo htmlspecialchars($producto['descripcion']); ?></p>
                        <p class="card-text"><strong>Precio:</strong> $<?php echo number_format($producto['precio'], 2); ?></p>
                        <p class="card-text"><strong>Stock:</strong> <?php echo htmlspecialchars($producto['stock']); ?></p>
                        <form method="post" action="../carrito/agregar.php">
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

<?php
include '../includes/footer.php';
?>
