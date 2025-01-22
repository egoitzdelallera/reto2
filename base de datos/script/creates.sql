-- Crear tabla Usuarios
CREATE TABLE Usuarios (
    id_usuario INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100) NOT NULL,
    contraseña VARCHAR(255) NOT NULL,
    notificaciones INT DEFAULT 0,
    tipo ENUM('Administrador', 'Operario', 'Tecnico') NOT NULL
);

-- Crear tabla Aulas
CREATE TABLE Aulas (
    id_aula INT PRIMARY KEY AUTO_INCREMENT,
    ubicacion VARCHAR(100) NOT NULL,
    id_responsable INT
    
);

-- Crear tabla Maquinas
CREATE TABLE Maquinas (
    id_maquina INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    foto VARCHAR(255),
    id_aula INT,
    FOREIGN KEY (id_aula) REFERENCES Aulas(id_aula)
);


-- Crear tabla Incidencias
CREATE TABLE Incidencias (
    id_incidencia INT PRIMARY KEY AUTO_INCREMENT,
    titulo VARCHAR(100) NOT NULL,
    descripcion TEXT,
    estado ENUM('Pendiente', 'En Proceso', 'Resuelta') DEFAULT 'Pendiente',
    gravedad ENUM('Maquina parada', 'Maquina en marcha', 'Aviso') NOT NULL,
    fecha_ini DATETIME NOT NULL,
    fecha_fin DATETIME,
    foto VARCHAR(255),
    id_maquina INT,
    id_usuario INT,
    FOREIGN KEY (id_maquina) REFERENCES Maquinas(id_maquina),
    FOREIGN KEY (id_usuario) REFERENCES Usuarios(id_usuario)
);

-- Crear tabla MantenimientosPrev
CREATE TABLE MantenimientosPrev (
    id_MantenimientosPrev INT PRIMARY KEY AUTO_INCREMENT,
    titulo VARCHAR(100) NOT NULL,
    descripcion TEXT,
    plazo ENUM('diario', 'semanal', 'mensual', 'anual') NOT NULL,
    hora TIME,
    dia DATE,
    mes DATE,
    id_usuario INT,
    id_maquina INT,
    FOREIGN KEY (id_usuario) REFERENCES Usuarios(id_usuario),
    FOREIGN KEY (id_maquina) REFERENCES Maquinas(id_maquina)

);

-- Crear tabla Historial
CREATE TABLE Historial (
    id_reparacion INT PRIMARY KEY AUTO_INCREMENT,
    id_final INT,
    tipo VARCHAR(50),
    id_incidencia INT,
    id_mantenimiento INT,
    id_usuario INT,
    fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_incidencia) REFERENCES Incidencias(id_incidencia),
    FOREIGN KEY (id_usuario) REFERENCES Usuarios(id_usuario),
    FOREIGN KEY (id_mantenimiento) REFERENCES MantenimientosPrev(id_MantenimientosPrev)
);

-- Índices para mejorar el rendimiento
CREATE INDEX idx_maquinas_aula ON Maquinas(id_aula);
CREATE INDEX idx_incidencias_maquina ON Incidencias(id_maquina);
CREATE INDEX idx_incidencias_usuario ON Incidencias(id_usuario);
CREATE INDEX idx_historial_incidencia ON Historial(id_incidencia);
CREATE INDEX idx_historial_mantenimiento ON Historial(id_mantenimiento);