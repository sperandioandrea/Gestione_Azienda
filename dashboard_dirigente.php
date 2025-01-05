<?php
session_start();
require_once "db.php";

// Controllo accesso e autorizzazione
if (!isset($_SESSION['user_id']) || $_SESSION['mansione'] !== 'dirigente') {
    header("Location: login.php");
    exit;
}

// Recupero dati dei dipendenti
$query_dipendenti = "SELECT nome, cognome, mansione, email FROM utenti WHERE mansione IN ('magazziniere', 'gestore vendite', 'gestore contabilita')";
$stmt_dipendenti = $pdo->query($query_dipendenti);
$dipendenti = $stmt_dipendenti->fetchAll(PDO::FETCH_ASSOC);

// Aggiunta: Recupero dati del magazzino
$query_magazzino = "SELECT nome_prodotto, quantita, prezzo FROM prodotti_magazzino";
$stmt_magazzino = $pdo->query($query_magazzino);
$prodotti_magazzino = $stmt_magazzino->fetchAll(PDO::FETCH_ASSOC);

// Recupera buste paga con dettagli del dipendente
$query_buste_paga = "SELECT buste_paga.*, utenti.nome, utenti.cognome, utenti.mansione 
                     FROM buste_paga 
                     INNER JOIN utenti ON buste_paga.id_user = utenti.id";
$stmt_buste_paga = $pdo->query($query_buste_paga);
$buste_paga = $stmt_buste_paga->fetchAll(PDO::FETCH_ASSOC);
?>