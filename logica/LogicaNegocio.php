<?php
// LogicaNegocio.php - Capa de lógica de negocio: Reglas académicas y validaciones. Puente entre Presentación y Datos.
// NO maneja vistas (HTML/HTTP) ni conexiones SQL directas. Solo llama a AccesoDatos para operaciones puras.
// Cada método aplica reglas específicas (e.g., umbrales académicos) y coordina CRUD.

require_once '../datos/Entidad.php';      // Estructuras de entidades (Usuario, Estudiante, Solicitud)
require_once '../datos/AccesoDatos.php';  // Capa inferior: Solo SQL y mapeo de BD

class LogicaNegocio {
    private $datos;  // Instancia de AccesoDatos para llamadas a BD

    public function __construct() {
        $this->datos = new AccesoDatos();  // Inicializa conexión a BD (separación: no SQL aquí)
    }

    /**
     * Regla de Negocio 1: Validar credenciales para login.
     * - Llama a Datos para verificar existencia en BD (separación: no SQL aquí).
     * - Regla simple: Si el usuario existe con password coincidente, retorna true.
     * - Razón académica: Autenticación como prerrequisito para acceso (en prod, agregar hashing y roles).
     * @param string $username
     * @param string $password
     * @return bool True si válido
     */
    public function validarCredenciales($username, $password) {
        $usuario = $this->datos->verificarUsuario($username, $password);
        return $usuario !== null;  // Regla: Existencia confirma validez (demo simple; en prod, hashear).
    }

    /**
     * Regla de Negocio 2: Procesar postulación con validación de entidades.
     * - Obtiene entidad Estudiante de Datos (solo datos crudos, sin lógica aquí).
     * - Aplica reglas académicas: Promedio >= 7.0 Y matricula_activa = TRUE (umbral estándar para becas).
     *   - Razón: Demuestra validación de entidad (Estudiante) basada en atributos BD.
     *   - Si falla, estado = 'RECHAZADA' (regla de negocio: rechazo automático por no cumplir prerrequisitos).
     *   - Si pasa, estado = 'EN EVALUACIÓN' (avanza a revisión manual).
     * - Llama a Datos solo para guardar (separación: no SQL ni vistas aquí).
     * - Retorna mensaje de éxito/error para Presentación.
     * @param array $datos (incluye 'estudiante_id' y 'datos_socioeconomicos')
     * @return string Mensaje de resultado
     */
    public function procesarPostulacion($datos) {
        // Validación de datos requeridos
        $campos_requeridos = ['estudiante_id', 'ingreso_familiar', 'num_familiares', 'tipo_beca'];
        foreach ($campos_requeridos as $campo) {
            if (!isset($datos[$campo])) {
                return "Error: Campo requerido '$campo' faltante.";
            }
        }
        // Obtener y validar estudiante
        $estudiante = $this->datos->obtenerEstudiante($datos['estudiante_id']);
        if ($estudiante === null) {
            return "Error: Estudiante no encontrado.";
        }
        // Validaciones de negocio
        $estado = 'PENDIENTE';
        // Validación según tipo de beca
        switch ($datos['tipo_beca']) {
            case 'ACADEMICA':
                if ($estudiante->promedio < 8.5) {
                    $estado = 'RECHAZADA';
                }
                break;
            case 'SOCIOECONOMICA':
                // Aquí podrías agregar lógica específica para becas socioeconómicas
                // Por ejemplo, validar el ingreso familiar
                break;
            case 'DEPORTIVA':
                // Aquí podrías agregar lógica específica para becas deportivas
                break;
            default:
                return "Error: Tipo de beca no válido.";
        }
        // Validaciones generales
        if (!$estudiante->matricula_activa) {
            $estado = 'RECHAZADA';
        }
        // Crear objeto Solicitud con todos los campos
        $solicitud = new Solicitud(
            null,
            $datos['estudiante_id'],
            $datos['ingreso_familiar'],
            $datos['num_familiares'],
            isset($datos['discapacidad']) ? $datos['discapacidad'] : false,
            $datos['zona_residencial'],
            $datos['tipo_beca'],
            $estado
        );
        if ($this->datos->guardarSolicitud($solicitud)) {
            return "Solicitud de beca procesada con estado: " . $estado;
        } else {
            return "Error al guardar la solicitud de beca.";
        }
    }

