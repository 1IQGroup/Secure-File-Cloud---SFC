<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Сторінка користувача</title>
    <link rel="stylesheet" href="style/uzytkownik.css">
</head>
<body>
    <div class="lewy">
        <!-- Przycisk wylogowania -->
        <form method="post">
            <button id="logout" type="submit" name="wyloguj">Вийти</button>
        </form>
        <button id="theme-toggle">Змінити тему</button>
        <form method="post">
            <button id="language-toggle" type="submit" name="jezyk">polski</button>
        </form>
    </div>
    <div class="prawy">
        <!-- Formularz wyszukiwania plików -->
        <h2>Пошук файлів</h2>
        <form method="GET">
            <input type="text" name="fraza" placeholder="Введіть фразу">
            <button class='button' type="submit">Пошук</button>
        </form><br>
        <!-- Formularz przesyłania pliku -->
        <form method="post" enctype="multipart/form-data" action="skrypty/dodaj_plik.php">
            <input type="file" name="file" id="fileInput">
            <label for="fileInput" id="fileInputLabel">Вибрати файл</label>
            <span id="fileMessage">файл не вибрано</span>
            <button class='button' type="submit" name="submit">завантажити файл</button>
        </form>
    </div>
    <!-- Wyczyszczenie floatów -->
    <div class="clearfix"></div>
</body>
</html>
