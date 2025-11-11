<?php
// presentacion/logout.php - Manejo de logout: Destruye sesión y redirige a login.
// Solo HTTP; no llama a Lógica ni Datos.

session_start();  // Reanuda sesión para destruirla

// Limpia variables de sesión
$_SESSION = array();
session_destroy();  // Destruye completamente la sesión

// Opcional: Elimina cookie de sesión (para mayor seguridad)
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Redirige al login
header('Location: index.php');
exit;
?>
