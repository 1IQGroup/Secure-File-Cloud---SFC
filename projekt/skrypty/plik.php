<!--
    plik.php
    skrypt odpowiedzialny za pobieranie udostępnionych plików
-->
<?php
require_once('baza.php');

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["kod"])) {
    $kod = $_GET["kod"];

    // Połączenie z bazą danych
    $conn = new mysqli($host, $uzytkownik_bd, $haslo_bd, $bd);

    // Sprawdzanie połączenia
    if ($conn->connect_error) {
        die("Błąd połączenia z bazą danych: " . $conn->connect_error);
    }

    // Znalezienie pliku na podstawie kodu
    $query = "SELECT nazwa, plik FROM pliki WHERE kod = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $kod);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $nazwa_pliku = $row['nazwa'];
        $plik = $row['plik'];

        // Ustawienie nagłówków do pobrania pliku
        header("Content-Disposition: attachment; filename=" . $nazwa_pliku);
        header("Content-Type: application/octet-stream");
        header("Content-Length: " . strlen($plik));

        // Wyświetlenie zawartości pliku
        echo $plik;
    } else {
        // Jeśli nie znaleziono pliku dla podanego kodu, zwróć odpowiedni komunikat błędu
        die("Nie można znaleźć pliku dla podanego kodu.");
    }

    // Zamknięcie połączenia z bazą danych
    $stmt->close();
    $conn->close();
    
    exit();
}
?>
