<?php
require_once 'conf/db_connection.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validar los datos recibidos del formulario
    $codigo = isset($_POST['codigo']) ? trim($_POST['codigo']) : '';
    $nombre = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';
    $bodega_id = isset($_POST['bodega']) ? intval($_POST['bodega']) : 0;
    $sucursal_id = isset($_POST['sucursal']) ? intval($_POST['sucursal']) : 0;
    $moneda_id = isset($_POST['moneda']) ? intval($_POST['moneda']) : 0;
    $precio = isset($_POST['precio']) ? trim($_POST['precio']) : '';
    $descripcion = isset($_POST['descripcion']) ? trim($_POST['descripcion']) : '';
    $materiales = isset($_POST['materiales']) ? $_POST['materiales'] : [];

    // Validar campos obligatorios en el servidor
    $errores = [];

    if (empty($codigo)) {
        $errores[] = 'El código del producto es obligatorio';
    } elseif (!preg_match('/^(?=.*[a-zA-Z])(?=.*[0-9])[a-zA-Z0-9]{5,15}$/', $codigo)) {
        $errores[] = 'El código del producto debe tener entre 5 y 15 caracteres y contener letras y números';
    } elseif (existeCodigoProducto($codigo)) {
        $errores[] = 'El código del producto ya está registrado';
    }

    if (empty($nombre)) {
        $errores[] = 'El nombre del producto es obligatorio';
    } elseif (strlen($nombre) < 2 || strlen($nombre) > 50) {
        $errores[] = 'El nombre del producto debe tener entre 2 y 50 caracteres';
    }

    if ($bodega_id <= 0) {
        $errores[] = 'Debe seleccionar una bodega';
    }

    if ($sucursal_id <= 0) {
        $errores[] = 'Debe seleccionar una sucursal';
    }

    if ($moneda_id <= 0) {
        $errores[] = 'Debe seleccionar una moneda';
    }

    if (empty($precio)) {
        $errores[] = 'El precio del producto es obligatorio';
    } elseif (!preg_match('/^(?!0\d)\d*(\.\d{1,2})?$/', $precio) || floatval($precio) <= 0) {
        $errores[] = 'El precio debe ser un número positivo con hasta dos decimales';
    }

    if (count($materiales) < 2) {
        $errores[] = 'Debe seleccionar al menos dos materiales';
    }

    if (empty($descripcion)) {
        $errores[] = 'La descripción del producto es obligatoria';
    } elseif (strlen($descripcion) < 10 || strlen($descripcion) > 1000) {
        $errores[] = 'La descripción debe tener entre 10 y 1000 caracteres';
    }

    // Si hay errores, devolver mensaje de error
    if (!empty($errores)) {
        echo json_encode([
            'success' => false,
            'message' => implode('. ', $errores)
        ]);
        exit;
    }

    // Intentar guardar el producto
    $resultado = guardarProducto($codigo, $nombre, $bodega_id, $sucursal_id, $moneda_id, $precio, $descripcion, $materiales);

    if ($resultado) {
        echo json_encode([
            'success' => true,
            'message' => 'Producto guardado correctamente'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Error al guardar el producto en la base de datos'
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Método de solicitud no válido'
    ]);
}
