<!--
    przypomnij.php
    skrypt odpowiedzialny za wysłanie emaila do użytkownika jeżeli ten zapomniał hasła
-->
<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include Composer's autoloader
require 'vendor/autoload.php';

// Połączenie z bazą danych
include('baza.php');

// Połączenie z bazą danych
$polaczenie = mysqli_connect($host, $uzytkownik_bd, $haslo_bd, $bd);

// Sprawdzenie czy połączenie udało się nawiązać
if (!$polaczenie) {
    die("Błąd połączenia z bazą danych: " . mysqli_connect_error());
}

// Pobranie adresu IP użytkownika
$ip = $_SERVER['REMOTE_ADDR'];

// Sprawdzenie, czy adres IP użytkownika jest zablokowany w bazie danych
$query = "SELECT * FROM blokada2 WHERE ip='$ip'";
$result = mysqli_query($polaczenie, $query);

if (mysqli_num_rows($result) > 0) {
    // Adres IP użytkownika znajduje się w tabeli blokada, przekierowanie na stronę główną
    header("location: ../przypomnij.php?error=2");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Pobranie adresu e-mail z formularza
    $email = $_POST['email'];
    
    // Sprawdzenie, czy podany e-mail istnieje już w bazie danych
    $query = "SELECT * FROM uzytkownicy WHERE email = ?";
    if ($stmt = mysqli_prepare($polaczenie, $query)) {
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        $emailExists = mysqli_stmt_num_rows($stmt) > 0;
        mysqli_stmt_close($stmt);
    } else {
        echo "Błąd przygotowania zapytania SQL: " . mysqli_error($polaczenie);
        exit;
    }
    mysqli_close($polaczenie);
    if (!$emailExists) {
        header("Location: ../przypomnij.php?error=1");
        exit;
    }

    // Tworzenie nowej instancji PHPMailera
    $mail = new PHPMailer(true); // true oznacza, że zostanie wyrzucony wyjątek w przypadku błędu

    try {
        // Konfiguracja serwera SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp-relay.brevo.com';
        $mail->SMTPAuth = true;
        $mail->Username = '766534001@smtp-brevo.com'; // Twój adres e-mail
        $mail->Password = 'HUDjbJ1O3YPVxfQy'; // Twoje hasło do konta
        $mail->SMTPSecure = 'auto';
        $mail->Port = 587;

        // Konfiguracja nadawcy i odbiorcy
        $mail->setFrom('martynskita@gmail.com', 'noreply@1iqteam.pl'); // Twój adres e-mail jako nadawca
        $mail->addAddress($email); // Adres e-mail odbiorcy

        // Treść wiadomości
        $mail->isHTML(true); // Ustawienie formatu wiadomości na HTML
        
        // Wygenerowanie losowego kodu
        $kod = bin2hex(random_bytes(10)); // Długość 20 znaków w formacie szesnastkowym
        
        // Dodanie danych do tabeli rejestracja
        $polaczenie = mysqli_connect($host, $uzytkownik_bd, $haslo_bd, $bd);
        $query = "INSERT INTO rejestracja (email, kod) VALUES (?, ?)";
    
        // Przygotowanie i wykonanie instrukcji przygotowanej
        if ($stmt = mysqli_prepare($polaczenie, $query)) {
            // Związanie parametrów
            mysqli_stmt_bind_param($stmt, "ss", $email, $kod);
        
            // Wykonanie zapytania
            mysqli_stmt_execute($stmt);

            // Zwolnienie wyniku zapytania
            mysqli_stmt_close($stmt);
        } else {
            // Obsługa błędu przygotowania zapytania
            echo "Błąd przygotowania zapytania SQL: " . mysqli_error($polaczenie);
        }
        // Zamknięcie połączenia z bazą danych (przeniesione na koniec skryptu)
        // mysqli_close($polaczenie);

        // Treść wiadomości
        $mail->CharSet = 'UTF-8';
        $mail->Encoding = 'base64';
        $mail->Subject = 'odzyskiwanie hasla';
        $mail->Body = "Zmien swoje haslo tutaj:<br>
        <a href='$moja_strona"."haslo.php?kod=$kod'>$moja_strona"."haslo.php?kod=$kod</a><br>
        Link wazny przez godzine.";

        // Wysłanie wiadomości
        $mail->send();
        echo 'Wiadomość e-mail została wysłana na adres: ' . $email;
        $insertQuery = "INSERT INTO blokada2 (ip) VALUES ('$ip')";
        mysqli_query($polaczenie, $insertQuery);
        
    } catch (Exception $e) {
        echo 'Wystąpił błąd podczas wysyłania wiadomości: ', $mail->ErrorInfo;
    }

    // Zamknięcie połączenia z bazą danych (przeniesione na koniec skryptu)
    mysqli_close($polaczenie);
}
?>