    /**
     * Regla simple para obtener estudiantes (sin filtros complejos; solo llama a Datos).
     * - Razón académica: Lógica coordina la obtención, pero no altera datos crudos aquí (e.g., no ordena en BD).
     * - Usado en dashboard para lista general.
     * @return array<Estudiante> Lista de entidades
     */
    public function obtenerEstudiantes() {
        return $this->datos->obtenerTodosEstudiantes();  // Separación: No SQL ni vistas aquí.
    }

    /**
     * Regla para crear estudiante con validación básica.
     * - Reglas de negocio: Nombre no vacío (mínimo 2 chars), promedio numérico entre 0 y 10 (estándar académico).
     *   - Razón: Evita datos inválidos (e.g., promedio 15 o nombre vacío no tiene sentido en contexto becas).
     *   - Matrícula activa: Default true, pero validada como bool.
     * - Si pasa validación, llama a Datos para INSERT.
     * - Retorna mensaje para Presentación.
     * @param array $datos (nombre, promedio, matricula_activa)
     * @return string Mensaje de resultado
     */
    public function crearEstudiante($datos) {
        if (empty(trim($datos['nombre'])) || strlen(trim($datos['nombre'])) < 2) {
            return "Error: Nombre requerido y mínimo 2 caracteres.";
        }
        if (!is_numeric($datos['promedio']) || $datos['promedio'] < 0 || $datos['promedio'] > 10) {
            return "Error: Promedio debe ser numérico entre 0 y 10.";
        }
        $matricula_activa = isset($datos['matricula_activa']) && $datos['matricula_activa'] === true;
        if (!is_bool($matricula_activa)) {
            return "Error: Matrícula activa debe ser true/false.";
        }

        $estudiante = new Estudiante(null, trim($datos['nombre']), (float) $datos['promedio'], $matricula_activa);
        if ($this->datos->agregarEstudiante($estudiante)) {
            return "Estudiante agregado exitosamente.";
        } else {
            return "Error al agregar estudiante (verifica BD).";
        }
    }

    /**
     * Obtener solicitudes (sin reglas complejas; solo para display en dashboard).
     * - Razón: Proporciona overview reciente; orden por fecha en Datos.
     * @return array Array asociativo de solicitudes
     */
    public function obtenerSolicitudes() {
        return $this->datos->obtenerTodasSolicitudes();  // Separación: Lógica no filtra aquí.
    }

    /**
     * Obtener detalles de una solicitud (sin filtros; solo para display detallado).
     * - Razón académica: Lógica coordina el join (estudiante + solicitud), pero no altera datos.
     * - Usado en ver_solicitud.php para mostrar socioeconómicos completos.
     * @param int $id ID de solicitud
     * @return array|null Datos detallados (incluye estudiante)
     */
    public function obtenerSolicitudDetallada($id) {
        return $this->datos->obtenerSolicitudPorId($id);  // Separación: No SQL aquí.
    }

    /**
     * Obtener solicitudes de un estudiante específico (filtro simple en PHP).
     * - Regla mínima: Solo las solicitudes donde estudiante_id coincide.
     * - Razón: Para detalles de estudiante (ver_estudiante.php); evita query extra en Datos.
     * @param int $estudiante_id
     * @return array Solicitudes filtradas
     */
    public function obtenerSolicitudesPorEstudiante($estudiante_id) {
        $todas = $this->obtenerSolicitudes();
        return array_filter($todas, function($sol) use ($estudiante_id) {
            return (int) $sol->estudiante_id === $estudiante_id;
        });
    }

    /**
     * Obtener un estudiante específico (reutilizado para detalles).
     * - Sin reglas; solo obtención.
     * - Razón: Puente directo a Datos para vistas detalladas.
     * @param int $id
     * @return Estudiante|null
     */
    public function obtenerEstudiante($id) {
        return $this->datos->obtenerEstudiante($id);  // Separación: No validaciones aquí.
    }

    /**
     * Obtener usuarios (sin reglas; para display en dashboard).
     * - Razón: Lista simple de admins/usuarios; excluye password por seguridad (regla de negocio básica).
     * @return array<Usuario> Lista sin passwords
     */
    public function obtenerUsuarios() {
        return $this->datos->obtenerTodosUsuarios();  // Separación: No SQL aquí.
    }
}
?>
