<?php
session_start();
include 'conexion.php';

if (!isset($_SESSION['usuario']) || $_SESSION['admin'] != 1) {
    echo "<h2>Acceso denegado.</h2>";
    exit;
}

$mensaje = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $precio = $_POST['precio'];
    $imagen = $_POST['imagen']; // Puedes hacer upload si querés, esto es solo texto URL

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
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Producto</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #fff0f5;
            padding: 20px;
        }

        h2 {
            color: #d63384;
        }

        form {
            background-color: #ffe6f0;
            padding: 20px;
            border-radius: 10px;
            width: 300px;
        }

        input[type="text"],
        input[type="number"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }

        input[type="submit"] {
            background-color: #28a745;
            color: white;
            padding: 8px 15px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        .mensaje {
            margin-top: 15px;
            background-color: #d4edda;
            border: 1px solid #28a745;
            padding: 10px;
            color: #155724;
            border-radius: 6px;
        }

        a {
            display: inline-block;
            margin-top: 10px;
            color: #007bff;
            text-decoration: none;
        }
    </style>
</head>
<body>

<h2>Agregar Nuevo Producto</h2>

<form method="POST">
    <input type="text" name="nombre" placeholder="Nombre del producto" required>
    <input type="number" step="0.01" name="precio" placeholder="Precio" required>
    <input type="text" name="imagen" placeholder="URL de imagen (opcional)">
    <input type="submit" value="Agregar">
</form>

<?php if ($mensaje): ?>
    <div class="mensaje"><?= $mensaje ?></div>
<?php endif; ?>

<a href="panel_admin.php">Volver al panel</a>

</body>
</html>
