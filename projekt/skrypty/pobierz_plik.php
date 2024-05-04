<!--
    pobierz_plik.php
    skrypt odpowiedzialny za pobieranie plików
-->
<?php
session_start();

// Sprawdzenie czy użytkownik jest zalogowany
if (!isset($_SESSION['zalogowany']) || $_SESSION['zalogowany'] !== true) {
    // Jeżeli nie, przekieruj na stronę logowania
    header("location: ../index.php");
    exit;
}

// Sprawdzenie czy użytkownik ma uprawnienia użytkownika
if (!isset($_SESSION['typ_uzytkownika']) || $_SESSION['typ_uzytkownika'] !== 'user') {
    // Jeżeli nie, przekieruj na stronę główną
    header("location: ../index.php");
    exit;
}

// Sprawdzenie czy żądanie jest typu POST i czy zawiera ID pliku
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['plik_id'])) {
    // Pobranie ID pliku
    $file_id = $_POST['plik_id'];
    
    // Połączenie z bazą danych
    require_once('baza.php');
    $conn = new mysqli($host, $uzytkownik_bd, $haslo_bd, $bd);

    // Sprawdzenie połączenia
    if ($conn->connect_error) {
        die("Błąd połączenia z bazą danych: " . $conn->connect_error);
    }
    $user_email = $_SESSION['email'];
    // Pobranie ID użytkownika na podstawie adresu e-mail
    $query_user = "SELECT id FROM uzytkownicy WHERE email = ?";
    $stmt_user = $conn->prepare($query_user);
    $stmt_user->bind_param("s", $user_email);
    $stmt_user->execute();
    $result_user = $stmt_user->get_result();

    if ($result_user->num_rows == 1) {
        $row_user = $result_user->fetch_assoc();
        $user_id = $row_user['id'];

        // Pobranie informacji o pliku
        $query_file = "SELECT nazwa, plik FROM pliki WHERE id = ? AND wlasciciel = ?";
        $stmt_file = $conn->prepare($query_file);
        $stmt_file->bind_param("ii", $file_id, $user_id);
        $stmt_file->execute();
        $result_file = $stmt_file->get_result();

        if ($result_file->num_rows == 1) {
            $row_file = $result_file->fetch_assoc();
            $file_name = $row_file['nazwa'];
            $file_content = $row_file['plik'];

            // Ustawienie nagłówków dla pobierania pliku
            $file_name = urldecode($file_name);
            header("Content-Disposition: attachment; filename=\"$file_name\"");
            header("Content-Type: application/octet-stream");
            header("Content-Length: " . strlen($file_content));

            // Wyślij zawartość pliku do użytkownika
            echo $file_content;
            exit();
        } else {
            header("location: ../user.php?error=1");
        }

        // Zamknięcie połączenia z bazą danych
        $stmt_file->close();
    } else {
        header("location: ../user.php?error=2");
    }

    // Zamknięcie połączenia z bazą danych
    $stmt_user->close();
    $conn->close();
} else {
    header("location: ../user.php?error=3");
}
?>
