<?php
require_once '../config/session.php';
require_once '../config/db.php';
require_role(['staff']);
$root='../'; $active='movies'; $pageTitle='Movies'; $title='Manage Movies';
$stmt=sqlsrv_query($conn,"SELECT movie_id,title,genre,release_year,age_rating,duration_minutes,poster_image FROM Movies_19 ORDER BY movie_id DESC");
$movies=db_fetch_all($stmt);
include '../includes/head.php'; include '../includes/sidebar.php';
?>
<div class="main-content">
<?php include '../includes/topbar.php'; ?>
<div class="page-section">
<div class="page-card">
    <div class="page-card-header"><span class="page-card-title"><i class="fa-solid fa-film me-2 text-warning"></i>All Movies (<?=count($movies)?>)</span><a href="movie_add.php" class="btn btn-warning btn-sm"><i class="fa-solid fa-plus me-1"></i>Add Movie</a></div>
    <?php if(empty($movies)): ?><p class="text-muted">No movies found.</p><?php else: ?>
    <div class="row g-3">
        <?php foreach($movies as $m): $p=$m['poster_image']?'../'.$m['poster_image']:null; ?>
        <div class="col-sm-6 col-md-4 col-xl-3">
            <div class="movie-card">
                <?php if($p&&file_exists($p)): ?><img src="<?=$root.$m['poster_image']?>" alt="<?=htmlspecialchars($m['title'])?>" class="movie-poster"><?php else: ?><div class="movie-poster-placeholder"><i class="fa-solid fa-image"></i><span style="font-size:0.75rem">No Poster</span></div><?php endif; ?>
                <div class="movie-body">
                    <div class="movie-title"><?=htmlspecialchars($m['title'])?></div>
                    <div class="movie-meta"><span class="movie-badge"><?=htmlspecialchars($m['genre'])?></span><span class="movie-badge"><?=$m['release_year']?></span><span class="movie-badge"><?=htmlspecialchars($m['age_rating'])?></span></div>
                    <div style="font-size:0.78rem;color:var(--clr-muted);margin-bottom:0.75rem;"><i class="fa-regular fa-clock me-1"></i><?=$m['duration_minutes']?> min</div>
                    <div class="movie-actions"><a href="movie_edit.php?id=<?=$m['movie_id']?>" class="btn btn-primary btn-sm"><i class="fa-solid fa-pen me-1"></i>Edit</a></div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>
</div></div>
<?php include '../includes/footer.php'; ?>
