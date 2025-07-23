<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Sistema de Facturación</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">

        <?php include_once 'includes/nav.php'?>

    <main class="flex-grow flex items-center justify-center">
        <div class="bg-white p-8 rounded-lg shadow-lg w-[90%] max-w-3xl text-center">
            <h1 class="text-3xl font-bold text-green-700 mb-4">Bienvenido al Sistema de Facturación</h1>
            <p class="text-gray-600 mb-6">
                Este sistema ha sido diseñado con el fin de registrar ventas, 
                generar facturas rápidas y obtener reportes diarios de ingresos. 
                ¡Optimiza tu tiempo y mejora la gestión de tu negocio!
            </p>

        </div>
    </main>

    <?php include_once 'includes/footer.php'?>

</body>
</html>
