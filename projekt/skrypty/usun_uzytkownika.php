<?php
// Połączenie z bazą danych
require_once('baza.php');
$conn = new mysqli($host, $uzytkownik_bd, $haslo_bd, $bd);

// Sprawdzenie połączenia
if ($conn->connect_error) {
    die("Błąd połączenia z bazą danych: " . $conn->connect_error);
}

if(isset($_POST['usun'])) {
    // Pobranie emaila użytkownika do usunięcia
    $email = $_POST['email'];

    // Usunięcie wszystkich plików użytkownika
    $query_usun_pliki = "DELETE FROM pliki WHERE wlasciciel IN (SELECT id FROM uzytkownicy WHERE email = ?)";
    $stmt_usun_pliki = $conn->prepare($query_usun_pliki);
    $stmt_usun_pliki->bind_param("s", $email);
    $stmt_usun_pliki->execute();
    $stmt_usun_pliki->close();

    // Usunięcie użytkownika
    $query_usun_uzytkownika = "DELETE FROM uzytkownicy WHERE email = ?";
    $stmt_usun_uzytkownika = $conn->prepare($query_usun_uzytkownika);
    $stmt_usun_uzytkownika->bind_param("s", $email);
    $stmt_usun_uzytkownika->execute();

    // Informacja o powodzeniu lub błędzie
    if ($stmt_usun_uzytkownika->affected_rows > 0) {
        header("location: ../admin.php");
    } else {
        header("location: ../admin.php");
    }

    // Zamknięcie połączenia
    $stmt_usun_uzytkownika->close();
    $conn->close();
}
?>