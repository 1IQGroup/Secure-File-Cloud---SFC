<!--
    przestan_udostepniac.php
    skrypt odpowiedzialny za zaprzestanie udostępniania pliku
-->
<?php
session_start();
require_once('baza.php');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["przestan_udostepniac"]) && isset($_POST["plik_id"]) && isset($_SESSION['email'])) {
    $plik_id = $_POST["plik_id"];

    // Połączenie z bazą danych
    $conn = new mysqli($host, $uzytkownik_bd, $haslo_bd, $bd);

    // Sprawdzanie połączenia
    if ($conn->connect_error) {
        die("Błąd połączenia z bazą danych: " . $conn->connect_error);
    }

    // Pobranie ID użytkownika na podstawie adresu e-mail
    $user_email = $_SESSION['email'];
    $query_user = "SELECT id FROM uzytkownicy WHERE email = ?";
    $stmt_user = $conn->prepare($query_user);
    $stmt_user->bind_param("s", $user_email);
    $stmt_user->execute();
    $result_user = $stmt_user->get_result();

    if ($result_user->num_rows == 1) {
        $row_user = $result_user->fetch_assoc();
        $user_id = $row_user['id'];

        // Usunięcie kodu udostępniania z pliku
        $query_update = "UPDATE pliki SET kod = NULL WHERE id = ? AND wlasciciel = ?";
        $stmt_update = $conn->prepare($query_update);
        $stmt_update->bind_param("ii", $plik_id, $user_id);
        $stmt_update->execute();

        // Zamknięcie połączenia z bazą danych
        $stmt_update->close();
    } else {
        header("location: ../user.php?error=2");
    }

    // Zamknięcie połączenia z bazą danych
    $stmt_user->close();
    $conn->close();
    
    // Przekierowanie na stronę użytkownika
    header("Location: ../user.php");
    exit();
}
?>
