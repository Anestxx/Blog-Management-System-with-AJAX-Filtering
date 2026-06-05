<?php
/**
 * Database connection file
 * Update these values based on your local/live server database settings.
 */

$host = 'sql104.infinityfree.com';
$dbname = 'if0_42106401_blogdb';
$username = 'if0_42106401';
$password = 'AxnftDhtf7pB';

try {
    $pdo = new PDO("mysql:host={$host};dbname={$dbname};charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die('Database connection failed: ' . $e->getMessage());
}

function e($value) {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function excerpt($text, $limit = 130) {
    $cleanText = trim(strip_tags((string) $text));
    if (mb_strlen($cleanText) <= $limit) {
        return $cleanText;
    }
    return mb_substr($cleanText, 0, $limit) . '...';
}
