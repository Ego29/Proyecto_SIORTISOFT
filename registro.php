<?php
session_start();
include "conexion.php";

$errores = [];
$registro_exitoso = false; // Flag para indicar registro correcto
$nombre = $correo = $telefono = $contrasena = $confirmar = "";
$rol_seleccionado = 2; // Valor por defecto: 2 = usuario normal

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = trim($_POST["nombre"] ?? '');
    $correo = trim($_POST["correo"] ?? '');
    $telefono = trim($_POST["telefono"] ?? '');
    $contrasena = trim($_POST["contrasena"] ?? '');
    $confirmar = trim($_POST["confirmar"] ?? '');
    $rol_seleccionado = (int)($_POST["rol"] ?? 2);

    // Validaciones
    if (empty($nombre) || !preg_match("/^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$/", $nombre)) {
        $errores[] = "El nombre es obligatorio y solo debe contener letras.";
    }
    if (empty($correo) || !filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $errores[] = "Correo inválido.";
    }
    if (empty($telefono) || !preg_match("/^3\d{9}$/", $telefono)) {
        $errores[] = "El teléfono debe tener 10 dígitos y empezar con 3.";
    }
    if (empty($contrasena) || strlen($contrasena) < 6) {
        $errores[] = "La contraseña es obligatoria y debe tener al menos 6 caracteres.";
    }
    if ($contrasena !== $confirmar) {
        $errores[] = "Las contraseñas no coinciden.";
    }
    if ($rol_seleccionado < 1 || $rol_seleccionado > 4) {
        $errores[] = "Rol seleccionado inválido.";
    }

    // Comprobar si el correo ya existe
    if (empty($errores)) {
        $stmt = $conn->prepare("SELECT ID_USUARIO FROM usuario WHERE CORREO = ?");
        $stmt->bind_param("s", $correo);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $errores[] = "El correo ya está registrado.";
        }
        $stmt->close();
    }

    // Insertar en la BD si no hay errores
    if (empty($errores)) {
        $hash = password_hash($contrasena, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO usuario (NOMBRE_USUARIO, CORREO, CONTRASENA_HASH, FK_ID_ROL) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sssi", $nombre, $correo, $hash, $rol_seleccionado);

        if ($stmt->execute()) {
            $registro_exitoso = true; // Registro correcto
        } else {
            $errores[] = "Error al registrar: " . $stmt->error;
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Registro - SIORTISOFT</title>

    <!-- Bootstrap & Iconos -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

    <!-- Estilo personalizado -->
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="css/registro.css" />
</head>

<body class="d-flex flex-column min-vh-100">
    <!-- Barra de navegación -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-black fixed-top">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <img src="img/loguito.png" alt="Logo Institucional" />
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link active" href="index.php">Inicio</a></li>
                    <li class="nav-item"><a class="nav-link" href="login.php">Iniciar Sesión</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div style="padding-top: 70px;"></div>

    <!-- Contenido principal -->
    <main class="flex-grow-1 d-flex justify-content-center align-items-center flex-column">
        <form class="login-form text-center bg-white p-4 shadow rounded" method="POST" action="registro.php">
            <h2>REGISTRO</h2>

            <?php if ($registro_exitoso): ?>
                <div class="alert alert-success text-start">
                    Usuario registrado correctamente. Redirigiendo a inicio de sesión...
                </div>
                <script>
                    setTimeout(function() {
                        window.location.href = "login.php";
                    }, 2000);
                </script>
            <?php endif; ?>

            <?php if (!empty($errores)): ?>
                <div class="alert alert-danger text-start">
                    <ul class="mb-0">
                        <?php foreach ($errores as $e) echo "<li>$e</li>"; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <div class="mb-3 text-start">
                <label for="nombre" class="form-label">Nombre Completo</label>
                <input type="text" name="nombre" id="nombre" class="form-control" value="<?= htmlspecialchars($nombre) ?>" required />
            </div>

            <div class="mb-3 text-start">
                <label for="correo" class="form-label">Correo Electrónico</label>
                <input type="email" name="correo" id="correo" class="form-control" value="<?= htmlspecialchars($correo) ?>" required />
            </div>

            <div class="mb-3 text-start">
                <label for="telefono" class="form-label">Teléfono</label>
                <input type="text" name="telefono" id="telefono" class="form-control" value="<?= htmlspecialchars($telefono) ?>" required />
            </div>

            <div class="mb-3 text-start">
                <label for="contrasena" class="form-label">Contraseña</label>
                <input type="password" name="contrasena" id="contrasena" class="form-control" required />
            </div>

            <div class="mb-3 text-start">
                <label for="confirmar" class="form-label">Confirmar Contraseña</label>
                <input type="password" name="confirmar" id="confirmar" class="form-control" required />
            </div>

            <div class="mb-3 text-start">
                <label for="rol" class="form-label">Selecciona un Rol</label>
                <select name="rol" id="rol" class="form-control" required>
                    <option value="1" <?= ($rol_seleccionado == 1) ? 'selected' : '' ?>>Administrador</option>
                    <option value="2" <?= ($rol_seleccionado == 2) ? 'selected' : '' ?>>Vigilante</option>
                    <option value="3" <?= ($rol_seleccionado == 3) ? 'selected' : '' ?>>Visitante</option>
                    <option value="4" <?= ($rol_seleccionado == 4) ? 'selected' : '' ?>>Usuario corriente</option>
                </select>
            </div>

            <button type="submit" class="btn btn-dark w-100">Registrarse</button>

            <div class="extra mt-3">
                ¿Ya tienes cuenta? <a href="login.php">Inicia Sesión</a>
            </div>
        </form>
    </main>

    <!-- Footer -->
    <footer class="text-center mt-4">
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
                    <a href="#">Política de Privacidad</a>
                    <a href="#">Términos de Servicio</a>
                    <a href="#">Preguntas Frecuentes</a>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>

</html>