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