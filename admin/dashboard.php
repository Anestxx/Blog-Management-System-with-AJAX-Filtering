<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/includes/auth.php';

$stmt = $pdo->query('SELECT * FROM blogs ORDER BY published_date DESC, id DESC');
$blogs = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | JobYaari Blogs</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <header class="site-header">
        <nav class="navbar container">
            <a class="brand" href="dashboard.php">Admin Panel</a>
            <div class="nav-links">
                <a href="../index.php">Website</a>
                <a href="logout.php">Logout</a>
            </div>
        </nav>
    </header>

    <main class="admin-page container">
        <div class="admin-toolbar">
            <div>
                <h1>Blog Management</h1>
                <p>Welcome, <?= e($_SESSION['admin_name'] ?? 'Admin') ?>. Manage all blog posts from here.</p>
            </div>
            <a href="blog_form.php" class="primary-btn">+ Add Blog</a>
        </div>

        <section class="admin-card">
            <div class="table-wrap">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!$blogs): ?>
                            <tr>
                                <td colspan="5">No blogs added yet.</td>
                            </tr>
                        <?php endif; ?>

                        <?php foreach ($blogs as $blog): ?>
                            <tr>
                                <td><img src="../<?= e($blog['image_path'] ?: 'assets/images/blog-default.svg') ?>" alt="" class="admin-thumb"></td>
                                <td>
                                    <strong><?= e($blog['title']) ?></strong><br>
                                    <span><?= e(excerpt($blog['short_description'] ?: $blog['content'], 70)) ?></span>
                                </td>
                                <td><span class="tag"><?= e($blog['category']) ?></span></td>
                                <td><?= date('d M Y', strtotime($blog['published_date'])) ?></td>
                                <td>
                                    <div class="action-row">
                                        <a href="../blog.php?id=<?= (int) $blog['id'] ?>" class="success-btn">View</a>
                                        <a href="blog_form.php?id=<?= (int) $blog['id'] ?>" class="secondary-btn">Edit</a>
                                        <form action="delete_blog.php" method="POST" onsubmit="return confirm('Delete this blog?');">
                                            <input type="hidden" name="id" value="<?= (int) $blog['id'] ?>">
                                            <button type="submit" class="danger-btn">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>
</body>
</html>
