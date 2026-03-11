<?php
require_once '../config/session.php';
require_once '../config/db.php';
require_role(['customer']);
$root='../'; $active='movies'; $pageTitle='Rate Movie'; $title='Rate Movie';
$uid=$_SESSION['user_id'];
$mid=isset($_GET['id'])?(int)$_GET['id']:0;
if(!$mid){header('Location: movies.php');exit;}
$mStmt=sqlsrv_query($conn,"SELECT * FROM Movies_19 WHERE movie_id=?",[[$mid,SQLSRV_PARAM_IN]]);
$movie=sqlsrv_fetch_array($mStmt,SQLSRV_FETCH_ASSOC);
if(!$movie){header('Location: movies.php');exit;}
$error=''; $success='';
// Check if already rated
$chk=sqlsrv_query($conn,"SELECT rating_id FROM Ratings_19 WHERE user_id=? AND movie_id=?",[[$uid,SQLSRV_PARAM_IN],[$mid,SQLSRV_PARAM_IN]]);
$existingRating=sqlsrv_fetch_array($chk,SQLSRV_FETCH_ASSOC);
if($_SERVER['REQUEST_METHOD']==='POST'){
    $rating=(int)($_POST['rating']??0); $review=trim($_POST['review_text']??'');
    if($rating<1||$rating>5){$error='Please select a rating (1-5 stars).';}
    elseif($existingRating){$error='You have already rated this movie.';}
    else{
        $ins=sqlsrv_query($conn,"INSERT INTO Ratings_19 (user_id,movie_id,rating,review_text) VALUES (?,?,?,?)",[[$uid,SQLSRV_PARAM_IN],[$mid,SQLSRV_PARAM_IN],[$rating,SQLSRV_PARAM_IN],[$review?:null,SQLSRV_PARAM_IN]]);
        if($ins===false){$e=sqlsrv_errors();$error='DB error: '.($e[0]['message']??'unknown');}
        else{$success='Rating submitted! Thank you.';$existingRating=true;}
    }
}
include '../includes/head.php'; include '../includes/sidebar.php';
?>
<div class="main-content">
<?php include '../includes/topbar.php'; ?>
<div class="page-section">
<div class="page-card" style="max-width:600px;">
    <div class="page-card-header"><span class="page-card-title"><i class="fa-solid fa-star me-2 text-warning"></i>Rate: <?=htmlspecialchars($movie['title'])?></span><a href="movies.php" class="btn btn-sm btn-outline-secondary">Back</a></div>
    <?php if($error): ?><div class="alert alert-danger"><?=htmlspecialchars($error)?></div><?php endif; ?>
    <?php if($success): ?><div class="alert alert-success"><?=htmlspecialchars($success)?><br><a href="movies.php" class="btn btn-warning btn-sm mt-2">Back to Movies</a></div><?php endif; ?>
    <?php if($existingRating&&!$success): ?><div class="alert alert-info"><i class="fa-solid fa-circle-info me-2"></i>You have already rated this movie.</div><?php endif; ?>
    <?php if(!$existingRating): ?>
    <div class="mb-3 p-3 rounded" style="background:rgba(255,255,255,0.04);border:1px solid var(--clr-border);">
        <div class="movie-meta"><span class="movie-badge"><?=htmlspecialchars($movie['genre'])?></span><span class="movie-badge"><?=$movie['release_year']?></span><span class="movie-badge"><?=htmlspecialchars($movie['age_rating'])?></span><span class="movie-badge"><i class="fa-regular fa-clock me-1"></i><?=$movie['duration_minutes']?> min</span></div>
    </div>
    <form method="POST" action="">
        <div class="mb-4">
            <label class="form-label fw-bold">Your Rating *</label>
            <div class="star-rating" id="starRating">
                <?php for($i=5;$i>=1;$i--): ?>
                <input type="radio" id="star<?=$i?>" name="rating" value="<?=$i?>">
                <label for="star<?=$i?>"><i class="fa-solid fa-star"></i></label>
                <?php endfor; ?>
            </div>
            <small class="text-muted">Click a star to rate</small>
        </div>
        <div class="mb-3">
            <label class="form-label" for="review_text">Review <span class="text-muted">(optional)</span></label>
            <textarea id="review_text" name="review_text" class="form-control" rows="3" maxlength="500" placeholder="Share your thoughts about this movie..."><?=htmlspecialchars($_POST['review_text']??'')?></textarea>
        </div>
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-warning px-4"><i class="fa-solid fa-paper-plane me-2"></i>Submit Rating</button>
            <a href="movies.php" class="btn btn-outline-secondary">Cancel</a>
        </div>
    </form>
    <?php endif; ?>
</div>
</div></div>
<?php include '../includes/footer.php'; ?>
