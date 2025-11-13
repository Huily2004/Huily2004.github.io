<?php
session_start();

// Verificar si el usuario es admin
if (!isset($_SESSION['admin']) || $_SESSION['admin'] != 1) {
    header("Location: inicio_de_sesion.html");
    exit();
}

// Conexión a la base de datos
$conexion = new mysqli("localhost", "root", "", "huilen");
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Procesar actualizaciones o eliminaciones
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['producto_id'];

    if (isset($_POST['eliminar']) && $_POST['eliminar'] == '1') {
        $sql = "DELETE FROM productos WHERE id = $id";
        $conexion->query($sql);
        $mensaje = "Producto eliminado correctamente.";
    } else {
        $nuevo_nombre = $conexion->real_escape_string($_POST['nuevo_nombre']);
        $nuevo_precio = $conexion->real_escape_string($_POST['nuevo_precio']);
        $sql = "UPDATE productos SET nombre='$nuevo_nombre', precio='$nuevo_precio' WHERE id=$id";
        $conexion->query($sql);
        $mensaje = "Producto actualizado correctamente.";
    }
}

// Obtener todos los productos
$sql = "SELECT * FROM productos";
$resultado = $conexion->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Administración</title>
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
            color: white;
            font-size: 1.8em;
        }

        main {
            max-width: 900px;
            margin: auto;
            padding: 20px;
        }

        .mensaje {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
        }

        .producto {
            background: #ffe4ec;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .producto img {
            max-width: 150px;
            display: block;
            margin: 10px 0;
            border-radius: 5px;
        }

        label {
            font-weight: bold;
        }

        input[type="text"],
        input[type="number"] {
            width: 100%;
            padding: 8px;
            margin: 6px 0 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        .acciones {
            display: flex;
            gap: 10px;
        }

        .acciones input[type="submit"],
        .acciones button {
            padding: 10px 15px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        .acciones input[type="submit"] {
            background-color: #ff69b4;
            color: white;
        }

        .acciones button {
            background-color: #dc3545;
            color: white;
        }

        .boton-agregar {
            background-color: #28a745;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 6px;
            margin-bottom: 20px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
    </style>
</head>
<body>
    <header>
        Panel de Administración
    </header>

    <main>
        <a href="agregar_producto.php" class="boton-agregar">+ Agregar nuevo producto</a>

        <?php if (isset($mensaje)): ?>
            <div class="mensaje"><?= $mensaje ?></div>
        <?php endif; ?>

        <?php if ($resultado->num_rows === 0): ?>
            <div class="mensaje">No hay productos registrados en este momento.</div>
        <?php endif; ?>

        <?php while ($producto = $resultado->fetch_assoc()): ?>
            <div class="producto">
                <strong><?= htmlspecialchars($producto['nombre']) ?></strong><br>
                Precio: $<?= number_format($producto['precio'], 2) ?><br>
                <?php if ($producto['imagen']): ?>
                    <img src="<?= htmlspecialchars($producto['imagen']) ?>" alt="Imagen del producto">
                <?php endif; ?>

                <form method="POST">
                    <input type="hidden" name="producto_id" value="<?= $producto['id'] ?>">

                    <label>Nuevo nombre:</label>
                    <input type="text" name="nuevo_nombre" value="<?= htmlspecialchars($producto['nombre']) ?>" required>

                    <label>Nuevo precio:</label>
                    <input type="number" name="nuevo_precio" value="<?= $producto['precio'] ?>" step="0.01" required>

                    <div class="acciones">
                        <input type="submit" value="Actualizar">
                        <input type="hidden" name="eliminar" value="0">
                        <button type="submit" name="eliminar" value="1" onclick="return confirm('¿Estás seguro de eliminar este producto?');">Eliminar producto</button>
                    </div>
                </form>
            </div>
        <?php endwhile; ?>
    </main>
</body>
</html>
