<?php 
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}
require_once 'db.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Factura</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">

    <?php include_once 'includes/nav.php'; ?>

    <div class="max-w-3xl mx-auto mt-10 bg-white p-8 rounded-lg shadow flex-grow">
        <h2 class="text-2xl font-bold text-gray-700 text-center mb-6">Crear Factura</h2>
        <form action="guardar_factura.php" method="POST" class="space-y-4">
            <div>
                <label class="font-semibold">Fecha:</label>
                <input type="date" name="fecha" value="<?php echo date('Y-m-d'); ?>" required 
                    class="w-full p-2 border rounded">
            </div>

            <div>
                <label class="font-semibold">Código Cliente:</label>
                <input type="text" name="codigo_cliente" required class="w-full p-2 border rounded">
            </div>

            <div>
                <label class="font-semibold">Nombre Cliente:</label>
                <input type="text" name="nombre_cliente" required class="w-full p-2 border rounded">
            </div>

            <h3 class="text-lg font-bold text-gray-600 mt-6">Artículos</h3>
            <table class="w-full text-sm text-center border" id="tabla-articulos">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="p-2">Artículo</th>
                        <th class="p-2">Cantidad</th>
                        <th class="p-2">Precio (RD$)</th>
                        <th class="p-2">Quitar</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="border-b">
                        <td><input type="text" name="articulo[]" placeholder="Nombre" class="w-full p-2 border rounded" required></td>
                        <td><input type="number" name="cantidad[]" placeholder="Cant." min="1" value="1" class="w-full p-2 border rounded" required></td>
                        <td><input type="number" name="precio[]" placeholder="Precio" step="0.01" min="0" class="w-full p-2 border rounded" required></td>
                        <td><button type="button" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded btn-remove">X</button></td>
                    </tr>
                </tbody>
            </table>
            <button type="button" id="btnAdd" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">+ Agregar Artículo</button>

            <div>
                <label class="font-semibold">Comentario:</label>
                <input type="text" name="comentario" class="w-full p-2 border rounded">
            </div>

            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded text-lg w-full">Guardar e Imprimir</button>
        </form>
    </div>

    <?php include_once 'includes/footer.php'; ?>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const btnAdd = document.getElementById("btnAdd");
            const tablaArticulos = document.getElementById("tabla-articulos").getElementsByTagName("tbody")[0];

            btnAdd.addEventListener("click", function() {
                const row = document.createElement("tr");
                row.classList.add("border-b");
                row.innerHTML = `
                    <td><input type="text" name="articulo[]" placeholder="Nombre" class="w-full p-2 border rounded" required></td>
                    <td><input type="number" name="cantidad[]" placeholder="Cant." min="1" value="1" class="w-full p-2 border rounded" required></td>
                    <td><input type="number" name="precio[]" placeholder="Precio" step="0.01" min="0" class="w-full p-2 border rounded" required></td>
                    <td><button type="button" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded btn-remove">X</button></td>
                `;
                tablaArticulos.appendChild(row);
            });

            tablaArticulos.addEventListener("click", function(e) {
                if (e.target.classList.contains("btn-remove")) {
                    e.target.closest("tr").remove();
                }
            });
        });
    </script>
</body>
</html>
