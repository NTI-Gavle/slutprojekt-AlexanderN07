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
                <span>
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
            <div class="mt-1">
                <?= e($post['content']) ?>
            </div>
            <div class="mt-3 flex max-w-md justify-between text-gray-200">
                <button>♡ <?= (int)$post['like_count'] ?></button>
                <button>↻ </button>
                <button>💬 <?= (int)$post['comment_count'] ?></button>
                <button>☆ <?= (int)$post['favorite_count'] ?></button>
                <button>↗</button>
            </div>
        </div>
    </div>
</article>