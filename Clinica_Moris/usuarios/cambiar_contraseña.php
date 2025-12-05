<?php
require "../config/seguridad.php";
require "../config/db.php";

$usuario_id = $_SESSION["usuario_id"];
$mensaje = "";

// Obtener contraseña actual
$sql = "SELECT password FROM usuarios WHERE id = $usuario_id LIMIT 1";
$result = $conn->query($sql);
$usuario = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $actual = $_POST["actual"];
    $nueva = $_POST["nueva"];
    $confirmar = $_POST["confirmar"];

    if (!password_verify($actual, $usuario["password"])) {
        $mensaje = "❌ La contraseña actual no es correcta.";
    } 
    else if (strlen($nueva) < 6) {
        $mensaje = "❌ La nueva contraseña debe tener al menos 6 caracteres.";
    }
    else if ($nueva !== $confirmar) {
        $mensaje = "❌ Las contraseñas nuevas no coinciden.";
    }
    else {
        $nuevo_hash = password_hash($nueva, PASSWORD_BCRYPT);
        $update = "UPDATE usuarios SET password='$nuevo_hash' WHERE id=$usuario_id";

        if ($conn->query($update)) {
            $mensaje = "✅ Contraseña cambiada correctamente.";
        } else {
            $mensaje = "❌ Error al cambiar contraseña.";
        }
    }
}
?>

<?php include "layout.php"; ?>

<h2>Cambiar Contraseña</h2>

<p><?php echo $mensaje; ?></p>

<form method="POST">
    <label>Contraseña Actual:</label><br>
    <input type="password" name="actual" required><br><br>

    <label>Nueva Contraseña:</label><br>
    <input type="password" name="nueva" required><br><br>

    <label>Confirmar Nueva Contraseña:</label><br>
    <input type="password" name="confirmar" required><br><br>

    <button type="submit">Cambiar Contraseña</button>
</form>

</div></div></body></html>
