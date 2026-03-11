<?php
require_once '../config/session.php';
require_once '../config/db.php';
require_role(['staff']);
$root='../'; $active='ratings'; $pageTitle='Ratings'; $title='All Ratings';
$stmt=sqlsrv_query($conn,"SELECT r.rating_id,u.username,m.title,r.rating,r.review_text,r.created_at FROM Ratings_19 r JOIN Users_19 u ON r.user_id=u.user_id JOIN Movies_19 m ON r.movie_id=m.movie_id ORDER BY r.created_at DESC");
$rows=db_fetch_all($stmt);
include '../includes/head.php'; include '../includes/sidebar.php';
?>
<div class="main-content">
<?php include '../includes/topbar.php'; ?>
<div class="page-section">
<div class="page-card">
    <div class="page-card-header"><span class="page-card-title"><i class="fa-solid fa-star me-2 text-warning"></i>All Ratings (<?=count($rows)?>)</span></div>
    <div class="table-responsive">
    <table class="table table-dark-custom">
        <thead><tr><th>#</th><th>Username</th><th>Movie</th><th>Rating</th><th>Review</th><th>Date</th></tr></thead>
        <tbody>
        <?php foreach($rows as $r):
            $dt=$r['created_at'] instanceof DateTime?$r['created_at']->format('d M Y'):date('d M Y',strtotime($r['created_at']??''));
            $stars=str_repeat('<i class="fa-solid fa-star text-warning"></i>',(int)$r['rating']).str_repeat('<i class="fa-regular fa-star text-muted"></i>',5-(int)$r['rating']);
        ?>
        <tr>
            <td class="text-muted"><?=$r['rating_id']?></td>
            <td class="fw-bold">@<?=htmlspecialchars($r['username'])?></td>
            <td><?=htmlspecialchars($r['title'])?></td>
            <td style="font-size:0.8rem"><?=$stars?></td>
            <td><small><?=htmlspecialchars($r['review_text']??'-')?></small></td>
            <td><small class="text-muted"><?=$dt?></small></td>
        </tr>
        <?php endforeach; ?>
        <?php if(empty($rows)): ?><tr><td colspan="6" class="text-center text-muted py-4">No ratings found.</td></tr><?php endif; ?>
        </tbody>
    </table>
    </div>
</div>
</div></div>
<?php include '../includes/footer.php'; ?>

