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

// Sprawdzenie czy żądanie jest typu POST i czy zawiera ID pliku do usunięcia
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['plik_id'])) {
    // Pobranie ID pliku do usunięcia
    $plik_id = $_POST['plik_id'];
    
    // Połączenie z bazą danych
    require_once('baza.php');
    $conn = new mysqli($host, $uzytkownik_bd, $haslo_bd, $bd);

    // Sprawdzenie połączenia
    if ($conn->connect_error) {
        die("Błąd połączenia z bazą danych: " . $conn->connect_error);
    }

    // Usunięcie pliku z bazy danych
    $query_delete = "DELETE FROM pliki WHERE id = ?";
    $stmt_delete = $conn->prepare($query_delete);
    $stmt_delete->bind_param("i", $plik_id);
    $stmt_delete->execute();
    
    // Zamknięcie połączenia z bazą danych
    $stmt_delete->close();
    $conn->close();

    // Przekierowanie na stronę użytkownika
    header("Location: ../user.php");
    exit();
} else {
    // Jeżeli nie, przekieruj na stronę użytkownika
    header("Location: ../user.php");
    exit();
}
?>
