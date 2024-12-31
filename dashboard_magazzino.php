<?php
require 'db.php';
session_start();

// Controllo accesso: Solo per utenti con mansione "magazziniere"
if (!isset($_SESSION['user_id']) || $_SESSION['mansione'] !== 'magazziniere') {
    header("Location: login.php");
    exit;
}

$message = "";

// Inserimento o aggiornamento di un prodotto
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];

    if ($action === 'add') {
        // Aggiunta prodotto
        $nome_prodotto = $_POST['nome_prodotto'];
        $quantita = $_POST['quantita'];

        if (!empty($nome_prodotto) && is_numeric($quantita) && $quantita > 0) {
            try {
                $stmt = $pdo->prepare("INSERT INTO prodotti_magazzino (nome_prodotto, quantita) 
                                       VALUES (:nome_prodotto, :quantita)
                                       ON DUPLICATE KEY UPDATE quantita = quantita + :quantita");
                $stmt->execute([
                    'nome_prodotto' => $nome_prodotto,
                    'quantita' => $quantita
                ]);
                $message = "Prodotto aggiunto con successo!";
            } catch (PDOException $e) {
                $message = "Errore nell'aggiunta del prodotto: " . $e->getMessage();
            }
        } else {
            $message = "Per favore, inserisci valori validi.";
        }
    } elseif ($action === 'edit') {
        // Modifica prodotto
        $id = $_POST['id'];
        $nome_prodotto = $_POST['nome_prodotto'];
        $quantita = $_POST['quantita'];

        if (!empty($id) && !empty($nome_prodotto) && is_numeric($quantita) && $quantita >= 0) {
            try {
                $stmt = $pdo->prepare("UPDATE prodotti_magazzino 
                                       SET nome_prodotto = :nome_prodotto, quantita = :quantita 
                                       WHERE id = :id");
                $stmt->execute([
                    'nome_prodotto' => $nome_prodotto,
                    'quantita' => $quantita,
                    'id' => $id
                ]);
                $message = "Prodotto aggiornato con successo!";
            } catch (PDOException $e) {
                $message = "Errore nella modifica del prodotto: " . $e->getMessage();
            }
        } else {
            $message = "Per favore, inserisci valori validi.";
        }
    }
}

// Recupera tutti i prodotti nel magazzino
try {
    $stmt = $pdo->query("SELECT * FROM prodotti_magazzino");
    $prodotti_magazzino = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Errore nel recupero del magazzino: " . $e->getMessage());
}
?>



<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Magazzino</title>
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

        form {
            display: flex;
            flex-direction: column;
            margin-bottom: 30px;
        }

        label {
            margin-bottom: 8px;
            font-weight: bold;
        }

        input {
            padding: 10px;
            margin-bottom: 20px;
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
    </style>
</head>
<body>
    <div class="container">
        <h1>Dashboard Magazzino</h1>
        <a href="logout.php" class="logout">Logout</a>
        <?php
        if (!empty($message)) {
            echo "<p class='" . (strpos($message, 'successo') ? 'message' : 'error') . "'>$message</p>";
        }
        ?>

        <!-- Form Aggiungi Prodotto -->
        <form method="POST" action="">
            <input type="hidden" name="action" value="add">
            <label for="nome_prodotto">Nome Prodotto:</label>
            <input type="text" id="nome_prodotto" name="nome_prodotto" required>

            <label for="quantita">Quantità:</label>
            <input type="number" id="quantita" name="quantita" required>

            <button type="submit">Aggiungi Prodotto</button>
        </form>

        <!-- Tabella Prodotti -->
        <h2>Prodotti in Magazzino</h2>
        <?php if (!empty($prodotti_magazzino)): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome Prodotto</th>
                        <th>Quantità</th>
                        <th>Azioni</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($prodotti_magazzino as $prodotto): ?>
                        <tr>
                            <td><?= htmlspecialchars($prodotto['id']) ?></td>
                            <td><?= htmlspecialchars($prodotto['nome_prodotto']) ?></td>
                            <td><?= htmlspecialchars($prodotto['quantita']) ?></td>
                            <td>
                                <!-- Form Modifica Prodotto -->
                                <form method="POST" action="" class="edit-form">
                                    <input type="hidden" name="action" value="edit">
                                    <input type="hidden" name="id" value="<?= htmlspecialchars($prodotto['id']) ?>">
                                    <input type="text" name="nome_prodotto" value="<?= htmlspecialchars($prodotto['nome_prodotto']) ?>" required>
                                    <input type="number" name="quantita" value="<?= htmlspecialchars($prodotto['quantita']) ?>" required>
                                    <button type="submit">Modifica</button>
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
