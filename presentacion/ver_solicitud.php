<?php
require_once 'auth.php';
require_once '../logica/LogicaNegocio.php';

$logica = new LogicaNegocio();

$id = (int) ($_GET['id'] ?? 0);
$solicitud = $logica->obtenerSolicitudDetallada($id);

if ($solicitud === null) {
    $mensaje = "Solicitud no encontrada.";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalles de la Solicitud</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <div class="container py-4">
        <h1 class="mb-4">Detalles de la Solicitud ID: <?php echo $id; ?></h1>

        <?php if (isset($mensaje)): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($mensaje); ?></div>
        <?php else: ?>
            <div class="card mb-4">
                <div class="card-body">
                    <p><strong>Estudiante:</strong> <?php echo htmlspecialchars($solicitud->estudiante_nombre); ?></p>
                    <p><strong>Ingreso Familiar:</strong> $<?php echo number_format($solicitud->ingreso_familiar, 2); ?></p>
                    <p><strong>Número de Familiares:</strong> <?php echo $solicitud->num_familiares; ?></p>
                    <p><strong>Discapacidad:</strong> <?php echo $solicitud->discapacidad ? 'Sí' : 'No'; ?></p>
                    <p><strong>Zona Residencial:</strong> <?php echo $solicitud->zona_residencial ?: 'No especificada'; ?></p>
                    <p><strong>Tipo de Beca:</strong> <?php echo $solicitud->tipo_beca ?: 'No especificado'; ?></p>
                    <p><strong>Estado:</strong> <?php echo $solicitud->estado ?: 'Pendiente'; ?></p>
                    <p><strong>Fecha de Solicitud:</strong> <?php echo date('d/m/Y H:i', strtotime($solicitud->fecha_solicitud)); ?></p>
                </div>
            </div>
        <?php endif; ?>

        <div>
            <a href="dashboard.php" class="btn btn-outline-secondary me-2">Volver al Dashboard</a>
            <a href="logout.php" class="btn btn-outline-danger">Cerrar Sesión</a>
        </div>
    </div>

</body>
</html>
