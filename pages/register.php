<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/user.css">
    <title>Felhasználó</title>
</head>
<body>
    <div class="form">
        <div class="logo">
            <img src="resources/logo.svg" alt="logo" width="155" height="155">
        </div>
        <form action="register.php" method="POST">
            <label for="username">Felhasználónév:</label>
            <input type="text" name="username" required><br>

            <label for="password">Jelszó:</label>
            <input type="password" name="password" required><br>

            <input type="submit" value="Regisztráció"><br>
        </form>

        <div class="register-link">
            <a class="register-link" onclick="loadPage('login.php')">Már van fiókod?</a>
        </div>
        <script src="scripts/script.js"></script>
    </div>
</body>
</html>