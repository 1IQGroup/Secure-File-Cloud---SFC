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
    header("location: ../rejestracja.php?error=2");
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
        echo "Помилка підготовки SQL-запиту: " . mysqli_error($polaczenie);
        exit;
    }
    if ($emailExists) {
        header("Location: ../rejestracja.php?error=1");
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
            echo "Помилка підготовки SQL-запиту: " . mysqli_error($polaczenie);
        }

        // Treść wiadomości
        $mail->CharSet = 'UTF-8';
        $mail->Encoding = 'base64';
        $mail->Subject = 'реєстрація';
        $mail->Body = "Дякуємо за реєстрацію на нашому сайті!<br>
        Щоб підтвердити свій обліковий запис, натисніть це посилання:<br>
        <a href='$moja_strona"."ukr/haslo.php?kod=$kod'>$moja_strona"."ukr/haslo.php?kod=$kod</a><br>
        Посилання дійсне протягом години.";

        // Wysłanie wiadomości
        $mail->send();
        echo 'Електронний лист надіслано на адресу: ' . $email;
        $insertQuery = "INSERT INTO blokada2 (ip) VALUES ('$ip')";
        mysqli_query($polaczenie, $insertQuery);
        
    } catch (Exception $e) {
        echo 'Під час надсилання повідомлення сталася помилка: ', $mail->ErrorInfo;
    }
}
?>
