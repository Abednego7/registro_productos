<?php
require_once 'conf/db_connection.php';

header('Content-Type: application/json');

if (isset($_GET['bodega_id']) && !empty($_GET['bodega_id'])) {
    $bodega_id = intval($_GET['bodega_id']);
    $sucursales = obtenerSucursalesPorBodega($bodega_id);

    echo json_encode($sucursales);
} else {
    echo json_encode([]);
}
