<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Strona użytkownika</title>
    <link rel="stylesheet" href="style/uzytkownik.css">
</head>
<body>
    <div class="lewy">
        <!-- Przycisk wylogowania -->
        <form method="post">
            <button id="logout" type="submit" name="wyloguj">Wyloguj</button>
        </form>
        <button id="theme-toggle">Zmień motyw</button>
        <form method="post">
            <button id="language-toggle" type="submit" name="jezyk">українська</button>
        </form>
    </div>
    <div class="prawy">
        <!-- Formularz wyszukiwania plików -->
        <h2>Wyszukiwanie plików</h2>
        <form method="GET">
            <input type="text" name="fraza" placeholder="Wprowadź frazę">
            <button class='button' type="submit">Szukaj</button>
        </form><br>
        <!-- Formularz przesyłania pliku -->
        <form method="post" enctype="multipart/form-data" action="skrypty/dodaj_plik.php">
            <input type="file" name="file" id="fileInput">
            <label for="fileInput" id="fileInputLabel">Wybierz plik</label>
            <span id="fileMessage">Nie wybrano pliku</span>
            <button class='button' type="submit" name="submit">Prześlij plik</button>
        </form>
    </div>
    <!-- Wyczyszczenie floatów -->
    <div class="clearfix"></div>
</body>
</html>
