document.addEventListener("DOMContentLoaded", function () {
    const form = document.querySelector("form");

    form.addEventListener("submit", function (event) {
        event.preventDefault();
        eliminarMensajesError();

        const nombre = document.getElementById("nombre");
        const institucion = document.getElementById("institucion");
        const email = document.getElementById("email");
        const telefono = document.getElementById("telefono");
        const mensaje = document.getElementById("mensaje");

        let hayErrores = false;

        // Validar nombre (solo letras y espacios)
        if (nombre.value.trim() === "" || !/^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$/.test(nombre.value)) {
            mostrarError(nombre, "El nombre es obligatorio y solo debe contener letras.");
            hayErrores = true;
        }

        // Validar institución (no vacío)
        if (institucion.value.trim() === "") {
            mostrarError(institucion, "El nombre de la institución es obligatorio.");
            hayErrores = true;
        }

        // Validar correo institucional
        if (!/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.(edu\.co)$/.test(email.value)) {
            mostrarError(email, "El correo debe ser institucional y terminar en .edu.co.");
            hayErrores = true;
        }

        // Validar teléfono: debe iniciar con 3 y tener exactamente 10 dígitos
        if (!/^3\d{9}$/.test(telefono.value)) {
            mostrarError(telefono, "El teléfono debe tener 10 dígitos y comenzar con 3.");
            hayErrores = true;
        }

        // Validar mensaje: mínimo 10 caracteres
        if (mensaje.value.trim().length < 10) {
            mostrarError(mensaje, "El mensaje debe tener al menos 10 caracteres.");
            hayErrores = true;
        }

        // Si todo está bien, enviar (aquí podrías usar fetch o AJAX)
        if (!hayErrores) {
            alert("Formulario enviado con éxito.");
            form.submit(); // o puedes hacer un envío por fetch aquí si deseas
        }
    });

    function mostrarError(input, mensaje) {
        // Eliminar errores anteriores del mismo campo si existen
        eliminarMensajeErrorCampo(input);

        const error = document.createElement("div");
        error.className = "error text-danger small mt-1";
        error.textContent = mensaje;
        input.insertAdjacentElement("afterend", error);
    }

    function eliminarMensajesError() {
        document.querySelectorAll(".error").forEach(el => el.remove());
    }

    function eliminarMensajeErrorCampo(input) {
        const siguiente = input.nextElementSibling;
        if (siguiente && siguiente.classList.contains("error")) {
            siguiente.remove();
        }
    }
});
        