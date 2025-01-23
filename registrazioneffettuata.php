<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrazione Effettuata</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #000;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: white;
            text-align: center;
        }

        .container {
            background-color: #1e1e1e;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.4);
            width: 100%;
            max-width: 450px;
        }

        h1 {
            font-size: 26px;
            margin-bottom: 15px;
            color: #FFD700; /* Giallo */
        }

        p {
            font-size: 18px;
            margin-bottom: 25px;
            color: #D3D3D3; /* Grigio chiaro per il testo */
        }

        a {
            color: #FFD700; /* Giallo */
            text-decoration: none;
            font-weight: bold;
        }

        a:hover {
            text-decoration: underline;
            color: #ffcc00; /* Giallo più chiaro al passaggio del mouse */
        }

        .note {
            font-size: 14px;
            color: #777;
        }

    </style>
    <script>
        // Reindirizzamento automatico alla pagina di login dopo 5 secondi
        setTimeout(function() {
            window.location.href = 'login.php';
        }, 5000);
    </script>
</head>
<body>
    <div class="container">
        <h1>Registrazione Effettuata!</h1>
        <p>La tua registrazione è stata completata con successo.</p>
        <p>Verrai reindirizzato alla pagina di login entro pochi secondi.</p>
        <p>Se non vieni reindirizzato, <a href="login.php">clicca qui</a>.</p>
      
    </div>
</body>
</html>

