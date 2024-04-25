<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Сторінка адміністратора</title>
    <link rel="stylesheet" href="style/uzytkownik.css">
</head>
<body>
    <div class="lewy">
        <h1>Ласкаво просимо на сайт адміністратора</h1>
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
        <!-- Dodatkowy kod strony -->
        <h3>Список користувачів:</h3>
        <table>
            <tr>
                <th>Електронна пошта</th>
                <th>Використаний простір (МБ)</th>
                <th>Обмеження (МБ)</th>
            </tr>
        <table>
    </div>
</body>
</html>
