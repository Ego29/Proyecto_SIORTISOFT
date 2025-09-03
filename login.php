<?php
session_start();
include "conexion.php";

$errores = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $correo = trim($_POST["correo"]);
    $contrasena = trim($_POST["contrasena"]);

    if (empty($correo)) {
        $errores[] = "El correo es obligatorio.";
    } elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $errores[] = "Formato de correo inválido.";
    }

    if (empty($contrasena)) {
        $errores[] = "La contraseña es obligatoria.";
    }

    if (empty($errores)) {
        $stmt = $conn->prepare("SELECT ID_USUARIO, CONTRASENA_HASH FROM usuario WHERE CORREO = ?");
        $stmt->bind_param("s", $correo);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado && $resultado->num_rows === 1) {
            $usuario = $resultado->fetch_assoc();

            if (password_verify($contrasena, $usuario["CONTRASENA_HASH"])) {
                $_SESSION["usuario_id"] = $usuario["ID_USUARIO"];
                header("Location: index1.html");
                exit;
            } else {
                $errores[] = "Contraseña incorrecta.";
            }
        } else {
            $errores[] = "El usuario no existe.";
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
        <title>SIORTISOFT</title>

        <!-- Bootstrap & Iconos -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
            crossorigin="anonymous" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

        <!-- Estilo personalizado -->
        <link rel="stylesheet" href="css/style.css" />
        <link rel="stylesheet" href="css/login.css" />
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
                        <li class="nav-item">
                            <a class="nav-link active" href="index.php">Inicio</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Separación del navbar -->
        <div style="padding-top: 70px;"></div>

        <!-- Contenido principal -->
        <main class="flex-grow-1">
            <div class="container d-flex justify-content-center align-items-center flex-column">
                
                <form class="login-form text-center bg-white p-4 shadow rounded" method="POST" action="login.php">
                    <h2>INICIO SESIÓN</h2>

                    <?php if (!empty($errores)): ?>
                        <div class="alert alert-danger text-start">
                            <ul class="mb-0">
                                <?php foreach ($errores as $error): ?>
                                    <li><?= htmlspecialchars($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <div class="mb-3 text-start">
                        <label for="correo" class="form-label">Correo</label>
                        <input type="email" name="correo" id="correo" class="form-control"
                            placeholder="Ingresa tu correo" required />
                    </div>

                    <div class="mb-3 text-start">
                        <label for="contrasena" class="form-label">Contraseña</label>
                        <input type="password" name="contrasena" id="contrasena" class="form-control"
                            placeholder="Ingresa tu contraseña" required />
                    </div>

                    <button type="submit" class="btn btn-dark w-100">Enviar</button>

                    <div class="extra mt-3">
                        ¿No tienes cuenta? <a href="registro.php">Regístrate</a><br>
                        <a href="recuperar.php">¿Olvidaste tu contraseña?</a>
                    </div>
                </form>
            </div>
        </main>

        <!-- Footer -->
        <footer class="text-center mt-4">
            <div class="container">
                <!-- Frase institucional -->
                <div class="footer-text mb-3">
                    <p class="mb-0">&copy; 2025 SIORTISOFT. Todos los derechos reservados.</p>
                    <p class="mb-0">Sistemas de Gestión para Instituciones Públicas.</p>
                </div>

                <!-- Íconos y enlaces -->
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

    </body>
    </html>
