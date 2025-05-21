<?php
include __DIR__ . '/../db/conexion.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre       = trim($_POST['nombre']);
    $apellido     = trim($_POST['apellido']);
    $documento    = trim($_POST['documento']);
    $tipo_usuario = isset($_POST['tipo_usuario']) && in_array($_POST['tipo_usuario'], ['administrador','docente','estudiante'])
                    ? $_POST['tipo_usuario']
                    : 'estudiante';
    $email        = trim($_POST['email']);
    $passwordRaw  = $_POST['contrasena'];
    $telefono     = trim($_POST['telefono']);
    $direccion    = trim($_POST['direccion']);
    $estado       = 'activo';

    // Validación de documento
    if (!preg_match('/^\d{10}$/', $documento)) {
        $mensaje = "El documento debe ser numérico y tener exactamente 10 dígitos.";
        $tipo    = "error";
    } else {
        // Validar único administrador
        if ($tipo_usuario === 'administrador') {
            $stmt = $pdo->query("SELECT COUNT(*) FROM usuarios WHERE tipo_usuario = 'administrador'");
            $total = $stmt->fetchColumn();
            if ($total > 0) {
                header("Location: register.php?mensaje=Ya existe un administrador registrado.&tipo=error");
                exit;
            }
        }

        // Insertar usuario
        $hashed = password_hash($passwordRaw, PASSWORD_DEFAULT);
        $sql = "INSERT INTO usuarios
                  (nombre, apellido, documento_identidad, tipo_usuario,
                   email, contrasena, telefono, direccion, estado)
                VALUES
                  (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $ok = $stmt->execute([
            $nombre,
            $apellido,
            $documento,
            $tipo_usuario,
            $email,
            $hashed,
            $telefono,
            $direccion,
            $estado
        ]);

        if ($ok) {
            $mensaje = "¡Registro exitoso! Bienvenido al sistema escolar.";
            $tipo    = "success";
        } else {
            $errorInfo = $stmt->errorInfo();
            $mensaje   = "Error: No se pudo completar el registro. ({$errorInfo[2]})";
            $tipo      = "error";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es" class="h-full bg-white">
<head>
    <meta charset="UTF-8">
    <title>Registro Escolar | Sistema Integral</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Sistema de registro para la comunidad educativa">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 50%, #a855f7 100%);
        }
        .input-focus-effect:focus {
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.3);
        }
        .wave {
            animation: wave 8s linear infinite;
        }
        @keyframes wave {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body class="h-full" x-data="{ showPassword: false, loading: false }">
    <div class="min-h-full flex">
        <!-- Left Panel with Animation -->
        <div class="hidden lg:block relative w-1/2 gradient-bg overflow-hidden">
            <div class="absolute inset-0 flex items-center justify-center">
                <div class="text-white text-center px-16 z-10">
                    <h1 class="text-5xl font-bold mb-6 animate__animated animate__fadeInDown">Bienvenido al <span class="text-indigo-200">Colegio Excelencia</span></h1>
                    <p class="text-xl mb-8 animate__animated animate__fadeIn animate__delay-1s">Únete a nuestra comunidad educativa y forma parte de la excelencia académica.</p>
                    
                    <div class="flex justify-center space-x-4 animate__animated animate__fadeInUp animate__delay-2s">
                        <div class="bg-white/20 p-4 rounded-lg backdrop-blur-sm">
                            <i class="fas fa-graduation-cap text-3xl mb-2"></i>
                            <p class="font-medium">Educación de calidad</p>
                        </div>
                        <div class="bg-white/20 p-4 rounded-lg backdrop-blur-sm">
                            <i class="fas fa-users text-3xl mb-2"></i>
                            <p class="font-medium">Comunidad inclusiva</p>
                        </div>
                        <div class="bg-white/20 p-4 rounded-lg backdrop-blur-sm">
                            <i class="fas fa-lock text-3xl mb-2"></i>
                            <p class="font-medium">Plataforma segura</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Animated background elements -->
            <div class="absolute -bottom-32 -left-32 w-64 h-64 rounded-full bg-indigo-400/20 wave"></div>
            <div class="absolute -top-16 -right-16 w-32 h-32 rounded-full bg-purple-400/20 wave" style="animation-delay: -2s;"></div>
            <div class="absolute top-1/4 -right-20 w-48 h-48 rounded-full bg-violet-400/20 wave" style="animation-delay: -4s;"></div>
        </div>
        
        <!-- Right Panel - Form -->
        <div class="flex flex-1 flex-col justify-center py-12 px-4 sm:px-6 lg:flex-none lg:px-20 xl:px-24">
            <div class="mx-auto w-full max-w-md lg:w-96">
                <div class="text-center lg:text-left">
                    <h2 class="text-3xl font-bold text-gray-900">Crear cuenta</h2>
                    <p class="mt-2 text-sm text-gray-600">
                        ¿Ya tienes una cuenta? 
                        <a href="login.php" class="font-medium text-indigo-600 hover:text-indigo-500">Inicia sesión aquí</a>
                    </p>
                </div>

                <!-- Notification -->
                <?php if (!empty($mensaje)): ?>
                <div class="mt-8 rounded-md <?= $tipo == 'error' ? 'bg-red-50 p-4' : 'bg-green-50 p-4' ?> animate__animated animate__bounceIn">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <?php if ($tipo == 'error'): ?>
                                <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                            <?php else: ?>
                                <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                            <?php endif; ?>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium <?= $tipo == 'error' ? 'text-red-800' : 'text-green-800' ?>">
                                <?= htmlspecialchars($mensaje) ?>
                            </p>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <div class="mt-8">
                    <div class="mt-6">
                        <form action="register.php" method="POST" class="space-y-6" @submit="loading = true">
                            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                                <!-- Nombre -->
                                <div>
                                    <label for="nombre" class="block text-sm font-medium text-gray-700 mb-1">Nombre</label>
                                    <input id="nombre" name="nombre" type="text"
                                           class="h-12 w-full border-b-2 border-gray-300 text-gray-900 focus:outline-none focus:border-indigo-600 input-focus-effect px-2"
                                           placeholder="Escribe tu nombre" required>
                                </div>
                                <!-- Apellido -->
                                <div>
                                    <label for="apellido" class="block text-sm font-medium text-gray-700 mb-1">Apellido</label>
                                    <input id="apellido" name="apellido" type="text"
                                           class="h-12 w-full border-b-2 border-gray-300 text-gray-900 focus:outline-none focus:border-indigo-600 input-focus-effect px-2"
                                           placeholder="Escribe tu apellido" required>
                                </div>
                            </div>
                            <!-- Documento -->
                            <div>
                                <label for="documento" class="block text-sm font-medium text-gray-700 mb-1">Documento (10 dígitos)</label>
                                <input id="documento" name="documento" type="text"
                                       class="h-12 w-full border-b-2 border-gray-300 text-gray-900 focus:outline-none focus:border-indigo-600 input-focus-effect px-2"
                                       placeholder="Ej: 1234567890" pattern="\d{10}" maxlength="10" required>
                            </div>
                            <!-- Tipo de Usuario -->
                            <div>
                                <label for="tipo_usuario" class="block text-sm font-medium text-gray-700 mb-1">Rol en la institución</label>
                                <div class="grid grid-cols-3 gap-3">
                                    <div class="relative">
                                        <input class="sr-only peer" type="radio" name="tipo_usuario" id="estudiante" value="estudiante" checked>
                                        <label for="estudiante" class="flex flex-col items-center justify-center p-3 bg-white border border-gray-300 rounded-lg cursor-pointer peer-checked:border-indigo-500 peer-checked:bg-indigo-50 hover:bg-gray-50">
                                            <i class="fas fa-user-graduate text-indigo-600 mb-1"></i>
                                            <span class="text-sm font-medium text-gray-900">Estudiante</span>
                                        </label>
                                    </div>
                                    
                                    <div class="relative">
                                        <input class="sr-only peer" type="radio" name="tipo_usuario" id="docente" value="docente">
                                        <label for="docente" class="flex flex-col items-center justify-center p-3 bg-white border border-gray-300 rounded-lg cursor-pointer peer-checked:border-indigo-500 peer-checked:bg-indigo-50 hover:bg-gray-50">
                                            <i class="fas fa-chalkboard-teacher text-indigo-600 mb-1"></i>
                                            <span class="text-sm font-medium text-gray-900">Docente</span>
                                        </label>
                                    </div>
                                    
                                    <div class="relative">
                                        <input class="sr-only peer" type="radio" name="tipo_usuario" id="administrador" value="administrador">
                                        <label for="administrador" class="flex flex-col items-center justify-center p-3 bg-white border border-gray-300 rounded-lg cursor-pointer peer-checked:border-indigo-500 peer-checked:bg-indigo-50 hover:bg-gray-50">
                                            <i class="fas fa-user-shield text-indigo-600 mb-1"></i>
                                            <span class="text-sm font-medium text-gray-900">Administrador</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Correo electrónico</label>
                                <input id="email" name="email" type="email"
                                       class="h-12 w-full border-b-2 border-gray-300 text-gray-900 focus:outline-none focus:border-indigo-600 input-focus-effect px-2"
                                       placeholder="usuario@correo.com" required>
                            </div>
                            <!-- Contraseña -->
                            <div>
                                <label for="contrasena" class="block text-sm font-medium text-gray-700 mb-1">Contraseña</label>
                                <div class="relative">
                                    <input id="contrasena" name="contrasena"
                                           :type="showPassword ? 'text' : 'password'"
                                           class="h-12 w-full border-b-2 border-gray-300 text-gray-900 focus:outline-none focus:border-indigo-600 input-focus-effect px-2 pr-10"
                                           placeholder="Mínimo 8 caracteres" required>
                                    <button type="button" @click="showPassword = !showPassword"
                                            class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 hover:text-indigo-600 focus:outline-none">
                                        <i :class="showPassword ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                                    </button>
                                </div>
                            </div>
                            <!-- Teléfono -->
                            <div>
                                <label for="telefono" class="block text-sm font-medium text-gray-700 mb-1">Teléfono (opcional)</label>
                                <input id="telefono" name="telefono" type="text"
                                       class="h-12 w-full border-b-2 border-gray-300 text-gray-900 focus:outline-none focus:border-indigo-600 input-focus-effect px-2"
                                       placeholder="Ej: 3001234567">
                            </div>
                            <!-- Dirección -->
                            <div>
                                <label for="direccion" class="block text-sm font-medium text-gray-700 mb-1">Dirección (opcional)</label>
                                <input id="direccion" name="direccion" type="text"
                                       class="h-12 w-full border-b-2 border-gray-300 text-gray-900 focus:outline-none focus:border-indigo-600 input-focus-effect px-2"
                                       placeholder="Ej: Calle 123 #45-67">
                            </div>
                            
                            <!-- Términos y condiciones -->
                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input id="terms" name="terms" type="checkbox" 
                                           class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded" required>
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="terms" class="font-medium text-gray-700">
                                        Acepto los <a href="#" class="text-indigo-600 hover:text-indigo-500">términos y condiciones</a> y la <a href="#" class="text-indigo-600 hover:text-indigo-500">política de privacidad</a>
                                    </label>
                                </div>
                            </div>
                            
                            <!-- Submit Button -->
                            <div>
                                <button type="submit" 
                                        class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-300 transform hover:scale-[1.02]"
                                        :disabled="loading">
                                    <span x-show="!loading">Registrarse</span>
                                    <span x-show="loading" class="flex items-center">
                                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        Procesando...
                                    </span>
                                </button>
                            </div>
                        </form>
                        <!-- Enlace a login debajo del formulario -->
                        <div class="mt-6 text-center">
                            <a href="login.php" class="text-sm text-indigo-600 hover:text-indigo-500 font-medium transition-colors duration-200">
                                ¿Ya tienes cuenta? Inicia sesión aquí
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Footer -->
    <footer class="fixed bottom-0 w-full bg-white py-4 border-t border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <p class="text-center text-xs text-gray-500">
                &copy; 2023 Colegio Excelencia. Todos los derechos reservados.
            </p>
        </div>
    </footer>
</body>
</html>