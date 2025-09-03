<?php
include "conexion.php"; // conexión a la BD

$mensaje_exito = "";
$errores = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = trim($_POST["nombre"]);
    $institucion = trim($_POST["institucion"]);
    $email = trim($_POST["email"]);
    $telefono = trim($_POST["telefono"]);
    $mensaje = trim($_POST["mensaje"]);

    // Validaciones
    if (empty($nombre) || !preg_match("/^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$/", $nombre)) {
        $errores[] = "El nombre es obligatorio y solo debe contener letras.";
    }

    if (empty($institucion)) {
        $errores[] = "El nombre de la institución es obligatorio.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || !preg_match("/\.edu\.co$/", $email)) {
        $errores[] = "El correo debe ser institucional y terminar en .edu.co.";
    }

    if (!preg_match("/^3\d{9}$/", $telefono)) {
        $errores[] = "El teléfono debe tener 10 dígitos y comenzar con 3.";
    }

    if (strlen($mensaje) < 10) {
        $errores[] = "El mensaje debe tener al menos 10 caracteres.";
    }

    // Si no hay errores -> insertar en BD
    if (empty($errores)) {
        $stmt = $conn->prepare("INSERT INTO contactos (nombre, institucion, email, telefono, mensaje) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $nombre, $institucion, $email, $telefono, $mensaje);

        if ($stmt->execute()) {
            $mensaje_exito = "Formulario enviado y guardado con éxito.";
        } else {
            $errores[] = "Error al guardar en la base de datos: " . $stmt->error;
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIORTISOFT</title>

    <!-- Bootstrap & Iconos -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Estilo personalizado -->
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <!-- Barra de navegación -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-black fixed-top">
        <div class="container">
            <a class="navbar-brand" href="index.html">
                <img src="img/loguito.png" alt="Logo Institucional">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" id="navbarDropdown" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">Nosotros</a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="somos.html">¿Quiénes Somos?</a></li>
                            <li><a class="dropdown-item" href="mision.html">Misión</a></li>
                            <li><a class="dropdown-item" href="vision.html">Visión</a></li>
                        </ul>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link active" href="login.php">Iniciar Sesión</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="registro.php">Registrarse</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div style="padding-top: 70px;"></div>

    <!-- Sección principal -->
    <section id="inicio" class="hero-section text-center py-5 mt-5">
        <div class="container">
            <h1 class="display-3 fw-bold mb-4">BIENVENIDOS A SIORTISOFT</h1>
            <p class="lead mb-5">Soluciones innovadoras y seguras para colegios, universidades y entidades
                gubernamentales.</p>
            <a href="soluciones.html" class="btn btn-outline-light btn-lg">Descubre Nuestras Soluciones</a>
            <a href="https://web.whatsapp.com/" class="btn btn-outline-light btn-lg">Contáctanos</a>
        </div>
    </section>

    <!-- Contacto -->
    <section id="contacto" class="form-controls-section py-5">
        <div class="container">
            <h2 class="text-center mb-5 display-4 fw-bold text-dark">Hablemos de tu Institución</h2>
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <p class="text-center lead mb-4 text-muted">
                        ¿Interesado en modernizar la gestión de ingresos de tu entidad? Contáctanos para una asesoría personalizada.
                    </p>

                    <!-- Mensajes de error o éxito -->
                    <?php if (!empty($errores)): ?>
                        <div class="alert alert-danger">
                            <ul>
                                <?php foreach ($errores as $error): ?>
                                    <li><?= $error ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($mensaje_exito)): ?>
                        <div class="alert alert-success">
                            <?= $mensaje_exito ?>
                        </div>
                    <?php endif; ?>

                    <form action="" method="POST" class="bg-white p-4 rounded shadow" novalidate>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="nombre" class="form-label">Nombre del Contacto</label>
                                <input type="text" class="form-control" name="nombre" id="nombre"
                                    placeholder="Tu Nombre Completo" required>
                            </div>
                            <div class="col-md-6">
                                <label for="institucion" class="form-label">Institución / Entidad</label>
                                <input type="text" class="form-control" name="institucion" id="institucion"
                                    placeholder="Nombre de la Institución" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Correo Electrónico Oficial</label>
                            <input type="email" class="form-control" name="email" id="email"
                                placeholder="contacto@tuinstitucion.edu.co" required>
                        </div>
                        <div class="mb-3">
                            <label for="telefono" class="form-label">Número de Teléfono</label>
                            <input type="tel" class="form-control" name="telefono" id="telefono"
                                placeholder="3XX XXX XXXX" required>
                        </div>
                        <div class="mb-3">
                            <label for="mensaje" class="form-label">¿Cómo podemos ayudarte?</label>
                            <textarea class="form-control" name="mensaje" id="mensaje" rows="5"
                                placeholder="Descríbenos tus necesidades o preguntas específicas..." required></textarea>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-dark btn-lg">Enviar Solicitud</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Pie de página -->
    <footer class="text-center">
        <div class="container">
            <div class="footer-text mb-3">
                <p class="mb-0">&copy; 2025 SIORTISOFT. Todos los derechos reservados.</p>
                <p class="mb-0">Sistemas de Gestión para Instituciones Públicas.</p>
            </div>
            <div class="footer-horizontal text-center">
                <div class="footer-icons d-flex justify-content-center gap-4 mb-2">
                    <a href="https://web.whatsapp.com/" aria-label="WhatsApp"><i class="bi bi-whatsapp"></i></a>
                    <a href="https://www.facebook.com/" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
                    <a href="https://x.com/" aria-label="Twitter"><i class="bi bi-twitter"></i></a>
                    <a href="https://www.instagram.com/" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
                </div>
                <div class="footer-links d-flex justify-content-center flex-wrap gap-4">
                    <a href="politica.html">Política de Privacidad</a>
                    <a href="terminos.html">Términos de Servicio</a>
                    <a href="faq.html">Preguntas Frecuentes</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

</html>