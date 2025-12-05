<?php
require "../config/seguridad.php";
require "../config/db.php";

$usuario_id = $_SESSION["usuario_id"];
$mensaje = "";

$sql = "SELECT * FROM usuarios WHERE id = $usuario_id LIMIT 1";
$result = $conn->query($sql);
$usuario = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $telefono = $_POST["telefono"];
    $direccion = $_POST["direccion"];

    $update = "UPDATE usuarios SET telefono='$telefono', direccion='$direccion' WHERE id=$usuario_id";

    if ($conn->query($update)) {
        $mensaje = "Datos actualizados correctamente.";
    } else {
        $mensaje = "Error al actualizar: " . $conn->error;
    }
}
?>

<?php include "layout.php"; ?>

<h2>Mi Perfil</h2>

<p><?php echo $mensaje; ?></p>

<form method="POST">

    <label>RUT:</label><br>
    <input type="text" value="<?php echo $usuario["rut"]; ?>" disabled><br><br>

    <label>Nombre:</label><br>
    <input type="text" value="<?php echo $usuario["nombre"]; ?>" disabled><br><br>

    <label>Apellido:</label><br>
    <input type="text" value="<?php echo $usuario["apellido"]; ?>" disabled><br><br>

    <label>Email:</label><br>
    <input type="email" value="<?php echo $usuario["email"]; ?>" disabled><br><br>

    <label>Teléfono:</label><br>
    <input type="text" name="telefono" value="<?php echo $usuario["telefono"]; ?>"><br><br>

    <label>Dirección:</label><br>
    <input type="text" name="direccion" value="<?php echo $usuario["direccion"]; ?>"><br><br>

    <button type="submit">Actualizar Datos</button>

</form>

</div></div></body></html>
