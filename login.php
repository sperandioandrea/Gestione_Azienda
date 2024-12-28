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


<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f3f4f6;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        h1 {
            font-size: 24px;
            margin-bottom: 20px;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            text-align: left;
            margin-bottom: 8px;
            font-weight: bold;
        }

        input {
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
        }

        button {
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        .error {
            color: red;
            font-size: 14px;
            margin-bottom: 15px;
        }

        p {
            margin-top: 15px;
            font-size: 14px;
        }

        a {
            color: #007bff;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        .logo {
            margin-bottom: 20px;
            max-width: 150px;
            height: auto;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <img src="IMG/logo.png" alt="Logo" class="logo">
        <h1>Login</h1>
        <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
        <form method="POST" action="">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <button type="submit">Login</button>
        </form>
        <p>Non hai ancora effettuato la registrazione? <a href="registrazione.php">Registrati</a></p>
    </div>
</body>
</html>
