<!--
    strona admin.php
    strona przeznaczona dla administratora z której ma podgląd na statystyki oraz ma możliwość edycji użytkowników
-->
<?php
session_start();

// Sprawdzenie czy użytkownik jest zalogowany
if (!isset($_SESSION['zalogowany']) || $_SESSION['zalogowany'] !== true) {
    // Jeżeli nie, przekieruj na stronę logowania
    header("location: index.php");
    exit;
}

// Sprawdzenie czy użytkownik ma uprawnienia administratora
if (!isset($_SESSION['typ_uzytkownika']) || $_SESSION['typ_uzytkownika'] !== 'admin') {
    // Jeżeli nie, przekieruj na stronę główną
    header("location: index.php");
    exit;
}

// Obsługa wylogowania
if (isset($_POST['wyloguj'])) {
    // Usunięcie zmiennych sesji
    unset($_SESSION['zalogowany']);
    unset($_SESSION['typ_uzytkownika']);
    // Przekierowanie na stronę logowania
    header("location: index.php");
    exit;
}
// Obsługa przekierowania z polskiego na ukraiński
if (isset($_POST['jezyk'])) {
    header("location: ukr/admin.php");
    exit;
}
// Połączenie z bazą danych
require_once('skrypty/baza.php');
$conn = new mysqli($host, $uzytkownik_bd, $haslo_bd, $bd);

// Sprawdzenie połączenia
if ($conn->connect_error) {
    die("Błąd połączenia z bazą danych: " . $conn->connect_error);
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Strona administratora</title>
    <link rel="stylesheet" href="style/uzytkownik.css">
</head>
<body>
    <div class="lewy">
        <?php echo $_SESSION['email'] ?>
        <h1>Witaj na stronie administratora</h1>

        <!-- Przycisk wylogowania -->
        <form method="post">
            <button id="logout" type="submit" name="wyloguj">Wyloguj</button>
        </form>
        <!-- motyw -->
        <button id="theme-toggle">Zmień motyw</button>
        <!-- język -->
        <form method="post">
            <button id="language-toggle" type="submit" name="jezyk">українська</button>
        </form>
    </div>
    <div class="prawy">
        <?php
        // Pobranie sumy rozmiarów plików użytkownika
        $query_zajete_miejsce = "SELECT SUM(bajty) AS zajete_miejsce FROM pliki";
        $stmt_zajete_miejsce = $conn->prepare($query_zajete_miejsce);
        $stmt_zajete_miejsce->execute();
        $result_zajete_miejsce = $stmt_zajete_miejsce->get_result();
        $stmt_zajete_miejsce->close();
        if ($result_zajete_miejsce->num_rows == 1) {
            $row_zajete_miejsce = $result_zajete_miejsce->fetch_assoc();
            $zajete_miejsce_bajty = $row_zajete_miejsce['zajete_miejsce']; // Suma zajętego miejsca w bajtach
            // Konwersja bajtów na megabajty
            $zajete_miejsce_MB = round($zajete_miejsce_bajty / (1024 * 1024), 2); // Zaokrąglamy do dwóch miejsc po przecinku
            echo "<h3>Ilość zajętego miejsca miejsca na dysku:</h3>";
            echo "<p>" . $zajete_miejsce_MB . "MB</p>";
        } else {
            echo "Błąd: Nie można znaleźć sumy zajętego miejsca użytkownika.";
        }
        ?>
        <!-- lista użytkowników razem z ich emailem, zajętym miejscem, ograniczeniem które można zmienić, możliwością ich usunięcia -->
        <h3>Lista użytkowników:</h3>
        <table>
            <tr>
                <th>Email</th>
                <th>Zajęte miejsce (MB)</th>
                <th>Ograniczenie (MB)</th>
            </tr>
            <?php
            // Pobranie listy użytkowników z informacjami
            $query_users = "SELECT u.email, IFNULL(round(SUM(p.bajty)/(1024*1024), 2), 0) AS zajete_miejsce, u.miejsce FROM uzytkownicy u LEFT JOIN pliki p ON u.id=p.wlasciciel WHERE u.admin=0 GROUP BY u.email ORDER BY zajete_miejsce DESC";
            $result_users = $conn->query($query_users);
            if ($result_users->num_rows > 0) {
                while ($row_user = $result_users->fetch_assoc()) {
                    $email = $row_user['email'];
                    $zajete_miejsce = $row_user['zajete_miejsce'];
                    $miejsce = $row_user['miejsce'];
                    echo "<tr>";
                    echo "<td>$email</td>";
                    echo "<td>$zajete_miejsce</td>";
                    echo "<td><form method='post' action='skrypty/zmien_miejsce.php'><input type='hidden' name='email' value='$email'><input class='button' type='number' name='nowe_miejsce' value='$miejsce'><button class='button' type='submit' name='zmien'>Zmień miejsce</button></form></td>";
                    echo "<td><form method='post' action='skrypty/usun_uzytkownika.php' onsubmit='return confirm(\"Czy na pewno chcesz usunąć użytkownika $email?\")'><input type='hidden' name='email' value='$email'><button class='button' type='submit' name='usun'>Usuń użytkownika</button></form></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='3'>Brak użytkowników.</td></tr>";
            }
            ?>
        <table>
    </div>
    <?php
    $conn->close();
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
</body>
</html>
