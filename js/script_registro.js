document.addEventListener("DOMContentLoaded", function () {
    const form = document.querySelector("form");
    const nombreInput = document.getElementById("nombre");
    const telefonoInput = document.getElementById("telefono");

    // Validación en tiempo real del campo nombre: solo letras y espacios
    nombreInput.addEventListener("input", function () {
        this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s]/g, '');
    });

    // Validación en tiempo real del campo teléfono: solo números
    telefonoInput.addEventListener("input", function () {
        this.value = this.value.replace(/\D/g, ''); // elimina todo lo que no sea dígito
    });

    form.addEventListener("submit", function (event) {
        event.preventDefault();

        const nombre = nombreInput.value.trim();
        const correo = document.getElementById("correo").value.trim();
        const telefono = telefonoInput.value.trim();
        const contrasena = document.getElementById("contrasena").value.trim();
        const confirmar = document.getElementById("confirmar").value.trim();

        eliminarMensajesError();
        let hayErrores = false;

        // Validar nombre
        if (nombre === "") {
            mostrarError("nombre", "El nombre es obligatorio.");
            hayErrores = true;
        } else if (!validarNombre(nombre)) {
            mostrarError("nombre", "Solo se permiten letras y espacios.");
            hayErrores = true;
        }

        // Validar correo
        if (correo === "") {
            mostrarError("correo", "El correo es obligatorio.");
            hayErrores = true;
        } else if (!validarCorreo(correo)) {
            mostrarError("correo", "Formato de correo inválido.");
            hayErrores = true;
        }

        // Validar teléfono
        if (telefono === "") {
            mostrarError("telefono", "El teléfono es obligatorio.");
            hayErrores = true;
        } else if (!validarTelefono(telefono)) {
            mostrarError("telefono", "Debe tener 10 dígitos, solo números y comenzar por 3.");
            hayErrores = true;
        }

        // Validar contraseña
        if (contrasena === "") {
            mostrarError("contrasena", "La contraseña es obligatoria.");
            hayErrores = true;
        } else if (!validarContrasena(contrasena)) {
            mostrarError("contrasena", "Debe tener al menos 8 caracteres, una mayúscula, una minúscula, un número y un símbolo.");
            hayErrores = true;
        }

        // Confirmar contraseña
        if (confirmar === "") {
            mostrarError("confirmar", "Debes confirmar tu contraseña.");
            hayErrores = true;
        } else if (contrasena !== confirmar) {
            mostrarError("confirmar", "Las contraseñas no coinciden.");
            hayErrores = true;
        }

        // Si todo está bien
        if (!hayErrores) {
            alert("Registro exitoso.");
            window.location.href = "login.html";
        }
    });

    // Funciones auxiliares

    function mostrarError(id, mensaje) {
        const input = document.getElementById(id);
        const error = document.createElement("div");
        error.className = "error";
        error.style.color = "red";
        error.style.fontSize = "0.9rem";
        error.style.marginTop = "-15px";
        error.style.marginBottom = "10px";
        error.textContent = mensaje;
        input.insertAdjacentElement("afterend", error);
    }

    function eliminarMensajesError() {
        const errores = document.querySelectorAll(".error");
        errores.forEach(e => e.remove());
    }

    function validarNombre(nombre) {
        const regex = /^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/;
        return regex.test(nombre);
    }

    function validarCorreo(correo) {
        const regex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        return regex.test(correo);
    }

    function validarTelefono(telefono) {
        const regex = /^3\d{9}$/;
        return regex.test(telefono);
    }

    function validarContrasena(contrasena) {
        const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&.#])[A-Za-z\d@$!%*?&.#]{8,}$/;
        return regex.test(contrasena);
    }
});
