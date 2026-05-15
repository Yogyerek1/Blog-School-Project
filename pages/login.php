<?php
    session_start();
    require_once '../database/db-config.php';

    $error_msg = "";

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $user_input = isset($_POST['username']) ? trim($_POST['username']) : '';
        $pass_input = isset($_POST['password']) ? $_POST['password'] : '';

        $sql = "SELECT ID, username, password, role FROM users WHERE username = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("s", $user_input);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($row = $result->fetch_assoc()) {
                if (password_verify($pass_input, $row['password'])) {
                    $_SESSION['user_id'] = $row['id'];
                    $_SESSION['username'] = $row['username'];
                    $_SESSION['role'] = $row['role'];

                    echo "<script>alert('Sikeres belépés!'); navigate('home');</script>";
                    exit;
                } else {
                    $error_msg = "Hibás jelszó!";
                }
            } else {
                $error_msg = "Nincs ilyen felhasználó!";
            }
            $stmt->close();
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
        <form action="pages/login.php" method="POST">
            <label for="username">Felhasználónév:</label>
            <input type="text" name="username" required><br>

            <label for="password">Jelszó:</label>
            <input type="password" name="password" required><br>

            <input type="submit" value="Bejelentkezés"><br>
        </form>

        <div class="register-link">
            <a class="register-link" onclick="navigate('register')">Még nincs fiókod?</a>
        </div>
        <script src="scripts/script.js"></script>
    </div>
</body>
</html>