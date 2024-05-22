CREATE TABLE IF NOT EXISTS Usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    apellidos VARCHAR(50) NOT NULL,
    dni VARCHAR(9) NOT NULL UNIQUE,
    email VARCHAR(50) NOT NULL UNIQUE,
    clave VARCHAR(255) NOT NULL,
    num_tarjeta_credito VARCHAR(16) NOT NULL,
    rol ENUM('Administrador', 'Recepcionista', 'Cliente') NOT NULL
);

CREATE TABLE IF NOT EXISTS Habitaciones (
    id_habitacion INT AUTO_INCREMENT PRIMARY KEY,
    numero VARCHAR(10) NOT NULL UNIQUE,
    capacidad INT NOT NULL,
    precio_por_noche DECIMAL(10, 2) NOT NULL,
    descripcion TEXT NOT NULL
);

CREATE TABLE IF NOT EXISTS Fotografias (
    id_fotografia INT AUTO_INCREMENT PRIMARY KEY,
    id_habitacion INT NOT NULL,
    ruta_foto VARCHAR(255) NOT NULL,
    FOREIGN KEY (id_habitacion) REFERENCES Habitaciones(id_habitacion)
);

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

CREATE TABLE IF NOT EXISTS Logs (
    id_log INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    fecha_hora TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    descripcion TEXT NOT NULL,
    FOREIGN KEY (id_usuario) REFERENCES Usuarios(id_usuario)
);
