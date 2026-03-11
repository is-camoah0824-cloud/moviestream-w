<?php
require_once '../config/session.php';
require_once '../config/db.php';
require_role(['staff']);
$root='../'; $active='watch_history'; $pageTitle='Watch History'; $title='Watch History';
$stmt=sqlsrv_query($conn,"SELECT w.watch_id,u.username,m.title,w.device,w.watched_at FROM WatchHistory_19 w JOIN Users_19 u ON w.user_id=u.user_id JOIN Movies_19 m ON w.movie_id=m.movie_id ORDER BY w.watched_at DESC");
$rows=db_fetch_all($stmt);
include '../includes/head.php'; include '../includes/sidebar.php';
?>
<div class="main-content">
<?php include '../includes/topbar.php'; ?>
<div class="page-section">
<div class="page-card">
    <div class="page-card-header"><span class="page-card-title"><i class="fa-solid fa-clock-rotate-left me-2 text-purple"></i>All Watch History (<?=count($rows)?>)</span></div>
    <div class="table-responsive">
    <table class="table table-dark-custom">
        <thead><tr><th>#</th><th>Username</th><th>Movie</th><th>Device</th><th>Watched At</th></tr></thead>
        <tbody>
        <?php foreach($rows as $r):
            $dt=$r['watched_at'] instanceof DateTime?$r['watched_at']->format('d M Y H:i'):date('d M Y H:i',strtotime($r['watched_at']??''));
        ?>
        <tr>
            <td class="text-muted"><?=$r['watch_id']?></td>
            <td class="fw-bold">@<?=htmlspecialchars($r['username'])?></td>
            <td><?=htmlspecialchars($r['title'])?></td>
            <td><span class="movie-badge"><?=htmlspecialchars($r['device']??'-')?></span></td>
            <td><small class="text-muted"><?=$dt?></small></td>
        </tr>
        <?php endforeach; ?>
        <?php if(empty($rows)): ?><tr><td colspan="5" class="text-center text-muted py-4">No watch history found.</td></tr><?php endif; ?>
        </tbody>
    </table>
    </div>
</div>
</div></div>
<?php include '../includes/footer.php'; ?>

