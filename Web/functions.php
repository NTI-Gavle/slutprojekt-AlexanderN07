<?php
require_once 'db.php';
function e(?string $value): string{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}
function current_user_id(): int{
    return isset($_SESSION['user_id'])
        ? (int) $_SESSION['user_id']
        : null;
}
function create_post(int $userId, string $content): void{
    global $pdo;
    if(trim($content) === ''){
        return;
    }
    $stmt = $pdo->prepare("
        INSERT INTO posts(
            user_id,
            content,
            created_at,
            updated_at,
            is_deleted
        )
        VALUES (?, ?, NOW(), NOW(), 0)
    ");
    $stmt->execute([
        $userId,
        $content
    ]);
}
function get_posts(): array{
    global $pdo;
    $sql = "
        SELECT
        p.*,
        u.username,
        u.profile_picture_url,
        (SELECT COUNT(*) FROM likes l WHERE l.post_id = p.id) AS like_count,
        (SELECT COUNT(*) FROM favorites f WHERE f.post_id = p.id) AS favorite_count,
        (SELECT COUNT(*) FROM comments c WHERE c.post_id = p.id and c.is_deleted = 0) as comment_count
        FROM posts p
        JOIN users u
            ON u.id = p.user_id
        WHERE p.is_deleted = 0
        ORDER BY p.created_at DESC
        LIMIT 50
    ";
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll();
}