<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/styles/user.css">
    <title>Felhasználó</title>
</head>
<body>
    <div class="login-form">
        <form action="user.php" method="POST">
            <label for="username">Felhasználónév:</label>
            <input type="text" name="username" required><br>

            <label for="password">Jelszó:</label>
            <input type="text" name="password" required><br>

            <input type="submit" value="Bejelentkezés">
        </form>
    </div>
</body>
</html>