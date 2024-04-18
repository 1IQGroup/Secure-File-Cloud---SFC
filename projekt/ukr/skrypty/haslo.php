<?php
// Sprawdzenie, czy dane zostały przesłane z formularza
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_GET['kod'])) {
    // Pobranie danych z formularza
    $haslo = $_POST['haslo'];
    $kod = $_GET['kod'];

    // Połączenie z bazą danych
    include('baza.php');
    $polaczenie = mysqli_connect($host, $uzytkownik_bd, $haslo_bd, $bd);

    // Sprawdzenie, czy udało się połączyć z bazą danych
    if (!$polaczenie) {
        die("Błąd połączenia z bazą danych: " . mysqli_connect_error());
    }

    // Pobranie emaila użytkownika na podstawie kodu z tabeli rejestracja
    $query = "SELECT email FROM rejestracja WHERE kod='$kod'";
    $result = mysqli_query($polaczenie, $query);

    if ($result && mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        $email = $row['email'];

        // Wstawienie nowego hasła i emaila użytkownika do tabeli uzytkownicy
        // Sprawdzenie, czy adres e-mail istnieje w bazie danych
        $checkQuery = "SELECT * FROM uzytkownicy WHERE email = '$email'";
        $checkResult = mysqli_query($polaczenie, $checkQuery);

        if (mysqli_num_rows($checkResult) > 0) {
            // Adres e-mail istnieje - wykonaj zapytanie aktualizacji hasła
            $updateQuery = "UPDATE uzytkownicy SET haslo = SHA2('$haslo', 256) WHERE email = '$email'";
            $updateResult = mysqli_query($polaczenie, $updateQuery);
        } else {
            // Adres e-mail nie istnieje - wykonaj zapytanie dodania nowego użytkownika
            $insertQuery = "INSERT INTO uzytkownicy (email, haslo) VALUES ('$email', SHA2('$haslo', 256))";
            $insertResult = mysqli_query($polaczenie, $insertQuery);
        }
        if ($insertResult||$updateResult) {
            // Usunięcie rekordu z tabeli rejestracja
            $deleteQuery = "DELETE FROM rejestracja WHERE kod='$kod'";
            $deleteResult = mysqli_query($polaczenie, $deleteQuery);
            if ($deleteResult) {
                header("location: ../index.php?login=1");
            } else {
                echo "Під час видалення запису з реєстраційної таблиці сталася помилка.";
            }
        } else {
            echo "Під час збереження нового пароля бази даних сталася помилка.";
        }
    } else {
        echo "Для вказаного коду не знайдено жодного користувача.";
    }

    // Zamknięcie połączenia z bazą danych
    mysqli_close($polaczenie);
} else {
    // Jeśli dane nie zostały przesłane, przekierowanie na stronę główną
    header("location: ../index.php");
    exit;
}
?>
