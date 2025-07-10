<?php
// perfil/actualizar.php
session_start();
require_once '../config/database.php';

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

$usuario_id = $_SESSION['usuario_id'];

// Obtener la información actual del usuario
$stmt = $pdo->prepare("SELECT nombre, email, telefono, direccion FROM usuarios WHERE id = ?");
$stmt->execute([$usuario_id]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

// Actualizar información si se envía el formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = trim($_POST['nombre']);
    $email = trim($_POST['email']);
    $telefono = trim($_POST['telefono']);
    $direccion = trim($_POST['direccion']);

    // Validar datos
    if (empty($nombre) || empty($email)) {
        $error = "Los campos Nombre y Email son obligatorios.";
    } else {
        // Verificar si el email ya está en uso por otro usuario
        $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = ? AND id != ?");
        $stmt->execute([$email, $usuario_id]);
        if ($stmt->fetch()) {
            $error = "El correo electrónico ya está en uso por otro usuario.";
        } else {
            // Actualizar el perfil en la base de datos
            $stmt = $pdo->prepare("UPDATE usuarios SET nombre = ?, email = ?, telefono = ?, direccion = ? WHERE id = ?");
            if ($stmt->execute([$nombre, $email, $telefono, $direccion, $usuario_id])) {
                $_SESSION['mensaje'] = "Perfil actualizado correctamente.";
                header("Location: index.php");
                exit();
            } else {
                $error = "Error al actualizar el perfil.";
            }
        }
    }
}
?>

<?php include('../includes/header.php'); ?>
<?php include('../includes/navbar.php'); ?>

<div class="container mt-5">
    <h2>Actualizar Perfil</h2>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    <div class="card">
        <div class="card-body">
            <form method="post" action="actualizar.php">
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre *</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" required value="<?php echo htmlspecialchars($usuario['nombre']); ?>">
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Correo Electrónico *</label>
                    <input type="email" class="form-control" id="email" name="email" required value="<?php echo htmlspecialchars($usuario['email']); ?>">
                </div>
                <div class="mb-3">
                    <label for="telefono" class="form-label">Teléfono</label>
                    <input type="text" class="form-control" id="telefono" name="telefono" value="<?php echo htmlspecialchars($usuario['telefono']); ?>">
                </div>
                <div class="mb-3">
                    <label for="direccion" class="form-label">Dirección</label>
                    <input type="text" class="form-control" id="direccion" name="direccion" value="<?php echo htmlspecialchars($usuario['direccion']); ?>">
                </div>
                <button type="submit" class="btn btn-success">Actualizar Perfil</button>
                <a href="index.php" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>
</div>

<?php include('../includes/footer.php'); ?>
