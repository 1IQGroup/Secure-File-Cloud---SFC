<!-- 
    strona rejestracja.php
    strona gdzie użytkownik ma możliwość zarejestrowania się
 -->
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rejestracja</title>
    <link rel="stylesheet" href="style/logowanie.css">
</head>
<body>
    <!-- guzik od zmiany motywu z jasnego na ciemny i vicewersa-->
    <button id="theme-toggle">Zmień motyw</button>
    <!-- guzik od zmiany języka z polskiego na ukraiński -->
    <form method="post">
        <button id="language-toggle" type="submit" name="jezyk">українська</button>
    </form>
    <!-- guzik X który cofa do index.php -->
    <form method="post">
        <button id="exit" type="submit" name="exit">X</button>
    </form>
    <?php
    // Obsługa przekierowania na inny język
    if (isset($_POST['jezyk'])) {
        header("location: ukr/rejestracja.php");
        exit;
    }
    // Obsługa cofnięcia się
    if (isset($_POST['exit'])) {
        header("location: index.php");
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
    <!-- formularz rejestracji -->
    <div class="login-container">
        <h2>Zarejestruj się</h2>
        <!-- rejestracja.php -->
        <form action="skrypty/rejestracja.php" method="POST"> <!-- Wskazujemy skrypt obsługujący formularz -->
            <!-- email -->
            <div class="form-group">
                <input type="email" name="email" placeholder="Adres email" required>
            </div>
            <!-- przycisk który aktywuje rejestracje -->
            <div class="form-group">
                <button type="submit" class="btn-login">Zarejestruj się</button>
            </div>
            <?php
                // obsługa błędów
                // jeżeli email istnieje
                if(isset($_GET['error']) && $_GET['error'] == 1) {
                    echo "<p class='error-message'>Podany email już istnieje</p>";
                }
                // jeżeli już wysłano wiadomość w ciągu jednej godziny
                if(isset($_GET['error']) && $_GET['error'] == 2) {
                    echo "<p class='error-message'>Za dużo wysłanych wiadomości<br>Spróbuj ponownie za godzinę</p>";
                }
            ?>
        </form>
    </div>
</body>
</html>
