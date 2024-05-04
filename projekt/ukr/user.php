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
// Obsługa przekierowania na inny język
if (isset($_POST['jezyk'])) {
    // Przekierowanie na stronę logowania
    header("location: ../user.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Сторінка користувача</title>
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
        echo "<h1>Ласкаво просимо на сторінку користувача</h1>";

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
            echo "<h3>Місце на диску:</h3>";
            echo "<p>" . $zajete_miejsce_MB . "MB / " . $miejsce_uzytkownika . "MB</p>";
        } else {
            echo "Помилка: не вдалося знайти загальну кількість використаного простору користувача.";
        }
        ?>

        <!-- Przycisk wylogowania -->
        <form method="post">
            <button id="logout" type="submit" name="wyloguj">Вийти</button>
        </form>
        <button id="theme-toggle">Змінити тему</button>
        <form method="post">
            <button id="language-toggle" type="submit" name="jezyk">polski</button>
        </form>
    </div>

    <div class="prawy">
        <!-- Formularz wyszukiwania plików -->
        <h2>Пошук файлів</h2>
        <form method="GET">
            <input type="text" name="fraza" placeholder="Введіть фразу">
            <button class='button' type="submit">Пошук</button>
        </form><br>
        <!-- Formularz przesyłania pliku -->
        <form method="post" enctype="multipart/form-data" action="skrypty/dodaj_plik.php">
            <input type="file" name="file" id="fileInput" onchange="updateFileMessage()">
            <label for="fileInput" id="fileInputLabel">Вибрати файл</label>
            <span id="fileMessage">файл не вибрано</span>
            <button class='button' type="submit" name="submit">завантажити файл</button>
        </form>
        <?php
        if(isset($_GET['error']) && $_GET['error'] == 1) {
            echo "<p class='error-message'>Помилка: не вдалося знайти файл для цього користувача.</p>";
        }
        if(isset($_GET['error']) && $_GET['error'] == 2) {
            echo "<p class='error-message'>Помилка: користувача не знайдено.</p>";
        }
        if(isset($_GET['error']) && $_GET['error'] == 3) {
            echo "<p class='error-message'>Помилка: помилка ідентифікатора файлу</p>";
        }
        if(isset($_GET['error']) && $_GET['error'] == 4) {
            echo "<p class='error-message'>Помилка: не вдалося завантажити файл</p>";
        }
        //Wyświetlanie plików użytkownika
        echo "<h2>Ваші файли</h2>";
        
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

        if ($result_files->num_rows > 0) {
            echo '<ul>';
            while ($row_file = $result_files->fetch_assoc()) {
                echo '<li><h3>' . htmlspecialchars(urldecode($row_file['nazwa']));
                echo '</h3></li><li> <form method="post" action="skrypty/pobierz_plik.php" style="display:inline-block;">
                    <input type="hidden" name="plik_id" value="' . $row_file['id'] . '">
                    <button class="button" type="submit" name="pobierz">Завантажити</button>
                    </form>';

                echo ' <form method="post" action="skrypty/usun_plik.php" style="display:inline-block;">
                    <input type="hidden" name="plik_id" value="' . $row_file['id'] . '">
                    <button class="button" type="submit" name="usun">Видалити</button>
                    </form>';

                // Udostępnianie pliku
                if(empty($row_file['kod'])) {
                    echo ' <form method="post" action="skrypty/udostepnij_plik.php" style="display:inline-block;">
                        <input type="hidden" name="plik_id" value="' . $row_file['id'] . '">
                        <button class="button" type="submit" name="udostepnij">Поділитися</button>
                        </form>';
                } else {
                    // Wyświetlanie linku do udostępnionego pliku
                    $link = $moja_strona . "ukr/skrypty/plik.php?kod=" . $row_file['kod'];
                    echo ' <button class="button" type="submit" onclick="copyToClipboard(\'' . $link . '\')">Копіювати посилання</button>
                    <!-- Przycisk przestań udostępniać -->
                    <form method="post" action="skrypty/przestan_udostepniac.php" style="display:inline-block;">
                    <input type="hidden" name="plik_id" value="' . $row_file['id'] . '">
                    <button class="button" type="submit" name="przestan_udostepniac">Припинити ділитися</button>
                    </form>';
                }

                echo '</li><li><br></li>';
            }
            echo '</ul>';
        } else {
            echo '<p>Немає файлів.</p>';
        }
        // Zamknięcie połączenia z bazą danych
        $stmt_files->close();
        $conn->close();
        ?>
    </div>
    <!-- Wyczyszczenie floatów -->
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
            alert("Посилання скопійовано в буфер обміну: " + text);
        }
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
