<?php
    session_start();
    require_once '../database/db-config.php';

    $user_id = $_SESSION['user_id'] ?? 0;
    $role = (int)($_SESSION['role'] ?? 0);

    if ($role == 0) {
        echo json_encode(['success' => false, 'error' => 'Nincs jogosultságod!']);
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {

        if ($_POST['action'] === 'create') {
            $title = trim($_POST['title'] ?? '');
            $description = trim($_POST['description'] ?? '');

            if (empty($title) || empty($description)) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'error' => 'Cím és tartalom megadása kötelező!']);
                exit;
            }

            $stmt = $conn->prepare("INSERT INTO blogs (userID, title, description) VALUES (?, ?, ?)");
            $stmt->bind_param("iss", $user_id, $title, $description);
            $stmt->execute();
            $stmt->close();

            header('Content-Type: application/json');
            echo json_encode(['success' => true]);
            exit;
        }

        if ($_POST['action'] === 'delete' && isset($_POST['blog_id'])) {
            $blog_id = (int)$_POST['blog_id'];
            $stmt = $conn->prepare("DELETE FROM blogs WHERE ID = ?");
            $stmt->bind_param("i", $blog_id);
            $stmt->execute();
            $stmt->close();

            header('Content-Type: application/json');
            echo json_encode(['success' => true]);
            exit;
        }
    }

    $result = $conn->query("SELECT blogs.ID, blogs.title, blogs.description, users.username FROM blogs JOIN users ON blogs.userID = users.ID ORDER BY blogs.ID DESC");
    $blogs = $result->fetch_all(MYSQLI_ASSOC);
?>

<div class="content-panel">
    <div class="content-panel-header">
        <h2 class="panel-title">📝 Tartalom</h2>
        <div class="btn-new" id="btn-new-article">+ Új cikk</div>
    </div>

    <div class="new-article-form" id="new-article-form" style="display:none;">
        <input type="text" class="article-input" id="article-title" placeholder="Cím...">
        <textarea class="article-textarea" id="article-description" placeholder="Tartalom..."></textarea>
        <div class="article-form-actions">
            <div class="btn-cancel" id="btn-cancel-article">Mégse</div>
            <div class="btn-save" id="btn-save-article">💾 Mentés</div>
        </div>
        <div id="article-error" class="article-error"></div>
    </div>

    <div class="blogs-list" id="blogs-list">
        <?php if (empty($blogs)): ?>
            <div class="dashboard-placeholder" style="height: 200px;">Még nincs egyetlen cikk sem.</div>
        <?php else: ?>
            <?php foreach ($blogs as $blog): ?>
                <div class="blog-row" id="blog-row-<?= $blog['ID'] ?>">
                    <div class="blog-info">
                        <span class="blog-title"><?= htmlspecialchars($blog['title']) ?></span>
                        <span class="blog-meta">✍ <?= htmlspecialchars($blog['username']) ?></span>
                    </div>
                    <div class="blog-delete" onclick="deleteBlog(<?= $blog['ID'] ?>)">🗑</div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<script>
document.getElementById('btn-new-article').addEventListener('click', function() {
    document.getElementById('new-article-form').style.display = 'flex';
    this.style.display = 'none';
});

document.getElementById('btn-cancel-article').addEventListener('click', function() {
    document.getElementById('new-article-form').style.display = 'none';
    document.getElementById('btn-new-article').style.display = 'flex';
    document.getElementById('article-title').value = '';
    document.getElementById('article-description').value = '';
    document.getElementById('article-error').textContent = '';
});

document.getElementById('btn-save-article').addEventListener('click', async function() {
    const title = document.getElementById('article-title').value.trim();
    const description = document.getElementById('article-description').value.trim();
    const errorEl = document.getElementById('article-error');
    errorEl.textContent = '';

    if (!title || !description) {
        errorEl.textContent = 'Cím és tartalom megadása kötelező!';
        return;
    }

    const formData = new FormData();
    formData.append('action', 'create');
    formData.append('title', title);
    formData.append('description', description);

    try {
        const res = await fetch('pages/dashboard_content.php', { method: 'POST', body: formData });
        const data = await res.json();
        if (data.success) {
            document.getElementById('btn-cancel-article').click();
            reloadBlogs();
        } else {
            errorEl.textContent = data.error;
        }
    } catch (err) {
        errorEl.textContent = 'Hálózati hiba történt.';
    }
});

async function deleteBlog(id) {
    if (!confirm('Biztosan törlöd ezt a cikket?')) return;

    const formData = new FormData();
    formData.append('action', 'delete');
    formData.append('blog_id', id);

    try {
        const res = await fetch('pages/dashboard_content.php', { method: 'POST', body: formData });
        const data = await res.json();
        if (data.success) {
            document.getElementById('blog-row-' + id).remove();
        }
    } catch (err) {
        alert('Hiba történt a törléskor.');
    }
}

async function reloadBlogs() {
    const res = await fetch('pages/dashboard_content.php');
    const html = await res.text();
    const temp = document.createElement('div');
    temp.innerHTML = html;
    const newList = temp.querySelector('#blogs-list');
    if (newList) {
        document.getElementById('blogs-list').innerHTML = newList.innerHTML;
    }
}
</script>