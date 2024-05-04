<!-- 
    strona index.php
    strona startowa gdzie użytkownik ma możliwość zalogowania się na swoje konto 
    lub przejście na stronę przypomnij.php jeżeli nie pamięta hasła 
    lub przejście na stronę rejestracja.php jeżeli chce się zarejestrować
 -->
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logowanie</title>
    <link rel="stylesheet" href="style/logowanie.css">
</head>
<body>
    <!-- guzik od zmiany motywu z jasnego na ciemny i vicewersa-->
    <button id="theme-toggle">Zmień motyw</button>
    <!-- guzik od zmiany języka z polskiego na ukraiński -->
    <form method="post">
        <button id="language-toggle" type="submit" name="jezyk">українська</button>
    </form>
    <?php
    // Obsługa przekierowania na inny język
    if (isset($_POST['jezyk'])) {
        header("location: ukr/index.php");
        exit;
    }
    ?>
    <script>
        // zmiana motywu
        const themeToggle = document.getElementById('theme-toggle');
        // Sprawdź, czy użytkownik ma zapisany preferowany motyw
        let currentTheme = localStorage.getItem('theme');
        if (!currentTheme) {
            // Jeśli nie ma zapisanego motywu, ustaw domyślnie motyw jasny
            currentTheme = 'light';
            localStorage.setItem('theme', currentTheme);
        }
        document.documentElement.setAttribute('data-theme', currentTheme);
        // Obsługa kliknięcia przycisku zmiany motywu
        themeToggle.addEventListener('click', () => {
            const currentTheme = document.documentElement.getAttribute('data-theme');
            const newTheme = currentTheme === 'light' ? 'dark' : 'light';
            document.documentElement.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
        });
    </script>
    <!-- formularz logowania -->
    <div class="login-container">
        <h2>Zaloguj się</h2>
        <!-- skrypty/login.php -->
        <form action="skrypty/login.php" method="POST">
            <!-- email -->
            <div class="form-group">
                <input type="email" name="email" placeholder="Adres email" required>
            </div>
            <!-- hasło -->
            <div class="form-group">
                <input type="password" name="haslo" placeholder="Hasło" required>
            </div>
            <!-- przycisk od logowania -->
            <div class="form-group">
                <button type="submit" class="btn-login">Zaloguj się</button>
            </div>
        </form>
        <!-- przekierowanie jeżeli użytkownik nie pamięta hasła -->
        <div class="forgot-password-link">
            <span>Zapomniałeś hasła? <a href="przypomnij.php">Zresetuj je</a></span>
        </div>
        <!-- przekierowanie jeżeli użytkownik chce założyć konto -->
        <div class="register-link">
            <span>Nie masz jeszcze konta? <a href="rejestracja.php">Zarejestruj się</a></span>
        </div>
        <?php
        // obsługa błędów
        // jeżeli dane są niepoprawne
        if(isset($_GET['error']) && $_GET['error'] == 1) {
            echo "<p class='error-message'>Nieprawidłowy email lub hasło</p>";
        }
        // jeżeli użytkonik został zablokowany z powodu za dużej liczby prób zalogowania się
        if(isset($_GET['error']) && $_GET['error'] == 2) {
            echo "<p class='error-message'>Za dużo prób logowania<br>Spróbuj ponownie za 20 minut</p>";
        }
        // jeżeli użytkownik się zarejestrował lub zmienił hasło
        if(isset($_GET['login']) && $_GET['login'] == 1) {
            echo "<p class='good-message'>Możesz się już zalogować</p>";
        }
        ?>
    </div>
</body>
</html>

