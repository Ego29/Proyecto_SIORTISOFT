document.addEventListener("DOMContentLoaded", function () {
    const form = document.querySelector("form");

    form.addEventListener("submit", function (event) {
        event.preventDefault(); // Evita que el formulario se envíe por defecto

        const correoInput = document.getElementById("correo");
        const passInput = document.getElementById("contrasena");
        const correo = correoInput.value.trim();
        const contraseña = passInput.value.trim();

        eliminarMensajesError();
        let hayErrores = false;

        // Validar correo
        if (correo === "") {
            mostrarMensajeError(correoInput, "El correo es obligatorio.");
            hayErrores = true;
        } else if (!validarCorreo(correo)) {
            mostrarMensajeError(correoInput, "Formato de correo inválido.");
            hayErrores = true;
        }

        // Validar contraseña
        if (contraseña === "") {
            mostrarMensajeError(passInput, "La contraseña es obligatoria.");
            hayErrores = true;
        } else if (!validarContrasena(contraseña)) {
            mostrarMensajeError(passInput,
                "Debe tener mínimo 8 caracteres, una mayúscula, una minúscula, un número y un símbolo.");
            hayErrores = true;
        }

        // Si no hay errores, redirige a index1.html
        if (!hayErrores) {
            window.location.href = "index1.html";
        }
    });
    

    function mostrarMensajeError(input, mensaje) {
        const error = document.createElement("div");
        error.className = "error";
        error.style.color = "red";
        error.style.fontSize = "0.9rem";
        error.style.marginTop = "5px";
        error.textContent = mensaje;
        input.parentElement.appendChild(error);
    }

    function eliminarMensajesError() {
        const errores = document.querySelectorAll(".error");
        errores.forEach(error => error.remove());
    }

    function validarCorreo(correo) {
        const regex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        return regex.test(correo);
    }

    function validarContrasena(contrasena) {
        // Al menos 8 caracteres, una minúscula, una mayúscula, un número y un símbolo
        const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&.#])[A-Za-z\d@$!%*?&.#]{8,}$/;
        return regex.test(contrasena);
    }
});
