<?php
    session_start();
    $logged_in = isset($_SESSION['username']);
    $role = $logged_in ? (int)$_SESSION['role'] : 0;
?>

<?php if ($role == 0): ?>
    <script>navigate('home');</script>
<?php else: ?>

<header>
    <link rel="stylesheet" href="styles/dashboard.css">
</header>

<div class="container">
    <div class="navigation-container">
        <div class="header-container">🛠 Dashboard</div><hr>
        <div class="nav-buttons-container">
            <?php if ($role >= 1): ?>
                <div class="nav-btn" id="btn-content" onclick="dashboardNavigate('content')">
                    📝 Tartalom
                </div>
            <?php endif; ?>
            <?php if ($role >= 2): ?>
                <div class="nav-btn" id="btn-users" onclick="dashboardNavigate('users')">
                    👥 Felhasználók
                </div>
            <?php endif; ?>
        </div>
    </div>
    <div class="content-container" id="dashboard-content">
        <div class="dashboard-placeholder">
            <span>Válassz a menüből</span>
        </div>
    </div>
</div>

<script>
function dashboardNavigate(panel) {
    document.querySelectorAll('.nav-btn').forEach(b => b.classList.remove('nav-btn-selected'));
    const activeBtn = document.getElementById('btn-' + panel);
    if (activeBtn) activeBtn.classList.add('nav-btn-selected');

    const content = document.getElementById('dashboard-content');

    if (panel === 'content') {
        fetch('pages/dashboard_content.php')
        .then(r => r.text())
        .then(html => {
            content.innerHTML = html;
            content.querySelectorAll('script').forEach(oldScript => {
                const newScript = document.createElement('script');
                newScript.textContent = oldScript.textContent;
                document.body.appendChild(newScript);
                oldScript.remove();
            });
        });
    } else if (panel === 'users') {
        fetch('pages/dashboard_users.php')
            .then(r => r.text())
            .then(html => {
                content.innerHTML = html;
                content.querySelectorAll('script').forEach(oldScript => {
                    const newScript = document.createElement('script');
                    newScript.textContent = oldScript.textContent;
                    document.body.appendChild(newScript);
                    oldScript.remove();
                });
            });
    }
}
</script>

<?php endif; ?>