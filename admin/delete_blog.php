<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: dashboard.php');
    exit;
}

$id = isset($_POST['id']) ? (int) $_POST['id'] : 0;

if ($id > 0) {
    $stmt = $pdo->prepare('SELECT image_path FROM blogs WHERE id = :id LIMIT 1');
    $stmt->execute([':id' => $id]);
    $blog = $stmt->fetch();

    $deleteStmt = $pdo->prepare('DELETE FROM blogs WHERE id = :id');
    $deleteStmt->execute([':id' => $id]);

    if ($blog && !empty($blog['image_path']) && str_starts_with($blog['image_path'], 'uploads/')) {
        $file = __DIR__ . '/../' . $blog['image_path'];
        if (is_file($file)) {
            unlink($file);
        }
    }
}

header('Location: dashboard.php');
exit;
