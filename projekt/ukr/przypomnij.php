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
    <div class="login-container">
        <h2>Я забув свій пароль</h2>
        <form action="skrypty/przypomnij.php" method="POST">
            <div class="form-group">
                <input type="email" name="email" placeholder="Електронна пошта" required>
            </div>
            <div class="form-group">
                <button type="submit" class="btn-login">Скинути пароль</button>
            </div>
        </form>
    </div>
</body>
</html>
