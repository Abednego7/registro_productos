-- Insertar datos de prueba en bodegas
INSERT INTO bodegas (nombre) VALUES 
('Bodega 1'), 
('Bodega 2'), 
('Bodega 3');

-- Insertar datos de prueba en sucursales
INSERT INTO sucursales (nombre, bodega_id) VALUES 
('Sucursal 1', 1), 
('Sucursal 2', 1),
('Sucursal 3', 2), 
('Sucursal 4', 2),
('Sucursal 5', 3);

-- Insertar datos de prueba en monedas
INSERT INTO monedas (nombre, codigo) VALUES 
('DOLAR', 'USD'), 
('EURO', 'EUR'), 
('PESO CHILENO', 'CLP');

-- Insertar datos de prueba en materiales
INSERT INTO materiales (nombre) VALUES 
('Plástico'), 
('Metal'), 
('Madera'), 
('Vidrio'), 
('Textil');