-- Crear la tabla Usuarios
CREATE TABLE IF NOT EXISTS Usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    apellidos VARCHAR(50) NOT NULL,
    dni VARCHAR(9) NOT NULL UNIQUE,
    email VARCHAR(50) NOT NULL UNIQUE,
    clave VARCHAR(255) NOT NULL,
    num_tarjeta_credito VARCHAR(16) NOT NULL,
    rol ENUM('Administrador', 'Recepcionista', 'Cliente', 'Anonimo') NOT NULL
);

-- Insertar datos en la tabla Usuarios
INSERT INTO `Usuarios` (`id_usuario`, `nombre`, `apellidos`, `dni`, `email`, `clave`, `num_tarjeta_credito`, `rol`) VALUES
(1, 'tia', 'Apellido1', '1', 'tia@void.ugr.es', 'tia', '4242424242424242', 'Administrador'),
(2, 'abuela', 'Apellido2', '2', 'abuela@void.ugr.es', 'abuela', '4242424242424242', 'Administrador'),
(3, 'director', 'Apellido3', '3', 'director@void.ugr.es', 'director', '4242424242424242', 'Recepcionista'),
(4, 'elsuper', 'Apellido4', '4', 'elsuper@void.ugr.es', 'elsuper', '4242424242424242', 'Recepcionista'),
(5, 'mortadelo', 'Apellido5', '5', 'mortadelo@void.ugr.es', 'mortadelo', '4242424242424242', 'Cliente'),
(6, 'filemon', 'Apellido6', '6', 'filemon@void.ugr.es', 'filemon', '4242424242424242', 'Cliente'),
(7, 'bacterio', 'Apellido7', '7', 'bacterio@void.ugr.es', 'bacterio', '4242424242424242', 'Cliente'),
(8, 'ofelia', 'Apellido8', '8', 'ofelia@void.ugr.es', 'ofelia', '4242424242424242', 'Cliente'),
(9, 'irma', 'Apellido9', '9', 'irma@void.ugr.es', 'irma', '4242424242424242', 'Cliente');

-- Crear la tabla Habitaciones
CREATE TABLE IF NOT EXISTS Habitaciones (
    `id_habitacion` int NOT NULL AUTO_INCREMENT,
    `numero` varchar(20) COLLATE utf8mb4_spanish_ci NOT NULL,
    `capacidad` int NOT NULL,
    `precio_por_noche` decimal(10, 2) NOT NULL,
    `descripcion` text COLLATE utf8mb4_spanish_ci NOT NULL,
    `n-imagenes` int NOT NULL,
    `estado` ENUM('Operativa', 'Pendiente', 'Confirmada', 'Mantenimiento') NOT NULL DEFAULT 'Operativa',
    PRIMARY KEY (`id_habitacion`),
    UNIQUE KEY `numero` (`numero`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- Insertar datos en la tabla Habitaciones
INSERT INTO `Habitaciones` (`id_habitacion`, `numero`, `capacidad`, `precio_por_noche`, `descripcion`, `n-imagenes`, `estado`) VALUES
(101, '101', 2, 25.5, 'Habitación doble con vistas al mar', 0, 'Operativa'),
(102, '102', 2, 25.5, 'Habitación doble con balcón', 0, 'Operativa'),
(103, '103', 2, 25.5, 'Habitación doble con terraza', 1, 'Operativa'),
(104, '104', 2, 25.5, 'Habitación doble estándar', 1, 'Operativa'),
(105, '105', 2, 25.5, 'Habitación doble con jacuzzi', 2, 'Operativa'),
(201, '201', 3, 25.5, 'Habitación triple con vistas al mar', 2, 'Operativa'),
(202, '202', 3, 25.5, 'Habitación triple con balcón', 3, 'Operativa'),
(203, '203', 3, 25.5, 'Habitación triple con terraza', 3, 'Operativa'),
(204, '204', 3, 25.5, 'Habitación triple estándar', 4, 'Operativa'),
(301, '301', 4, 25.5, 'Habitación cuádruple con vistas al mar', 4, 'Operativa'),
(302, '302', 4, 25.5, 'Habitación cuádruple con balcón', 0, 'Operativa'),
(1, 'Suite presidencial', 4, 25.5, 'Suite presidencial con vistas panorámicas', 0, 'Operativa'),
(2, 'Suite nupcial', 2, 25.5, 'Suite nupcial con jacuzzi y vistas al mar', 0, 'Operativa');

-- Crear la tabla Fotografias
CREATE TABLE IF NOT EXISTS Fotografias (
    `id_fotografia` int NOT NULL AUTO_INCREMENT,
    `id_habitacion` int NOT NULL,
    `nombre_archivo` varchar(255) COLLATE utf8mb4_spanish_ci NOT NULL,
    `imagen` longblob NOT NULL,
    PRIMARY KEY (`id_fotografia`),
    FOREIGN KEY (`id_habitacion`) REFERENCES `Habitaciones`(`id_habitacion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- Crear la tabla Reservas
CREATE TABLE IF NOT EXISTS Reservas (
    id_reserva INT AUTO_INCREMENT PRIMARY KEY,
    id_cliente INT NOT NULL,
    id_habitacion INT NOT NULL,
    num_personas INT NOT NULL,
    comentarios TEXT,
    dia_entrada DATE NOT NULL,
    dia_salida DATE NOT NULL,
    estado ENUM('Pendiente', 'Confirmada', 'Mantenimiento') NOT NULL,
    marca_tiempo TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_cliente) REFERENCES Usuarios(id_usuario),
    FOREIGN KEY (id_habitacion) REFERENCES Habitaciones(id_habitacion)
);

-- Crear la tabla Logs
CREATE TABLE IF NOT EXISTS Logs (
    id_log INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    fecha_hora TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    descripcion TEXT NOT NULL,
    FOREIGN KEY (id_usuario) REFERENCES Usuarios(id_usuario)
);

ALTER TABLE Logs
DROP FOREIGN KEY Logs_ibfk_1,
ADD FOREIGN KEY (id_usuario) REFERENCES Usuarios(id_usuario) ON DELETE CASCADE;