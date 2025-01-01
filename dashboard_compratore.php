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
        }

        .error {
            color: red;
            font-weight: bold;
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
    </style>
</head>
<body>
    <div class="container">
        <h1>Benvenuto nello shop, Compratore</h1>
        <a href="logout.php" class="carrello-btn">Logout</a>

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
                                <button type="submit">Aggiungi al carrello</button>
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
