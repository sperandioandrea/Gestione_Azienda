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