<?php
session_start();
include "../config/db.php";

// Si ya está logueado, redirigir
if (isset($_SESSION["admin"])) {
    header("Location: /mi_tienda/admin/index.php");
    exit;
}

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $usuario = trim($_POST["usuario"]);
    $password = trim($_POST["password"]);

    // Buscar usuario admin en la BD
    $stmt = $conexion->prepare("SELECT id, password FROM admins WHERE usuario = ?");
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($idAdmin, $hashGuardado);
        $stmt->fetch();

        // Verificar contraseña
        if (password_verify($password, $hashGuardado)) {

            session_regenerate_id(true); // Seguridad

            $_SESSION["admin"] = $usuario;
            $_SESSION["admin_id"] = $idAdmin;

            header("Location: /mi_tienda/admin/index.php");
            exit;
        } else {
            $mensaje = "Contraseña incorrecta.";
        }
    } else {
        $mensaje = "Usuario no encontrado.";
    }

    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>
    <link rel="stylesheet" href="/mi_tienda/admin/admin.css">
</head>
<body>

<div class="login-container">
    <h2>Panel Administrador</h2>

    <?php if ($mensaje): ?>
        <p class="login-error"><?= $mensaje ?></p>
    <?php endif; ?>

    <form method="POST" class="login-form">
        <label>Usuario</label>
        <input type="text" name="usuario" required>

        <label>Contraseña</label>
        <input type="password" name="password" required>

        <button type="submit" class="btn">Entrar</button>
    </form>

    <a href="/mi_tienda/" class="volver">← Volver a la tienda</a>
</div>

</body>
</html>
