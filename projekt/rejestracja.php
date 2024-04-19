<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rejestracja</title>
    <link rel="stylesheet" href="style/logowanie.css">
</head>
<body>
    <!-- motyw -->
    <button id="theme-toggle">Zmień motyw</button>
    <!-- język -->
    <form method="post">
        <button id="language-toggle" type="submit" name="jezyk">українська</button>
    </form>
    <form method="post">
        <button id="exit" type="submit" name="exit">X</button>
    </form>
    <div class="login-container">
        <h2>Zarejestruj się</h2>
        <form action="skrypty/rejestracja.php" method="POST"> <!-- Wskazujemy skrypt obsługujący formularz -->
            <div class="form-group">
                <input type="email" name="email" placeholder="Adres email" required>
            </div>
            <div class="form-group">
                <button type="submit" class="btn-login">Zarejestruj się</button>
            </div>
        </form>
    </div>
</body>
</html>
