<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "facturaya";

$conn = new mysqli($host, $user, $pass);

if ($conn->query("CREATE DATABASE IF NOT EXISTS $dbname") === TRUE) {
    echo "Base de datos '$dbname' creada o ya existe.<br>";
} else {
    die("Error creando base de datos: " . $conn->error);
}

$conn->select_db($dbname);

$tables = [
    "CREATE TABLE IF NOT EXISTS usuarios (
        id INT AUTO_INCREMENT PRIMARY KEY,
        usuario VARCHAR(50) UNIQUE NOT NULL,
        clave VARCHAR(255) NOT NULL,
        creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )",
    "CREATE TABLE IF NOT EXISTS clientes (
        id INT AUTO_INCREMENT PRIMARY KEY,
        codigo VARCHAR(10) UNIQUE NOT NULL,
        nombre VARCHAR(100) NOT NULL
    )",
    "CREATE TABLE IF NOT EXISTS facturas (
        id INT AUTO_INCREMENT PRIMARY KEY,
        numero VARCHAR(20) UNIQUE NOT NULL,
        fecha DATE NOT NULL,
        cliente_id INT NOT NULL,
        total DECIMAL(10,2) NOT NULL,
        comentario VARCHAR(255),
        FOREIGN KEY (cliente_id) REFERENCES clientes(id)
    )",
    "CREATE TABLE IF NOT EXISTS detalles (
        id INT AUTO_INCREMENT PRIMARY KEY,
        factura_id INT NOT NULL,
        articulo VARCHAR(100) NOT NULL,
        cantidad INT NOT NULL,
        precio DECIMAL(10,2) NOT NULL,
        total DECIMAL(10,2) NOT NULL,
        FOREIGN KEY (factura_id) REFERENCES facturas(id)
    )"
];

foreach ($tables as $sql) {
    if ($conn->query($sql) === TRUE) {
        echo "Tabla creada correctamente.<br>";
    } else {
        echo "Error creando tabla: " . $conn->error . "<br>";
    }
}

$usuario = "demo";
$clave = password_hash("tareafacil25", PASSWORD_BCRYPT);

$check = $conn->query("SELECT * FROM usuarios WHERE usuario='demo'");
if ($check->num_rows == 0) {
    $stmt = $conn->prepare("INSERT INTO usuarios (usuario, clave) VALUES (?, ?)");
    $stmt->bind_param("ss", $usuario, $clave);
    $stmt->execute();
    echo "Usuario 'demo' creado con clave 'tareafacil25'.<br>";
} else {
    echo "Usuario 'demo' ya existe.<br>";
}

echo "<hr>Instalación completada. <a href='login.php'>Ir al login</a>";
?>
