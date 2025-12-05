<?php
session_start();
include "../config/db.php";

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nombre = trim($_POST["nombre"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $password2 = $_POST["password2"];

    if ($nombre === "" || $email === "" || $password === "") {
        $mensaje = "Completa todos los campos.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $mensaje = "Correo inválido.";
    } elseif ($password !== $password2) {
        $mensaje = "Las contraseñas no coinciden.";
    } else {

        // Ver si el correo ya existe
        $query = $conexion->prepare("SELECT id FROM usuarios WHERE email=?");
        $query->bind_param("s", $email);
        $query->execute();
        $existe = $query->get_result()->num_rows;

        if ($existe > 0) {
            $mensaje = "Este correo ya está registrado.";
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $conexion->prepare(
                "INSERT INTO usuarios(nombre, email, password) VALUES (?, ?, ?)"
            );

            $stmt->bind_param("sss", $nombre, $email, $hash);
            $stmt->execute();

            $_SESSION["usuario"] = [
                "id" => $stmt->insert_id,
                "nombre" => $nombre,
                "email" => $email
            ];

            header("Location: perfil.php");
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Registro</title>
</head>
<body>

<h2>Crear cuenta</h2>

<?php if ($mensaje): ?>
<p style="color:red"><?= $mensaje ?></p>
<?php endif; ?>

<form method="POST">
    Nombre: <input type="text" name="nombre" required><br>
    Correo: <input type="email" name="email" required><br>
    Contraseña: <input type="password" name="password" required><br>
    Repetir contraseña: <input type="password" name="password2" required><br>

    <button type="submit">Registrarme</button>
</form>

</body>
</html>
