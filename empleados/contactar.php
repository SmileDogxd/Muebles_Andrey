<?php
// empleados/contactar.php
session_start();
require_once '../config/database.php';

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

$usuario_id = $_SESSION['usuario_id'];

// Obtener todos los empleados
$stmt = $pdo->prepare("SELECT * FROM empleados ORDER BY nombre ASC");
$stmt->execute();
$empleados = $stmt->fetchAll();

// Procesar el formulario de contacto
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $empleado_id = $_POST['empleado_id'];
    $mensaje = trim($_POST['mensaje']);

    // Validaciones
    if (empty($empleado_id) || empty($mensaje)) {
        $error = "Por favor, selecciona un empleado y escribe un mensaje.";
    } else {
        // Obtener la información del empleado
        $stmt = $pdo->prepare("SELECT email FROM empleados WHERE id = ?");
        $stmt->execute([$empleado_id]);
        $empleado = $stmt->fetch();

        if ($empleado) {
            // Enviar el correo electrónico (simplificado)
            $destinatario = $empleado['email'];
            $asunto = "Contacto desde Dashboard de Usuario";
            $cuerpo = "Has recibido un mensaje de un usuario:\n\n" . htmlspecialchars($mensaje);

            // Usar la función mail() de PHP (requiere configuración del servidor)
            if (mail($destinatario, $asunto, $cuerpo)) {
                $_SESSION['mensaje'] = "Mensaje enviado exitosamente.";
            } else {
                $_SESSION['error'] = "Error al enviar el mensaje. Inténtalo de nuevo más tarde.";
            }
        } else {
            $error = "Empleado no encontrado.";
        }
    }
}
?>

<?php include('../includes/header.php'); ?>
<?php include('../includes/navbar.php'); ?>

<div class="container mt-5">
    <h2>Contactar con un Empleado</h2>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <?php if (isset($_SESSION['mensaje'])): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($_SESSION['mensaje']); ?></div>
        <?php unset($_SESSION['mensaje']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($_SESSION['error']); ?></div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <form method="post" action="contactar.php">
        <div class="mb-3">
            <label for="empleado_id" class="form-label">Selecciona un Empleado *</label>
            <select class="form-select" id="empleado_id" name="empleado_id" required>
                <option value="">-- Selecciona un Empleado --</option>
                <?php foreach ($empleados as $empleado): ?>
                    <option value="<?php echo $empleado['id']; ?>">
                        <?php echo htmlspecialchars($empleado['nombre']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="mensaje" class="form-label">Mensaje *</label>
            <textarea class="form-control" id="mensaje" name="mensaje" rows="5" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Enviar Mensaje</button>
    </form>
</div>

<?php include('../includes/footer.php'); ?>
