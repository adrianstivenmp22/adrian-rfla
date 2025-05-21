<?php
session_start();
if (empty($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Colegio - Dashboard</title>
    
    <!-- Frameworks y librerías -->
    <link href="https://cdn.jsdelivr.net/npm/daisyui@3.9.4/dist/full.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <!-- Estilos personalizados -->
    <style>
        :root {
            --primary: #4f46e5;
            --secondary: #10b981;
            --accent: #f59e0b;
            --dark: #1e293b;
            --light: #f8fafc;
        }
        
        .sidebar-item:hover {
            transform: translateX(5px);
            transition: transform 0.3s ease;
        }
        
        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }
        
        .gradient-bg {
            background: linear-gradient(135deg, var(--primary) 0%, #7c3aed 100%);
        }
    </style>
</head>
<body class="bg-gray-50 font-sans">
    <div class="drawer lg:drawer-open">
        <input id="my-drawer" type="checkbox" class="drawer-toggle" />
        
        <!-- Sidebar -->
        <div class="drawer-side z-20">
            <label for="my-drawer" class="drawer-overlay"></label>
            <div class="menu p-4 w-80 h-full bg-white shadow-xl">
                <!-- Logo y título -->
                <div class="flex items-center mb-10 p-4 gradient-bg rounded-lg text-white">
                    <i class="fas fa-graduation-cap text-3xl mr-3"></i>
                    <h1 class="text-2xl font-bold">Colegio Moderno</h1>
                </div>
                
                <!-- Menú de navegación -->
                <ul class="space-y-2">
                    <li>
                        <a href="inicio.php" class="sidebar-item flex items-center py-3 px-4 rounded-lg text-gray-700 hover:bg-indigo-50 hover:text-indigo-600">
                            <i class="fas fa-home mr-3 text-lg"></i>
                            <span class="font-medium">Inicio</span>
                        </a>
                    </li>
                    <li>
                        <a href="estudiantes.php" class="sidebar-item flex items-center py-3 px-4 rounded-lg text-gray-700 hover:bg-indigo-50 hover:text-indigo-600">
                            <i class="fas fa-users mr-3 text-lg"></i>
                            <span class="font-medium">Estudiantes</span>
                            <span class="badge badge-primary ml-auto">24</span>
                        </a>
                    </li>
                    <li>
                        <a href="profesores.php" class="sidebar-item flex items-center py-3 px-4 rounded-lg text-gray-700 hover:bg-indigo-50 hover:text-indigo-600">
                            <i class="fas fa-chalkboard-teacher mr-3 text-lg"></i>
                            <span class="font-medium">Profesores</span>
                        </a>
                    </li>
                    <li>
                        <a href="cursos.php" class="sidebar-item flex items-center py-3 px-4 rounded-lg text-gray-700 hover:bg-indigo-50 hover:text-indigo-600">
                            <i class="fas fa-book mr-3 text-lg"></i>
                            <span class="font-medium">Cursos</span>
                        </a>
                    </li>
                    <li>
                        <a href="calificaciones.php" class="sidebar-item flex items-center py-3 px-4 rounded-lg text-gray-700 hover:bg-indigo-50 hover:text-indigo-600">
                            <i class="fas fa-clipboard-list mr-3 text-lg"></i>
                            <span class="font-medium">Calificaciones</span>
                        </a>
                    </li>
                    <li>
                        <a href="asistencias.php" class="sidebar-item flex items-center py-3 px-4 rounded-lg text-gray-700 hover:bg-indigo-50 hover:text-indigo-600">
                            <i class="fas fa-calendar-check mr-3 text-lg"></i>
                            <span class="font-medium">Asistencias</span>
                        </a>
                    </li>
                    <li>
                        <a href="configuracion.php" class="sidebar-item flex items-center py-3 px-4 rounded-lg text-gray-700 hover:bg-indigo-50 hover:text-indigo-600">
                            <i class="fas fa-cog mr-3 text-lg"></i>
                            <span class="font-medium">Configuración</span>
                        </a>
                    </li>
                </ul>
                
                <!-- Pie de sidebar -->
                <div class="mt-auto pt-4 border-t border-gray-200">
                    <div class="flex items-center p-3 rounded-lg bg-gray-50">
                        <div class="avatar online">
                            <div class="w-12 rounded-full">
                                <img src="https://ui-avatars.com/api/?name=<?= urlencode($_SESSION['user_name']) ?>&background=4f46e5&color=fff" />
                            </div>
                        </div>
                        <div class="ml-3">
                            <p class="font-semibold"><?= htmlspecialchars($_SESSION['user_name']) ?></p>
                            <p class="text-sm text-gray-500">Administrador</p>
                        </div>
                        <a href="../auth/logout.php" class="ml-auto btn btn-ghost btn-sm">
                            <i class="fas fa-sign-out-alt"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Contenido principal -->
        <div class="drawer-content flex flex-col">
            <!-- Barra superior -->
            <header class="sticky top-0 z-10 bg-white shadow-sm">
                <div class="navbar px-6 py-3">
                    <div class="flex-none lg:hidden">
                        <label for="my-drawer" class="btn btn-square btn-ghost">
                            <i class="fas fa-bars text-xl"></i>
                        </label>
                    </div>
                    <div class="flex-1">
                        <h2 class="text-xl font-semibold text-gray-800">Panel de Control</h2>
                    </div>
                    <div class="flex-none gap-4">
                        <div class="dropdown dropdown-end">
                            <label tabindex="0" class="btn btn-ghost btn-circle">
                                <div class="indicator">
                                    <i class="fas fa-bell text-xl"></i>
                                    <span class="badge badge-sm indicator-item badge-primary">3</span>
                                </div>
                            </label>
                            <div tabindex="0" class="mt-3 z-[1] card card-compact dropdown-content w-72 bg-base-100 shadow">
                                <div class="card-body">
                                    <span class="font-bold text-lg">3 Notificaciones</span>
                                    <div class="divider my-1"></div>
                                    <a href="#" class="flex items-start py-2 hover:bg-gray-50 rounded px-2">
                                        <div class="bg-indigo-100 p-2 rounded-full mr-3">
                                            <i class="fas fa-user-graduate text-indigo-600"></i>
                                        </div>
                                        <div>
                                            <p class="font-medium">Nuevo estudiante</p>
                                            <p class="text-sm text-gray-500">Juan Pérez se ha matriculado</p>
                                        </div>
                                    </a>
                                    <a href="#" class="flex items-start py-2 hover:bg-gray-50 rounded px-2">
                                        <div class="bg-amber-100 p-2 rounded-full mr-3">
                                            <i class="fas fa-calendar-day text-amber-600"></i>
                                        </div>
                                        <div>
                                            <p class="font-medium">Evento próximo</p>
                                            <p class="text-sm text-gray-500">Reunión de padres en 2 días</p>
                                        </div>
                                    </a>
                                    <a href="#" class="flex items-start py-2 hover:bg-gray-50 rounded px-2">
                                        <div class="bg-green-100 p-2 rounded-full mr-3">
                                            <i class="fas fa-check-circle text-green-600"></i>
                                        </div>
                                        <div>
                                            <p class="font-medium">Tarea completada</p>
                                            <p class="text-sm text-gray-500">Matemáticas 10° entregada</p>
                                        </div>
                                    </a>
                                    <div class="card-actions mt-2">
                                        <a href="#" class="link link-primary text-sm">Ver todas</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="dropdown dropdown-end">
                            <label tabindex="0" class="btn btn-ghost btn-circle avatar">
                                <div class="w-10 rounded-full">
                                    <img src="https://ui-avatars.com/api/?name=<?= urlencode($_SESSION['user_name']) ?>&background=4f46e5&color=fff" />
                                </div>
                            </label>
                            <ul tabindex="0" class="menu menu-sm dropdown-content mt-3 z-[1] p-2 shadow bg-base-100 rounded-box w-52">
                                <li>
                                    <a href="perfil.php" class="justify-between">
                                        Perfil
                                        <span class="badge">Nuevo</span>
                                    </a>
                                </li>
                                <li><a>Configuración</a></li>
                                <li><a href="../auth/logout.php">Cerrar sesión</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </header>
            
            <!-- Contenido del dashboard -->
            <main class="flex-1 p-6 bg-gray-50">
                <div class="flex flex-col space-y-6">
                    <!-- Bienvenida -->
                    <div data-aos="fade-up" class="gradient-bg text-white rounded-2xl p-6 shadow-lg">
                        <h1 class="text-3xl font-bold">Bienvenido, <?= htmlspecialchars($_SESSION['user_name']) ?></h1>
                        <p class="mt-2 opacity-90">Administra el sistema escolar de manera eficiente</p>
                        <div class="mt-4 flex flex-wrap gap-3">
                            <div class="badge badge-outline bg-white/20">Hoy: <?= date('d/m/Y') ?></div>
                            <div class="badge badge-outline bg-white/20">24 Estudiantes</div>
                            <div class="badge badge-outline bg-white/20">8 Profesores</div>
                        </div>
                    </div>
                    
                    <!-- Estadísticas rápidas -->
                    <div data-aos="fade-up" data-aos-delay="100" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div class="dashboard-card bg-white p-6 rounded-xl shadow-sm border-l-4 border-indigo-500">
                            <div class="flex items-center">
                                <div class="bg-indigo-100 p-3 rounded-full mr-4">
                                    <i class="fas fa-users text-indigo-600 text-xl"></i>
                                </div>
                                <div>
                                    <p class="text-gray-500">Estudiantes</p>
                                    <p class="text-2xl font-bold">24</p>
                                </div>
                            </div>
                            <div class="mt-4">
                                <div class="flex items-center text-sm text-green-600">
                                    <i class="fas fa-arrow-up mr-1"></i>
                                    <span>5% desde ayer</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="dashboard-card bg-white p-6 rounded-xl shadow-sm border-l-4 border-blue-500">
                            <div class="flex items-center">
                                <div class="bg-blue-100 p-3 rounded-full mr-4">
                                    <i class="fas fa-chalkboard-teacher text-blue-600 text-xl"></i>
                                </div>
                                <div>
                                    <p class="text-gray-500">Profesores</p>
                                    <p class="text-2xl font-bold">8</p>
                                </div>
                            </div>
                            <div class="mt-4">
                                <div class="flex items-center text-sm text-green-600">
                                    <i class="fas fa-arrow-up mr-1"></i>
                                    <span>2% desde ayer</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="dashboard-card bg-white p-6 rounded-xl shadow-sm border-l-4 border-green-500">
                            <div class="flex items-center">
                                <div class="bg-green-100 p-3 rounded-full mr-4">
                                    <i class="fas fa-book text-green-600 text-xl"></i>
                                </div>
                                <div>
                                    <p class="text-gray-500">Cursos</p>
                                    <p class="text-2xl font-bold">12</p>
                                </div>
                            </div>
                            <div class="mt-4">
                                <div class="flex items-center text-sm text-gray-500">
                                    <span>Sin cambios</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="dashboard-card bg-white p-6 rounded-xl shadow-sm border-l-4 border-amber-500">
                            <div class="flex items-center">
                                <div class="bg-amber-100 p-3 rounded-full mr-4">
                                    <i class="fas fa-calendar-check text-amber-600 text-xl"></i>
                                </div>
                                <div>
                                    <p class="text-gray-500">Asistencia hoy</p>
                                    <p class="text-2xl font-bold">92%</p>
                                </div>
                            </div>
                            <div class="mt-4">
                                <div class="flex items-center text-sm text-red-600">
                                    <i class="fas fa-arrow-down mr-1"></i>
                                    <span>3% desde ayer</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Gráficos y tablas -->
                    <div data-aos="fade-up" data-aos-delay="200" class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Gráfico de asistencia -->
                        <div class="bg-white p-6 rounded-xl shadow-sm">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="font-semibold text-lg">Asistencia mensual</h3>
                                <select class="select select-sm select-bordered">
                                    <option>Enero 2024</option>
                                    <option>Febrero 2024</option>
                                    <option selected>Marzo 2024</option>
                                </select>
                            </div>
                            <div class="h-64">
                                <!-- Espacio para gráfico (usar Chart.js o similar) -->
                                <div class="flex items-center justify-center h-full bg-gray-50 rounded-lg">
                                    <p class="text-gray-400">Gráfico de asistencia</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Últimas calificaciones -->
                        <div class="bg-white p-6 rounded-xl shadow-sm">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="font-semibold text-lg">Últimas calificaciones</h3>
                                <a href="#" class="link link-primary text-sm">Ver todas</a>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Estudiante</th>
                                            <th>Curso</th>
                                            <th>Nota</th>
                                            <th>Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <div class="flex items-center">
                                                    <div class="avatar">
                                                        <div class="w-8 rounded-full">
                                                            <img src="https://ui-avatars.com/api/?name=Maria+Garcia&background=random" />
                                                        </div>
                                                    </div>
                                                    <span class="ml-2">María García</span>
                                                </div>
                                            </td>
                                            <td>Matemáticas</td>
                                            <td>9.5</td>
                                            <td><span class="badge badge-success">Excelente</span></td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="flex items-center">
                                                    <div class="avatar">
                                                        <div class="w-8 rounded-full">
                                                            <img src="https://ui-avatars.com/api/?name=Carlos+Lopez&background=random" />
                                                        </div>
                                                    </div>
                                                    <span class="ml-2">Carlos López</span>
                                                </div>
                                            </td>
                                            <td>Ciencias</td>
                                            <td>7.8</td>
                                            <td><span class="badge badge-info">Bueno</span></td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="flex items-center">
                                                    <div class="avatar">
                                                        <div class="w-8 rounded-full">
                                                            <img src="https://ui-avatars.com/api/?name=Ana+Martinez&background=random" />
                                                        </div>
                                                    </div>
                                                    <span class="ml-2">Ana Martínez</span>
                                                </div>
                                            </td>
                                            <td>Historia</td>
                                            <td>6.2</td>
                                            <td><span class="badge badge-warning">Regular</span></td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="flex items-center">
                                                    <div class="avatar">
                                                        <div class="w-8 rounded-full">
                                                            <img src="https://ui-avatars.com/api/?name=Pedro+Sanchez&background=random" />
                                                        </div>
                                                    </div>
                                                    <span class="ml-2">Pedro Sánchez</span>
                                                </div>
                                            </td>
                                            <td>Literatura</td>
                                            <td>5.0</td>
                                            <td><span class="badge badge-error">Insuficiente</span></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Eventos próximos -->
                    <div data-aos="fade-up" data-aos-delay="300" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <div class="lg:col-span-2 bg-white p-6 rounded-xl shadow-sm">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="font-semibold text-lg">Eventos próximos</h3>
                                <a href="#" class="link link-primary text-sm">Ver calendario</a>
                            </div>
                            <div class="space-y-4">
                                <div class="flex items-start p-3 hover:bg-gray-50 rounded-lg transition">
                                    <div class="bg-indigo-100 text-indigo-600 p-2 rounded-lg mr-4">
                                        <i class="fas fa-calendar-day text-lg"></i>
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="font-medium">Reunión de padres</h4>
                                        <p class="text-sm text-gray-500">15 Marzo, 2024 - 15:00</p>
                                        <p class="mt-1 text-gray-600">Reunión general para discutir el progreso académico.</p>
                                    </div>
                                    <button class="btn btn-sm btn-ghost">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                </div>
                                <div class="flex items-start p-3 hover:bg-gray-50 rounded-lg transition">
                                    <div class="bg-green-100 text-green-600 p-2 rounded-lg mr-4">
                                        <i class="fas fa-running text-lg"></i>
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="font-medium">Día deportivo</h4>
                                        <p class="text-sm text-gray-500">20 Marzo, 2024 - Todo el día</p>
                                        <p class="mt-1 text-gray-600">Competencias deportivas interclases.</p>
                                    </div>
                                    <button class="btn btn-sm btn-ghost">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                </div>
                                <div class="flex items-start p-3 hover:bg-gray-50 rounded-lg transition">
                                    <div class="bg-amber-100 text-amber-600 p-2 rounded-lg mr-4">
                                        <i class="fas fa-graduation-cap text-lg"></i>
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="font-medium">Entrega de boletines</h4>
                                        <p class="text-sm text-gray-500">25 Marzo, 2024 - 08:00 a 12:00</p>
                                        <p class="mt-1 text-gray-600">Entrega de calificaciones del segundo trimestre.</p>
                                    </div>
                                    <button class="btn btn-sm btn-ghost">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Progreso académico -->
                        <div class="bg-white p-6 rounded-xl shadow-sm">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="font-semibold text-lg">Progreso académico</h3>
                                <select class="select select-sm select-bordered">
                                    <option>1er Trimestre</option>
                                    <option selected>2do Trimestre</option>
                                    <option>3er Trimestre</option>
                                </select>
                            </div>
                            <div class="space-y-4">
                                <div>
                                    <div class="flex justify-between mb-1">
                                        <span class="font-medium">Matemáticas</span>
                                        <span class="text-sm font-medium">85%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                                        <div class="bg-indigo-600 h-2.5 rounded-full" style="width: 85%"></div>
                                    </div>
                                </div>
                                <div>
                                    <div class="flex justify-between mb-1">
                                        <span class="font-medium">Ciencias</span>
                                        <span class="text-sm font-medium">76%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                                        <div class="bg-blue-600 h-2.5 rounded-full" style="width: 76%"></div>
                                    </div>
                                </div>
                                <div>
                                    <div class="flex justify-between mb-1">
                                        <span class="font-medium">Historia</span>
                                        <span class="text-sm font-medium">92%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                                        <div class="bg-green-600 h-2.5 rounded-full" style="width: 92%"></div>
                                    </div>
                                </div>
                                <div>
                                    <div class="flex justify-between mb-1">
                                        <span class="font-medium">Literatura</span>
                                        <span class="text-sm font-medium">68%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                                        <div class="bg-amber-600 h-2.5 rounded-full" style="width: 68%"></div>
                                    </div>
                                </div>
                                <div>
                                    <div class="flex justify-between mb-1">
                                        <span class="font-medium">Inglés</span>
                                        <span class="text-sm font-medium">79%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                                        <div class="bg-purple-600 h-2.5 rounded-full" style="width: 79%"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-6 text-center">
                                <button class="btn btn-primary btn-sm">
                                    <i class="fas fa-chart-line mr-2"></i>
                                    Ver reporte completo
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    
    <!-- Scripts -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        // Inicializar animaciones
        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: true
        });
        
        // Cambiar tema claro/oscuro
        function toggleTheme() {
            const html = document.documentElement;
            const currentTheme = html.getAttribute('data-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            html.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
        }
        
        // Cargar tema guardado
        const savedTheme = localStorage.getItem('theme') || 'light';
        document.documentElement.setAttribute('data-theme', savedTheme);
    </script>
</body>
</html>