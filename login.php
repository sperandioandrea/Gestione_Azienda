<?php
require 'db.php';
session_start();

// Debug per eventuali errori
ini_set('display_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT id, password, mansione FROM utenti WHERE username = :username");
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        // Rigenera la sessione
        session_regenerate_id(true);

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['mansione'] = $user['mansione'];

        // Reindirizzamento in base alla mansione
        switch ($user['mansione']) {
            case 'magazziniere':
                header("Location: dashboard_magazzino.php");
                exit;
            case 'gestore vendite':
                header("Location: dashboard_gestore_vendite.php");
                exit;
            case 'gestore contabilita':
                header("Location: dashboard_contabilita.php");
                exit;
            case 'compratore':
                header("Location: dashboard_compratore.php");
                exit;
            case 'dirigente':
                header("Location: dashboard_dirigente.php");
                exit;
            default:
                // Mansione non valida
                $_SESSION['error'] = "Mansione non valida.";
                header("Location: login.php");
                exit;
        }
    } else {
        // Login fallito
        $error = "Nome utente o password errati.";
    }
}
?>