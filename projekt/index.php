<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logowanie</title>
    <link rel="stylesheet" href="style/logowanie.css">
</head>
<body>
    <!-- motyw -->
    <button id="theme-toggle">Zmień motyw</button>
    <!-- język -->
    <form method="post">
        <button id="language-toggle" type="submit" name="jezyk">українська</button>
    </form>
    <div class="login-container">
        <h2>Zaloguj się</h2>
        <form action="skrypty/login.php" method="POST">
            <div class="form-group">
                <input type="email" name="email" placeholder="Adres email" required>
            </div>
            <div class="form-group">
                <input type="password" name="haslo" placeholder="Hasło" required>
            </div>
            <div class="form-group">
                <button type="submit" class="btn-login">Zaloguj się</button>
            </div>
        </form>
        <div class="forgot-password-link">
            <span>Zapomniałeś hasła? <a href="przypomnij.php">Zresetuj je</a></span>
        </div>
        <div class="register-link">
            <span>Nie masz jeszcze konta? <a href="rejestracja.php">Zarejestruj się</a></span>
        </div>
    </div>
</body>
</html>

