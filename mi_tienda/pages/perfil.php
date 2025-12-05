<?php
include "../config/auth_user.php";
?>
<!DOCTYPE html>
<html>
<head>
    <title>Mi cuenta</title>
</head>
<body>

<h1>Bienvenido, <?= $_SESSION["usuario"]["nombre"] ?></h1>
<p>Correo: <?= $_SESSION["usuario"]["email"] ?></p>

<a href="logout.php">Cerrar sesión</a>

</body>
</html>
