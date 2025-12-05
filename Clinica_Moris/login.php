<?php
require "config/db.php";
session_start();

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    $consulta = $conn->query("SELECT * FROM usuarios WHERE email='$email' LIMIT 1");

    if ($consulta->num_rows > 0) {
        $usuario = $consulta->fetch_assoc();

        if (password_verify($password, $usuario["password"])) {
            $_SESSION["usuario_id"] = $usuario["id"];
            $_SESSION["usuario_nombre"] = $usuario["nombre"];
            $_SESSION["usuario_apellido"] = $usuario["apellido"];

            header("Location: usuarios/index.php");
            exit;
        } else {
            $mensaje = "❌ Contraseña incorrecta.";
        }
    } else {
        $mensaje = "❌ No existe una cuenta con este correo.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión - Clínica Moris</title>

    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="login-page">
    <div class="login-box">

        <img src="assets/img/ClinicaMoris.jpg" class="logo">

        <h2>Iniciar Sesión</h2>

        <?php if ($mensaje): ?>
            <div class="error-box"><?php echo $mensaje; ?></div>
        <?php endif; ?>

        <form method="POST">
            <input type="email" name="email" placeholder="Correo electrónico" required>
            <input type="password" name="password" placeholder="Contraseña" required>

            <button class="btn-primary" type="submit">Ingresar</button>
        </form>

        <p class="register-text">
            ¿No tienes cuenta? <a href="registro.php">Registrarse</a>
        </p>
    </div>
</div>

</body>
</html>
