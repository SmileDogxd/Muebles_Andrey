<?php
// auth/register.php
session_start();
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = trim($_POST['nombre']);
    $email = trim($_POST['email']);
    $contraseña = trim($_POST['contraseña']);
    $confirmar_contraseña = trim($_POST['confirmar_contraseña']);
    $telefono = trim($_POST['telefono']);
    $direccion = trim($_POST['direccion']);

    // Validaciones básicas
    if (empty($nombre) || empty($email) || empty($contraseña) || empty($confirmar_contraseña)) {
        $error = "Por favor, completa todos los campos obligatorios.";
    } elseif ($contraseña !== $confirmar_contraseña) {
        $error = "Las contraseñas no coinciden.";
    } else {
        // Verificar si el email ya existe
        $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $error = "El correo electrónico ya está registrado.";
        } else {
            // Hashear la contraseña
            $hashed_contraseña = password_hash($contraseña, PASSWORD_DEFAULT);

            // Insertar el nuevo usuario
            $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, email, contraseña, telefono, direccion) VALUES (?, ?, ?, ?, ?)");
            if ($stmt->execute([$nombre, $email, $hashed_contraseña, $telefono, $direccion])) {
                $_SESSION['mensaje'] = "Registro exitoso. Por favor, inicia sesión.";
                header("Location: login.php");
                exit();
            } else {
                $error = "Error al registrar el usuario.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrarse - P&W</title>
    <link rel="stylesheet" href="../styles/registro.css">
    <link rel="icon" href="../images/logo_invertido.png" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;family=Rubik:wght@300..900&display=swap" rel="stylesheet">
</head>
<body>
    <div class="register-container">
        <div class="register-box">
            <img src="../images/logo_principal.png" alt="Logo" class="logo">
            <h2>Registro de Usuario</h2>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    <form method="post" action="register.php">
        <div class="input-group">
            <label for="nombre" class="form-label">Nombre *</label>
            <input type="text" class="form-control" id="nombre" name="nombre" required value="<?php echo isset($nombre) ? htmlspecialchars($nombre) : ''; ?>">
        </div>
        <div class="input-group">
            <label for="email" class="form-label">Correo Electrónico *</label>
            <input type="email" class="form-control" id="email" name="email" required value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>">
        </div>
        <div class="input-group">
            <label for="contraseña" class="form-label">Contraseña *</label>
            <input type="password" class="form-control" id="contraseña" name="contraseña" required>
        </div>
        <div class="input-group">
            <label for="confirmar_contraseña" class="form-label">Confirmar Contraseña *</label>
            <input type="password" class="form-control" id="confirmar_contraseña" name="confirmar_contraseña" required>
        </div>
        <div class="input-group">
            <label for="telefono" class="form-label">Teléfono</label>
            <input type="text" class="form-control" id="telefono" name="telefono" value="<?php echo isset($telefono) ? htmlspecialchars($telefono) : ''; ?>">
        </div>
        <div class="input-group">
            <label for="direccion" class="form-label">Dirección</label>
            <input type="text" class="form-control" id="direccion" name="direccion" value="<?php echo isset($direccion) ? htmlspecialchars($direccion) : ''; ?>">
        </div>
        <button type="submit" class="btn btn-primary">Registrar</button>
        <a href="login.php" class="btn btn-link">¿Ya tienes una cuenta? Inicia sesión</a>
    </form>
        </div>
    </div>
</body>
</html>


<?php include('../includes/footer.php'); ?>

