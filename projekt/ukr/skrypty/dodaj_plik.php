<?php
session_start();
require_once('baza.php');

//error_reporting(E_ALL);
//ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["file"]) && isset($_SESSION['email'])) {
    $file_name = $_FILES["file"]["name"];
    $file_temp = $_FILES["file"]["tmp_name"];
    $file_size = $_FILES["file"]["size"];
    $user_email = $_SESSION['email'];
    if($file_name == NULL){
        header("Location: ../user.php");
    }
    // Połączenie z bazą danych
    $conn = new mysqli($host, $uzytkownik_bd, $haslo_bd, $bd);
    // Sprawdzanie połączenia
    if ($conn->connect_error) {
        die("Błąd połączenia z bazą danych: " . $conn->connect_error);
    }

    // Pobranie sumy rozmiarów plików użytkownika
    $query_zajete_miejsce = "SELECT SUM(p.bajty) AS zajete_miejsce FROM uzytkownicy u INNER JOIN pliki p ON u.id = p.wlasciciel WHERE u.email = ?";
    $stmt_zajete_miejsce = $conn->prepare($query_zajete_miejsce);
    $stmt_zajete_miejsce->bind_param("s", $user_email);
    $stmt_zajete_miejsce->execute();
    $result_zajete_miejsce = $stmt_zajete_miejsce->get_result();
    $stmt_zajete_miejsce->close();

    if ($result_zajete_miejsce->num_rows == 1) {
        $row_zajete_miejsce = $result_zajete_miejsce->fetch_assoc();
        $zajete_miejsce_bajty = $row_zajete_miejsce['zajete_miejsce']; // Suma zajętego miejsca w bajtach

        // Konwersja bajtów na megabajty
        $zajete_miejsce_MB = round($zajete_miejsce_bajty / (1024 * 1024), 2); // Zaokrąglamy do dwóch miejsc po przecinku
    }

    // Pobranie miejsca użytkownika
    $query = "SELECT miejsce FROM uzytkownicy WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $user_email);
    $stmt->execute();
    $result = $stmt->get_result();
    $miejsce_uzytkownika = $result->fetch_assoc()['miejsce']; // Pobranie miejsca użytkownika
    $stmt->close();

    // Sprawdź czy plik został poprawnie przesłany
    if (round($file_size / (1024 * 1024), 2)+$zajete_miejsce_MB < $miejsce_uzytkownika) {
        $file_content = file_get_contents($file_temp);

        // Pobranie ID użytkownika na podstawie adresu e-mail
        $user_email = $_SESSION['email'];
        $query_user = "SELECT id FROM uzytkownicy WHERE email = ?";
        $stmt_user = $conn->prepare($query_user);
        $stmt_user->bind_param("s", $user_email);
        $stmt_user->execute();
        $result_user = $stmt_user->get_result();
        
        if ($result_user->num_rows == 1) {
            $row_user = $result_user->fetch_assoc();
            $owner_id = $row_user['id'];

            // Zakodowanie nazwy pliku
            $file_name_encoded = urlencode($file_name);

            // Wstawienie pliku do bazy danych
            $query = "INSERT INTO pliki (nazwa, plik, wlasciciel, bajty) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ssii", $file_name_encoded, $file_content, $owner_id, $file_size);
            $stmt->execute();
            
            // Zamknięcie połączenia z bazą danych
            $stmt->close();
        } else {
            header("location: ../user.php?error=2");
        }

        // Zamknięcie połączenia z bazą danych
        $stmt_user->close();
        $conn->close();
        
        // Przekierowanie z powrotem na stronę użytkownika
        header("Location: ../user.php");
        exit();
    } else {
        header("location: ../user.php?error=4");
    }
}
?>
