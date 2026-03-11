<?php
require_once '../config/session.php';
require_once '../config/db.php';
require_role(['staff']);
$root='../'; $active='movies'; $pageTitle='Edit Movie'; $title='Edit Movie';
$id=isset($_GET['id'])?(int)$_GET['id']:0;
if(!$id){header('Location: movies.php');exit;}
$s=sqlsrv_query($conn,"SELECT * FROM Movies_19 WHERE movie_id=?",[[$id,SQLSRV_PARAM_IN]]);
$movie=sqlsrv_fetch_array($s,SQLSRV_FETCH_ASSOC);
if(!$movie){header('Location: movies.php');exit;}
$error=''; $success='';
if($_SERVER['REQUEST_METHOD']==='POST'){
    $t=trim($_POST['title']??''); $g=trim($_POST['genre']??''); $y=(int)($_POST['release_year']??0);
    $a=trim($_POST['age_rating']??''); $d=(int)($_POST['duration_minutes']??0);
    if(!$t||!$g||!$y||!$a||!$d){$error='All fields are required.';}
    else{
        $pp=$movie['poster_image'];
        if(!empty($_FILES['poster']['name'])){
            $allowed=['image/jpeg','image/jpg','image/png'];
            $finfo=finfo_open(FILEINFO_MIME_TYPE);$mime=finfo_file($finfo,$_FILES['poster']['tmp_name']);finfo_close($finfo);
            if(!in_array($mime,$allowed)){$error='Only JPG/PNG allowed.';}
            elseif($_FILES['poster']['size']>5*1024*1024){$error='Image under 5MB only.';}
            else{
                $ext=($mime==='image/png')?'png':'jpg';
                $fn='movie_'.time().'_'.bin2hex(random_bytes(4)).'.'.$ext;
                $dest='../assets/images/movies/'.$fn;
                if(move_uploaded_file($_FILES['poster']['tmp_name'],$dest)){
                    if($movie['poster_image']&&file_exists('../'.$movie['poster_image']))unlink('../'.$movie['poster_image']);
                    $pp='assets/images/movies/'.$fn;
                }else{$error='Failed to save poster.';}
            }
        }
        if(!$error){
            $u=sqlsrv_query($conn,"UPDATE Movies_19 SET title=?,genre=?,release_year=?,age_rating=?,duration_minutes=?,poster_image=? WHERE movie_id=?",
                [[$t,SQLSRV_PARAM_IN],[$g,SQLSRV_PARAM_IN],[$y,SQLSRV_PARAM_IN],[$a,SQLSRV_PARAM_IN],[$d,SQLSRV_PARAM_IN],[$pp,SQLSRV_PARAM_IN],[$id,SQLSRV_PARAM_IN]]);
            if($u===false){$e=sqlsrv_errors();$error='DB error: '.($e[0]['message']??'unknown');}
            else{$success='Movie updated!';$s=sqlsrv_query($conn,"SELECT * FROM Movies_19 WHERE movie_id=?",[[$id,SQLSRV_PARAM_IN]]);$movie=sqlsrv_fetch_array($s,SQLSRV_FETCH_ASSOC);}
        }
    }
}
$genres=['Action','Adventure','Animation','Comedy','Drama','Horror','Sci-Fi','Thriller','Romance','Documentary'];
$ratings=['G','PG','PG-13','R','NC-17'];
include '../includes/head.php'; include '../includes/sidebar.php';
?>
<div class="main-content">
<?php include '../includes/topbar.php'; ?>
<div class="page-section">
<?php if($error): ?><div class="alert alert-danger auto-dismiss alert-dismissible"><i class="fa-solid fa-triangle-exclamation me-2"></i><?=htmlspecialchars($error)?><button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button></div><?php endif; ?>
<?php if($success): ?><div class="alert alert-success auto-dismiss alert-dismissible"><i class="fa-solid fa-circle-check me-2"></i><?=htmlspecialchars($success)?><button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button></div><?php endif; ?>
<div class="page-card" style="max-width:700px;">
    <div class="page-card-header"><span class="page-card-title"><i class="fa-solid fa-pen text-warning me-2"></i>Edit: <?=htmlspecialchars($movie['title'])?></span><a href="movies.php" class="btn btn-sm btn-outline-secondary">Back</a></div>
    <form method="POST" action="" enctype="multipart/form-data">
        <div class="row g-3">
            <div class="col-12"><label class="form-label">Title *</label><input type="text" name="title" class="form-control" value="<?=htmlspecialchars($movie['title'])?>" required></div>
            <div class="col-sm-6"><label class="form-label">Genre *</label><select name="genre" class="form-select" required><?php foreach($genres as $g): ?><option value="<?=$g?>" <?=$movie['genre']===$g?'selected':''?>><?=$g?></option><?php endforeach; ?></select></div>
            <div class="col-sm-6"><label class="form-label">Age Rating *</label><select name="age_rating" class="form-select" required><?php foreach($ratings as $r): ?><option value="<?=$r?>" <?=$movie['age_rating']===$r?'selected':''?>><?=$r?></option><?php endforeach; ?></select></div>
            <div class="col-sm-6"><label class="form-label">Release Year *</label><input type="number" name="release_year" class="form-control" min="1900" max="2100" value="<?=$movie['release_year']?>" required></div>
            <div class="col-sm-6"><label class="form-label">Duration (min) *</label><input type="number" name="duration_minutes" class="form-control" min="1" value="<?=$movie['duration_minutes']?>" required></div>
            <div class="col-12">
                <label class="form-label">Replace Poster <span class="text-muted small">(optional, JPG/PNG max 5MB)</span></label>
                <?php if($movie['poster_image']&&file_exists('../'.$movie['poster_image'])): ?>
                <div class="mb-2"><img src="<?=$root.$movie['poster_image']?>" alt="Current" style="max-height:120px;border-radius:8px;"><span class="text-muted small ms-3">Current poster</span></div>
                <?php endif; ?>
                <input type="file" name="poster" class="form-control" accept="image/jpeg,image/png">
            </div>
            <div class="col-12 d-flex gap-2 pt-1">
                <button type="submit" class="btn btn-warning px-5"><i class="fa-solid fa-floppy-disk me-2"></i>Save Changes</button>
                <a href="movies.php" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </div>
    </form>
</div>
</div></div>
<?php include '../includes/footer.php'; ?>

