<?php
    session_start();
    require_once '../database/db-config.php';

    $result = $conn->query("
        SELECT blogs.ID, blogs.title, blogs.description, users.username
        FROM blogs
        JOIN users ON blogs.userID = users.ID
        ORDER BY blogs.ID DESC
    ");
    $blogs = $result->fetch_all(MYSQLI_ASSOC);
?>

<div class="home-wrapper">
    <div class="home-hero">
        <h1 class="hero-title">📰 Blog</h1>
        <p class="hero-subtitle">Olvasd el legújabb cikkeinket</p>
    </div>

    <div class="articles-container">
        <?php if (empty($blogs)): ?>
            <div class="no-articles">Még nincsenek cikkek. Gyere vissza később!</div>
        <?php else: ?>
            <?php foreach ($blogs as $blog): ?>
                <div class="article-card">
                    <div class="article-header">
                        <h2 class="article-title"><?= htmlspecialchars($blog['title']) ?></h2>
                        <span class="article-author">✍ <?= htmlspecialchars($blog['username']) ?></span>
                    </div>
                    <p class="article-body"><?= nl2br(htmlspecialchars($blog['description'])) ?></p>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>