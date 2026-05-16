<?php
    session_start();
    require_once '../database/db-config.php';

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['user_id'], $_POST['role'])) {
        $user_id = (int)$_POST['user_id'];
        $new_role = (int)$_POST['role'];

        $stmt = $conn->prepare("UPDATE users SET role = ? WHERE ID = ?");
        $stmt->bind_param("ii", $new_role, $user_id);
        $stmt->execute();
        $stmt->close();

        header('Content-Type: application/json');
        echo json_encode(['success' => true]);
        exit;
    }

    $result = $conn->query("SELECT ID, username, role FROM users ORDER BY ID ASC");
    $users = $result->fetch_all(MYSQLI_ASSOC);
?>

<div class="users-panel">
    <h2 class="panel-title">👥 Felhasználók</h2>
    <div class="users-list">
        <?php foreach ($users as $user): ?>
            <div class="user-row" id="user-row-<?= $user['ID'] ?>">
                <span class="user-id">#<?= $user['ID'] ?></span>
                <span class="user-name"><?= htmlspecialchars($user['username']) ?></span>
                <select class="role-select" data-id="<?= $user['ID'] ?>" data-original="<?= $user['role'] ?>">
                    <option value="0" <?= $user['role'] == 0 ? 'selected' : '' ?>>Olvasó</option>
                    <option value="1" <?= $user['role'] == 1 ? 'selected' : '' ?>>Szerkesztő</option>
                    <option value="2" <?= $user['role'] == 2 ? 'selected' : '' ?>>Adminisztrátor</option>
                </select>
                <span class="save-status" id="status-<?= $user['ID'] ?>"></span>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<script>
document.querySelectorAll('.role-select').forEach(function(select) {
    select.addEventListener('change', async function() {
        const userId = this.getAttribute('data-id');
        const newRole = this.value;
        const statusEl = document.getElementById('status-' + userId);

        statusEl.textContent = '...';
        statusEl.className = 'save-status saving';

        const formData = new FormData();
        formData.append('user_id', userId);
        formData.append('role', newRole);

        try {
            const res = await fetch('pages/dashboard_users.php', { method: 'POST', body: formData });
            const data = await res.json();
            if (data.success) {
                statusEl.textContent = '✓ Mentve';
                statusEl.className = 'save-status saved';
                setTimeout(() => { statusEl.textContent = ''; }, 2000);
            }
        } catch (err) {
            statusEl.textContent = '✗ Hiba';
            statusEl.className = 'save-status error';
        }
    });
});
</script>