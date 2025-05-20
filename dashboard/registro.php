<?php
include 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $documento = $_POST['documento'];
    $tipo_usuario = $_POST['tipo_usuario'];
    $email = $_POST['email'];
    $contrasena = password_hash($_POST['contrasena'], PASSWORD_BCRYPT);
    $telefono = $_POST['telefono'];
    $direccion = $_POST['direccion'];
    $estado = "activo";
    // Antes del INSERT, agregamos esta validación:
    $sql_check = "SELECT COUNT(*) AS total FROM Usuarios WHERE tipo_usuario = 'administrador'";
    $result = $conn->query($sql_check);
    $row = $result->fetch_assoc();

    if ($tipo_usuario == 'administrador' && $row['total'] > 0) {
        header("Location: formulario.php?mensaje=Ya existe un administrador registrado.&tipo=error");
        exit();
    }

    $sql = "INSERT INTO Usuarios (nombre, apellido, documento_identidad, tipo_usuario, email, contrasena, telefono, direccion, estado) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    
    if ($stmt) {
        $stmt->bind_param("sssssssss", $nombre, $apellido, $documento, $tipo_usuario, $email, $contrasena, $telefono, $direccion, $estado);

        if ($stmt->execute()) {
            echo '
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
            <div class="container mt-4">
                <div class="alert alert-success text-center w-75 mx-auto" role="alert">
                    <strong>¡Éxito!</strong> El usuario fue registrado correctamente.
                </div>
            </div>';
        } else {
            echo '
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
            <div class="container mt-4">
                <div class="alert alert-danger text-center w-75 mx-auto" role="alert">
                    <strong>Error:</strong> No se pudo registrar el usuario. ' . $stmt->error . '
                </div>
            </div>';
        }

        $stmt->close();
    } else {
        echo '
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <div class="container mt-4">
            <div class="alert alert-danger text-center w-75 mx-auto" role="alert">
                <strong>Error:</strong> Fallo en la preparación de la consulta. ' . $conn->error . '
            </div>
        </div>';
    }

    $conn->close();
}
?><!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f6f9;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .registro-card {
            max-width: 500px;
            width: 100%;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 0 30px rgba(0,0,0,0.1);
            background-color: #fff;
        }
        .btn-primary {
            width: 100%;
        }
    </style>
</head>
<body>

<div class="registro-card">
    <h3 class="text-center mb-4">Registro</h3>

    <!-- Mensaje de éxito o error -->
    <?php if (isset($_GET['mensaje'])): ?>
        <div class="alert <?= $_GET['tipo'] == 'error' ? 'alert-danger' : 'alert-success' ?> text-center p-2" role="alert">
            <?= htmlspecialchars($_GET['mensaje']) ?>
        </div>
    <?php endif; ?>

    <form action="registro.php" method="POST">
        <input class="form-control mb-2" type="text" name="nombre" placeholder="Nombre" required>
        <input class="form-control mb-2" type="text" name="apellido" placeholder="Apellido" required>
        <input class="form-control mb-2" type="text" name="documento" placeholder="Documento de identidad" required>
        <select class="form-select mb-2" name="tipo_usuario" required>
            <option value="">Seleccione tipo</option>
            <option value="administrador">Administrador</option>
            <option value="docente">Docente</option>
            <option value="estudiante">Estudiante</option>
        </select>
        <input class="form-control mb-2" type="email" name="email" placeholder="Correo electrónico" required>
        <input class="form-control mb-2" type="password" name="contrasena" placeholder="Contraseña" required>
        <input class="form-control mb-2" type="text" name="telefono" placeholder="Teléfono">
        <input class="form-control mb-3" type="text" name="direccion" placeholder="Dirección">

        <button class="btn btn-primary" type="submit">Registrador</button>
    </form>
</div>

</body>
</html>
