<?php
session_start();
include 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST['usuario'];
    $pass = $_POST['pass'];

    // Consultar la base de datos para verificar las credenciales
    $sql = "SELECT id, usuario, password, admin FROM usuarios WHERE usuario = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("s", $usuario);
        $stmt->execute();
        $result = $stmt->get_result();

        // Verificar si se encontró un usuario
        if ($result->num_rows == 1) {
            $user_data = $result->fetch_assoc();
            // Verificar si la contraseña es correcta
            if (password_verify($pass, $user_data['password'])) {
                $_SESSION['loggedin'] = true;
                $_SESSION['id'] = $user_data['id'];
                $_SESSION['usuario'] = $user_data['usuario'];
                $_SESSION['admin'] = $user_data['admin'];

                // Redirigir al panel de administración si es admin
                if ($_SESSION['admin'] == 1) {
                    header("Location: edita_precios.php"); // O tu página de administrador
                    exit;
                } else {
                    // Redirigir al index si no es admin
                    header("Location: index.php");
                    exit;
                }
            } else {
                echo "<div class='error'>Contraseña incorrecta. <a href='login.php'>Intentar de nuevo</a></div>";
            }
        } else {
            echo "<div class='error'>Usuario no encontrado. <a href='login.php'>Intentar de nuevo</a></div>";
        }
        $stmt->close();
    } else {
        echo "<div class='error'>Error al procesar el login. Intente más tarde.</div>";
    }
    $conn->close();
}
?>
