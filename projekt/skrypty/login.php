<!--
    login.php
    skrypt odpowiedzialny za logowanie na stronie
-->
<?php
session_start();

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
$query = "SELECT * FROM blokada WHERE ip='$ip'";
$result = mysqli_query($polaczenie, $query);

if (mysqli_num_rows($result) > 0) {
    // Adres IP użytkownika znajduje się w tabeli blokada, przekierowanie na stronę główną
    header("location: ../index.php?error=2");
    exit;
}

// Sprawdzenie czy formularz został wysłany
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Pobranie danych z formularza
    $email = $_POST['email'];
    $haslo = $_POST['haslo'];

    // Zapytanie SQL w celu sprawdzenia czy istnieje użytkownik o podanych danych logowania
    $query = "SELECT * FROM uzytkownicy WHERE email=? AND haslo=SHA2(?,256)";
    
    // Przygotowanie zapytania SQL
    $stmt = mysqli_prepare($polaczenie, $query);
    
    // Sprawdzenie czy zapytanie zostało prawidłowo przygotowane
    if ($stmt) {
        // Związanie parametrów
        mysqli_stmt_bind_param($stmt, "ss", $email, $haslo);
        
        // Wykonanie zapytania
        mysqli_stmt_execute($stmt);
        
        // Pobranie wyniku zapytania
        $result = mysqli_stmt_get_result($stmt);
        
        // Sprawdzenie czy znaleziono użytkownika w bazie danych
        if (mysqli_num_rows($result) == 1) {
            // Pobranie danych użytkownika
            $row = mysqli_fetch_assoc($result);
            
            // Ustawienie zmiennej sesji na true po poprawnym zalogowaniu
            $_SESSION['zalogowany'] = true;
            
            // Sprawdzenie typu użytkownika i ustawienie odpowiedniej zmiennej sesji
            $_SESSION['typ_uzytkownika'] = $row['admin'] == 1 ? 'admin' : 'user';

            // przypisanie emaila
            $_SESSION['email'] = $email;

            // Przekierowanie na odpowiednią stronę w zależności od typu użytkownika
            if ($_SESSION['typ_uzytkownika'] == 'admin') {
                header("location: ../admin.php");
            } else {
                header("location: ../user.php");
            }
            exit; // Warto zakończyć dalsze wykonywanie skryptu po przekierowaniu
        } else {
            // Jeżeli nie znaleziono użytkownika, zapisz adres IP użytkownika w tabeli blokada
            $insertQuery = "INSERT INTO blokada (ip) VALUES ('$ip')";
            mysqli_query($polaczenie, $insertQuery);
            // Jeżeli nie znaleziono użytkownika, przekieruj z komunikatem błędu
            header("location: ../index.php?error=1");
            exit; // Warto zakończyć dalsze wykonywanie skryptu po przekierowaniu
        }
    } else {
        // Błąd przygotowania zapytania
        die("Błąd przygotowania zapytania SQL: " . mysqli_error($polaczenie));
    }
}

// Zamknięcie połączenia z bazą danych
mysqli_close($polaczenie);
?>

