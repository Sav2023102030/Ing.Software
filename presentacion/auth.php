<?php
// presentacion/auth.php - Utilitario para protección de rutas: Verifica sesión en todas las vistas protegidas.
// NO es una vista; solo manejo HTTP de autenticación. Usar con require_once al inicio de archivos protegidos.

session_start();  // Inicia o reanuda sesión

// Chequeo: Si no hay sesión válida, redirige al login y detiene ejecución
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: index.php');  // Redirige a login (mismo directorio)
    exit;  // Detiene ejecución para evitar mostrar contenido protegido
}

// Opcional: Regenera ID de sesión para seguridad (anti-fixation, pero simple)
if (!isset($_SESSION['regenerated'])) {
    session_regenerate_id(true);
    $_SESSION['regenerated'] = true;
}
?>
