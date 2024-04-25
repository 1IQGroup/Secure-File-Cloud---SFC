<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Strona administratora</title>
    <link rel="stylesheet" href="style/uzytkownik.css">
</head>
<body>
    <div class="lewy">
        <h1>Witaj na stronie administratora</h1>
        <!-- Przycisk wylogowania -->
        <form method="post">
            <button id="logout" type="submit" name="wyloguj">Wyloguj</button>
        </form>
        <!-- motyw -->
        <button id="theme-toggle">Zmień motyw</button>
        <!-- język -->
        <form method="post">
            <button id="language-toggle" type="submit" name="jezyk">українська</button>
        </form>
    </div>
    <div class="prawy">
        <!-- Dodatkowy kod strony -->
        <h3>Lista użytkowników:</h3>
        <table>
            <tr>
                <th>Email</th>
                <th>Zajęte miejsce (MB)</th>
                <th>Ograniczenie (MB)</th>
            </tr>
        <table>
    </div>
</body>
</html>
