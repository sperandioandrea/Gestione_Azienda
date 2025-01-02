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