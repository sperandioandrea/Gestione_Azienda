<?php
require 'db.php';
session_start();

// Controllo accesso: Solo per utenti con mansione "gestore vendite"
if (!isset($_SESSION['user_id']) || $_SESSION['mansione'] !== 'gestore vendite') {
    header("Location: login.php");
    exit;
}

$message = "";

// Aggiornamento prodotti con prezzo e descrizione
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $prezzo = $_POST['prezzo'];
    $descrizione = $_POST['descrizione'];

    if (!empty($id) && is_numeric($prezzo) && $prezzo > 0 && !empty($descrizione)) {
        try {
            $stmt = $pdo->prepare("UPDATE prodotti_magazzino 
                                   SET prezzo = :prezzo, descrizione = :descrizione 
                                   WHERE id = :id");
            $stmt->execute([
                'prezzo' => $prezzo,
                'descrizione' => $descrizione,
                'id' => $id
            ]);
            $message = "Prodotto aggiornato con successo!";
        } catch (PDOException $e) {
            $message = "Errore nell'aggiornamento del prodotto: " . $e->getMessage();
        }
    } else {
        $message = "Per favore, inserisci valori validi.";
    }
}

// Recupera tutti i prodotti disponibili
try {
    $stmt = $pdo->query("SELECT * FROM prodotti_magazzino");
    $prodotti_magazzino = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Errore nel recupero dei prodotti: " . $e->getMessage());
}
?>



<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Gestore Vendite</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f3f4f6;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        h1 {
            text-align: center;
            color: #007bff;
        }

        .logout {
            float: right;
            padding: 10px;
            background-color: #dc3545;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .logout:hover {
            background-color: #c82333;
        }

        .message {
            text-align: center;
            color: green;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .error {
            text-align: center;
            color: red;
            font-weight: bold;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th, table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }

        table th {
            background-color: #007bff;
            color: white;
        }

        .edit-form {
            display: flex;
            flex-direction: column;
            margin-top: 10px;
        }

        .edit-form input {
            margin-bottom: 10px;
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
</head>
<body>
    <div class="container">
        <h1>Dashboard Gestore Vendite</h1>
        <a href="logout.php" class="logout">Logout</a>

        <?php
        if (!empty($message)) {
            echo "<p class='" . (strpos($message, 'successo') ? 'message' : 'error') . "'>$message</p>";
        }
        ?>

        <h2>Prodotti in Magazzino</h2>
        <?php if (!empty($prodotti_magazzino)): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome Prodotto</th>
                        <th>Quantità</th>
                        <th>Prezzo</th>
                        <th>Descrizione</th>
                        <th>Azioni</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($prodotti_magazzino as $prodotto): ?>
                        <tr>
                            <td><?= htmlspecialchars($prodotto['id']) ?></td>
                            <td><?= htmlspecialchars($prodotto['nome_prodotto']) ?></td>
                            <td><?= htmlspecialchars($prodotto['quantita']) ?></td>
                            <td><?= htmlspecialchars($prodotto['prezzo'] ?? 'N/D') ?></td>
                            <td><?= htmlspecialchars($prodotto['descrizione'] ?? 'N/D') ?></td>
                            <td>
                                <!-- Modulo per aggiornare prezzo e descrizione -->
                                <form method="POST" action="" class="edit-form">
                                    <input type="hidden" name="id" value="<?= htmlspecialchars($prodotto['id']) ?>">
                                    <input type="number" step="0.01" name="prezzo" placeholder="Prezzo" value="<?= htmlspecialchars($prodotto['prezzo'] ?? '') ?>" required>
                                    <input type="text" name="descrizione" placeholder="Descrizione" value="<?= htmlspecialchars($prodotto['descrizione'] ?? '') ?>" required>
                                    <button type="submit">Aggiorna</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Nessun prodotto presente in magazzino.</p>
        <?php endif; ?>
    </div>
</body>
</html>
