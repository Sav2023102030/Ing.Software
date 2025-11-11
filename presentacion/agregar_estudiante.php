<?php
require_once 'auth.php';
require_once '../logica/LogicaNegocio.php';

$logica = new LogicaNegocio();
$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $datos = [
        'nombre' => $_POST['nombre'] ?? '',
        'promedio' => (float) ($_POST['promedio'] ?? 0),
        'matricula_activa' => isset($_POST['matricula_activa'])
    ];
    $mensaje = $logica->crearEstudiante($datos);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Estudiante</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <div class="container py-4">
        <h1 class="mb-4">Agregar Nuevo Estudiante</h1>

        <?php if ($mensaje): ?>
            <div class="alert <?php echo strpos($mensaje, 'Error') !== false ? 'alert-danger' : 'alert-success'; ?>">
                <?php echo htmlspecialchars($mensaje); ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="mb-4">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre:</label>
                <input type="text" id="nombre" name="nombre" class="form-control" required placeholder="Ej. Pedro Sánchez">
            </div>

            <div class="mb-3">
                <label for="promedio" class="form-label">Promedio (0-20):</label>
                <input type="number" id="promedio" name="promedio" class="form-control" step="0.01" min="0" max="20" required placeholder="Ej. 8.5">
            </div>

            <div class="form-check mb-3">
                <input type="checkbox" id="matricula_activa" name="matricula_activa" value="1" class="form-check-input" checked>
                <label class="form-check-label" for="matricula_activa">Matrícula Activa</label>
            </div>

            <button type="submit" class="btn btn-primary">Agregar Estudiante</button>
        </form>

        <a href="dashboard.php" class="btn btn-outline-secondary me-2">Volver al Dashboard</a>
        <a href="logout.php" class="btn btn-outline-danger">Cerrar Sesión</a>
    </div>

</body>
</html>
