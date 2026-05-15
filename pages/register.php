<?php
    require_once '../database/db-config.php';

    $error_msg = "";

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $user = isset($_POST['username']) ? trim($_POST['username']) : '';
        $pass = isset($_POST['password']) ? $_POST['password'] : '';
        $role = 0;

        if (strlen($user) < 3 || strlen($pass) < 4) {
            $error_msg = "Túl rövid felhasználónév vagy jelszó!";
        } else {
            $hashed_password = password_hash($pass, PASSWORD_DEFAULT);
            $sql = "INSERT INTO users (username, password, role) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);

            if ($stmt) {
                $stmt->bind_param("ssi", $user, $hashed_password, $role);
                try {
                    if ($stmt->execute()) {
                        echo "<script>alert('Sikeres regisztráció!'); loadPage('login.php');</script>";
                        exit;
                    }
                } catch (mysqli_sql_exception $e) {
                    $error_msg = ($e->getCode() == 1062) ? "Ez a név már foglalt!" : "Hiba: " . $e->getMessage();
                }
                $stmt->close();
            }
        }
    }
?>

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
        <form action="pages/register.php" method="POST">
            <label for="username">Felhasználónév:</label>
            <input type="text" name="username" required><br>

            <label for="password">Jelszó:</label>
            <input type="password" name="password" required><br>

            <input type="submit" value="Regisztráció"><br>
        </form>

        <div class="register-link">
            <a class="register-link" onclick="navigate('login')">Már van fiókod?</a>
        </div>
        <script src="scripts/script.js"></script>
    </div>
</body>
</html>