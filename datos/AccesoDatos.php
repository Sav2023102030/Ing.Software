<?php
// AccesoDatos.php - Capa exclusiva de acceso a datos: CRUD con MySQLi. No contiene reglas de negocio.

require_once 'Entidad.php';

class AccesoDatos {
    private $conexion;

    public function __construct() {
        $this->conexion = new mysqli('localhost', 'root', '', 'becas_db');
        if ($this->conexion->connect_error) {
            die("Error de conexión: " . $this->conexion->connect_error);
        }
    }

    // Verifica credenciales de usuario
    public function verificarUsuario($username, $password) {
        $stmt = $this->conexion->prepare("SELECT id, username, password FROM Usuario WHERE username = ? AND password = ?");
        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            return new Usuario($row['id'], $row['username'], $row['password']);
        }
        return null;
    }

    // Obtiene estudiante por ID
    public function obtenerEstudiante($id) {
        $stmt = $this->conexion->prepare("SELECT id, nombre, promedio, matricula_activa FROM Estudiante WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            return new Estudiante($row['id'], $row['nombre'], $row['promedio'], $row['matricula_activa']);
        }
        return null;
    }

    // Guarda solicitud en tabla Solicitud
    public function guardarSolicitud($solicitud) {
        $stmt = $this->conexion->prepare(
            "INSERT INTO Solicitud (estudiante_id, ingreso_familiar, num_familiares, 
            discapacidad, zona_residencial, tipo_beca, estado) 
            VALUES (?, ?, ?, ?, ?, ?, ?)"
        );
        $stmt->bind_param(
            "idiisss",
            $solicitud->estudiante_id,
            $solicitud->ingreso_familiar,
            $solicitud->num_familiares,
            $solicitud->discapacidad,
            $solicitud->zona_residencial,
            $solicitud->tipo_beca,
            $solicitud->estado
        );
        return $stmt->execute();
    }

    // Obtiene todas las solicitudes con detalles del estudiante
    public function obtenerTodasSolicitudes() {
        $result = $this->conexion->query(
            "SELECT s.*, e.nombre as estudiante_nombre 
            FROM Solicitud s 
            JOIN Estudiante e ON s.estudiante_id = e.id 
            ORDER BY s.fecha_solicitud DESC"
        );
        $solicitudes = [];
        while ($row = $result->fetch_assoc()) {
            $solicitud = new Solicitud(
                $row['id'],
                $row['estudiante_id'],
                $row['ingreso_familiar'],
                $row['num_familiares'],
                $row['discapacidad'],
                $row['zona_residencial'],
                $row['tipo_beca'],
                $row['estado'],
                $row['fecha_solicitud']
            );
            $solicitud->estudiante_nombre = $row['estudiante_nombre'];
            $solicitudes[] = $solicitud;
        }
        return $solicitudes;
    }

    // Obtiene una solicitud por ID con detalles del estudiante
    public function obtenerSolicitudPorId($id) {
        $stmt = $this->conexion->prepare(
            "SELECT s.*, e.nombre as estudiante_nombre 
            FROM Solicitud s 
            JOIN Estudiante e ON s.estudiante_id = e.id 
            WHERE s.id = ?"
        );
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $solicitud = new Solicitud(
                $row['id'],
                $row['estudiante_id'],
                $row['ingreso_familiar'],
                $row['num_familiares'],
                $row['discapacidad'],
                $row['zona_residencial'],
                $row['tipo_beca'],
                $row['estado'],
                $row['fecha_solicitud']
            );
            $solicitud->estudiante_nombre = $row['estudiante_nombre'];
            return $solicitud;
        }
        return null;
    }

    // Obtiene todos los estudiantes
    public function obtenerTodosEstudiantes() {
        $result = $this->conexion->query("SELECT id, nombre, promedio, matricula_activa FROM Estudiante ORDER BY id");
        $estudiantes = [];
        while ($row = $result->fetch_assoc()) {
            $estudiantes[] = new Estudiante($row['id'], $row['nombre'], $row['promedio'], $row['matricula_activa']);
        }
        return $estudiantes;
    }

    // Agrega estudiante
    public function agregarEstudiante($estudiante) {
        $stmt = $this->conexion->prepare("INSERT INTO Estudiante (nombre, promedio, matricula_activa) VALUES (?, ?, ?)");
        $stmt->bind_param("sdi", $estudiante->nombre, $estudiante->promedio, $estudiante->matricula_activa);
        return $stmt->execute();
    }

    // Obtiene todos los usuarios (sin password)
    public function obtenerTodosUsuarios() {
        $result = $this->conexion->query("SELECT id, username FROM Usuario");
        $usuarios = [];
        while ($row = $result->fetch_assoc()) {
            $usuarios[] = new Usuario($row['id'], $row['username'], null);
        }
        return $usuarios;
    }

    public function __destruct() {
        $this->conexion->close();
    }
}
?>
