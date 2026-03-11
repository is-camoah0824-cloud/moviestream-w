<?php
require_once '../config/session.php';
require_once '../config/db.php';
require_role(['staff']);
$root='../'; $active='dashboard'; $pageTitle='Staff Dashboard'; $title='Staff Dashboard';
function ct2($conn,$t){$s=sqlsrv_query($conn,"SELECT COUNT(*) AS n FROM $t");$r=sqlsrv_fetch_array($s,SQLSRV_FETCH_ASSOC);return $r['n']??0;}
$total_movies=ct2($conn,'Movies_19'); $total_watches=ct2($conn,'WatchHistory_19'); $total_ratings=ct2($conn,'Ratings_19');
$stmt=sqlsrv_query($conn,"SELECT TOP 5 movie_id,title,genre,release_year,poster_image FROM Movies_19 ORDER BY movie_id DESC");
$recent_movies=db_fetch_all($stmt);
include '../includes/head.php'; include '../includes/sidebar.php';
?>
<div class="main-content">
<?php include '../includes/topbar.php'; ?>
<div class="page-section">
<div class="row g-3 mb-4">
    <div class="col-sm-4"><div class="stat-card"><div class="stat-icon gold"><i class="fa-solid fa-film"></i></div><div><div class="stat-value"><?=$total_movies?></div><div class="stat-label2">Movies</div></div></div></div>
    <div class="col-sm-4"><div class="stat-card"><div class="stat-icon purple"><i class="fa-solid fa-play"></i></div><div><div class="stat-value"><?=$total_watches?></div><div class="stat-label2">Total Watches</div></div></div></div>
    <div class="col-sm-4"><div class="stat-card"><div class="stat-icon gold"><i class="fa-solid fa-star"></i></div><div><div class="stat-value"><?=$total_ratings?></div><div class="stat-label2">Ratings</div></div></div></div>
</div>
<div class="page-card">
    <div class="page-card-header"><span class="page-card-title"><i class="fa-solid fa-film text-warning me-2"></i>Recently Added Movies</span><a href="movie_add.php" class="btn btn-warning btn-sm"><i class="fa-solid fa-plus me-1"></i>Add Movie</a></div>
    <?php if(empty($recent_movies)): ?><p class="text-muted">No movies yet.</p><?php else: ?>
    <div class="row g-3">
        <?php foreach($recent_movies as $m): $p=$m['poster_image']?'../'.$m['poster_image']:null; ?>
        <div class="col-sm-6 col-md-4 col-lg-3">
            <div class="movie-card">
                <?php if($p&&file_exists($p)): ?><img src="<?=$root.$m['poster_image']?>" alt="<?=htmlspecialchars($m['title'])?>" class="movie-poster"><?php else: ?><div class="movie-poster-placeholder"><i class="fa-solid fa-image"></i></div><?php endif; ?>
                <div class="movie-body">
                    <div class="movie-title"><?=htmlspecialchars($m['title'])?></div>
                    <div class="movie-meta"><span class="movie-badge"><?=htmlspecialchars($m['genre'])?></span><span class="movie-badge"><?=$m['release_year']?></span></div>
                    <div class="movie-actions"><a href="movie_edit.php?id=<?=$m['movie_id']?>" class="btn btn-primary btn-sm"><i class="fa-solid fa-pen me-1"></i>Edit</a></div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>
<div class="page-card mt-3">
    <div class="page-card-title mb-3"><i class="fa-solid fa-bolt text-warning me-2"></i>Quick Actions</div>
    <div class="d-flex gap-2 flex-wrap">
        <a href="movie_add.php" class="btn btn-warning"><i class="fa-solid fa-plus me-1"></i>Add Movie</a>
        <a href="movies.php" class="btn btn-outline-light"><i class="fa-solid fa-film me-1"></i>All Movies</a>
        <a href="watch_history.php" class="btn btn-outline-info"><i class="fa-solid fa-clock-rotate-left me-1"></i>Watch History</a>
        <a href="ratings.php" class="btn btn-outline-warning"><i class="fa-solid fa-star me-1"></i>Ratings</a>
    </div>
</div>
</div></div>
<?php include '../includes/footer.php'; ?>
