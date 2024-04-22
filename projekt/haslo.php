<?php
// Sprawdzenie, czy kod został przekazany jako parametr URL
if(isset($_GET['kod'])) {
    // Pobranie kodu z parametru URL
    $kod = $_GET['kod'];
    
    // Połączenie z bazą danych
    include('skrypty/baza.php');
    $polaczenie = mysqli_connect($host, $uzytkownik_bd, $haslo_bd, $bd);

    // Sprawdzenie, czy udało się połączyć z bazą danych
    if (!$polaczenie) {
        die("Błąd połączenia z bazą danych: " . mysqli_connect_error());
    }

    // Zabezpieczenie przed atakami SQL injection
    $kod = mysqli_real_escape_string($polaczenie, $kod);

    // Zapytanie do bazy danych w celu sprawdzenia, czy kod istnieje w tabeli rejestracja
    $query = "SELECT * FROM rejestracja WHERE kod='$kod'";
    $result = mysqli_query($polaczenie, $query);

    // Sprawdzenie, czy kod został znaleziony w bazie danych
    if(mysqli_num_rows($result) == 1) {
        // Użytkownik ma poprawny kod - wyświetlenie formularza zmiany hasła
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ustawienie hasła</title>
    <link rel="stylesheet" href="style/logowanie.css">
</head>
<body>
    <!-- motyw -->
    <button id="theme-toggle">Zmień motyw</button>
    <!-- język -->
    <form method="post">
        <button id="language-toggle" type="submit" name="jezyk">українська</button>
    </form>
    <?php
    // Obsługa przekierowania na inny język
    if (isset($_POST['jezyk'])) {
        header("location: ukr/haslo.php?kod=$kod");
        exit;
    }
    ?>
    <script>
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
    <div class="login-container">
        <h2>Podaj hasło</h2>
        <form action="skrypty/haslo.php?kod=<?php echo $kod; ?>" method="POST"> <!-- Wskazujemy skrypt obsługujący formularz -->
            <div class="form-group">
                <input type="password" name="haslo" id="haslo" placeholder="hasło" required>
            </div>
            <div class="form-group">
                <button type="submit" class="btn-login">Zatwierdź</button>
            </div>
        </form>
        <script>
            // Walidacja hasła po stronie klienta
            document.addEventListener('DOMContentLoaded', function() {
                // Pobranie referencji do pola hasła
                const passwordInput = document.getElementById('haslo');

                // Funkcja sprawdzająca poprawność hasła
                function validatePassword() {
                    const password = passwordInput.value;

                    // Sprawdzenie, czy hasło zawiera minimum 8 znaków
                    if (password.length < 8) {
                        passwordInput.setCustomValidity('Hasło musi zawierać co najmniej 8 znaków.');
                        return;
                    }

                    // Sprawdzenie, czy hasło zawiera co najmniej jedną dużą literę
                    if (!/[A-Z]/.test(password)) {
                        passwordInput.setCustomValidity('Hasło musi zawierać co najmniej jedną dużą literę.');
                        return;
                    }

                    // Sprawdzenie, czy hasło zawiera co najmniej jedną cyfrę
                    if (!/\d/.test(password)) {
                        passwordInput.setCustomValidity('Hasło musi zawierać co najmniej jedną cyfrę.');
                        return;
                    }

                    // Sprawdzenie, czy hasło zawiera co najmniej jeden znak specjalny
                    if (!/[@$!%*?&]/.test(password)) {
                        passwordInput.setCustomValidity('Hasło musi zawierać co najmniej jeden znak specjalny.');
                        return;
                    }

                    // Hasło spełnia wszystkie kryteria - brak komunikatu o błędzie
                    passwordInput.setCustomValidity('');
                }

                // Nasłuchiwanie na zmiany w polu hasła i wywołanie funkcji walidacji
                passwordInput.addEventListener('input', validatePassword);
            });
        </script>
    </div>
</body>
</html>

<?php
    } else {
        // Kod nie został znaleziony w bazie danych - przekierowanie do strony głównej
        header("location: index.php");
        exit;
    }

    // Zamknięcie połączenia z bazą danych
    mysqli_close($polaczenie);
} else {
    // Jeśli kod nie został przekazany jako parametr URL, przekierowanie do strony głównej
    header("location: index.php");
    exit;
}
?>