<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar Curso</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        /* ======== RESET ======== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Poppins", sans-serif;
        }

        /* ======== BODY ======== */
        body {
            background: linear-gradient(135deg, #d8f3ff, #f0faff);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px;
        }

        /* ======== CONTENEDOR ======== */
        .container {
            background: #ffffffee;
            backdrop-filter: blur(10px);
            padding: 40px 50px;
            border-radius: 25px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            width: 100%;
            max-width: 550px;
            transition: all 0.4s ease;
        }

        .container:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.2);
        }

        /* ======== TÍTULO ======== */
        h2 {
            text-align: center;
            color: #0077b6;
            font-size: 28px;
            margin-bottom: 25px;
            text-shadow: 0 1px 2px rgba(0,0,0,0.1);
        }

        /* ======== INPUT GROUP ======== */
        .input-group {
            position: relative;
            margin-bottom: 25px;
        }

        .input-group input,
        .input-group select {
            width: 100%;
            padding: 14px;
            border: 1px solid #bcd6e3;
            border-radius: 10px;
            background: #f9fdff;
            font-size: 15px;
            color: #333;
            outline: none;
        }

        .input-group label {
            position: absolute;
            top: 12px;
            left: 15px;
            color: #777;
            font-size: 14px;
            background: #f9fdff;
            padding: 0 5px;
            transition: all 0.2s ease;
            pointer-events: none;
        }

        /* ======== LABEL FLOTANTE ======== */
        .input-group input:focus + label,
        .input-group input:not(:placeholder-shown) + label,
        .input-group select:focus + label,
        .input-group select:not([value=""]) + label {
            top: -8px;
            left: 12px;
            font-size: 12px;
            color: #0077b6;
            background: #ffffff;
        }

        /* ======== BOTÓN ======== */
        button {
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 10px;
            background: linear-gradient(135deg, #0096c7, #00b4d8);
            color: white;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.4s ease;
            box-shadow: 0 4px 15px rgba(0, 180, 216, 0.3);
        }

        button:hover {
            background: linear-gradient(135deg, #00b4d8, #48cae4);
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(0, 180, 216, 0.4);
        }

        /* ======== MENSAJES ======== */
        .success, .error {
            text-align: center;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-weight: 500;
        }

        .success {
            background: #d8fcd8;
            color: #2b9348;
        }

        .error {
            background: #ffe0e0;
            color: #c1121f;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php
        include 'conexion.php';

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $id = $_POST["id"];
            $nuevo_nombre = $_POST["nombre"];
            $nuevo_horario = $_POST["horario"];
            $nueva_fecha = $_POST["fecha"];

            $sql = "UPDATE cursos SET nombre = ?, horario = ?, fecha_inicio = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssi", $nuevo_nombre, $nuevo_horario, $nueva_fecha, $id);

            if ($stmt->execute()) {
                echo '<div class="success">✅ Curso modificado correctamente.</div>';
            } else {
                echo '<div class="error">❌ Error: ' . $conn->error . '</div>';
            }

            $stmt->close();
            $conn->close();
        }
        ?>

        <!-- Formulario -->
        <form method="post">
            <h2>Modificar Curso</h2>
            
            <div class="input-group">
                <input type="number" name="id" id="id" placeholder=" " required>
                <label for="id">ID del curso:</label>
            </div>
            
            <div class="input-group">
                <input type="text" name="nombre" id="nombre" placeholder=" ">
                <label for="nombre">Nuevo nombre:</label>
            </div>
            
            <div class="input-group">
                <input type="text" name="horario" id="horario" placeholder=" ">
                <label for="horario">Nuevo horario:</label>
            </div>
            
            <div class="input-group">
                <input type="date" name="fecha" id="fecha" placeholder=" ">
                <label for="fecha">Nueva fecha:</label>
            </div>
            
            <button type="submit">Modificar</button>
        </form>
    </div>
</body>
</html>