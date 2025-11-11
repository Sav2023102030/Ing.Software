<?php
require_once 'auth.php';
require_once '../logica/LogicaNegocio.php';

$logica = new LogicaNegocio();

$id = (int) ($_GET['id'] ?? 0);
$estudiante = $logica->obtenerEstudiante($id);
$solicitudes = $logica->obtenerSolicitudesPorEstudiante($id);

if ($estudiante === null) {
    $mensaje = "Estudiante no encontrado.";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalles del Estudiante</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <div class="container py-4">
        <h1 class="mb-4">Detalles del Estudiante ID: <?php echo $id; ?></h1>

        <?php if (isset($mensaje)): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($mensaje); ?></div>
        <?php else: ?>
            <div class="card mb-4">
                <div class="card-body">
                    <p><strong>Nombre:</strong> <?php echo htmlspecialchars($estudiante->nombre); ?></p>
                    <p><strong>Promedio:</strong> <?php echo number_format($estudiante->promedio, 2); ?></p>
                    <p><strong>Matrícula Activa:</strong> 
                        <?php echo $estudiante->matricula_activa ? '<span class="badge bg-success">Sí</span>' : '<span class="badge bg-danger">No</span>'; ?>
                    </p>
                </div>
            </div>

            <h2 class="mb-3">Solicitudes Asociadas</h2>

            <?php if (empty($solicitudes)): ?>
                <p class="text-muted">No hay solicitudes para este estudiante.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead class="table-primary">
                            <tr>
                                <th>ID Solicitud</th>
                                <th>Estado</th>
                                <th>Fecha</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($solicitudes as $sol): ?>
                                <tr>
                                    <td><?php echo $sol->id; ?></td>
                                    <td><?php echo htmlspecialchars($sol->estado); ?></td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($sol->fecha_solicitud)); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <div class="mt-4">
            <a href="dashboard.php" class="btn btn-outline-secondary me-2">Volver al Dashboard</a>
            <a href="logout.php" class="btn btn-outline-danger">Cerrar Sesión</a>
        </div>
    </div>

</body>
</html>
