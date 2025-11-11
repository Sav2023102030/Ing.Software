<?php
require_once 'auth.php';
require_once '../logica/LogicaNegocio.php';

$logica = new LogicaNegocio();
$usuarios = $logica->obtenerUsuarios();
$estudiantes = $logica->obtenerEstudiantes();
$solicitudes = $logica->obtenerSolicitudes();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Gestión de Becas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-4">
        <h1 class="mb-3">Dashboard de Postulaciones</h1>
        <p class="text-muted">Resumen general del sistema de becas. Accede a acciones rápidas o revisa información detallada.</p>

        <div class="mb-4">
            <a href="agregar_estudiante.php" class="btn btn-primary me-2">Agregar Estudiante</a>
            <a href="formulario.php" class="btn btn-success me-2">Postular a Beca</a>
            <a href="logout.php" class="btn btn-danger">Cerrar Sesión</a>
        </div>

        <!-- Estudiantes -->
        <h2 class="mt-4">Estudiantes Registrados</h2>
        <?php if (empty($estudiantes)): ?>
            <p class="fst-italic text-muted">No hay estudiantes registrados.</p>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle">
                    <thead class="table-primary">
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Promedio</th>
                            <th>Matrícula Activa</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($estudiantes as $est): ?>
                            <tr>
                                <td><?php echo $est->id; ?></td>
                                <td><?php echo htmlspecialchars($est->nombre); ?></td>
                                <td><?php echo number_format($est->promedio, 2); ?></td>
                                <td><?php echo $est->matricula_activa ? 'Sí' : 'No'; ?></td>
                                <td>
                                    <a href="ver_estudiante.php?id=<?php echo $est->id; ?>" class="btn btn-sm btn-outline-info">Ver</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>

        <!-- Solicitudes -->
        <h2 class="mt-5">Solicitudes de Beca</h2>
        <?php if (empty($solicitudes)): ?>
            <p class="fst-italic text-muted">No hay solicitudes registradas.</p>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle">
                    <thead class="table-primary">
                        <tr>
                            <th>ID</th>
                            <th>Estudiante</th>
                            <th>Tipo de Beca</th>
                            <th>Estado</th>
                            <th>Fecha</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($solicitudes as $sol): ?>
                            <tr>
                                <td><?php echo $sol->id; ?></td>
                                <td><?php echo $sol->estudiante_nombre ?? 'ID ' . $sol->estudiante_id; ?></td>
                                <td><?php echo $sol->tipo_beca; ?></td>
                                <td><strong><?php echo $sol->estado; ?></strong></td>
                                <td><?php echo date('d/m/Y H:i', strtotime($sol->fecha_solicitud)); ?></td>
                                <td>
                                    <a href="ver_solicitud.php?id=<?php echo $sol->id; ?>" class="btn btn-sm btn-outline-info">Ver</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>

        <p class="text-muted mt-4"><small>Última actualización: <?php echo date('d/m/Y H:i'); ?></small></p>
    </div>
</body>
</html>
