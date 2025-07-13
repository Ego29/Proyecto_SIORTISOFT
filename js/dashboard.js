document.addEventListener("DOMContentLoaded", () => {
    const totalUsuarios = document.getElementById("totalUsuarios");
    const totalOpiniones = document.getElementById("totalOpiniones");
    const totalJornadas = document.getElementById("totalJornadas");
    const tablaResultados = document.getElementById("tablaResultados");
    const tablaDatos = document.getElementById("tablaDatos");
    const btnAgregarUsuario = document.getElementById("btnAgregarUsuario");

    const usuarios = [
        { id: 121, nombre: "SANTIAGO JIMENEZ", correo: "SANTIAGO@GMAIL.COM", rol: "Administrador" },
        { id: 122, nombre: "LAURA MARTÍNEZ", correo: "LAURA@GMAIL.COM", rol: "Invitado" },
        { id: 123, nombre: "JUAN PÉREZ", correo: "JUAN@GMAIL.COM", rol: "Usuario" },
        { id: 124, nombre: "MARIA GOMEZ", correo: "MARIA@GMAIL.COM", rol: "Administrador" },
        { id: 125, nombre: "PEDRO RODRIGUEZ", correo: "PEDRO@GMAIL.COM", rol: "Invitado" },
        { id: 126, nombre: "ANA LOPEZ", correo: "ANA@GMAIL.COM", rol: "Usuario" }
    ];

    const opiniones = [
        { id: 1, titulo: "Sugerencias", descripcion: "Mejorar el sistema de notificaciones" },
        { id: 2, titulo: "Quejas", descripcion: "El login es lento" },
        { id: 1, titulo: "sugerencias", descripcion: "La interfaz es confusa" },
        { id: 1, titulo: "Sugerencias", descripcion: "Añadir más opciones de filtros" },
        { id: 2, titulo: "Quejas", descripcion: "Los informes tardan en generarse" },
        { id: 1, titulo: "sugerencias", descripcion: "El sistema se congela a veces" }
    ];

    const jornadas = [
        { id: "JN001", nombre: "Mañana", horario: "07:00 - 12:00" },
        { id: "JN002", nombre: "Tarde", horario: "13:00 - 18:00" },
        { id: "JN003", nombre: "Noche", horario: "18:30 - 22:30" }
    ];

    const notificaciones = [
        "Sistema actualizado correctamente",
        "Nuevo usuario registrado",
        "Mantenimiento programado"
    ];

    const mensajes = [
        "Correo de bienvenida enviado",
        "Recordatorio de contraseña",
        "Confirmación de cuenta"
    ];

    let chartUsuarios;

    totalUsuarios.textContent = usuarios.length;
    totalOpiniones.textContent = opiniones.length;
    totalJornadas.textContent = jornadas.length;

    document.getElementById("cardUsuarios").addEventListener("click", () => {
        btnAgregarUsuario.style.display = "block";
        actualizarUsuarios();
    });

    document.getElementById("cardOpiniones").addEventListener("click", () => {
        btnAgregarUsuario.style.display = "none";
        mostrarTabla("Opiniones", ["ID", "Título", "Descripción"],
            opiniones.map(o => [o.id, o.titulo, o.descripcion])
        );
    });

    document.getElementById("cardJornadas").addEventListener("click", () => {
        btnAgregarUsuario.style.display = "none";
        mostrarTabla("Jornadas", ["ID", "Nombre", "Horario"],
            jornadas.map(j => [j.id, j.nombre, j.horario])
        );
    });

    function mostrarTabla(titulo, columnas, datos) {
        tablaResultados.style.display = "block";
        tablaDatos.querySelector("thead").innerHTML =
            `<tr>${columnas.map(col => `<th>${col}</th>`).join("")}</tr>`;
        tablaDatos.querySelector("tbody").innerHTML =
            datos.map(fila => `<tr>${fila.map(c => `<td>${c}</td>`).join("")}</tr>`).join("");
    }

    function actualizarUsuarios() {
        mostrarTabla("Usuarios", ["ID", "Nombre", "Correo", "Rol", "Acciones"],
            usuarios.map((u, index) => [
                u.id,
                u.nombre,
                u.correo,
                u.rol,
                `<button class='btn btn-sm btn-primary me-1' onclick='editarUsuario(${index})'>Editar</button>
                 <button class='btn btn-sm btn-danger' onclick='eliminarUsuario(${index})'>Eliminar</button>`
            ])
        );

        if (chartUsuarios) chartUsuarios.destroy();
        const ctx1 = document.getElementById("graficoUsuarios").getContext("2d");
        chartUsuarios = new Chart(ctx1, {
            type: "bar",
            data: {
                labels: usuarios.map(u => u.nombre),
                datasets: [{
                    label: "Usuarios",
                    data: Array(usuarios.length).fill(1),
                    backgroundColor: "#4a69bd"
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false },
                    title: { display: true, text: "Usuarios por Nombre" }
                }
            }
        });

        totalUsuarios.textContent = usuarios.length;
    }

    window.editarUsuario = function(index) {
        const usuario = usuarios[index];
        const nuevoNombre = prompt("Editar nombre:", usuario.nombre);
        const nuevoCorreo = prompt("Editar correo:", usuario.correo);
        const nuevoRol = prompt("Editar rol:", usuario.rol);

        if (nuevoNombre && nuevoCorreo && nuevoRol) {
            usuarios[index] = {
                ...usuario,
                nombre: nuevoNombre,
                correo: nuevoCorreo,
                rol: nuevoRol
            };
            actualizarUsuarios();
        }
    }

    window.eliminarUsuario = function(index) {
        if (confirm("¿Estás seguro de eliminar este usuario?")) {
            usuarios.splice(index, 1);
            actualizarUsuarios();
        }
    }

    window.agregarUsuario = function() {
        const modal = new bootstrap.Modal(document.getElementById("modalAgregarUsuario"));
        modal.show();
        document.getElementById("formAgregarUsuario").reset();
        document.getElementById("nuevoNombre").focus();
    }

    document.getElementById("formAgregarUsuario").addEventListener("submit", function(e) {
        e.preventDefault();
        const nombre = document.getElementById("nuevoNombre").value;
        const correo = document.getElementById("nuevoCorreo").value;
        const rol = document.getElementById("nuevoRol").value;

        const nuevoID = Math.max(...usuarios.map(u => u.id)) + 1;
        usuarios.push({ id: nuevoID, nombre, correo, rol });

        bootstrap.Modal.getInstance(document.getElementById("modalAgregarUsuario")).hide();
        document.getElementById("formAgregarUsuario").reset();
        actualizarUsuarios();
    });

    // Gráfico de opiniones
    const ctx2 = document.getElementById("graficoOpiniones").getContext("2d");
    new Chart(ctx2, {
        type: "pie",
        data: {
            labels: ["Sugerencias", "Quejas"],
            datasets: [{
                label: "Opiniones",
                data: [
                    opiniones.filter(o => o.titulo.toLowerCase().includes("sugerencia")).length,
                    opiniones.filter(o => o.titulo.toLowerCase().includes("queja")).length
                ],
                backgroundColor: ["#ff6384", "#36a2eb"]
            }]
        },
        options: {
            responsive: true,
            plugins: {
                title: { display: true, text: "Distribución de Opiniones" }
            }
        }
    });

    // Gráfico de jornadas
    const ctx3 = document.getElementById("graficoJornadas").getContext("2d");
    new Chart(ctx3, {
        type: "doughnut",
        data: {
            labels: jornadas.map(j => j.nombre),
            datasets: [{
                label: "Jornadas",
                data: jornadas.map(() => 1),
                backgroundColor: ["#ffce56", "#ff9f40", "#4bc0c0"]
            }]
        },
        options: {
            responsive: true,
            plugins: {
                title: { display: true, text: "Jornadas Activas" }
            }
        }
    });

    // Mostrar notificaciones
    notificaciones.forEach(notif => {
        const li = document.createElement("li");
        li.className = "list-group-item";
        li.textContent = notif;
        document.getElementById("listaNotificaciones").appendChild(li);
    });

    // Mostrar mensajes
    mensajes.forEach(msg => {
        const li = document.createElement("li");
        li.className = "list-group-item";
        li.textContent = msg;
        document.getElementById("listaMensajes").appendChild(li);
    });
});
