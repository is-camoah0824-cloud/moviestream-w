<?php
require_once '../config/session.php';
require_once '../config/db.php';
require_role(['staff']);
$root='../'; $active='movie_add'; $pageTitle='Add Movie'; $title='Add New Movie';
$error=''; $success='';
if ($_SERVER['REQUEST_METHOD']==='POST') {
    $t=trim($_POST['title']??''); $g=trim($_POST['genre']??''); $y=(int)($_POST['release_year']??0);
    $a=trim($_POST['age_rating']??''); $d=(int)($_POST['duration_minutes']??0);
    if(!$t||!$g||!$y||!$a||!$d) { $error='All fields are required.'; }
    elseif($y<1900||$y>2100) { $error='Invalid release year.'; }
    elseif($d<=0) { $error='Duration must be positive.'; }
    else {
        $poster_path=null;
        if (!empty($_FILES['poster']['name'])) {
            $allowed=['image/jpeg','image/jpg','image/png'];
            $finfo=finfo_open(FILEINFO_MIME_TYPE); $mime=finfo_file($finfo,$_FILES['poster']['tmp_name']); finfo_close($finfo);
            if(!in_array($mime,$allowed)) { $error='Only JPG/PNG images allowed.'; }
            elseif($_FILES['poster']['size']>5*1024*1024) { $error='Image must be under 5MB.'; }
            else {
                $ext=($mime==='image/png')?'png':'jpg';
                $fname='movie_'.time().'_'.bin2hex(random_bytes(4)).'.'.$ext;
                $dest='../assets/images/movies/'.$fname;
                if(!move_uploaded_file($_FILES['poster']['tmp_name'],$dest)) { $error='Failed to save image.'; }
                else { $poster_path='assets/images/movies/'.$fname; }
            }
        }
        if (!$error) {
            $sql="INSERT INTO Movies_19 (title,genre,release_year,age_rating,duration_minutes,poster_image) VALUES (?,?,?,?,?,?)";
            $params=[[$t,SQLSRV_PARAM_IN],[$g,SQLSRV_PARAM_IN],[$y,SQLSRV_PARAM_IN],[$a,SQLSRV_PARAM_IN],[$d,SQLSRV_PARAM_IN],[$poster_path,SQLSRV_PARAM_IN]];
            $stmt=sqlsrv_query($conn,$sql,$params);
            if($stmt===false){$e=sqlsrv_errors();$error='DB error: '.($e[0]['message']??'unknown');}
            else{$success="Movie \"$t\" added successfully!"; $_POST=[];}
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
<?php if($error): ?><div class="alert alert-danger auto-dismiss alert-dismissible"><i class="fa-solid fa-circle-exclamation me-2"></i><?=htmlspecialchars($error)?><button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button></div><?php endif; ?>
<?php if($success): ?><div class="alert alert-success auto-dismiss alert-dismissible"><i class="fa-solid fa-circle-check me-2"></i><?=htmlspecialchars($success)?><button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button></div><?php endif; ?>
<div class="row g-3">
<div class="col-lg-8">
<div class="page-card">
    <div class="page-card-header"><span class="page-card-title"><i class="fa-solid fa-plus-circle text-warning me-2"></i>Movie Details</span></div>
    <form method="POST" action="" enctype="multipart/form-data">
        <div class="row g-3">
            <div class="col-12"><label class="form-label" for="mv_title">Movie Title *</label><input type="text" id="mv_title" name="title" class="form-control" placeholder="Enter movie title" value="<?=htmlspecialchars($_POST['title']??'')?>" required></div>
            <div class="col-sm-6"><label class="form-label" for="mv_genre">Genre *</label><select id="mv_genre" name="genre" class="form-select" required><option value="">-- Select --</option><?php foreach($genres as $g2): ?><option value="<?=$g2?>" <?=($_POST['genre']??'')===$g2?'selected':''?>><?=$g2?></option><?php endforeach; ?></select></div>
            <div class="col-sm-6"><label class="form-label" for="mv_rating">Age Rating *</label><select id="mv_rating" name="age_rating" class="form-select" required><option value="">-- Select --</option><?php foreach($ratings as $r2): ?><option value="<?=$r2?>" <?=($_POST['age_rating']??'')===$r2?'selected':''?>><?=$r2?></option><?php endforeach; ?></select></div>
            <div class="col-sm-6"><label class="form-label" for="mv_year">Release Year *</label><input type="number" id="mv_year" name="release_year" class="form-control" min="1900" max="2100" placeholder="e.g. 2024" value="<?=htmlspecialchars($_POST['release_year']??'')?>" required></div>
            <div class="col-sm-6"><label class="form-label" for="mv_dur">Duration (minutes) *</label><input type="number" id="mv_dur" name="duration_minutes" class="form-control" min="1" placeholder="e.g. 120" value="<?=htmlspecialchars($_POST['duration_minutes']??'')?>" required></div>
            <div class="col-12">
                <label class="form-label">Movie Poster <span class="text-warning">(JPG/PNG, max 5MB)</span></label>
                <div class="upload-zone" id="uploadZone" onclick="document.getElementById('poster').click()">
                    <i class="fa-solid fa-cloud-arrow-up" id="uploadIcon"></i>
                    <p id="uploadText">Click to upload or drag &amp; drop poster image</p>
                    <p class="text-warning small" id="uploadFilename" style="display:none"></p>
                    <img id="previewImg" src="" alt="Preview" style="max-height:200px;border-radius:8px;margin-top:1rem;display:none;">
                </div>
                <input type="file" id="poster" name="poster" accept="image/jpeg,image/png" style="display:none">
            </div>
            <div class="col-12 d-flex gap-2 pt-2">
                <button type="submit" class="btn btn-warning px-5 py-2"><i class="fa-solid fa-plus me-2"></i>Add Movie</button>
                <a href="movies.php" class="btn btn-outline-secondary px-4">Cancel</a>
            </div>
        </div>
    </form>
</div></div>
<div class="col-lg-4"><div class="page-card">
    <div class="page-card-title mb-3"><i class="fa-solid fa-circle-info text-info me-2"></i>Tips</div>
    <ul style="color:var(--clr-muted);font-size:0.88rem;padding-left:1.2rem;line-height:2.2;">
        <li>Use a <strong>portrait-format</strong> poster image.</li>
        <li>Only <strong>JPG and PNG</strong> accepted.</li>
        <li>Max file size: <strong>5 MB</strong>.</li>
        <li>Posters saved in <code>assets/images/movies/</code>.</li>
        <li>Placeholder shown if no poster is uploaded.</li>
    </ul>
</div></div>
</div></div></div>
<?php include '../includes/footer.php'; ?>
<script>
const pin=document.getElementById('poster'),uz=document.getElementById('uploadZone'),ui=document.getElementById('uploadIcon'),ut=document.getElementById('uploadText'),uf=document.getElementById('uploadFilename'),prev=document.getElementById('previewImg');
pin.addEventListener('change',function(){const f=this.files[0];if(f){uf.textContent=f.name;uf.style.display='block';ut.style.display='none';ui.style.display='none';const r=new FileReader();r.onload=e=>{prev.src=e.target.result;prev.style.display='block';};r.readAsDataURL(f);}});
uz.addEventListener('dragover',e=>{e.preventDefault();uz.classList.add('drag-over');});
uz.addEventListener('dragleave',()=>uz.classList.remove('drag-over'));
uz.addEventListener('drop',e=>{e.preventDefault();uz.classList.remove('drag-over');if(e.dataTransfer.files.length){pin.files=e.dataTransfer.files;pin.dispatchEvent(new Event('change'));}});
</script>

