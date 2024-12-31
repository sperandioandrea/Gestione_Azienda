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