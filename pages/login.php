<?php
    session_start();
    require_once '../database/db-config.php';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $error_msg = "";
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
                    $_SESSION['user_id'] = $row['ID'];
                    $_SESSION['username'] = $row['username'];
                    $_SESSION['role'] = $row['role'];

                    header('Content-Type: application/json');
                    echo json_encode(['success' => true]);
                    exit;
                } else {
                    $error_msg = "Hibás jelszó!";
                }
            } else {
                $error_msg = "Nincs ilyen felhasználó!";
            }
            $stmt->close();
        }

        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'error' => $error_msg]);
        exit;
    }
?>

<link rel="stylesheet" href="styles/user.css">

<div class="form">
    <div class="logo">
        <img src="resources/logo.svg" alt="logo" width="155" height="155">
    </div>
    <form id="login-form">
        <label for="username">Felhasználónév:</label>
        <input type="text" name="username" required><br>

        <label for="password">Jelszó:</label>
        <input type="password" name="password" required><br>

        <div id="login-error" style="color: red; margin-bottom: 8px;"></div>

        <input type="submit" value="Bejelentkezés"><br>
    </form>

    <div class="register-link">
        <a class="register-link" onclick="navigate('register')">Még nincs fiókod?</a>
    </div>
</div>

<script>
    document.getElementById('login-form').addEventListener('submit', async function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        const errorDiv = document.getElementById('login-error');
        errorDiv.textContent = '';

        try {
            const res = await fetch('pages/login.php', { method: 'POST', body: formData });
            const data = await res.json();
            if (data.success) {
                navigate('home');
            } else {
                errorDiv.textContent = data.error;
            }
        } catch (err) {
            errorDiv.textContent = 'Hálózati hiba történt.';
        }
    });
</script>