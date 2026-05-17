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
function get_post(int $postId): ?array{
    global $pdo;
    $stmt = $pdo->prepare("
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
        WHERE p.id = ?
        LIMIT 1
    ");
    $stmt->execute([$postId]);
    $post = $stmt->fetch();
    return $post ?: null;
}
function create_comment(int $postId, int $userId, string $content, ?int $parentCommentId = null): void{
    global $pdo;
    if(trim($content) === ''){
        return;
    }
    $stmt = $pdo->prepare("
        INSERT INTO comments(
            post_id,
            parent_comment_id,
            user_id,
            content,
            created_at,
            updated_at,
            is_deleted
        )
        VALUES (?, ?, ?, ?, NOW(), NOW(), 0)
    ");
    $stmt->execute([
        $postId,
        $parentCommentId,
        $userId,
        $content
    ]);
}
function get_comments(int $postId): array{
    global $pdo;
    $stmt = $pdo->prepare("
        SELECT
            c.*,
            u.username,
            u.profile_picture_url,
            (SELECT COUNT(*) FROM comment_likes cl WHERE cl.comment_id = c.id) AS like_count,
            (SELECT COUNT(*) FROM comment_favorites cf WHERE cf.comment_id = c.id) AS favorite_count,
            (SELECT COUNT(*) FROM comments replies WHERE replies.parent_comment_id = c.id AND replies.is_deleted = 0) AS comment_count
        FROM comments c
        JOIN users u
            ON u.id = c.user_id
        WHERE c.post_id = ?
        AND c.parent_comment_id IS NULL
        AND c.is_deleted = 0
        ORDER BY c.created_at DESC
    ");
    $stmt->execute([$postId]);
    return $stmt->fetchAll();
}
function get_comment(int $commentId): ?array {
    global $pdo;
    $stmt = $pdo->prepare("
        SELECT
            c.*,
            u.username,
            u.profile_picture_url,
            (SELECT COUNT(*) FROM comment_likes cl WHERE cl.comment_id = c.id) AS like_count,
            (SELECT COUNT(*) FROM comment_favorites cf WHERE cf.comment_id = c.id) AS favorite_count,
            (SELECT COUNT(*) FROM comments replies WHERE replies.parent_comment_id = c.id AND replies.is_deleted = 0) AS comment_count
        FROM comments c
        JOIN users u
            ON u.id = c.user_id
        WHERE c.id = ?
        LIMIT 1
    ");
    $stmt->execute([$commentId]);
    $comment = $stmt->fetch();
    return $comment ?: null;
}
function get_comment_replies(int $commentId): array {

    global $pdo;

    $stmt = $pdo->prepare("
        SELECT
            c.*,
            u.username,
            u.profile_picture_url,
            (SELECT COUNT(*) FROM comment_likes cl WHERE cl.comment_id = c.id) AS like_count,
            (SELECT COUNT(*) FROM comment_favorites cf WHERE cf.comment_id = c.id) AS favorite_count,
            (SELECT COUNT(*) FROM comments replies WHERE replies.parent_comment_id = c.id AND replies.is_deleted = 0) AS comment_count
        FROM comments c
        JOIN users u
            ON u.id = c.user_id
        WHERE c.parent_comment_id = ?
        AND c.is_deleted = 0
        ORDER BY c.created_at DESC
    ");

    $stmt->execute([$commentId]);

    return $stmt->fetchAll();
}