<?php
session_start();
include 'conexion.php'; // Conectar a la base de datos

// Obtener productos de la base de datos
$sql = "SELECT id, nombre, precio, imagen FROM productos";
$resultado = $conn->query($sql);

if ($resultado->num_rows == 0) {
    echo "<p>No hay productos disponibles.</p>";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tienda Online - Huilén</title>
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background: #fff0f5;
            color: #333;
        }

        header {
            background: #ffb6c1;
            padding: 20px;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        header h1 {
            margin: 0;
            font-size: 2em;
            color: #fff;
        }

        main {
            padding: 20px;
            max-width: 1000px;
            margin: auto;
        }

        .producto {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            background: #fff;
            border-radius: 8px;
            padding: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .producto img {
            margin-right: 15px;
            border-radius: 6px;
        }

        .precio-tabla {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
        }

        .precio-tabla th, .precio-tabla td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: center;
        }

        .precio-tabla th {
            background: #ffb6c1;
            color: white;
        }

        a {
            color: #d63384;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        footer {
            margin-top: 40px;
            text-align: center;
            color: #888;
        }
    </style>
</head>
<body>

    <header>
        <h1>Bienvenidos a tu tienda online</h1>
    </header>

    <main>
        <h3>Seleccione el producto que desee:</h3>

        <?php while ($producto = $resultado->fetch_assoc()): ?>
            <div class="producto">
                <img src="<?= htmlspecialchars($producto['imagen']) ?>" width="100" height="100" alt="<?= htmlspecialchars($producto['nombre']) ?>">
                <?= htmlspecialchars($producto['nombre']) ?>
            </div>
        <?php endwhile; ?>

        <h3>Lista de precios:</h3>
        <table class="precio-tabla">
            <tr>
                <th>PRODUCTOS</th>
                <th>PRECIOS</th>
            </tr>
            <?php
            // Recorremos de nuevo para mostrar los precios en la tabla
            $resultado->data_seek(0); // Volver al primer elemento
            while ($producto = $resultado->fetch_assoc()):
            ?>
                <tr>
                    <td><?= htmlspecialchars($producto['nombre']) ?></td>
                    <td>$<?= number_format($producto['precio'], 2) ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    </main>

    <footer>
        <p>© 2025 Tienda Huilén. Todos los derechos reservados.</p>
    </footer>

</body>
</html>

<?php $conn->close(); ?>
