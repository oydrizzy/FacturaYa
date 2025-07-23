<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}
require_once 'db.php';
$hoy = date('Y-m-d');

$stmt = $conn->prepare("SELECT f.id, f.numero, f.fecha, f.total, c.nombre 
                        FROM facturas f
                        JOIN clientes c ON c.id = f.cliente_id
                        WHERE f.fecha = ?
                        ORDER BY f.id DESC");
$stmt->bind_param("s", $hoy);
$stmt->execute();
$result = $stmt->get_result();

$total_facturas = 0;
$total_dinero = 0;
$facturas = [];

while ($row = $result->fetch_assoc()) {
    $facturas[] = $row;
    $total_facturas++;
    $total_dinero += $row['total'];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte Diario</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">

    <?php include_once 'includes/nav.php'; ?>

    <main class="flex-grow max-w-5xl mx-auto p-6">
        <h1 class="text-3xl font-bold text-green-700 text-center mb-6">📊 Reporte Diario</h1>
        <div class="bg-white p-6 rounded-lg shadow mb-6 text-center">
            <p class="text-lg font-semibold text-gray-700">Fecha: 
                <span class="text-green-700"><?php echo date('d/m/Y'); ?></span>
            </p>
            <p class="text-lg mt-2"><strong>Cantidad de Facturas:</strong> <?php echo $total_facturas; ?></p>
            <p class="text-lg"><strong>Total Recaudado:</strong> RD$<?php echo number_format($total_dinero, 2); ?></p>
        </div>

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="min-w-full text-sm text-left text-gray-600">
                <thead class="bg-green-600 text-white">
                    <tr>
                        <th class="px-4 py-3">Nº Recibo</th>
                        <th class="px-4 py-3">Fecha</th>
                        <th class="px-4 py-3">Cliente</th>
                        <th class="px-4 py-3 text-right">Total (RD$)</th>
                        <th class="px-4 py-3 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($total_facturas > 0): ?>
                        <?php foreach ($facturas as $index => $f): ?>
                            <tr class="<?php echo $index % 2 === 0 ? 'bg-gray-50' : 'bg-white'; ?>">
                                <td class="px-4 py-2"><?php echo $f['numero']; ?></td>
                                <td class="px-4 py-2"><?php echo $f['fecha']; ?></td>
                                <td class="px-4 py-2"><?php echo $f['nombre']; ?></td>
                                <td class="px-4 py-2 text-right">RD$<?php echo number_format($f['total'], 2); ?></td>
                                <td class="px-4 py-2 text-center">
                                    <a href="imprimir.php?id=<?php echo $f['id']; ?>" 
                                       class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm">🖨️ Imprimir</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center px-4 py-3 text-gray-500">No hay facturas registradas hoy.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </main>

    <?php include_once 'includes/footer.php'; ?>

</body>
</html>
