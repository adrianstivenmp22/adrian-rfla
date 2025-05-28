<?php
session_start();
if (empty($_SESSION['user_id']) || $_SESSION['user_role'] !== 'estudiante') {
    header('Location: ../auth/login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Estudiante</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto mt-10">
        <h1 class="text-3xl font-bold mb-4">Bienvenido Estudiante, <?= htmlspecialchars($_SESSION['user_name']) ?></h1>
        <p class="mb-6">Este es el panel exclusivo para estudiantes.</p>
        <a href="../auth/logout.php" class="text-blue-600 underline">Cerrar sesión</a>
    </div>
</body>
</html>