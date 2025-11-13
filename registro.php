<?php
include 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST['usuario'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $dni = $_POST['dni'];
    $mail = $_POST['mail'];
    $pass = $_POST['pass'];
    $pass_confirm = $_POST['pass_confirm'];

    if ($pass !== $pass_confirm) {
        echo "<div class='error'>Las contraseñas no coinciden. <a href='registro.html'>Volver</a></div>";
        exit;
    }

    $check_sql = "SELECT * FROM usuarios WHERE usuario = ? OR dni = ? OR mail = ?";
    $stmt_check = $conn->prepare($check_sql);
    $stmt_check->bind_param("sss", $usuario, $dni, $mail);
    $stmt_check->execute();
    $resultado = $stmt_check->get_result();
    if ($resultado->num_rows > 0) {
        echo "<div class='error'>Usuario, DNI o Email ya registrado. <a href='registro.html'>Intentar de nuevo</a></div>";
        exit;
    }

    $pass_hashed = password_hash($pass, PASSWORD_DEFAULT);
    $es_admin = isset($_POST['admin']) ? 1 : 0;

    $sql = "INSERT INTO usuarios (usuario, nombre, apellido, dni, mail, password, admin) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("ssssssi", $usuario, $nombre, $apellido, $dni, $mail, $pass_hashed, $es_admin);  // Usa $es_admin aquí

        if ($stmt->execute()) {
            echo "<div class='exito'>¡Registro exitoso! <a href='index.html'>Iniciar sesión</a></div>";
        } else {
            echo "<div class='error'>Error al registrar: " . $stmt->error . "</div>";
        }

        $stmt->close();
    } else {
        echo "<div class='error'>Error al preparar la consulta.</div>";
    }

    $conn->close();
}
?>
<style>
body {
    font-family: 'Segoe UI', sans-serif;
    background: #ffeef8;
    text-align: center;
    padding: 40px;
}
.exito {
    background: #d4edda;
    padding: 15px;
    border-radius: 10px;
    color: #155724;
    font-weight: bold;
}
.error {
    background: #f8d7da;
    padding: 15px;
    border-radius: 10px;
    color: #721c24;
    font-weight: bold;
}
a {
    color: #c2185b;
    text-decoration: none;
}
</style>
