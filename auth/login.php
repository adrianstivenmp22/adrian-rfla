<?php
session_start();
require_once __DIR__ . '/../db/conexion.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$error = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("
        SELECT 
          id_usuario AS id, 
          nombre, 
          contrasena,
          tipo_usuario
        FROM usuarios 
        WHERE email = :email
    ");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch();

    if ($user) {
        $stored = $user['contrasena'];
        $login_ok = false;

        if (password_verify($password, $stored)) {
            $login_ok = true;
        } elseif ($password === $stored) {
            $login_ok = true;
            $newHash = password_hash($password, PASSWORD_DEFAULT);
            $upd = $pdo->prepare("UPDATE usuarios SET contrasena = :h WHERE id_usuario = :id");
            $upd->execute(['h'=>$newHash, 'id'=>$user['id']]);
        }

        if ($login_ok) {
            $_SESSION['user_id']   = $user['id'];
            $_SESSION['user_name'] = $user['nombre'];
            $_SESSION['user_role'] = $user['tipo_usuario'];
            // Redirección según el tipo de usuario
            if ($user['tipo_usuario'] === 'administrador') {
                header('Location: ../dashboard/dashboard_admin.php');
            } elseif ($user['tipo_usuario'] === 'docente') {
                header('Location: ../dashboard/dashboard_docente.php');
            } else {
                header('Location: ../dashboard/dashboard_estudiante.php');
            }
            exit;
        } else {
            $error = 'Credenciales incorrectas. Por favor, inténtalo de nuevo.';
        }
    } else {
        $error = 'No se encontró una cuenta con ese correo electrónico.';
    }
}
?>
<!DOCTYPE html>
<html lang="es" class="h-full bg-white">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Acceso al Portal Escolar | Colegio San Miguel</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            200: '#bae6fd',
                            300: '#7dd3fc',
                            400: '#38bdf8',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            700: '#0369a1',
                            800: '#075985',
                            900: '#0c4a6e',
                        },
                        secondary: {
                            50: '#f5f3ff',
                            100: '#ede9fe',
                            200: '#ddd6fe',
                            300: '#c4b5fd',
                            400: '#a78bfa',
                            500: '#8b5cf6',
                            600: '#7c3aed',
                            700: '#6d28d9',
                            800: '#5b21b6',
                            900: '#4c1d95',
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                }
            }
        }
    </script>
    <style>
        .bg-auth {
            background-image: url('https://images.unsplash.com/photo-1523050854058-8df90110c9f1?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1470&q=80');
            background-position: center;
            background-size: cover;
        }
        .input-focus-effect:focus {
            box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.3);
        }
        .btn-hover-effect:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px -10px rgba(14, 165, 233, 0.5);
        }
        .btn-hover-effect:active {
            transform: translateY(0);
        }
        .floating {
            animation: floating 6s ease-in-out infinite;
        }
        @keyframes floating {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-15px); }
            100% { transform: translateY(0px); }
        }
    </style>
