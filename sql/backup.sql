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
(1, 'tia', '', '1', 'tia@void.ugr.es', 'tia', '', 'Administrador'),
(2, 'abuela', '', '2', 'abuela@void.ugr.es', 'abuela', '', 'Administrador'),
(3, 'director', '', '3', 'director@void.ugr.es', 'director', '', 'Recepcionista'),
(4, 'elsuper', '', '4', 'elsuper@void.ugr.es', 'elsuper', '', 'Recepcionista'),
(5, 'mortadelo', '', '5', 'mortadelo@void.ugr.es', 'mortadelo', '', 'Cliente'),
(6, 'filemon', '', '6', 'filemon@void.ugr.es', 'filemon', '', 'Cliente'),
(7, 'bacterio', '', '7', 'bacterio@void.ugr.es', 'bacterio', '', 'Cliente'),
(8, 'ofelia', '', '8', 'ofelia@void.ugr.es', 'ofelia', '', 'Cliente'),
(9, 'irma', '', '9', 'irma@void.ugr.es', 'irma', '', 'Cliente');

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
(101, '101', 2, 25.5, '', 0, 'Operativa'),
(102, '102', 2, 25.5, '', 0, 'Operativa'),
(103, '103', 2, 25.5, '', 1, 'Operativa'),
(104, '104', 2, 25.5, '', 1, 'Operativa'),
(105, '105', 2, 25.5, '', 2, 'Operativa'),
(201, '201', 3, 25.5, '', 2, 'Operativa'),
(202, '202', 3, 25.5, '', 3, 'Operativa'),
(203, '203', 3, 25.5, '', 3, 'Operativa'),
(204, '204', 3, 25.5, '', 4, 'Operativa'),
(301, '301', 4, 25.5, '', 4, 'Operativa'),
(302, '302', 4, 25.5, '', 0, 'Operativa'),
(1, 'Suite presidencial', 4, 25.5, '', 0, 'Operativa'),
(2, 'Suite nupcial', 2, 25.5, '', 0, 'Operativa');

-- Crear la tabla Fotografias
CREATE TABLE IF NOT EXISTS Fotografias (
    `id_fotografia` int NOT NULL AUTO_INCREMENT,
    `id_habitacion` int NOT NULL,
    `nombre_archivo` varchar(255) COLLATE utf8mb4_spanish_ci NOT NULL,
    `imagen` blob NOT NULL,
    PRIMARY KEY (`id_fotografia`),
    FOREIGN KEY (`id_habitacion`) REFERENCES `Habitaciones`(`id_habitacion`) ON DELETE CASCADE
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
