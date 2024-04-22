<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Нагадування</title>
    <link rel="stylesheet" href="style/logowanie.css">
</head>
<body>
    <!-- motyw -->
    <button id="theme-toggle">Змінити тему</button>
    <!-- język -->
    <form method="post">
        <button id="language-toggle" type="submit" name="jezyk">polski</button>
    </form>
    <form method="post">
        <button id="exit" type="submit" name="exit">X</button>
    </form>
    <?php
    // Obsługa przekierowania na inny język
    if (isset($_POST['jezyk'])) {
        header("location: ../przypomnij.php");
        exit;
    }
    // Obsługa cofnięcia się
    if (isset($_POST['exit'])) {
        header("location: index.php");
        exit;
    }
    ?>
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
    <div class="login-container">
        <h2>Я забув свій пароль</h2>
        <form action="skrypty/przypomnij.php" method="POST">
            <div class="form-group">
                <input type="email" name="email" placeholder="Електронна пошта" required>
            </div>
            <div class="form-group">
                <button type="submit" class="btn-login">Скинути пароль</button>
            </div>
            <?php
                if(isset($_GET['error']) && $_GET['error'] == 1) {
                    echo "<p class='error-message'>Надана електронна адреса не існує</p>";
                }
                if(isset($_GET['error']) && $_GET['error'] == 2) {
                    echo "<p class='error-message'>Надіслано забагато повідомлень<br>Повторіть спробу через годину</p>";
                }
            ?>
        </form>
    </div>
</body>
</html>
