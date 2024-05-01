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
    <script>
        const themeToggle = document.getElementById('theme-toggle');
        // Sprawdź, czy użytkownik ma zapisany preferowany motyw
        let currentTheme = localStorage.getItem('theme');
        if (!currentTheme) {
            // Jeśli nie ma zapisanego motywu, ustaw domyślnie motyw jasny
            currentTheme = 'light';
            localStorage.setItem('theme', currentTheme);
        }
        document.documentElement.setAttribute('data-theme', currentTheme);
        // Obsługa kliknięcia przycisku zmiany motywu
        themeToggle.addEventListener('click', () => {
            const currentTheme = document.documentElement.getAttribute('data-theme');
            const newTheme = currentTheme === 'light' ? 'dark' : 'light';
            document.documentElement.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
        });
    </script>
</body>
</html>
