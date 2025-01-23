<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['mansione'] !== 'compratore') {
    header("Location: login.php");
    exit;
}

require_once "db.php";

// Recupera tutti i prodotti disponibili nel magazzino
$query = "SELECT id, nome_prodotto, prezzo, descrizione, quantita FROM prodotti_magazzino";
$stmt = $pdo->query($query);
$prodotti = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Gestione aggiunta al carrello
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_prodotto']) && isset($_POST['quantita'])) {
    $id_prodotto = $_POST['id_prodotto'];
    $quantita = $_POST['quantita'];

    // Controllo quantita disponibile
    $stmt = $pdo->prepare("SELECT quantita FROM prodotti_magazzino WHERE id = :id");
    $stmt->execute([':id' => $id_prodotto]);
    $prodotto = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($prodotto && $quantita > 0 && $quantita <= $prodotto['quantita']) {
        // Aggiungi al carrello
        if (!isset($_SESSION['carrello'])) {
            $_SESSION['carrello'] = [];
        }
        $_SESSION['carrello'][] = [
            'id_prodotto' => $id_prodotto,
            'quantita' => $quantita,
        ];
        $success_message = "Prodotto aggiunto al carrello con successo!";
    } else {
        $error_message = "Quantita non valida o prodotto non disponibile.";
    }
}


?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Compratore</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #000;
            color: #fff;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: auto;
            background-color: #333;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        h1 {
            text-align: center;
            color: #f1c40f; /* Giallo per il titolo */
            font-size: 2.5em;
            margin-bottom: 20px;
        }

        h2 {
            color: #fff;
            margin-bottom: 10px;
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
            background-color: #f39c12; /* Giallo per le intestazioni */
            color: #fff;
        }

        .success {
            color: #28a745;
            font-weight: bold;
            margin: 15px 0;
        }

        .error {
            color: #e74c3c;
            font-weight: bold;
            margin: 15px 0;
        }

        .carrello-btn, .logout-btn {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #f39c12; /* Giallo per i bottoni */
            color: white;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
        }

        .carrello-btn:hover, .logout-btn:hover {
            background-color: #f1c40f; /* Colore hover per i bottoni */
        }

        .logout-btn {
            background-color: #444; /* Nero chiaro per il bottone logout */
            font-family: 'Verdana', sans-serif; /* Cambiato il carattere per il logout */
            position: absolute;
            right: 20px;
            top: 20px;
            padding: 10px 20px;
            font-size: 1.1em;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Benvenuto nello shop, Compratore</h1>
        <a href="logout.php" class="logout-btn">Logout</a>

        <?php if (!empty($success_message)): ?>
            <p class="success"><?= $success_message ?></p>
        <?php elseif (!empty($error_message)): ?>
            <p class="error"><?= $error_message ?></p>
        <?php endif; ?>

        <h2>Prodotti Disponibili</h2>
        <table>
            <thead>
                <tr>
                    <th>Nome_prodotto</th>
                    <th>Prezzo (€)</th>
                    <th>Descrizione</th>
                    <th>Quantita</th>
                    <th>Azione</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($prodotti as $prodotto): ?>
                    <tr>
                        <td><?= htmlspecialchars($prodotto['nome_prodotto']) ?></td>
                        <td><?= number_format($prodotto['prezzo'], 2) ?></td>
                        <td><?= htmlspecialchars($prodotto['descrizione']) ?></td>
                        <td><?= htmlspecialchars($prodotto['quantita']) ?></td>
                        <td>
                            <form method="POST" action="">
                                <input type="hidden" name="id_prodotto" value="<?= $prodotto['id'] ?>">
                                <input type="number" name="quantita" min="1" max="<?= $prodotto['quantita'] ?>" required>
                                <button type="submit" class="carrello-btn">Aggiungi al carrello</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <a href="carrello.php" class="carrello-btn">Vai al Carrello</a>
    </div>
</body>
</html>

