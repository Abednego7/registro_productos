// Validaciones para el formulario de producto

// Validar el formulario completo
function validarFormulario() {
    let isValid = true;

    // Validar código de producto
    if (!validarCodigo()) {
        isValid = false;
    }

    // Validar nombre de producto
    if (!validarNombre()) {
        isValid = false;
    }

    // Validar bodega
    if (!validarBodega()) {
        isValid = false;
    }

    // Validar sucursal si hay bodega seleccionada
    if (document.getElementById('bodega').value !== '') {
        if (!validarSucursal()) {
            isValid = false;
        }
    }

    // Validar moneda
    if (!validarMoneda()) {
        isValid = false;
    }

    // Validar precio
    if (!validarPrecio()) {
        isValid = false;
    }

    // Validar materiales
    if (!validarMateriales()) {
        isValid = false;
    }

    // Validar descripción
    if (!validarDescripcion()) {
        isValid = false;
    }

    return isValid;
}

// Validar código de producto
function validarCodigo() {
    const codigo = document.getElementById('codigo').value.trim();
    const regex = /^(?=.*[a-zA-Z])(?=.*[0-9])[a-zA-Z0-9]{5,15}$/;

    if (codigo === '') {
        alert('El código del producto no puede estar en blanco.');
        return false;
    }

    if (!regex.test(codigo)) {
        if (codigo.length < 5 || codigo.length > 15) {
            alert('El código del producto debe tener entre 5 y 15 caracteres.');
        } else {
            alert('El código del producto debe contener letras y números');
        }
        return false;
    }

    return true;
}

// Validar nombre de producto
function validarNombre() {
    const nombre = document.getElementById('nombre').value.trim();

    if (nombre === '') {
        alert('El nombre del producto no puede estar en blanco.');
        return false;
    }

    if (nombre.length < 2 || nombre.length > 50) {
        alert('El nombre del producto debe tener entre 2 y 50 caracteres.');
        return false;
    }

    return true;
}

// Validar bodega
function validarBodega() {
    const bodega = document.getElementById('bodega').value;

    if (bodega === '' || bodega === '0') {
        alert('Debe seleccionar una bodega.');
        return false;
    }

    return true;
}

// Validar sucursal
function validarSucursal() {
    const sucursal = document.getElementById('sucursal').value;

    if (sucursal === '' || sucursal === '0') {
        alert('Debe seleccionar una sucursal para la bodega seleccionada.');
        return false;
    }

    return true;
}

// Validar moneda
function validarMoneda() {
    const moneda = document.getElementById('moneda').value;

    if (moneda === '' || moneda === '0') {
        alert('Debe seleccionar una moneda para el producto.');
        return false;
    }

    return true;
}

// Validar precio
function validarPrecio() {
    const precio = document.getElementById('precio').value.trim();
    const regex = /^(?!0\d)\d*(\.\d{1,2})?$/;

    if (precio === '') {
        alert('El precio del producto no puede estar en blanco.');
        return false;
    }

    if (!regex.test(precio) || parseFloat(precio) <= 0) {
        alert('El precio del producto debe ser un número positivo con hasta dos decimales.');
        return false;
    }

    return true;
}

// Validar materiales del producto
function validarMateriales() {
    const checkboxes = document.querySelectorAll('input[name="materiales[]"]:checked');

    if (checkboxes.length < 2) {
        alert('Debe seleccionar al menos dos materiales para el producto.');
        return false;
    }

    return true;
}

// Validar descripción del producto
function validarDescripcion() {
    const descripcion = document.getElementById('descripcion').value.trim();

    if (descripcion === '') {
        alert('La descripción del producto no puede estar en blanco.');
        return false;
    }

    if (descripcion.length < 10 || descripcion.length > 1000) {
        alert('La descripción del producto debe tener entre 10 y 1000 caracteres.');
        return false;
    }

    return true;
}

// Función para verificar si el código ya existe en la base de datos
function verificarCodigoUnico(codigo, callback) {
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'verificar_codigo.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function () {
        if (this.status === 200) {
            const response = JSON.parse(this.responseText);
            callback(response.existe);
        }
    };

    xhr.send('codigo=' + encodeURIComponent(codigo));
}

// Función para cargar las sucursales según la bodega seleccionada
function cargarSucursales() {
    const bodegaId = document.getElementById('bodega').value;
    const sucursalSelect = document.getElementById('sucursal');

    // Limpiar opciones actuales
    sucursalSelect.innerHTML = '<option value=""></option>';

    if (bodegaId === '') {
        return;
    }

    const xhr = new XMLHttpRequest();
    xhr.open('GET', 'obtener_sucursales.php?bodega_id=' + bodegaId, true);

    xhr.onload = function () {
        if (this.status === 200) {
            const sucursales = JSON.parse(this.responseText);

            sucursales.forEach(function (sucursal) {
                const option = document.createElement('option');
                option.value = sucursal.id;
                option.textContent = sucursal.nombre;
                sucursalSelect.appendChild(option);
            });
        }
    };

    xhr.send();
}

// Eventos de escucha cuando el DOM está cargado
document.addEventListener('DOMContentLoaded', function () {
    // Evento para el cambio de bodega
    document.getElementById('bodega').addEventListener('change', cargarSucursales);

    // Inicializar contadores de caracteres
    const codigoInput = document.getElementById('codigo');
    const nombreInput = document.getElementById('nombre');
    const descripcionInput = document.getElementById('descripcion');

    // Manejar envío del formulario
    document.getElementById('productoForm').addEventListener('submit', function (e) {
        e.preventDefault();

        if (validarFormulario()) {
            const codigo = document.getElementById('codigo').value.trim();

            verificarCodigoUnico(codigo, function (existe) {
                if (existe) {
                    alert('El código del producto ya está registrado.');
                } else {
                    enviarFormulario();
                }
            });
        }
    });
});