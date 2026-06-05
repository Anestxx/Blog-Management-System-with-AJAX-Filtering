<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/includes/auth.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$isEdit = $id > 0;
$error = '';

$blog = [
    'title' => '',
    'short_description' => '',
    'content' => '',
    'category' => '',
    'image_path' => '',
    'published_date' => date('Y-m-d')
];

if ($isEdit) {
    $stmt = $pdo->prepare('SELECT * FROM blogs WHERE id = :id LIMIT 1');
    $stmt->execute([':id' => $id]);
    $blogData = $stmt->fetch();

    if (!$blogData) {
        header('Location: dashboard.php');
        exit;
    }

    $blog = $blogData;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $shortDescription = trim($_POST['short_description'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $publishedDate = trim($_POST['published_date'] ?? date('Y-m-d'));
    $imagePath = $blog['image_path'] ?: 'assets/images/blog-default.svg';

    if ($title === '' || $content === '' || $category === '' || $publishedDate === '') {
        $error = 'Title, content, category and date are required.';
    } else {
        if (!empty($_FILES['image']['name'])) {
            $allowedTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif', 'image/svg+xml'];
            $fileType = mime_content_type($_FILES['image']['tmp_name']);

            if (!in_array($fileType, $allowedTypes, true)) {
                $error = 'Only JPG, PNG, WEBP, GIF or SVG images are allowed.';
            } else {
                $extension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
                $safeName = 'blog_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $extension;
                $targetDir = __DIR__ . '/../uploads/';
                $targetPath = $targetDir . $safeName;

                if (!is_dir($targetDir)) {
                    mkdir($targetDir, 0755, true);
                }

                if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
                    $imagePath = 'uploads/' . $safeName;
                } else {
                    $error = 'Image upload failed. Please try again.';
                }
            }
        }

        if ($error === '') {
            if ($isEdit) {
                $stmt = $pdo->prepare('UPDATE blogs SET title = :title, short_description = :short_description, content = :content, category = :category, image_path = :image_path, published_date = :published_date WHERE id = :id');
                $stmt->execute([
                    ':title' => $title,
                    ':short_description' => $shortDescription,
                    ':content' => $content,
                    ':category' => $category,
                    ':image_path' => $imagePath,
                    ':published_date' => $publishedDate,
                    ':id' => $id
                ]);
            } else {
                $stmt = $pdo->prepare('INSERT INTO blogs (title, short_description, content, category, image_path, published_date) VALUES (:title, :short_description, :content, :category, :image_path, :published_date)');
                $stmt->execute([
                    ':title' => $title,
                    ':short_description' => $shortDescription,
                    ':content' => $content,
                    ':category' => $category,
                    ':image_path' => $imagePath,
                    ':published_date' => $publishedDate
                ]);
            }

            header('Location: dashboard.php');
            exit;
        }

        $blog = [
            'title' => $title,
            'short_description' => $shortDescription,
            'content' => $content,
            'category' => $category,
            'image_path' => $imagePath,
            'published_date' => $publishedDate
        ];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $isEdit ? 'Edit Blog' : 'Add Blog' ?> | Admin Panel</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <header class="site-header">
        <nav class="navbar container">
            <a class="brand" href="dashboard.php">Admin Panel</a>
            <div class="nav-links">
                <a href="dashboard.php">Dashboard</a>
                <a href="../index.php">Website</a>
                <a href="logout.php">Logout</a>
            </div>
        </nav>
    </header>

    <main class="admin-page container">
        <section class="admin-card">
            <h1><?= $isEdit ? 'Edit Blog' : 'Add New Blog' ?></h1>
            <p>Fill all required fields and upload a blog image.</p>

            <?php if ($error): ?>
                <div class="alert alert-error"><?= e($error) ?></div>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data" class="form-grid">
                <div class="field span-2">
                    <label for="title">Title</label>
                    <input type="text" id="title" name="title" value="<?= e($blog['title']) ?>" required>
                </div>

                <div class="field span-2">
                    <label for="short_description">Short Description</label>
                    <textarea id="short_description" name="short_description" rows="3" placeholder="Brief blog summary"><?= e($blog['short_description']) ?></textarea>
                </div>

                <div class="field span-2">
                    <label for="content">Content</label>
                    <textarea id="content" name="content" required><?= e($blog['content']) ?></textarea>
                </div>

                <div class="field">
                    <label for="category">Category</label>
                    <input type="text" id="category" name="category" value="<?= e($blog['category']) ?>" placeholder="Admit Card, Result, News" required>
                </div>

                <div class="field">
                    <label for="published_date">Date</label>
                    <input type="date" id="published_date" name="published_date" value="<?= e($blog['published_date']) ?>" required>
                </div>

                <div class="field span-2">
                    <label for="image">Image</label>
                    <input type="file" id="image" name="image" accept="image/*">
                    <?php if (!empty($blog['image_path'])): ?>
                        <img src="../<?= e($blog['image_path']) ?>" alt="Current blog image" class="current-image">
                    <?php endif; ?>
                </div>

                <div class="action-row span-2">
                    <button type="submit" class="primary-btn"><?= $isEdit ? 'Update Blog' : 'Create Blog' ?></button>
                    <a href="dashboard.php" class="secondary-btn">Cancel</a>
                </div>
            </form>
        </section>
    </main>
</body>
</html>
