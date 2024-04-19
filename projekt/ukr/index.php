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
    </div>
</body>
</html>

