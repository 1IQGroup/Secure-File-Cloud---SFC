<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Логін</title>
    <link rel="stylesheet" href="style/logowanie.css">
</head>
<body>
    <!-- motyw -->
    <button id="theme-toggle">Змінити тему</button>
    <!-- język -->
    <form method="post">
        <button id="language-toggle" type="submit" name="jezyk">polski</button>
    </form>
    <?php
    // Obsługa przekierowania na inny język
    if (isset($_POST['jezyk'])) {
        header("location: ../index.php");
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
        <h2>авторизуватися</h2>
        <form action="skrypty/login.php" method="POST">
            <div class="form-group">
                <input type="email" name="email" placeholder="Електронна пошта" required>
            </div>
            <div class="form-group">
                <input type="password" name="haslo" placeholder="Пароль" required>
            </div>
            <div class="form-group">
                <button type="submit" class="btn-login">авторизуватися</button>
            </div>
        </form>
        <div class="forgot-password-link">
            <span>Ви забули свій пароль? <a href="przypomnij.php">Скинути їх</a></span>
        </div>
        <div class="register-link">
            <span>У вас ще немає облікового запису? <a href="rejestracja.php">зареєструватися</a></span>
        </div>
        <?php
        if(isset($_GET['error']) && $_GET['error'] == 1) {
            echo "<p class='error-message'>Неправильна адреса електронної пошти або пароль</p>";
        }
        if(isset($_GET['error']) && $_GET['error'] == 2) {
            echo "<p class='error-message'>Забагато спроб входу<br>Повторіть спробу через 20 хвилин</p>";
        }
        if(isset($_GET['login']) && $_GET['login'] == 1) {
            echo "<p class='good-message'>Ви можете увійти зараз</p>";
        }
        ?>
    </div>
</body>
</html>

