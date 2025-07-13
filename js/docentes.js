document.addEventListener("DOMContentLoaded", () => {
    const totalUsuarios = document.getElementById("totalUsuarios");
    const totalOpiniones = document.getElementById("totalOpiniones");
    const totalJornadas = document.getElementById("totalJornadas");
    const tablaResultados = document.getElementById("tablaResultados");
    const tablaDatos = document.getElementById("tablaDatos");

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

    totalUsuarios.textContent = usuarios.length;
    totalOpiniones.textContent = opiniones.length;
    totalJornadas.textContent = jornadas.length;

    document.getElementById("cardUsuarios").addEventListener("click", () => {
        mostrarTabla("Usuarios", ["ID", "Nombre", "Correo", "Rol", "Acciones"], usuarios.map(u => [
            u.id, u.nombre, u.correo, u.rol,
            `<button class='btn btn-sm btn-primary me-1'>Editar</button>
             <button class='btn btn-sm btn-danger'>Eliminar</button>`
        ]));
    });

    document.getElementById("cardOpiniones").addEventListener("click", () => {
        mostrarTabla("Opiniones", ["ID", "Título", "Descripción"], opiniones.map(o => [o.id, o.titulo, o.descripcion]));
    });

    document.getElementById("cardJornadas").addEventListener("click", () => {
        mostrarTabla("Jornadas", ["ID", "Nombre", "Horario"], jornadas.map(j => [j.id, j.nombre, j.horario]));
    });

    function mostrarTabla(titulo, columnas, datos) {
        tablaResultados.style.display = "block";
        tablaDatos.querySelector("thead").innerHTML = `<tr>${columnas.map(col => `<th>${col}</th>`).join("")}</tr>`;
        tablaDatos.querySelector("tbody").innerHTML = datos.map(fila => `<tr>${fila.map(c => `<td>${c}</td>`).join("")}</tr>`).join("");
    }

    const ctx1 = document.getElementById("graficoUsuarios").getContext("2d");
    new Chart(ctx1, {
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

    const ctx2 = document.getElementById("graficoOpiniones").getContext("2d");
    new Chart(ctx2, {
        type: "pie",
        data: {
            labels: opiniones.map(o => o.titulo),
            datasets: [{
                label: "Opiniones",
                data: [opiniones.length, 1],
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

    notificaciones.forEach(notif => {
        const li = document.createElement("li");
        li.className = "list-group-item";
        li.textContent = notif;
        document.getElementById("listaNotificaciones").appendChild(li);
    });

    mensajes.forEach(msg => {
        const li = document.createElement("li");
        li.className = "list-group-item";
        li.textContent = msg;
        document.getElementById("listaMensajes").appendChild(li);
    });
});