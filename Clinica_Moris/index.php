<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Clínica Moris</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body class="landing-body">

    <!-- Barra superior -->
    <header class="landing-header">
        <div class="landing-container">

            <div class="logo">
                <img src="assets/img/ClinicaMoris.jpg" alt="Clínica Moris">
            </div>

            <nav class="main-nav">
                <a href="#servicios">Examenes</a>
                <a href="#especialidades">Especialidades</a>
                <a href="#nosotros">Sobre Nosotros</a>
                <a href="#contacto">Contacto</a>
            </nav>

            <div class="auth-links">
                <a href="login.php" class="btn-login">Iniciar Sesión</a>
                <a href="registro.php" class="btn-register">Registrarse</a>
            </div>

        </div>
    </header>


    <!-- HERO -->
    <section class="hero">
    <div class="hero-overlay"></div>
        <div class="hero-content">
            <h1>Bienvenido a Clínica Moris</h1>
            <p>Salud moderna, tecnología avanzada y atención humana.</p>
            <a href="login.php" class="hero-button">Acceder a Mi Cuenta</a>
        </div>
</section>

    <!-- SECCIÓN SERVICIOS -->
    <section id="servicios" class="section">
        <h2>Servicios</h2>
        <div class="section-grid">
            <div class="card">
                <div class="icon"></div>
                <h3>Consulta General</h3>
                <p>Atención médica integral para control y diagnóstico inicial.</p>
            </div>
            <div class="card">
                <div class="icon"></div>
                <h3>Exámenes de Laboratorio</h3>
                <p>Resultados digitales disponibles desde tu cuenta en línea.</p>
            </div>
            <div class="card">
                <div class="icon"></div>
                <h3>Imagenología</h3>
                <p>Resonancias, radiografías y ecografías con informes detallados.</p>
            </div>
        </div>
    </section>

    <!-- SECCIÓN ESPECIALIDADES -->
    <section id="especialidades" class="section section-alt">
        <h2>Especialidades</h2>
        <div class="section-grid">
            <div class="card">
                <div class="icon"></div>
                <h3>Medicina Interna</h3>
                <p>Diagnóstico y tratamiento de enfermedades del adulto.</p>
            </div>
            <div class="card">
                <div class="icon"></div>
                <h3>Pediatría</h3>
                <p>Cuidado especializado para niños y adolescentes.</p>
            </div>
            <div class="card">
                <div class="icon"></div>
                <h3>Traumatología</h3>
                <p>Atención de lesiones musculares y óseas.</p>
            </div>
            <div class="card">
                <div class="icon"></div>
                <h3>Neurología</h3>
                <p>Atención experta en trastornos del sistema nervioso.</p>
            </div>
        </div>
    </section>

    <!-- SECCIÓN SOBRE NOSOTROS -->
    <section id="nosotros" class="section about">
        <div class="about-container">

            <div class="about-text">
                <h2>Sobre Nosotros</h2>
                <p>
                    En Clínica Moris, combinamos experiencia médica con tecnología avanzada 
                    para brindar una atención humana, eficiente y de calidad. Nuestro compromiso 
                    es acompañar a cada paciente en su proceso de salud con dedicación, seguridad 
                    y profesionalismo.
                </p>

                <ul>
                    <li>✔ Atención personalizada</li>
                    <li>✔ Profesionales altamente capacitados</li>
                    <li>✔ Resultados y exámenes disponibles en línea</li>
                    <li>✔ Tecnología moderna de diagnóstico</li>
                </ul>
            </div>
            <div class="about-img">
                <img src="assets/img/ClinicaMoris.jpg" alt="Clínica Moris">
            </div>

        </div>
    </section>

<!-- SECCIÓN CONTACTO -->
<section id="contacto" class="section contact-section">

    <h2>Contacto</h2>

    <div class="contact-container">

        <!-- INFO DE CONTACTO -->
        <div class="contact-info">
            <p><strong>📍 Dirección:</strong> Av. Salud 123, Santiago, Chile</p>
            <p><strong>📞 Teléfono:</strong> +56 9 5555 5555</p>
            <p><strong>✉ Correo:</strong> contacto@clinicamoris.cl</p>
            <p><strong>🕒 Horario:</strong> Lunes a Viernes — 08:00 a 19:00</p>

            <a href="https://wa.me/56955555555" target="_blank" class="whatsapp-btn">💬 WhatsApp</a>
        </div>

        <!-- FORMULARIO -->
        <form action="config/notificaciones.php" method="POST" class="contact-form">
            <input type="text" name="nombre" placeholder="Tu nombre" required>
            <input type="email" name="email" placeholder="Tu correo" required>
            <textarea name="mensaje" placeholder="Escribe tu mensaje" required></textarea>
            <button type="submit">Enviar Mensaje</button>
        </form>

    </div>

    <!-- MAPA GOOGLE -->
    <div class="map-container">
        <iframe 
            src="https://maps.google.com/maps?q=Santiago%20Chile&t=&z=13&ie=UTF8&iwloc=&output=embed">
        </iframe>
    </div>

</section>

    <!-- FOOTER -->
    <footer class="footer">
        <div class="footer-container">

            <div class="footer-links">
                <a href="#servicios">Servicios</a>
                <a href="#especialidades">Especialidades</a>
                <a href="#nosotros">Sobre Nosotros</a>
                <a href="#contacto">Contacto</a>
            </div>

            <div class="footer-copy">
                © <?php echo date("Y"); ?> Clínica Moris — Todos los derechos reservados.
            </div>

        </div>
    </footer>

</body>
</html>
