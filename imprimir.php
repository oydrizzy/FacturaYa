<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}
require_once 'db.php';
$id = $_GET['id'] ?? 0;
$factura = $conn->query("SELECT f.*, c.nombre FROM facturas f JOIN clientes c ON c.id=f.cliente_id WHERE f.id=$id")->fetch_assoc();
$detalles = $conn->query("SELECT * FROM detalles WHERE factura_id=$id");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Factura <?php echo $factura['numero']; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            button { display: none; }
            body { margin: 0; padding: 0; }
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-6">

    <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
        <h1 class="text-2xl font-bold text-green-700 text-center mb-4">
            FacturaYa
        </h1>
        <h2 class="text-lg font-semibold text-gray-700 text-center">
            Factura: <?php echo $factura['numero']; ?>
        </h2>
        <p class="text-center text-gray-500 text-sm mb-4">
            Fecha: <?php echo date('d/m/Y', strtotime($factura['fecha'])); ?>
        </p>

        <div class="mb-4">
            <p><strong>Cliente:</strong> <?php echo $factura['nombre']; ?></p>
            <?php if (!empty($factura['comentario'])): ?>
                <p><strong>Comentario:</strong> <?php echo $factura['comentario']; ?></p>
            <?php endif; ?>
        </div>

        <table class="w-full text-sm text-left text-gray-700 border-collapse mb-4">
            <thead class="bg-green-600 text-white">
                <tr>
                    <th class="px-2 py-1">Artículo</th>
                    <th class="px-2 py-1 text-center">Cant.</th>
                    <th class="px-2 py-1 text-center">Precio</th>
                    <th class="px-2 py-1 text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $detalles->fetch_assoc()): ?>
                    <tr class="border-b">
                        <td class="px-2 py-1"><?php echo $row['articulo']; ?></td>
                        <td class="px-2 py-1 text-center"><?php echo $row['cantidad']; ?></td>
                        <td class="px-2 py-1 text-center">RD$<?php echo number_format($row['precio'], 2); ?></td>
                        <td class="px-2 py-1 text-right">RD$<?php echo number_format($row['total'], 2); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <div class="text-right text-lg font-bold text-gray-800 mb-4">
            Total a Pagar: RD$<?php echo number_format($factura['total'], 2); ?>
        </div>

        <div class="text-center">
            <button onclick="window.print()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded shadow">
                🖨️ Imprimir Factura
            </button>
        </div>
    </div>

</body>
</html>
