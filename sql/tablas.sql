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

CREATE TABLE IF NOT EXISTS Fotografias (
    `id_fotografia` int NOT NULL AUTO_INCREMENT,
    `id_habitacion` int NOT NULL,
    `nombre_archivo` varchar(255) COLLATE utf8mb4_spanish_ci NOT NULL,
    `imagen` longblob NOT NULL,
    PRIMARY KEY (`id_fotografia`),
    FOREIGN KEY (`id_habitacion`) REFERENCES `Habitaciones`(`id_habitacion`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

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
    PRIMARY KEY (`id_reserva`),
    FOREIGN KEY (id_cliente) REFERENCES Usuarios(id_usuario),
    FOREIGN KEY (id_habitacion) REFERENCES Habitaciones(id_habitacion)
);

CREATE TABLE IF NOT EXISTS Logs (
    id_log INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    fecha_hora TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    descripcion TEXT NOT NULL,
    FOREIGN KEY (id_usuario) REFERENCES Usuarios(id_usuario) ON DELETE CASCADE;
);
