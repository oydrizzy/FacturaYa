
<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}
require_once 'db.php';

$fecha = $_POST['fecha'];
$codigo = $_POST['codigo_cliente'];
$nombre = $_POST['nombre_cliente'];
$comentario = $_POST['comentario'] ?? '';

$articulos = $_POST['articulo'];   
$cantidades = $_POST['cantidad'];  
$precios = $_POST['precio'];       

$conn->begin_transaction();

$stmt = $conn->prepare("SELECT id FROM clientes WHERE codigo = ?");
$stmt->bind_param("s", $codigo);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $cliente_id = $result->fetch_assoc()['id'];
} else {
    $stmt = $conn->prepare("INSERT INTO clientes (codigo, nombre) VALUES (?, ?)");
    $stmt->bind_param("ss", $codigo, $nombre);
    $stmt->execute();
    $cliente_id = $stmt->insert_id;
}

$res = $conn->query("SELECT COUNT(*) AS total FROM facturas");
$count = $res->fetch_assoc()['total'] + 1;
$numero = "REC-" . str_pad($count, 3, "0", STR_PAD_LEFT);

$totalFactura = 0;
for ($i = 0; $i < count($articulos); $i++) {
    $totalFactura += (float)$cantidades[$i] * (float)$precios[$i];
}

$stmt = $conn->prepare("INSERT INTO facturas (numero, fecha, cliente_id, total, comentario) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("ssids", $numero, $fecha, $cliente_id, $totalFactura, $comentario);
$stmt->execute();
$factura_id = $stmt->insert_id;

for ($i = 0; $i < count($articulos); $i++) {
    $nombre_art = trim($articulos[$i]);
    $cant = (int)$cantidades[$i];
    $precio = (float)$precios[$i];
    $total = $cant * $precio;

    $stmt = $conn->prepare("INSERT INTO detalles (factura_id, articulo, cantidad, precio, total) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("isidd", $factura_id, $nombre_art, $cant, $precio, $total);
    $stmt->execute();
}

$conn->commit();
header("Location: imprimir.php?id=" . $factura_id);
exit;
?>
