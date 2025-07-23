<?php
session_start();
require_once 'db.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = trim($_POST['usuario']);
    $clave = $_POST['clave'];

    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE usuario = ?");
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($clave, $user['clave'])) {
            $_SESSION['usuario'] = $usuario;
            header("Location: index.php");
            exit;
        } else {
            $error = "Contraseña incorrecta.";
        }
    } else {
        $error = "Usuario no encontrado.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-sm">
        <h2 class="text-2xl font-bold text-green-600 mb-4 text-center">Iniciar Sesión</h2>
        <?php if ($error): ?>
            <p class="text-red-500 text-center mb-3"><?php echo $error; ?></p>
        <?php endif; ?>
        <form method="POST" class="space-y-3">
            <input type="text" name="usuario" placeholder="Usuario" required class="w-full p-2 border rounded">
            <input type="password" name="clave" placeholder="Contraseña" required class="w-full p-2 border rounded">
            <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white p-2 rounded">Ingresar</button>
        </form>
    </div>
</body>
</html>
