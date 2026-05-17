<article class="border-b border-pink-500 p-4 bg-fuchsia-950 text-white">
    <?php if (!empty($post['reposted_by'])): ?>
        <div class="mb-2 text-sm text-gray-300">↻ Reposted by <?= e($post['reposted_by']) ?></div>
    <?php endif; ?>
    <div class="flex gap-3">
        <a href="profile.php?id=<?= (int)$post['user_id'] ?>" class="h-10 w-10 shrink-0 rounded-full bg-pink-500 overflow-hidden">
            <?php if(!empty($post['profile_picture_url'])): ?>
                <img src="" alt="pfp" class="h-10 w-10 shrink-0 rounded-full">
            <?php endif; ?>
        </a>
        <div class="min-w-0 flex-1">
            <div class="flex items-center gap-2">
                <a href="profile.php?id=<?= (int)$post['user_id'] ?>" class="font-bold hover:underline">
                    <?= e($post['username']) ?>
                </a>
                <span class="text-gray-300">
                    @<?= e($post['username']) ?>
                    <b>|</b>
                    <?php
                    $date = new DateTime($post['created_at']);        
                    $now = new DateTime();
                    if ($date->format('Y') === $now->format('Y')) {
                        echo e($date->format('M j \a\t H:i'));
                    } else {
                        echo e($date->format('M j, Y \a\t H:i'));
                    }
                    ?>
                </span>
            </div>
            <a href="<?= (($post['content_type'] ?? 'post') === 'comment') ? 'comment.php?id=' . (int)$post['id'] : 'post.php?id=' . (int)$post['id'] ?>">
                <div class="mt-1">
                    <?= e($post['content']) ?>
                </div>
            </a>
            <div class="mt-3 flex max-w-md justify-between text-gray-200">
                <form method="POST">
                    <?php if (($post['content_type'] ?? 'post') === 'comment'): ?>
                        <input type="hidden" name="like_comment_id" value="<?= (int)$post['id'] ?>">
                    <?php else: ?>
                        <input type="hidden" name="like_post_id" value="<?= (int)$post['id'] ?>">
                    <?php endif; ?>
                    <button type="submit">♡ <?= (int)$post['like_count'] ?></button>
                </form>
                <form method="POST">
                    <?php if (($post['content_type'] ?? 'post') === 'comment'): ?>
                        <input type="hidden" name="repost_comment_id" value="<?= (int)$post['id'] ?>">
                    <?php else: ?>
                        <input type="hidden" name="repost_post_id" value="<?= (int)$post['id'] ?>">
                    <?php endif; ?>
                    <button type="submit">↻ <?= (int)($post['repost_count'] ?? 0) ?></button>
                </form>
                <button>💬 <?= (int)$post['comment_count'] ?></button>
                <form method="POST">
                    <?php if (($post['content_type'] ?? 'post') === 'comment'): ?>
                        <input type="hidden" name="favorite_comment_id" value="<?= (int)$post['id'] ?>">
                    <?php else: ?>
                        <input type="hidden" name="favorite_post_id" value="<?= (int)$post['id'] ?>">
                    <?php endif; ?>
                    <button type="submit">☆ <?= (int)$post['favorite_count'] ?></button>
                </form>
                <button>↗</button>
            </div>
        </div>
    </div>
</article>