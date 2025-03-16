<?php
require_once 'conf/db_connection.php';

header('Content-Type: application/json');

if (isset($_POST['codigo'])) {
    $codigo = trim($_POST['codigo']);
    $existe = existeCodigoProducto($codigo);

    echo json_encode(['existe' => $existe]);
} else {
    echo json_encode(['error' => 'No se proporcionó un código']);
}
