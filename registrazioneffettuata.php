<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrazione Effettuata</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f3f4f6;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            text-align: center;
        }

        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
        }

        h1 {
            font-size: 24px;
            margin-bottom: 20px;
            color: #007bff;
        }

        p {
            font-size: 16px;
            margin-bottom: 20px;
            color: #333;
        }

        a {
            color: #007bff;
            text-decoration: none;
            font-weight: bold;
        }

        a:hover {
            text-decoration: underline;
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