</head>
<body class="h-full">
    <div class="min-h-full flex">
        <div class="flex-1 flex flex-col justify-center py-12 px-4 sm:px-6 lg:flex-none lg:px-20 xl:px-24">
            <div class="mx-auto w-full max-w-sm lg:w-96">
                <div class="text-center">
                    <img class="h-16 w-auto mx-auto animate__animated animate__fadeIn" src="https://cdn-icons-png.flaticon.com/512/197/197572.png" alt="Logo Colegio">
                    <h2 class="mt-6 text-3xl font-extrabold text-gray-900 animate__animated animate__fadeIn animate__delay-1s">
                        Portal Escolar
                    </h2>
                    <p class="mt-2 text-sm text-gray-600 animate__animated animate__fadeIn animate__delay-1s">
                        Sistema de gestión académica del Colegio San Miguel
                    </p>
                </div>

                <div class="mt-8 animate__animated animate__fadeIn animate__delay-2s">
                    <?php if (!empty($error)): ?>
                        <div class="rounded-md bg-red-50 p-4 mb-4 transition-all duration-300 animate__animated animate__shakeX">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-red-800"><?= htmlspecialchars($error) ?></h3>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="bg-white py-8 px-6 shadow rounded-lg sm:px-10 border border-gray-100">
                        <form class="mb-0 space-y-6" action="" method="POST">
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700">
                                    Correo electrónico
                                </label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                            <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                                        </svg>
                                    </div>
                                    <input id="email" name="email" type="email" autocomplete="email" required 
                                        class="input-focus-effect py-3 pl-10 block w-full border border-gray-300 rounded-md focus:ring-primary-500 focus:border-primary-500 transition duration-150"
                                        placeholder="usuario@colegiosanmiguel.edu">
                                </div>
                            </div>

                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700">
                                    Contraseña
                                </label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                                    </div>
                                    <input id="password" name="password" type="password" autocomplete="current-password" required 
                                        class="input-focus-effect py-3 pl-10 block w-full border border-gray-300 rounded-md focus:ring-primary-500 focus:border-primary-500 transition duration-150"
                                        placeholder="••••••••">
                                </div>
                            </div>

                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <input id="remember-me" name="remember-me" type="checkbox" 
                                        class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                                    <label for="remember-me" class="ml-2 block text-sm text-gray-700">
                                        Recordar mi sesión
                                    </label>
                                </div>

                                <div class="text-sm">
                                    <a href="#" class="font-medium text-primary-600 hover:text-primary-500 transition-colors duration-200">
                                        ¿Olvidaste tu contraseña?
                                    </a>
                                </div>
                            </div>

                            <div>
                                <button type="submit" 
                                    class="btn-hover-effect w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-gradient-to-r from-primary-600 to-secondary-500 hover:from-primary-700 hover:to-secondary-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-all duration-200">
                                    Iniciar sesión
                                </button>
                            </div>
                        </form>

                        <div class="mt-6">
                            <div class="relative">
                                <div class="absolute inset-0 flex items-center">
                                    <div class="w-full border-t border-gray-300"></div>
                                </div>
                                <div class="relative flex justify-center text-sm">
                                    <span class="px-2 bg-white text-gray-500">
                                        ¿Necesitas ayuda?
                                    </span>
                                </div>
                            </div>

                            <div class="mt-6 text-center">
                                <a href="#" class="text-sm font-medium text-primary-600 hover:text-primary-500 transition-colors duration-200">
                                    Contacta al soporte técnico
                                </a>
                            </div>
                            <!-- Enlace a registro -->
                            <div class="mt-4 text-center">
                                <a href="register.php" class="text-sm text-primary-600 hover:text-primary-500 font-medium transition-colors duration-200">
                                    ¿No tienes cuenta? Regístrate aquí
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="hidden lg:block relative w-0 flex-1">
            <div class="absolute inset-0 bg-auth bg-opacity-90">
                <div class="absolute inset-0 bg-gradient-to-t from-primary-900 to-primary-700 mix-blend-multiply opacity-70"></div>
            </div>
            <div class="relative h-full flex flex-col justify-center items-center text-white px-20">
                <div class="floating">
                    <img src="https://cdn-icons-png.flaticon.com/512/3976/3976626.png" alt="Estudiantes" class="h-64 w-auto">
                </div>
                <h3 class="mt-8 text-3xl font-bold text-center">Bienvenido al sistema educativo</h3>
                <p class="mt-4 text-center text-primary-100 max-w-lg">
                    Plataforma integral para la gestión académica, comunicación y seguimiento del proceso educativo de nuestra comunidad.
                </p>
                <div class="mt-12 grid grid-cols-3 gap-8 text-center">
                    <div class="animate__animated animate__fadeInUp animate__delay-1s">
                        <div class="text-4xl font-bold">2,500+</div>
                        <div class="mt-1 text-primary-200">Estudiantes</div>
                    </div>
                    <div class="animate__animated animate__fadeInUp animate__delay-2s">
                        <div class="text-4xl font-bold">150+</div>
                        <div class="mt-1 text-primary-200">Docentes</div>
                    </div>
                    <div class="animate__animated animate__fadeInUp animate__delay-3s">
                        <div class="text-4xl font-bold">25+</div>
                        <div class="mt-1 text-primary-200">Años de experiencia</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="lg:hidden bg-white py-4 border-t border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <p class="text-center text-xs text-gray-500">
                &copy; 2023 Colegio San Miguel. Todos los derechos reservados.
            </p>
        </div>
    </footer>

    <script>
        // Efecto de carga suave
        document.body.classList.add('opacity-0');
        window.addEventListener('DOMContentLoaded', () => {
            document.body.classList.add('transition-opacity', 'duration-500');
            document.body.classList.remove('opacity-0');
        });

        // Validación de formulario
        const form = document.querySelector('form');
        form.addEventListener('submit', (e) => {
            const email = document.getElementById('email');
            const password = document.getElementById('password');
            
            if (!email.value || !password.value) {
                e.preventDefault();
                if (!email.value) {
                    email.classList.add('border-red-500');
                }
                if (!password.value) {
                    password.classList.add('border-red-500');
                }
            }
        });

        // Eliminar clases de error al escribir
        document.getElementById('email').addEventListener('input', function() {
            this.classList.remove('border-red-500');
        });
        document.getElementById('password').addEventListener('input', function() {
            this.classList.remove('border-red-500');
        });
    </script>
</body>
</html>