<?php
require "config/db.php";
session_start();

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $rut = $_POST["rut"];
    $nombre = $_POST["nombre"];
    $apellido = $_POST["apellido"];
    $email = $_POST["email"];
    $telefono = $_POST["telefono"];
    $direccion = $_POST["direccion"];
    $password = $_POST["password"];

    // Verificar si email ya existe
    $buscar = $conn->query("SELECT id FROM usuarios WHERE email='$email'");
    if ($buscar->num_rows > 0) {
        $mensaje = "Este correo ya está registrado.";
    } else {

        // Hashear la contraseña
        $hash = password_hash($password, PASSWORD_BCRYPT);

        // Insertar usuario
        $sql = "INSERT INTO usuarios (rut, nombre, apellido, email, telefono, direccion, password)
                VALUES ('$rut', '$nombre', '$apellido', '$email', '$telefono', '$direccion', '$hash')";

        if ($conn->query($sql)) {
            $mensaje = "Registrado correctamente. Ahora puede iniciar sesión.";
        } else {
            $mensaje = "Error al registrar: " . $conn->error;
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Registrarse - Clínica Moris</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="login-page">

    <div class="login-box">
        <img src="assets/img/ClinicaMoris.jpg" class="logo">

        <h2>Crear Cuenta</h2>

        <?php if (!empty($mensaje)) : ?>
            <div class="error-box"><?php echo $mensaje; ?></div>
        <?php endif; ?>

        <form method="POST">
            <input type="text" name="rut" placeholder="RUT" required>
            <input type="text" name="nombre" placeholder="Nombre" required>
            <input type="text" name="apellido" placeholder="Apellido" required>
            <input type="email" name="email" placeholder="Correo electrónico" required>
            <input type="text" name="telefono" placeholder="Teléfono">
            <input type="text" name="direccion" placeholder="Dirección">
            <input type="password" name="password" placeholder="Contraseña" required>

            <button class="btn-primary" type="submit">Registrarme</button>
        </form>

        <p class="register-text">
            ¿Ya tienes cuenta?  
            <a href="login.php">Iniciar Sesión</a>
        </p>
    </div>

</div>

</body>
</html>

