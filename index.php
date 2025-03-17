<?php
require_once 'conf/db_connection.php';

// Obtener datos para los selects
$bodegas = obtenerBodegas();
$monedas = obtenerMonedas();
$materiales = obtenerMateriales();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Registro de Productos</title>
    <link rel="stylesheet" href="css/styles.css">
</head>

<body>
    <div class="container">
        <h1>Formulario de Producto</h1>

        <div id="successMessage" class="success-message"></div>

        <form id="productoForm" method="post">
            <div class="form-row">
                <div class="form-col">
                    <div class="form-group">
                        <label for="codigo">Código</label>
                        <input type="text" id="codigo" name="codigo">
                    </div>
                </div>
                <div class="form-col">
                    <div class="form-group">
                        <label for="nombre">Nombre</label>
                        <input type="text" id="nombre" name="nombre">
                    </div>
                </div>
            </div>

            <div class="form-row">
                <div class="form-col">
                    <div class="form-group">
                        <label for="bodega">Bodega</label>
                        <select id="bodega" name="bodega">
                            <option value=""></option>
                            <?php foreach ($bodegas as $bodega): ?>
                                <option value="<?php echo $bodega['id']; ?>"><?php echo $bodega['nombre']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="form-col">
                    <div class="form-group">
                        <label for="sucursal">Sucursal</label>
                        <select id="sucursal" name="sucursal">
                            <option value=""></option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-row">
                <div class="form-col">
                    <div class="form-group">
                        <label for="moneda">Moneda</label>
                        <select id="moneda" name="moneda">
                            <option value=""></option>
                            <?php foreach ($monedas as $moneda): ?>
                                <option value="<?php echo $moneda['id']; ?>"><?php echo $moneda['nombre']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="form-col">
                    <div class="form-group">
                        <label for="precio">Precio</label>
                        <input type="text" id="precio" name="precio">
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label>Material del Producto</label>
                <div class="checkbox-group">
                    <?php foreach ($materiales as $material): ?>
                        <label>
                            <input type="checkbox" name="materiales[]" value="<?php echo $material['id']; ?>">
                            <?php echo $material['nombre']; ?>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="form-group">
                <label for="descripcion">Descripción</label>
                <textarea id="descripcion" name="descripcion"></textarea>
            </div>

            <button type="submit" class="btn-guardar">Guardar Producto</button>
        </form>
    </div>

    <script src="js/validations.js"></script>
    <script src="js/ajax.js"></script>
</body>

</html>