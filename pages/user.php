<?php
    session_start();
    $logged_in = isset($_SESSION['username']);
    $username = $logged_in ? htmlspecialchars($_SESSION['username']) : '';
    $role = $logged_in ? (int)$_SESSION['role'] : 0;
?>

<?php if (!$logged_in): ?>
    <script>navigate('login');</script>
<?php else: ?>
<header>
    <link rel="stylesheet" href="styles/user.css">
</header>
<div class="form user-card">
    <div class="logo">
        <img src="resources/user.svg" alt="user" width="100" height="100">
    </div>

    <h2 class="user-greeting">Szia, <span class="user-name"><?= $username ?></span>!</h2>
    <p class="user-role-label"><?php
        if ($role == 0) echo 'Olvasó';
        if ($role == 1) echo 'Szerkesztő';
        if ($role == 2) echo 'Adminisztrátor';
    ?></p>

    <div class="user-actions">
        <?php if ($role > 0): ?>
            <div class="btn btn-dashboard" onclick="navigate('dashboard')">
                🛠 Dashboard
            </div>
        <?php endif; ?>
        <div class="btn btn-logout" id="logout-btn">Kijelentkezés</div>
    </div>
</div>

<script>
document.getElementById('logout-btn').addEventListener('click', async function() {
    try {
        const res = await fetch('pages/logout.php', { method: 'POST' });
        const data = await res.json();
        if (data.success) {
            navigate('home');
        }
    } catch (err) {
        alert('Hiba történt a kijelentkezésnél.');
    }
});
</script>

<?php endif; ?>