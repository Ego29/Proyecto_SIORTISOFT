<?php
$errores = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $correo = trim($_POST["correo"]);
    $contrasena = trim($_POST["contrasena"]);

    // Validación básica
    if (empty($correo)) {
        $errores[] = "El correo es obligatorio.";
    } elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $errores[] = "El formato del correo no es válido.";
    }

    if (empty($contrasena)) {
        $errores[] = "La contraseña es obligatoria.";
    }

    if (empty($errores)) {
        // Conexión a la base de datos
        $conexion = new mysqli("localhost", "root", "", "sistema1");

        if ($conexion->connect_error) {
            die("Error de conexión: " . $conexion->connect_error);
        }

        $stmt = $conexion->prepare("SELECT CONTRASENA_HASH FROM USUARIO WHERE CORREO = ?");
        if (!$stmt) {
            die("Error en la consulta: " . $conexion->error);
        }

        $stmt->bind_param("s", $correo);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 1) {
            $stmt->bind_result($hashAlmacenado);
            $stmt->fetch();

            // Aquí puedes usar password_verify si los hashes están encriptados con password_hash
            if ($contrasena === $hashAlmacenado) {
                header("Location: index1.html");
                exit;
            } else {
                $errores[] = "Correo o contraseña incorrectos.";
            }
        } else {
            $errores[] = "Correo o contraseña incorrectos.";
        }

        $stmt->close();
        $conexion->close();
    }
}
?>
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>SIORTISOFT</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="css/login.css" />
</head>
<body class="d-flex flex-column min-vh-100">
    <nav class="navbar navbar-expand-lg navbar-dark bg-black fixed-top">
        <div class="container">
            <a class="navbar-brand" href="index.html"><img src="img/loguito.png" alt="Logo Institucional" /></a>
        </div>
    </nav>

    <div style="padding-top: 70px;"></div>

    <main class="flex-grow-1">
        <div class="container d-flex justify-content-center align-items-center flex-column">
            <form class="login-form text-center" method="post">
                <h2>INICIO SESIÓN</h2>

                <?php if (!empty($errores)): ?>
                    <div class="alert alert-danger w-100">
                        <ul class="mb-0">
                            <?php foreach ($errores as $error): ?>
                                <li><?= htmlspecialchars($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <label for="correo">Correo</label>
                <input type="email" name="correo" id="correo" placeholder="Ingresa tu correo" required />
                <br />

                <label for="contrasena">Contraseña</label>
                <input type="password" name="contrasena" id="contrasena" placeholder="Ingresa tu contraseña" required />
                <br />

                <input type="submit" value="Enviar" class="btn btn-dark mt-2" />

                <div class="extra mt-3">
                    ¿No tienes cuenta? <a href="registro.php">Regístrate</a> |
                    <a href="recuperar.php">¿Olvidaste tu contraseña?</a>
                </div>
            </form>
        </div>
    </main>

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
                    <a href="politica.html">Política de Privacidad</a>
                    <a href="terminos.html">Términos de Servicio</a>
                    <a href="faq.html">Preguntas Frecuentes</a>
                </div>
            </div>
        </div>
    </footer>
    <script src="js/scritp_login.js"></script>
    
</body>
</html>
<?php
// Cerrar conexión
