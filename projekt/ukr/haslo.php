<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Встановлення пароля</title>
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
        <h2>введіть пароль</h2>
        <form action="skrypty/haslo.php?kod=<?php echo $kod; ?>" method="POST"> <!-- Wskazujemy skrypt obsługujący formularz -->
            <div class="form-group">
                <input type="password" name="haslo" id="haslo" placeholder="пароль" required>
            </div>
            <div class="form-group">
                <button type="submit" class="btn-login">Підтвердити</button>
            </div>
        </form>
    </div>
</body>
</html>