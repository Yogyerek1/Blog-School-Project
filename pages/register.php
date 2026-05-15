<?php
    require_once '../database/db-config.php';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $error_msg = "";
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
                        header('Content-Type: application/json');
                        echo json_encode(['success' => true]);
                        exit;
                    }
                } catch (mysqli_sql_exception $e) {
                    $error_msg = ($e->getCode() == 1062) ? "Ez a név már foglalt!" : "Hiba: " . $e->getMessage();
                }
                $stmt->close();
            }
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
    <form id="register-form">
        <label for="username">Felhasználónév:</label>
        <input type="text" name="username" required><br>

        <label for="password">Jelszó:</label>
        <input type="password" name="password" required><br>

        <div id="register-error" style="color: red; margin-bottom: 8px;"></div>

        <input type="submit" value="Regisztráció"><br>
    </form>

    <div class="register-link">
        <a class="register-link" onclick="navigate('login')">Már van fiókod?</a>
    </div>
</div>

<script>
document.getElementById('register-form').addEventListener('submit', async function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const errorDiv = document.getElementById('register-error');
    errorDiv.textContent = '';

    try {
        const res = await fetch('pages/register.php', { method: 'POST', body: formData });
        const data = await res.json();
        if (data.success) {
            alert('Sikeres regisztráció!');
            navigate('login');
        } else {
            errorDiv.textContent = data.error;
        }
    } catch (err) {
        errorDiv.textContent = 'Hálózati hiba történt.';
    }
});
</script>