<?php
session_start();
include "../config/db.php";

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    $query = $conexion->prepare("SELECT * FROM usuarios WHERE email=? LIMIT 1");
    $query->bind_param("s", $email);
    $query->execute();

    $res = $query->get_result();

    if ($res->num_rows === 0) {
        $mensaje = "Usuario no encontrado.";
    } else {
        $usuario = $res->fetch_assoc();

        if (password_verify($password, $usuario["password"])) {

            $_SESSION["usuario"] = [
                "id" => $usuario["id"],
                "nombre" => $usuario["nombre"],
                "email" => $usuario["email"]
            ];

            header("Location: perfil.php");
            exit;

        } else {
            $mensaje = "Contraseña incorrecta.";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Iniciar sesión</title>
</head>
<body>

<h2>Login</h2>

<?php if ($mensaje): ?>
<p style="color:red"><?= $mensaje ?></p>
<?php endif; ?>

<form method="POST">
    Correo: <input type="email" name="email" required><br>
    Contraseña: <input type="password" name="password" required><br>

    <button type="submit">Entrar</button>
</form>

</body>
</html>
