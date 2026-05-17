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
function get_profile(int $userId): ?array {
    global $pdo;
    $stmt = $pdo->prepare("
        SELECT
            u.*,
            (SELECT COUNT(*) FROM posts p WHERE p.user_id = u.id AND p.is_deleted = 0) +
            (SELECT COUNT(*) FROM comments c WHERE c.user_id = u.id AND c.is_deleted = 0) AS post_count,
            (SELECT COUNT(*) FROM media m JOIN posts p ON p.id = m.post_id WHERE p.user_id = u.id AND p.is_deleted = 0) AS media_count,
            (SELECT COUNT(*) FROM likes l WHERE l.user_id = u.id) +
            (SELECT COUNT(*) FROM comment_likes cl WHERE cl.user_id = u.id) AS liked_count,
            (SELECT COUNT(*) FROM favorites f WHERE f.user_id = u.id) +
            (SELECT COUNT(*) FROM comment_favorites cf WHERE cf.user_id = u.id) AS favorite_count
        FROM users u
        WHERE u.id = ?
        LIMIT 1
    ");
    $stmt->execute([$userId]);
    $profile = $stmt->fetch();
    return $profile ?: null;
}
function get_profile_content(int $userId, string $type = 'posts'): array {
    global $pdo;
    switch ($type) {
        case 'likes':
            $stmt = $pdo->prepare("
                SELECT
                    p.id,
                    p.user_id,
                    p.content,
                    p.created_at,
                    p.updated_at,
                    p.is_deleted,
                    u.username,
                    u.profile_picture_url,
                    'post' AS content_type,
                    (SELECT COUNT(*) FROM likes l WHERE l.post_id = p.id) AS like_count,
                    (SELECT COUNT(*) FROM favorites f WHERE f.post_id = p.id) AS favorite_count,
                    (SELECT COUNT(*) FROM comments c WHERE c.post_id = p.id AND c.is_deleted = 0) AS comment_count
                FROM likes l
                JOIN posts p
                    ON p.id = l.post_id
                JOIN users u
                    ON u.id = p.user_id
                WHERE l.user_id = ?
                AND p.is_deleted = 0
                UNION ALL
                SELECT
                    c.id,
                    c.user_id,
                    c.content,
                    c.created_at,
                    c.updated_at,
                    c.is_deleted,
                    u.username,
                    u.profile_picture_url,
                    'comment' AS content_type,
                    (SELECT COUNT(*) FROM comment_likes cl WHERE cl.comment_id = c.id) AS like_count,
                    (SELECT COUNT(*) FROM comment_favorites cf WHERE cf.comment_id = c.id) AS favorite_count,
                    (SELECT COUNT(*) FROM comments replies WHERE replies.parent_comment_id = c.id AND replies.is_deleted = 0) AS comment_count
                FROM comment_likes cl
                JOIN comments c
                    ON c.id = cl.comment_id
                JOIN users u
                    ON u.id = c.user_id
                WHERE cl.user_id = ?
                AND c.is_deleted = 0
                ORDER BY created_at DESC
            ");
            $stmt->execute([$userId, $userId]);
            return $stmt->fetchAll();
        case 'favorites':
            $stmt = $pdo->prepare("
                SELECT
                    p.id,
                    p.user_id,
                    p.content,
                    p.created_at,
                    p.updated_at,
                    p.is_deleted,
                    u.username,
                    u.profile_picture_url,
                    'post' AS content_type,
                    (SELECT COUNT(*) FROM likes l WHERE l.post_id = p.id) AS like_count,
                    (SELECT COUNT(*) FROM favorites f WHERE f.post_id = p.id) AS favorite_count,
                    (SELECT COUNT(*) FROM comments c WHERE c.post_id = p.id AND c.is_deleted = 0) AS comment_count
                FROM favorites f
                JOIN posts p
                    ON p.id = f.post_id
                JOIN users u
                    ON u.id = p.user_id
                WHERE f.user_id = ?
                AND p.is_deleted = 0
                UNION ALL
                SELECT
                    c.id,
                    c.user_id,
                    c.content,
                    c.created_at,
                    c.updated_at,
                    c.is_deleted,
                    u.username,
                    u.profile_picture_url,
                    'comment' AS content_type,
                    (SELECT COUNT(*) FROM comment_likes cl WHERE cl.comment_id = c.id) AS like_count,
                    (SELECT COUNT(*) FROM comment_favorites cf WHERE cf.comment_id = c.id) AS favorite_count,
                    (SELECT COUNT(*) FROM comments replies WHERE replies.parent_comment_id = c.id AND replies.is_deleted = 0) AS comment_count
                FROM comment_favorites cf
                JOIN comments c
                    ON c.id = cf.comment_id
                JOIN users u
                    ON u.id = c.user_id
                WHERE cf.user_id = ?
                AND c.is_deleted = 0
                ORDER BY created_at DESC
            ");
            $stmt->execute([$userId, $userId]);
            return $stmt->fetchAll();
        default:
            $stmt = $pdo->prepare("
                SELECT
                    p.*,
                    u.username,
                    u.profile_picture_url,
                    (SELECT COUNT(*) FROM likes l WHERE l.post_id = p.id) AS like_count,
                    (SELECT COUNT(*) FROM favorites f WHERE f.post_id = p.id) AS favorite_count,
                    (SELECT COUNT(*) FROM comments c WHERE c.post_id = p.id AND c.is_deleted = 0) AS comment_count
                FROM posts p
                JOIN users u
                    ON u.id = p.user_id
                WHERE p.user_id = ?
                AND p.is_deleted = 0
                ORDER BY p.created_at DESC
            ");
            $stmt->execute([$userId]);
            return $stmt->fetchAll();
        
    }
}
function toggle_like(int $postId, int $userId): void{
    global $pdo;
    $stmt = $pdo->prepare("
        SELECT id
        FROM likes
        WHERE post_id = ?
        AND user_id = ?
        LIMIT 1
    ");
    $stmt->execute([
        $postId,
        $userId
    ]);
    $existing = $stmt->fetch();
    if($existing){
        $stmt = $pdo->prepare("
            DELETE FROM likes
            WHERE id = ?
        ");
        $stmt->execute([
            $existing['id']
        ]);
    }
    else{
        $stmt = $pdo->prepare("
            INSERT INTO likes
            (post_id, user_id, created_at)
            VALUES (?, ?, NOW())
        ");
        $stmt->execute([
            $postId,
            $userId
        ]);
    }
}
function toggle_comment_like(int $commentId, int $userId): void{
    global $pdo;
    $stmt = $pdo->prepare("
        SELECT id
        FROM comment_likes
        WHERE comment_id = ?
        AND user_id = ?
        LIMIT 1
    ");
    $stmt->execute([
        $commentId,
        $userId
    ]);
    $existing = $stmt->fetch();
    if($existing){
        $stmt = $pdo->prepare("
            DELETE FROM comment_likes
            WHERE id = ?
        ");
        $stmt->execute([
            $existing['id']
        ]);
    }
    else{
        $stmt = $pdo->prepare("
            INSERT INTO comment_likes
            (comment_id, user_id, created_at)
            VALUES (?, ?, NOW())
        ");
        $stmt->execute([
            $commentId,
            $userId
        ]);
    }
}