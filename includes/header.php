<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Muebles Andrey - Dashboard de Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
