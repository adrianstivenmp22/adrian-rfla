<?php
// Parámetros de conexión
$host = "localhost";
$usuario = "root";
$contrasena = "";
$base_datos = "institucion_educativa";
//             width: 300px;

// Crear conexión
$conexion = mysqli_connect($host, $usuario, $contrasena, $base_datos);

// Verificar conexión
if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}

// Opcional: establecer codificación de caracteres
mysqli_set_charset($conexion, "utf8");
?>
