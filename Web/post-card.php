<article class="border-b border-pink-500 p-4 bg-fuchsia-950 text-white">
    <div class="flex gap-3">
        <div class="h-10 w-10 shrink-0 rounded-full bg-pink-500">
            <?php if(!empty($post['profile_picture_url'])): ?>
                <img src="" alt="pfp" class="h-10 w-10 shrink-0 rounded-full">
            <?php endif; ?>
        </div>
        <div class="min-w-0 flex-1">
            <div class="flex items-center gap-2">
                <b><?= e($post['username']) ?></b>
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
                <button>↻ </button>
                <button>💬 <?= (int)$post['comment_count'] ?></button>
                <button>☆ <?= (int)$post['favorite_count'] ?></button>
                <button>↗</button>
            </div>
        </div>
    </div>
</article>