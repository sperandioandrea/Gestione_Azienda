<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['mansione'] !== 'compratore') {
    header("Location: login.php");
    exit;
}

require_once "db.php";

if (!isset($_SESSION['carrello']) || empty($_SESSION['carrello'])) {
    $carrello_vuoto = true;
} else {
    $carrello_vuoto = false;
    $totale = 0;
    $prodotti_carrello = [];

    // Recupera i dettagli dei prodotti nel carrello
    foreach ($_SESSION['carrello'] as $item) {
        $stmt = $pdo->prepare("SELECT id, nome_prodotto, prezzo, quantita FROM prodotti_magazzino WHERE id = :id");
        $stmt->execute([':id' => $item['id_prodotto']]);
        $prodotto = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($prodotto) {
            $prodotto['quantita_acquistata'] = $item['quantita'];
            $prodotti_carrello[] = $prodotto;
            $totale += $prodotto['prezzo'] * $item['quantita'];
        }
    }
}

// Gestione acquisto
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['compra'])) {
    foreach ($_SESSION['carrello'] as $item) {
        $stmt = $pdo->prepare("UPDATE prodotti_magazzino SET quantita = quantita - :quantita WHERE id = :id");
        $stmt->execute([
            ':quantita' => $item['quantita'],
            ':id' => $item['id_prodotto']
        ]);
    }

    // Svuota il carrello
    unset($_SESSION['carrello']);
    $success_message = "Acquisto effettuato con successo!";
    $carrello_vuoto = true;
}

?>



<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrello</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f3f4f6;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        h1 {
            text-align: center;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
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

        .success {
            color: green;
            font-weight: bold;
            text-align: center;
        }

        .carrello-btn {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
        }

        .carrello-btn:hover {
            background-color: #218838;
        }

        .compra-btn {
            display: block;
            margin: 20px auto;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-align: center;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .compra-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Il tuo Carrello</h1>

        <?php if (!empty($success_message)): ?>
            <p class="success"><?= $success_message ?></p>
        <?php endif; ?>

        <?php if ($carrello_vuoto): ?>
            <p>Il tuo carrello è vuoto. <a href="dashboard_compratore.php" class="carrello-btn">Torna ai prodotti</a></p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Nome_prodotto</th>
                        <th>Prezzo (€)</th>
                        <th>Quantita Acquistata</th>
                        <th>Subtotale (€)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($prodotti_carrello as $prodotto): ?>
                        <tr>
                            <td><?= htmlspecialchars($prodotto['nome_prodotto']) ?></td>
                            <td><?= number_format($prodotto['prezzo'], 2) ?></td>
                            <td><?= htmlspecialchars($prodotto['quantita_acquistata']) ?></td>
                            <td>€<?= number_format($prodotto['prezzo'] * $prodotto['quantita_acquistata'], 2) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <h3>Totale: €<?= number_format($totale, 2) ?></h3>

            <form method="POST" action="">
                <button type="submit" name="compra" class="compra-btn">Compra</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>