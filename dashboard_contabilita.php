<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['mansione'] !== 'gestore contabilita') {
    header("Location: login.php");
    exit;
}

require_once "db.php";

// Gestione inserimento busta paga
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_user = $_POST['id_user'];
    $data_stipendio = $_POST['data_stipendio'];
    $ore_mensili = $_POST['ore_mensili'];

    // Recupera la mansione del dipendente
    $stmt = $pdo->prepare("SELECT mansione FROM utenti WHERE id = :id_user");
    $stmt->execute([':id_user' => $id_user]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $mansione = $user['mansione'];

        // Calcola stipendio in base alla mansione
        $stipendio_orario = 0;
        if ($mansione === 'gestore vendite') {
            $stipendio_orario = 14;
        } elseif ($mansione === 'gestore contabilita') {
            $stipendio_orario = 16;
        } elseif ($mansione === 'magazziniere') {
            $stipendio_orario = 10;
        }

        $stipendio = $ore_mensili * $stipendio_orario;

        // Inserisci la busta paga
        $query = "INSERT INTO buste_paga (stipendio, data_stipendio, ore_mensili, id_user) 
                  VALUES (:stipendio, :data_stipendio, :ore_mensili, :id_user)";
        $stmt = $pdo->prepare($query);

        if ($stmt->execute([
            ':stipendio' => $stipendio,
            ':data_stipendio' => $data_stipendio,
            ':ore_mensili' => $ore_mensili,
            ':id_user' => $id_user,
        ])) {
            $success_message = "Busta paga aggiunta con successo!";
        } else {
            $error_message = "Errore nell'aggiunta della busta paga.";
        }
    } else {
        $error_message = "Dipendente non trovato.";
    }
}

// Recupera dipendenti con mansioni specifiche
$stmt = $pdo->query("SELECT id, nome, cognome, mansione FROM utenti WHERE mansione IN ('magazziniere', 'gestore vendite', 'gestore contabilita')");
$dipendenti = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Recupera buste paga con dettagli del dipendente
$query = "SELECT buste_paga.*, utenti.nome, utenti.cognome, utenti.mansione 
          FROM buste_paga 
          INNER JOIN utenti ON buste_paga.id_user = utenti.id";
$stmt = $pdo->query($query);
$buste_paga = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>



<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Contabilità</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f3f4f6;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 80%;
            margin: auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        h1, h2, h3 {
            text-align: center;
        }

        form {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        select, input, button {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button {
            background-color: #007bff;
            color: white;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f4f4f4;
        }

        .logout-btn {
            display: block;
            text-align: right;
            margin-bottom: 20px;
            text-decoration: none;
            color: #007bff;
        }

        .logout-btn:hover {
            text-decoration: underline;
        }

        .success {
            color: green;
            font-weight: bold;
        }

        .error {
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Dashboard Contabilità</h1>
        <a href="logout.php" class="logout-btn">Logout</a>

        <h2>Aggiungi Busta Paga</h2>
        <?php if (!empty($success_message)): ?>
            <p class="success"><?= $success_message ?></p>
        <?php elseif (!empty($error_message)): ?>
            <p class="error"><?= $error_message ?></p>
        <?php endif; ?>
        <form method="POST" action="">
            <label for="id_user">Seleziona Dipendente:</label>
            <select name="id_user" id="id_user" required>
                <option value="">-- Seleziona Dipendente --</option>
                <?php foreach ($dipendenti as $dipendente): ?>
                    <option value="<?= $dipendente['id'] ?>">
                        <?= htmlspecialchars($dipendente['nome'] . ' ' . $dipendente['cognome'] . ' - ' . $dipendente['mansione']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="data_stipendio">Data Stipendio:</label>
            <input type="date" name="data_stipendio" id="data_stipendio" required>

            <label for="ore_mensili">Ore Mensili:</label>
            <input type="number" name="ore_mensili" id="ore_mensili" required>

            <button type="submit">Aggiungi</button>
        </form>

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