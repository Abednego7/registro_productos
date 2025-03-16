// Función para enviar el formulario usando AJAX
function enviarFormulario() {
    // Obtener datos del formulario
    const formData = new FormData(document.getElementById('productoForm'));

    // Crear el objeto XMLHttpRequest
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'guardar_producto.php', true);

    // Manejar la respuesta
    xhr.onload = function () {
        if (this.status === 200) {
            try {
                const response = JSON.parse(this.responseText);

                if (response.success) {
                    // Mostrar mensaje de éxito
                    const successMessage = document.getElementById('successMessage');
                    successMessage.style.display = 'block';
                    successMessage.textContent = 'Producto guardado exitosamente.';

                    // Limpiar el formulario
                    document.getElementById('productoForm').reset();

                    // Ocultar mensaje después de unos segundos
                    setTimeout(function () {
                        successMessage.style.display = 'none';
                    }, 5000);
                } else {
                    // Mostrar mensaje de error
                    alert('Error al guardar el producto: ' + response.message);
                }
            } catch (e) {
                alert('Ha ocurrido un error en el servidor.');
            }
        } else {
            alert('Ha ocurrido un error en la comunicación con el servidor.');
        }
    };

    // Manejar errores de red
    xhr.onerror = function () {
        alert('Error de red al intentar guardar el producto.');
    };

    // Enviar la solicitud
    xhr.send(formData);
}