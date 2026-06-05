<?php
require_once __DIR__ . '/config/database.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$stmt = $pdo->prepare("SELECT * FROM blogs WHERE id = :id LIMIT 1");
$stmt->execute([':id' => $id]);
$blog = $stmt->fetch();

if (!$blog) {
    http_response_code(404);
}

$categoryStmt = $pdo->query("SELECT DISTINCT category FROM blogs ORDER BY category ASC");
$categories = $categoryStmt->fetchAll();
$recentStmt = $pdo->prepare("SELECT id, title, published_date FROM blogs WHERE id != :id ORDER BY published_date DESC, id DESC LIMIT 4");
$recentStmt->execute([':id' => $id]);
$recentBlogs = $recentStmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $blog ? e($blog['title']) : 'Blog not found' ?> | JobYaari Blogs</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <header class="site-header">
        <nav class="navbar container">
            <a class="brand" href="index.php">JobYaari Blogs</a>
            <div class="nav-links">
                <a href="index.php">Blogs</a>
                <a href="admin/login.php">Admin</a>
            </div>
        </nav>
    </header>

    <main class="container detail-layout">
        <?php if (!$blog): ?>
            <section class="empty-state single-empty">
                <h1>Blog not found</h1>
                <p>The blog you are looking for may have been removed.</p>
                <a href="index.php" class="primary-btn">Back to Blogs</a>
            </section>
        <?php else: ?>
            <article class="blog-detail">
                <a href="index.php" class="back-link">← Back to all blogs</a>
                <div class="blog-meta detail-meta">
                    <span class="tag"><?= e($blog['category']) ?></span>
                    <span><?= date('d M Y', strtotime($blog['published_date'])) ?></span>
                </div>
                <h1><?= e($blog['title']) ?></h1>
                <img src="<?= e($blog['image_path'] ?: 'assets/images/blog-default.svg') ?>" alt="<?= e($blog['title']) ?>" class="detail-image">
                <p class="lead-text"><?= e($blog['short_description']) ?></p>
                <div class="content-body">
                    <?= nl2br(e($blog['content'])) ?>
                </div>
            </article>

            <aside class="sidebar-card">
                <h3>Search more blogs</h3>
                <form action="index.php" method="GET" class="sidebar-search">
                    <input type="text" name="search" placeholder="Search blogs...">
                    <select name="category">
                        <option value="">All Categories</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= e($category['category']) ?>"><?= e($category['category']) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <input type="date" name="date">
                    <button type="submit" class="primary-btn full-width">Apply Filter</button>
                </form>

                <div class="recent-posts">
                    <h3>Recent Posts</h3>
                    <?php foreach ($recentBlogs as $recent): ?>
                        <a href="blog.php?id=<?= (int) $recent['id'] ?>" class="recent-link">
                            <strong><?= e($recent['title']) ?></strong>
                            <span><?= date('d M Y', strtotime($recent['published_date'])) ?></span>
                        </a>
                    <?php endforeach; ?>
                </div>
            </aside>
        <?php endif; ?>
    </main>

    <footer class="footer">
        <div class="container">
            <p>© <?= date('Y') ?> JobYaari Blog Management System.</p>
        </div>
    </footer>
</body>
</html>
