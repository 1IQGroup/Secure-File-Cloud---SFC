<!--
    zmien_miejsce.php
    skrypt odpowiedzialny za zmianę miejsca które przysługuje użytkownikowi
-->
<?php
// Połączenie z bazą danych
require_once('baza.php');
$conn = new mysqli($host, $uzytkownik_bd, $haslo_bd, $bd);

// Sprawdzenie połączenia
if ($conn->connect_error) {
    die("Błąd połączenia z bazą danych: " . $conn->connect_error);
}

if(isset($_POST['zmien'])) {
    // Pobranie nowej wartości z formularza
    $nowe_miejsce = $_POST['nowe_miejsce'];
    $email = $_POST['email']; // Dodane pole ukryte z wartością email

    // Aktualizacja wartości w bazie danych
    $query_update = "UPDATE uzytkownicy SET miejsce = ? WHERE email = ?";
    $stmt_update = $conn->prepare($query_update);
    $stmt_update->bind_param("ss", $nowe_miejsce, $email);
    $stmt_update->execute();

    // Informacja o powodzeniu lub błędzie
    if ($stmt_update->affected_rows > 0) {
        header("location: ../admin.php");
    } else {
        header("location: ../admin.php");
    }

    // Zamknięcie połączenia
    $stmt_update->close();
    $conn->close();
}
?>