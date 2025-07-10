<?php
// auth/login.php
session_start();
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $contraseña = trim($_POST['contraseña']);

    if (empty($email) || empty($contraseña)) {
        $error = "Por favor, completa todos los campos.";
    } else {
        // Obtener el usuario por email
        $stmt = $pdo->prepare("SELECT id, contraseña FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        $usuario = $stmt->fetch();

        if ($usuario && password_verify($contraseña, $usuario['contraseña'])) {
            // Iniciar sesión
            $_SESSION['usuario_id'] = $usuario['id'];
            header("Location: ../dashboard.php");
            exit();
        } else {
            $error = "Correo electrónico o contraseña incorrectos.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesión - P&W</title>
    <link rel="stylesheet" href="../styles/login.css">
    <link rel="icon" href="../images/logo_invertido.png" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800&family=Grey+Qo&family=Matemasie&family=Noto+Sans+JP:wght@100..900&family=Rubik:ital,wght@0,300..900;1,300..900&display=swap" rel="stylesheet">
</head>
<body>

<div class="login-container">
        <div class="login-box">
            <img src="../images/logo_principal.png" alt="Logo" class="logo">
            <h2>Inicia Sesión</h2>

            <?php if (!empty($mensaje)): ?>
                <p><?php echo $mensaje; ?></p>
            <?php endif; ?>

            <form action="login.php" method="POST">
                <div class="input-group">
                    <input type="email" name="email" id="email" required placeholder="Email">
                </div>
                <div class="input-group">
                    <input type="password" name="contraseña" id="contraseña" required placeholder="Contraseña">
                </div>
                <div class="options">
                    <label><input type="checkbox"> Recuérdame</label>
                    <a href="#" id="forgot-password-link">¿Olvidaste tu contraseña?</a>
                </div>
                <button type="submit" id="logear">Iniciar Sesión</button>
            </form>
            <p>¿No tienes una cuenta? <a href="register.php">Regístrate</a></p>
            <p><a href="/Project_Wood/index.html">Regresar</a></p>
        </div>
        </div>
    </div>

    <div id="forgot-password-modal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Recuperar Contraseña</h2>
            <form action="">
                <div class="input-group">
                    <input type="email" id="forgot-email" required placeholder="Ingresa tu email">
                </div>
                <button type="submit" id="send-code">Enviar Código</button>
            </form>
        </div>
    </div>
    <script>
        // Obtener elementos del DOM
        var modal = document.getElementById("forgot-password-modal");
        var btn = document.getElementById("forgot-password-link");
        var span = document.getElementsByClassName("close")[0];

        // Abrir el modal al hacer clic en el enlace
        btn.onclick = function() {
            modal.style.display = "flex";
        }

        // Cerrar el modal al hacer clic 
        span.onclick = function() {
            modal.style.display = "none";
        }

        // Cerrar el modal si se hace clic fuera del contenido
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>
</body>
</html>
<?php include('../includes/footer.php'); ?>
