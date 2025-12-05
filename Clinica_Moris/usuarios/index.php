<?php
require "../config/seguridad.php";
require "../config/db.php";

$usuario_id = $_SESSION["usuario_id"];

// Próxima hora reservada
$proxima = $conn->query("
    SELECT h.*, d.nombre AS doctor
    FROM horas_medicas h
    LEFT JOIN doctores d ON d.id = h.doctor_id
    WHERE usuario_id = $usuario_id AND h.fecha >= CURDATE() AND h.estado = 'reservada'
    ORDER BY h.fecha ASC, h.hora ASC
    LIMIT 1
")->fetch_assoc();

// Último examen
$ultimo_examen = $conn->query("
    SELECT * FROM examenes 
    WHERE usuario_id = $usuario_id 
    ORDER BY fecha_examen DESC 
    LIMIT 1
")->fetch_assoc();

// Última receta
$ultima_receta = $conn->query("
    SELECT * FROM recetas 
    WHERE usuario_id = $usuario_id 
    ORDER BY fecha_receta DESC 
    LIMIT 1
")->fetch_assoc();

?>

<?php include "layout.php"; 

$nombre = $_SESSION["usuario_nombre"] ?? "Usuario";
$apellido = $_SESSION["usuario_apellido"] ?? "";


echo "Bienvenido, $nombre $apellido";
?>

<h2>Bienvenido, <?php echo $_SESSION["usuario_nombre"]; ?></h2>

<div class="cards">


    <!-- Próxima Hora -->
    <div class="card">
        <h3>📅 Próxima Hora</h3>
        <?php if ($proxima): ?>
            <p><strong>Fecha:</strong> <?php echo $proxima["fecha"]; ?></p>
            <p><strong>Hora:</strong> <?php echo $proxima["hora"]; ?></p>
            <p><strong>Doctor:</strong> <?php echo $proxima["doctor"]; ?></p>
        <?php else: ?>
            <p>No tienes horas reservadas.</p>
        <?php endif; ?>
    </div>

    <!-- Último Examen -->
    <div class="card">
        <h3>🧪 Último Examen</h3>
        <?php if ($ultimo_examen): ?>
            <p><strong><?php echo $ultimo_examen["tipo"]; ?></strong></p>
            <p><?php echo $ultimo_examen["fecha_examen"]; ?></p>
            <a class="btn" href="../uploads/examenes/<?php echo $usuario_id; ?>/<?php echo $ultimo_examen["archivo_pdf"]; ?>" download>Descargar</a>
        <?php else: ?>
            <p>Aún no tienes exámenes.</p>
        <?php endif; ?>
    </div>

    <!-- Última Receta -->
    <div class="card">
        <h3>💊 Última Receta</h3>
        <?php if ($ultima_receta): ?>
            <p><strong>Doctor:</strong> <?php echo $ultima_receta["doctor_nombre"]; ?></p>
            <p><?php echo $ultima_receta["fecha_receta"]; ?></p>
            <a class="btn" href="../uploads/examenes/<?php echo $usuario_id; ?>/<?php echo $ultima_receta["archivo_pdf"]; ?>" download>Descargar</a>
        <?php else: ?>
            <p>No tienes recetas registradas.</p>
        <?php endif; ?>
    </div>

</div>

<!-- BOTONES GRANDES -->
<div class="quick-links">

    <a class="qbtn" href="examenes.php">🧪 Ver Exámenes</a>
    <a class="qbtn" href="recetas.php">💊 Ver Recetas</a>
    <a class="qbtn" href="reservar_hora.php">📅 Reservar Hora</a>
    <a class="qbtn" href="perfil.php">👤 Mi Perfil</a>

</div>

