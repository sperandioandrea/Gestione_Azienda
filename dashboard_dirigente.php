<?php
session_start();
require_once "db.php";

// Controllo accesso e autorizzazione
if (!isset($_SESSION['user_id']) || $_SESSION['mansione'] !== 'dirigente') {
    header("Location: login.php");
    exit;
}

// Recupero dati dei dipendenti
$query_dipendenti = "SELECT nome, cognome, mansione, email FROM utenti WHERE mansione IN ('magazziniere', 'gestore vendite', 'gestore contabilita')";
$stmt_dipendenti = $pdo->query($query_dipendenti);
$dipendenti = $stmt_dipendenti->fetchAll(PDO::FETCH_ASSOC);

// Aggiunta: Recupero dati del magazzino
$query_magazzino = "SELECT nome_prodotto, quantita, prezzo FROM prodotti_magazzino";
$stmt_magazzino = $pdo->query($query_magazzino);
$prodotti_magazzino = $stmt_magazzino->fetchAll(PDO::FETCH_ASSOC);

// Recupera buste paga con dettagli del dipendente
$query_buste_paga = "SELECT buste_paga.*, utenti.nome, utenti.cognome, utenti.mansione 
                     FROM buste_paga 
                     INNER JOIN utenti ON buste_paga.id_user = utenti.id";
$stmt_buste_paga = $pdo->query($query_buste_paga);
$buste_paga = $stmt_buste_paga->fetchAll(PDO::FETCH_ASSOC);
?>




<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Dirigente</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f3f4f6;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        h1, h2 {
            text-align: center;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        .logout-btn {
            display: inline-block;
            margin: 20px 0;
            padding: 10px 20px;
            background-color: #dc3545;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .logout-btn:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Dashboard Dirigente</h1>
        <a href="logout.php" class="logout-btn">Logout</a>

        <h2>Dipendenti</h2>
        <table>
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Cognome</th>
                    <th>Mansione</th>
                    <th>Email</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($dipendenti as $dipendente): ?>
                    <tr>
                        <td><?= htmlspecialchars($dipendente['nome']) ?></td>
                        <td><?= htmlspecialchars($dipendente['cognome']) ?></td>
                        <td><?= htmlspecialchars($dipendente['mansione']) ?></td>
                        <td><?= htmlspecialchars($dipendente['email']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h2>Prodotti in Magazzino</h2>
        <table>
            <thead>
                <tr>
                    <th>Nome Prodotto</th>
                    <th>Quantità Disponibile</th>
                    <th>Prezzo Unitario (€)</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($prodotti_magazzino as $prodotto): ?>
                    <tr>
                        <td><?= htmlspecialchars($prodotto['nome_prodotto']) ?></td>
                        <td><?= htmlspecialchars($prodotto['quantita']) ?></td>
                        <td><?= number_format($prodotto['prezzo'], 2) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h2>Elenco Buste Paga</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Cognome</th>
                    <th>Mansione</th>
                    <th>Data Stipendio</th>
                    <th>Ore Mensili</th>
                    <th>Stipendio</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($buste_paga as $busta): ?>
                    <tr>
                        <td><?= htmlspecialchars($busta['id']) ?></td>
                        <td><?= htmlspecialchars($busta['nome']) ?></td>
                        <td><?= htmlspecialchars($busta['cognome']) ?></td>
                        <td><?= htmlspecialchars($busta['mansione']) ?></td>
                        <td><?= htmlspecialchars($busta['data_stipendio']) ?></td>
                        <td><?= htmlspecialchars($busta['ore_mensili']) ?></td>
                        <td>€<?= number_format($busta['stipendio'], 2) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
