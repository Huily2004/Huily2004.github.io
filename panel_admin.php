<?php
session_start();
include 'conexion.php';

if (!isset($_SESSION['usuario']) || $_SESSION['admin'] != 1) {
    echo "<h2>Acceso denegado. Solo para administradores.</h2>";
    exit;
}

// Manejo de mensajes
$mensaje = "";

// Actualizar o eliminar
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['producto_id'])) {
        $producto_id = $_POST['producto_id'];

        if (isset($_POST['accion']) && $_POST['accion'] == 'editar') {
            $nuevo_nombre = $_POST['nuevo_nombre'] ?? '';
            $nuevo_precio = $_POST['nuevo_precio'] ?? '';

            if ($nuevo_nombre !== '') {
                $sql = "UPDATE productos SET nombre = ? WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("si", $nuevo_nombre, $producto_id);
                $stmt->execute();
                $stmt->close();
                $mensaje .= "Nombre actualizado. ";
            }

            if ($nuevo_precio !== '') {
                $sql = "UPDATE productos SET precio = ? WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("di", $nuevo_precio, $producto_id);
                $stmt->execute();
                $stmt->close();
                $mensaje .= "Precio actualizado.";
            }
        }

        if (isset($_POST['accion']) && $_POST['accion'] == 'eliminar') {
            $sql = "DELETE FROM productos WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $producto_id);
            $stmt->execute();
            $stmt->close();
            $mensaje = "Producto eliminado correctamente.";
        }

        // Redirigir después de realizar la acción
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit();
    }

    // Agregar producto
    if (isset($_POST['agregar_producto'])) {
        $nombre = $_POST['nombre'];
        $precio = $_POST['precio'];
        $imagen = $_POST['imagen'] ?? '';

        if (!empty($nombre) && !empty($precio)) {
            $sql = "INSERT INTO productos (nombre, precio, imagen) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sds", $nombre, $precio, $imagen);
            $stmt->execute();
            $stmt->close();
            $mensaje = "Producto agregado correctamente.";
        } else {
            $mensaje = "Faltan campos obligatorios.";
        }

        // Redirigir después de agregar el producto
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit();
    }
}

$resultado = $conn->query("SELECT id, nombre, precio, imagen FROM productos");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Admin - Productos</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #fff0f5;
            padding: 20px;
        }

        h2 {
            color: #d63384;
        }

        .producto {
            background-color: #ffe6f0;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 10px;
            border: 1px solid #ffa3c2;
        }

        .formulario {
            margin-top: 10px;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        input[type="text"],
        input[type="number"] {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 6px;
            width: 100%;
        }

        .acciones {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        button {
            padding: 8px 15px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        .editar {
            background-color: #007bff;
            color: white;
        }

        .eliminar {
            background-color: #dc3545;
            color: white;
        }

        .agregar {
            background-color: #28a745;
            color: white;
            margin-bottom: 20px;
        }

        .mensaje {
            background-color: #d4edda;
            border: 1px solid #28a745;
            padding: 10px;
            color: #155724;
            border-radius: 6px;
            margin-bottom: 15px;
        }

        img {
            max-width: 120px;
            border-radius: 10px;
        }

        .formulario-agregar {
            margin-top: 20px;
            background-color: #ffe6f0;
            padding: 15px;
            border-radius: 10px;
            border: 1px solid #ffa3c2;
        }
    </style>
</head>
<body>

<h2>Panel de Administración</h2>

<!-- Formulario de agregar producto -->
<h3>Agregar Producto</h3>
<form method="POST" class="formulario-agregar">
    <input type="text" name="nombre" placeholder="Nombre del producto" required>
    <input type="number" step="0.01" name="precio" placeholder="Precio" required>
    <input type="text" name="imagen" placeholder="URL de imagen (opcional)">
    <button type="submit" name="agregar_producto">Agregar Producto</button>
</form>

<!-- Mensaje de acción -->
<?php if ($mensaje): ?>
    <div class="mensaje"><?= $mensaje ?></div>
<?php endif; ?>

<!-- Mostrar productos -->
<?php while ($producto = $resultado->fetch_assoc()): ?>
    <div class="producto">
        <strong><?= htmlspecialchars($producto['nombre']) ?></strong><br>
        Precio: $<?= number_format($producto['precio'], 2) ?><br>
        <?php if ($producto['imagen']): ?>
            <img src="<?= htmlspecialchars($producto['imagen']) ?>" alt="Imagen del producto">
        <?php endif; ?>

        <form class="formulario" method="POST">
            <input type="hidden" name="producto_id" value="<?= $producto['id'] ?>">
            <input type="text" name="nuevo_nombre" placeholder="Nuevo nombre">
            <input type="number" step="0.01" name="nuevo_precio" placeholder="Nuevo precio">

            <div class="acciones">
                <button type="submit" name="accion" value="editar" class="editar">Editar producto</button>
                <button type="submit" name="accion" value="eliminar" class="eliminar" onclick="return confirm('¿Eliminar este producto?');">Eliminar producto</button>
            </div>
        </form>
    </div>
<?php endwhile; ?>

</body>
</html>
