<?php
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="/Project_Wood/dashboard.php">Muebles Andrey</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" 
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <?php if (isset($_SESSION['usuario_id'])): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/Project_Wood/productos/index.php">Productos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/Project_Wood/carrito/index.php">Carrito</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/Project_Wood/facturas/index.php">Mis Facturas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/Project_Wood/perfil/index.php">Mi Perfil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/Project_Wood/auth/logout.php">Cerrar Sesión</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/Project_Wood/auth/login.php">Iniciar Sesión</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/Project_Wood/auth/register.php">Registrar</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
