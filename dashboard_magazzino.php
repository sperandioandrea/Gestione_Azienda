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