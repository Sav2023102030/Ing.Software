<?php
// Entidad.php - Define estructuras de datos para las entidades. Solo para mapeo de BD.

//Entidad Usuario: Estructura para credenciales de login.
class Usuario {
    public $id;
    public $username;
    public $password;

    public function __construct($id = null, $username = null, $password = null) {
        $this->id = $id;
        $this->username = $username;
        $this->password = $password;
    }
}

//Entidad Estudiante: Estructura para datos académicos.
class Estudiante {
    public $id;
    public $nombre;
    public $promedio;
    public $matricula_activa;

    public function __construct($id = null, $nombre = null, $promedio = null, $matricula_activa = null) {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->promedio = $promedio;
        $this->matricula_activa = $matricula_activa;
    }
}

//Entidad Solicitud: Estructura para postulaciones.
class Solicitud {
    public $id;
    public $estudiante_id;
    public $ingreso_familiar;
    public $num_familiares;
    public $discapacidad;
    public $zona_residencial;
    public $tipo_beca;
    public $estado;
    public $fecha_solicitud;

    public function __construct(
        $id = null,
        $estudiante_id = null,
        $ingreso_familiar = null,
        $num_familiares = null,
        $discapacidad = false,
        $zona_residencial = null,
        $tipo_beca = null,
        $estado = 'PENDIENTE',
        $fecha_solicitud = null
    ) {
        $this->id = $id;
        $this->estudiante_id = $estudiante_id;
        $this->ingreso_familiar = $ingreso_familiar;
        $this->num_familiares = $num_familiares;
        $this->discapacidad = $discapacidad;
        $this->zona_residencial = $zona_residencial;
        $this->tipo_beca = $tipo_beca;
        $this->estado = $estado;
        $this->fecha_solicitud = $fecha_solicitud;
    }
}
?>
