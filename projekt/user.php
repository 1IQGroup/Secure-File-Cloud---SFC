<!--
    strona user.php
    strona przeznaczona dla użytkowników z której mają możliwość wrzucania plików do chmury oraz mają parę opcji robienia czegoś z nimi (pobranie, usunięcie, udostępnienie)
-->
<?php
session_start();

// Sprawdzenie czy użytkownik jest zalogowany
if (!isset($_SESSION['zalogowany']) || $_SESSION['zalogowany'] !== true) {
    // Jeżeli nie, przekieruj na stronę logowania
    header("location: index.php");
    exit;
}

// Sprawdzenie czy użytkownik ma uprawnienia użytkownika
if (!isset($_SESSION['typ_uzytkownika']) || $_SESSION['typ_uzytkownika'] !== 'user') {
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
    // Przekierowanie na stronę logowania
    header("location: ukr/user.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Strona użytkownika</title>
    <link rel="stylesheet" href="style/uzytkownik.css">
</head>
<body>
    <div class="lewy">
        <?php
        // Połączenie z bazą danych
        require_once('skrypty/baza.php');
        $conn = new mysqli($host, $uzytkownik_bd, $haslo_bd, $bd);

        // Sprawdzenie połączenia
        if ($conn->connect_error) {
            die("Błąd połączenia z bazą danych: " . $conn->connect_error);
        }
        $user_email = $_SESSION['email'];
        echo $user_email;
        echo "<h1>Witaj na stronie użytkownika</h1>";

        // Pobranie miejsca użytkownika
        $query = "SELECT miejsce FROM uzytkownicy WHERE email = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $user_email);
        $stmt->execute();
        $result = $stmt->get_result();
        $miejsce_uzytkownika = $result->fetch_assoc()['miejsce']; // Pobranie miejsca użytkownika
        $stmt->close();

        // Pobranie sumy rozmiarów plików użytkownika
        $query_zajete_miejsce = "SELECT SUM(p.bajty) AS zajete_miejsce FROM uzytkownicy u INNER JOIN pliki p ON u.id = p.wlasciciel WHERE u.email = ?";
        $stmt_zajete_miejsce = $conn->prepare($query_zajete_miejsce);
        $stmt_zajete_miejsce->bind_param("s", $user_email);
        $stmt_zajete_miejsce->execute();
        $result_zajete_miejsce = $stmt_zajete_miejsce->get_result();
        $stmt_zajete_miejsce->close();

        if ($result_zajete_miejsce->num_rows == 1) {
            $row_zajete_miejsce = $result_zajete_miejsce->fetch_assoc();
            $zajete_miejsce_bajty = $row_zajete_miejsce['zajete_miejsce']; // Suma zajętego miejsca w bajtach

            // Konwersja bajtów na megabajty
            $zajete_miejsce_MB = round($zajete_miejsce_bajty / (1024 * 1024), 2); // Zaokrąglamy do dwóch miejsc po przecinku

            // Wyświetlenie wyniku
            echo "<h3>Ilość miejsca na dysku:</h3>";
            echo "<p>" . $zajete_miejsce_MB . "MB / " . $miejsce_uzytkownika . "MB</p>";
        } else {
            echo "Błąd: Nie można znaleźć sumy zajętego miejsca użytkownika.";
        }
        ?>

        <!-- Przycisk wylogowania -->
        <form method="post">
            <button id="logout" type="submit" name="wyloguj">Wyloguj</button>
        </form>
        <!-- przycisk zmiany motywu -->
        <button id="theme-toggle">Zmień motyw</button>
        <form method="post">
            <button id="language-toggle" type="submit" name="jezyk">українська</button>
        </form>
    </div>

    <div class="prawy">
        <!-- Formularz wyszukiwania plików -->
        <h2>Wyszukiwanie plików</h2>
        <form method="GET">
            <input type="text" name="fraza" placeholder="Wprowadź frazę">
            <button class='button' type="submit">Szukaj</button>
        </form><br>
        <!-- Formularz przesyłania pliku -->
        <form method="post" enctype="multipart/form-data" action="skrypty/dodaj_plik.php">
            <input type="file" name="file" id="fileInput" onchange="updateFileMessage()">
            <label for="fileInput" id="fileInputLabel">Wybierz plik</label>
            <span id="fileMessage">Nie wybrano pliku</span>
            <button class='button' type="submit" name="submit">Prześlij plik</button>
        </form>
        <?php
        // obsługa błędów
        // jeżeli plik nie może być znaleziony
        if(isset($_GET['error']) && $_GET['error'] == 1) {
            echo "<p class='error-message'>Błąd: Nie można znaleźć pliku dla tego użytkownika.</p>";
        }
        // jeżeli nie można znaleźć użytkownika
        if(isset($_GET['error']) && $_GET['error'] == 2) {
            echo "<p class='error-message'>Błąd: Nie można znaleźć użytkownika.</p>";
        }
        // jeżeli id pliku jest błędne
        if(isset($_GET['error']) && $_GET['error'] == 3) {
            echo "<p class='error-message'>Błąd: Błąd identyfikatora pliku</p>";
        }
        // jeżeli nie uda się przesłać pliku
        if(isset($_GET['error']) && $_GET['error'] == 4) {
            echo "<p class='error-message'>Błąd: Nie udało się przesłać pliku</p>";
        }
        //Wyświetlanie plików użytkownika
        echo "<h2>Twoje pliki</h2>";
        
        // Pobranie listy plików użytkownika z uwzględnieniem wyszukiwanej frazy
        if(!empty($_GET['fraza'])) {
            $fraza = '%' . $_GET['fraza'] . '%';
            $query_files = "SELECT p.id, p.nazwa, p.kod 
            FROM pliki p 
            INNER JOIN uzytkownicy u ON p.wlasciciel = u.id 
            WHERE u.email = ? AND p.nazwa LIKE ?";
            $stmt_files = $conn->prepare($query_files);
            $stmt_files->bind_param("ss", $user_email, $fraza);
        } else {
            // Pobranie wszystkich plików użytkownika, jeśli nie ma frazy wyszukiwania
            $query_files = "SELECT p.id, p.nazwa, p.kod 
            FROM pliki p 
            INNER JOIN uzytkownicy u ON p.wlasciciel = u.id 
            WHERE u.email = ?";
            $stmt_files = $conn->prepare($query_files);
            $stmt_files->bind_param("s", $user_email);
        }
        
        $stmt_files->execute();
        $result_files = $stmt_files->get_result();
        // jeżeli są pliki to je wyświetl razem z opcjami
        if ($result_files->num_rows > 0) {
            echo '<ul>';
            // tworzenie listy plików
            while ($row_file = $result_files->fetch_assoc()) {
                // nazwa pliku
                echo '<li><h3>' . htmlspecialchars(urldecode($row_file['nazwa']));
                // możliwość pobrania pliku
                echo '</h3></li><li> <form method="post" action="skrypty/pobierz_plik.php" style="display:inline-block;">
                    <input type="hidden" name="plik_id" value="' . $row_file['id'] . '">
                    <button class="button" type="submit" name="pobierz">Pobierz</button>
                    </form>';
                // możliwość usunięcia pliku
                echo ' <form method="post" action="skrypty/usun_plik.php" style="display:inline-block;">
                    <input type="hidden" name="plik_id" value="' . $row_file['id'] . '">
                    <button class="button" type="submit" name="usun">Usuń</button>
                    </form>';

                // Udostępnianie pliku
                if(empty($row_file['kod'])) {
                    echo ' <form method="post" action="skrypty/udostepnij_plik.php" style="display:inline-block;">
                        <input type="hidden" name="plik_id" value="' . $row_file['id'] . '">
                        <button class="button" type="submit" name="udostepnij">Udostępnij</button>
                        </form>';
                } else {
                    // Wyświetlanie linku do udostępnionego pliku
                    $link = $moja_strona . "skrypty/plik.php?kod=" . $row_file['kod'];
                    // skopiowanie pliku
                    echo ' <button class="button" type="submit" onclick="copyToClipboard(\'' . $link . '\')">Kopiuj link</button> ';
                    // jeżeli plik jest udostępniony to możliwość przestania udostępniania
                    echo '<form method="post" action="skrypty/przestan_udostepniac.php" style="display:inline-block;">
                    <input type="hidden" name="plik_id" value="' . $row_file['id'] . '">
                    <button class="button" type="submit" name="przestan_udostepniac">Przestań udostępniać</button>
                    </form>';
                }

                echo '</li><li><br></li>';
            }
            echo '</ul>';
        } else {
            // jeżeli użytkownik nie ma plików to komunikat brak plików
            echo '<p>Brak plików.</p>';
        }
        // Zamknięcie połączenia z bazą danych
        $stmt_files->close();
        $conn->close();
        ?>
    </div>
    <!-- Wyczyszczenie floatów bo bez tego wygląd strony się psuje-->
    <div class="clearfix"></div>

    <script>
        // Funkcja do kopiowania linku do schowka
        function copyToClipboard(text) {
            var dummy = document.createElement("textarea");
            document.body.appendChild(dummy);
            dummy.value = text;
            dummy.select();
            document.execCommand("copy");
            document.body.removeChild(dummy);
            alert("Skopiowano link do schowka: " + text);
        }
        // funkcja do zmiany motywu strony
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
        // funkcja do modyfikacji podstawowego napisu "nie wybrano pliku" i "Wybrano plik:..."
        function updateFileMessage() {
            var fileInput = document.getElementById('fileInput');
            var fileMessage = document.getElementById('fileMessage');

            if (fileInput.files.length > 0) {
                fileMessage.textContent = 'Wybrano plik: ' + fileInput.files[0].name;
            } else {
                fileMessage.textContent = 'Nie wybrano pliku';
            }
        }
    </script>
</body>
</html>
