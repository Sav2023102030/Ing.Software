<?php
session_start();
require_once '../logica/LogicaNegocio.php';

$logica = new LogicaNegocio();
$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    if ($logica->validarCredenciales($username, $password)) {
        $_SESSION['logged_in'] = true;
        header('Location: dashboard.php');
        exit;
    } else {
        $mensaje = 'Credenciales inválidas.';
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login - Gestión de Becas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <div class="container d-flex flex-column justify-content-center align-items-center vh-100">
        <div class="card p-4 shadow-sm" style="min-width: 320px; max-width: 400px; width: 100%;">
            <h2 class="text-center mb-4">Iniciar Sesión</h2>

            <?php if ($mensaje): ?>
                <div class="alert alert-danger">
                    <?php echo htmlspecialchars($mensaje); ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label for="username" class="form-label">Usuario:</label>
                    <input type="text" id="username" name="username" class="form-control" required placeholder="Ej. admin">
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Contraseña:</label>
                    <input type="password" id="password" name="password" class="form-control" required placeholder="Ej. pass123">
                </div>

                <button type="submit" class="btn btn-primary w-100">Iniciar Sesión</button>
            </form>

            <p class="text-muted mt-3 text-center">
                <small>Usuario de prueba: <strong>admin</strong> / <strong>pass123</strong></small>
            </p>
        </div>
    </div>

</body>
</html>
