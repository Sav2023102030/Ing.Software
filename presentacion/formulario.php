<?php
require_once 'auth.php';
require_once '../logica/LogicaNegocio.php';

$logica = new LogicaNegocio();
$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $datos = [
        'estudiante_id' => (int) ($_POST['estudiante_id'] ?? 0),
        'ingreso_familiar' => (float) ($_POST['ingreso_familiar'] ?? 0),
        'num_familiares' => (int) ($_POST['num_familiares'] ?? 0),
        'discapacidad' => $_POST['discapacidad'] === '1',
        'zona_residencial' => $_POST['zona_residencial'] ?? null,
        'tipo_beca' => $_POST['tipo_beca'] ?? null
    ];
    $mensaje = $logica->procesarPostulacion($datos);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Postulación a Beca</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-4">
        <h1 class="mb-3">Formulario de Postulación a Beca</h1>
        <p class="text-muted">
            Completa los datos para postular a una beca.<br>
            Usa ID de Estudiante de prueba: <strong>1</strong> (válido) o <strong>2</strong> (inválido).
        </p>

        <?php if (!empty($mensaje)): ?>
            <div class="alert <?php echo str_contains($mensaje, 'Error') ? 'alert-danger' : 'alert-success'; ?>">
                <?php echo htmlspecialchars($mensaje); ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="mb-4 needs-validation" novalidate>
            <div class="mb-3">
                <label for="estudiante_id" class="form-label">ID del Estudiante:</label>
                <input type="number" id="estudiante_id" name="estudiante_id" class="form-control" required min="1">
            </div>

            <div class="mb-3">
                <label for="ingreso_familiar" class="form-label">Ingreso Familiar Mensual (S/):</label>
                <input type="number" step="0.01" id="ingreso_familiar" name="ingreso_familiar" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="num_familiares" class="form-label">Número de Familiares:</label>
                <input type="number" id="num_familiares" name="num_familiares" class="form-control" required min="1">
            </div>

            <div class="mb-3">
                <label for="discapacidad" class="form-label">¿Tiene alguna discapacidad?</label>
                <select id="discapacidad" name="discapacidad" class="form-select">
                    <option value="0">No</option>
                    <option value="1">Sí</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="zona_residencial" class="form-label">Zona Residencial:</label>
                <select id="zona_residencial" name="zona_residencial" class="form-select" required>
                    <option value="URBANA">Urbana</option>
                    <option value="RURAL">Rural</option>
                    <option value="MARGINAL">Marginal</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="tipo_beca" class="form-label">Tipo de Beca:</label>
                <select id="tipo_beca" name="tipo_beca" class="form-select" required>
                    <option value="ACADEMICA">Académica</option>
                    <option value="DEPORTIVA">Deportiva</option>
                    <option value="SOCIOECONOMICA">Socioeconómica</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Enviar Solicitud</button>
        </form>

        <a href="dashboard.php" class="btn btn-outline-secondary me-2">Volver al Dashboard</a>
        <a href="logout.php" class="btn btn-outline-danger">Cerrar Sesión</a>
    </div>
</body>
</html>