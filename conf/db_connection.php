<?php
require_once 'config.php';

function conectarDB()
{
    try {
        $conn = new PDO("pgsql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
        // Configuracion de PDO para que lance excepciones en errores
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    } catch (PDOException $e) {
        die("Error de conexión: " . $e->getMessage());
    }
}

// Función para verificar si un código de producto ya existe
function existeCodigoProducto($codigo)
{
    try {
        $conn = conectarDB();
        $stmt = $conn->prepare("SELECT COUNT(*) FROM productos WHERE codigo = :codigo");
        $stmt->bindParam(':codigo', $codigo);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    } catch (PDOException $e) {
        die("Error al verificar código: " . $e->getMessage());
    }
}

// Función para obtener todas las bodegas
function obtenerBodegas()
{
    try {
        $conn = conectarDB();
        $stmt = $conn->query("SELECT id, nombre FROM bodegas ORDER BY nombre");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("Error al obtener bodegas: " . $e->getMessage());
    }
}

// Función para obtener sucursales por bodega
function obtenerSucursalesPorBodega($bodega_id)
{
    try {
        $conn = conectarDB();
        $stmt = $conn->prepare("SELECT id, nombre FROM sucursales WHERE bodega_id = :bodega_id ORDER BY nombre");
        $stmt->bindParam(':bodega_id', $bodega_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("Error al obtener sucursales: " . $e->getMessage());
    }
}

// Función para obtener todas las monedas
function obtenerMonedas()
{
    try {
        $conn = conectarDB();
        $stmt = $conn->query("SELECT id, nombre, codigo FROM monedas ORDER BY nombre");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("Error al obtener monedas: " . $e->getMessage());
    }
}

// Función para obtener todos los materiales
function obtenerMateriales()
{
    try {
        $conn = conectarDB();
        $stmt = $conn->query("SELECT id, nombre FROM materiales ORDER BY nombre");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("Error al obtener materiales: " . $e->getMessage());
    }
}

// Función para guardar un producto
function guardarProducto($codigo, $nombre, $bodega_id, $sucursal_id, $moneda_id, $precio, $descripcion, $materiales)
{
    try {
        $conn = conectarDB();
        $conn->beginTransaction();

        // Insertar producto
        $stmt = $conn->prepare("INSERT INTO productos (codigo, nombre, bodega_id, sucursal_id, moneda_id, precio, descripcion) 
                                VALUES (:codigo, :nombre, :bodega_id, :sucursal_id, :moneda_id, :precio, :descripcion) RETURNING id");
        $stmt->bindParam(':codigo', $codigo);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':bodega_id', $bodega_id);
        $stmt->bindParam(':sucursal_id', $sucursal_id);
        $stmt->bindParam(':moneda_id', $moneda_id);
        $stmt->bindParam(':precio', $precio);
        $stmt->bindParam(':descripcion', $descripcion);
        $stmt->execute();

        $producto_id = $stmt->fetchColumn(); // Para obtener el ID insertado

        // Insertar materiales del producto
        $stmt = $conn->prepare("INSERT INTO producto_material (producto_id, material_id) VALUES (:producto_id, :material_id)");
        foreach ($materiales as $material_id) {
            $stmt->bindParam(':producto_id', $producto_id);
            $stmt->bindParam(':material_id', $material_id);
            $stmt->execute();
        }

        $conn->commit();
        return true;
    } catch (PDOException $e) {
        $conn->rollBack();
        return false;
    }
}
