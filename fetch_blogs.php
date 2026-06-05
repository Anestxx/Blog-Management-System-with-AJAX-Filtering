<?php
require_once __DIR__ . '/config/database.php';

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$category = isset($_GET['category']) ? trim($_GET['category']) : '';
$date = isset($_GET['date']) ? trim($_GET['date']) : '';

$query = "SELECT * FROM blogs WHERE 1=1";
$params = [];

if ($search !== '') {
    $query .= " AND (title LIKE :search OR short_description LIKE :search OR content LIKE :search OR category LIKE :search)";
    $params[':search'] = '%' . $search . '%';
}

if ($category !== '') {
    $query .= " AND category = :category";
    $params[':category'] = $category;
}

if ($date !== '') {
    $query .= " AND published_date = :published_date";
    $params[':published_date'] = $date;
}

$query .= " ORDER BY published_date DESC, id DESC";

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$blogs = $stmt->fetchAll();

if (!$blogs) {
    echo '<div class="empty-state">';
    echo '<h3>No blogs found</h3>';
    echo '<p>Try changing the search keyword, category, or date filter.</p>';
    echo '</div>';
    exit;
}

foreach ($blogs as $blog):
    $imagePath = !empty($blog['image_path']) ? $blog['image_path'] : 'assets/images/blog-default.svg';
?>
    <article class="blog-card">
        <a href="blog.php?id=<?= (int) $blog['id'] ?>" class="blog-image-wrap">
            <img src="<?= e($imagePath) ?>" alt="<?= e($blog['title']) ?>" class="blog-image">
        </a>
        <div class="blog-card-body">
            <div class="blog-meta">
                <span class="tag"><?= e($blog['category']) ?></span>
                <span><?= date('d M Y', strtotime($blog['published_date'])) ?></span>
            </div>
            <h3><a href="blog.php?id=<?= (int) $blog['id'] ?>"><?= e($blog['title']) ?></a></h3>
            <p><?= e($blog['short_description'] ?: excerpt($blog['content'])) ?></p>
            <a class="read-more" href="blog.php?id=<?= (int) $blog['id'] ?>">Read full blog →</a>
        </div>
    </article>
<?php endforeach; ?>
