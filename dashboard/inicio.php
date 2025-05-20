<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

$nombre = $_SESSION['usuario']['nombre'];
$tipo = $_SESSION['usuario']['tipo_usuario'];

echo "<h1>¡Bienvenido, $nombre!</h1>";
echo "<p>Usted ha iniciado sesión como <strong>$tipo</strong></p>";

switch ($tipo) {
    case 'administrador':
        header("Location: admin.php");
        break;
    case 'docente':
        header("Location: docente.php");
        break;
    case 'estudiante':
        header("Location: estudiante.php");
        break;
    default:
        echo "Rol desconocido.";
        break;
}
?>
