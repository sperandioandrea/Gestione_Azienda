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