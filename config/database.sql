-- Crear base de datos
CREATE DATABASE IF NOT EXISTS becas_db;
USE becas_db;

-- Tabla Usuario (para login simple)
DROP TABLE IF EXISTS Usuario;
CREATE TABLE Usuario (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL  -- En producción, hashear con password_hash()
);

-- Tabla Estudiante (entidad clave para validación)
DROP TABLE IF EXISTS Estudiante;
CREATE TABLE Estudiante (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    promedio DECIMAL(4,2) NOT NULL,
    matricula_activa BOOLEAN NOT NULL DEFAULT TRUE
);

-- Tabla Solicitud (registra postulaciones con estado)
DROP TABLE IF EXISTS Solicitud;
CREATE TABLE Solicitud (
    id INT AUTO_INCREMENT PRIMARY KEY,
    estudiante_id INT NOT NULL,
    ingreso_familiar DECIMAL(10,2),
    num_familiares INT,
    discapacidad BOOLEAN DEFAULT FALSE,
    zona_residencial ENUM('URBANA','RURAL','MARGINAL'),
    tipo_beca ENUM('ACADEMICA', 'DEPORTIVA', 'SOCIOECONOMICA') NOT NULL,
    estado ENUM('PENDIENTE', 'EN_EVALUACION', 'APROBADA', 'RECHAZADA') NOT NULL DEFAULT 'PENDIENTE',
    fecha_solicitud TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (estudiante_id) REFERENCES Estudiante(id)
);

-- Datos de prueba
INSERT INTO Usuario (username, password) VALUES ('admin', 'pass123');-- Contraseña plana para demo; hashear en prod.

INSERT INTO Estudiante (nombre, promedio, matricula_activa) VALUES 
('Juan Pérez', 9.50, TRUE),  -- Válido: promedio >= 7.0 y matricula activa
('María López', 10.00, FALSE); -- Inválido: promedio < 7.0 y matricula inactiva

-- Verificar datos
SELECT * FROM Usuario;
SELECT * FROM Estudiante;