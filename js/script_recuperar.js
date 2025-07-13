document.getElementById('recuperarForm').addEventListener('submit', function (e) {
  e.preventDefault(); // evitar recargar página

  const correo = document.getElementById('correo').value;

  fetch('procesar_recuperacion.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded',
    },
    body: new URLSearchParams({ correo: correo })
  })
    .then(response => response.text())
    .then(data => {
      document.getElementById('respuesta').innerText = data;
    })
    .catch(error => {
      document.getElementById('respuesta').innerText = 'Error al enviar la solicitud.';
      console.error('Error:', error);
    });
});