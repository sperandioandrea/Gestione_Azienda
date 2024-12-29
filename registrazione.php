<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tipo_utente = $_POST['tipo_utente'];
    $nome = $_POST['nome'];
    $cognome = $_POST['cognome'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    if ($tipo_utente === 'dipendente') {
        $mansione = $_POST['mansione'];
        $stmt = $pdo->prepare("INSERT INTO utenti (nome, cognome, email, username, password, mansione) VALUES (:nome, :cognome, :email, :username, :password, :mansione)");
        $stmt->execute([
            'nome' => $nome,
            'cognome' => $cognome,
            'email' => $email,
            'username' => $username,
            'password' => $password,
            'mansione' => $mansione
        ]);
    } elseif ($tipo_utente === 'cliente') {
        $stmt = $pdo->prepare("INSERT INTO utenti (nome, cognome, email, username, password, mansione) VALUES (:nome, :cognome, :email, :username, :password, 'compratore')");
        $stmt->execute([
            'nome' => $nome,
            'cognome' => $cognome,
            'email' => $email,
            'username' => $username,
            'password' => $password
        ]);
    }

    header('Location: registrazioneffettuata.php');
    exit;
}
?>


<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrazione</title>
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

        .registration-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 500px;
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

        input, select {
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
    </style>
    <script>
        function toggleMansione() {
            const userType = document.querySelector('input[name="tipo_utente"]:checked').value;
            const mansioneField = document.getElementById('mansione-field');
            if (userType === 'dipendente') {
                mansioneField.style.display = 'block';
            } else {
                mansioneField.style.display = 'none';
            }
        }
    </script>
</head>
<body>
    <div class="registration-container">
        <h1>Registrazione</h1>
        <form method="POST" action="">
            <label>
                <input type="radio" name="tipo_utente" value="dipendente" onclick="toggleMansione()" required> Dipendente
            </label>
            <label>
                <input type="radio" name="tipo_utente" value="cliente" onclick="toggleMansione()" required> Cliente
            </label>

            <label for="nome">Nome:</label>
            <input type="text" id="nome" name="nome" required>

            <label for="cognome">Cognome:</label>
            <input type="text" id="cognome" name="cognome" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <div id="mansione-field" style="display: none;">
                <label for="mansione">Mansione:</label>
                <select id="mansione" name="mansione">
                    <option value="magazziniere">Magazziniere</option>
                    <option value="gestore vendite">Gestore Vendite</option>
                    <option value="gestore contabilita">Gestore Contabilita</option>
                    <option value="dirigente">Dirigente</option>
                </select>
            </div>

            <button type="submit">Registrati</button>
        </form>
    </div>
</body>
</html>
