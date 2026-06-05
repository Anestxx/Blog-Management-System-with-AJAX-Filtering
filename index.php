<?php
require_once __DIR__ . '/config/database.php';

$categoryStmt = $pdo->query("SELECT DISTINCT category FROM blogs ORDER BY category ASC");
$categories = $categoryStmt->fetchAll();
$searchValue = isset($_GET['search']) ? trim($_GET['search']) : '';
$categoryValue = isset($_GET['category']) ? trim($_GET['category']) : '';
$dateValue = isset($_GET['date']) ? trim($_GET['date']) : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JobYaari Blog Management System</title>
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

    <main>
        <section class="hero">
            <div class="container hero-content">
                <span class="eyebrow">Career Updates • Admit Cards • Results</span>
                <h1>Latest government job updates in one clean blog system.</h1>
                <p>Browse fresh posts, search by keyword, and filter instantly by category or date without refreshing the page.</p>
            </div>
        </section>

        <section class="container filter-section">
            <div class="section-heading">
                <h2>Explore Blogs</h2>
                <p>AJAX + jQuery filtering is enabled below.</p>
            </div>

            <form id="filterForm" class="filter-card">
                <div class="field">
                    <label for="search">Search</label>
                    <input type="text" id="search" name="search" placeholder="Search title, content, category..." value="<?= e($searchValue) ?>">
                </div>
                <div class="field">
                    <label for="category">Category</label>
                    <select id="category" name="category">
                        <option value="">All Categories</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= e($category['category']) ?>" <?= $categoryValue === $category['category'] ? 'selected' : '' ?>>
                                <?= e($category['category']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="field">
                    <label for="date">Date</label>
                    <input type="date" id="date" name="date" value="<?= e($dateValue) ?>">
                </div>
                <button type="button" id="clearFilters" class="secondary-btn">Clear</button>
            </form>

            <div id="blogResults" class="blog-grid" aria-live="polite">
                <div class="loader">Loading blogs...</div>
            </div>
        </section>
    </main>

    <footer class="footer">
        <div class="container">
            <p>© <?= date('Y') ?> JobYaari Blog Management System. Built with PHP, MySQL, jQuery and AJAX.</p>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="assets/js/blog-filter.js"></script>
</body>
</html>
