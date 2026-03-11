<?php
require_once '../config/session.php';
require_once '../config/db.php';
require_role(['admin']);
$root='../'; $active='users'; $pageTitle='All Users'; $title='All Users';
$msg=''; $msgType='success';
if(isset($_GET['delete']) && is_numeric($_GET['delete'])){
    $uid=(int)$_GET['delete'];
    if($uid===$_SESSION['user_id']){$msg='Cannot delete your own account.';$msgType='warning';}
    else{
        sqlsrv_query($conn,"DELETE FROM WatchHistory_19 WHERE user_id=?",[[$uid,SQLSRV_PARAM_IN]]);
        sqlsrv_query($conn,"DELETE FROM Ratings_19 WHERE user_id=?",[[$uid,SQLSRV_PARAM_IN]]);
        sqlsrv_query($conn,"DELETE FROM Subscriptions_19 WHERE user_id=?",[[$uid,SQLSRV_PARAM_IN]]);
        $d=sqlsrv_query($conn,"DELETE FROM Users_19 WHERE user_id=?",[[$uid,SQLSRV_PARAM_IN]]);
        $msg=$d!==false?'User deleted.':'Error deleting user.'; $msgType=$d!==false?'success':'danger';
    }
}
$stmt=sqlsrv_query($conn,"SELECT user_id,username,full_name,role,email,country,created_at FROM Users_19 ORDER BY created_at DESC");
$users=db_fetch_all($stmt);
include '../includes/head.php'; include '../includes/sidebar.php';
?>
<div class="main-content">
<?php include '../includes/topbar.php'; ?>
<div class="page-section">
<?php if($msg): ?><div class="alert alert-<?=$msgType?> auto-dismiss alert-dismissible"><?=htmlspecialchars($msg)?><button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button></div><?php endif; ?>
<div class="page-card">
    <div class="page-card-header">
        <span class="page-card-title"><i class="fa-solid fa-users me-2 text-info"></i>All Users (<?=count($users)?>)</span>
    </div>
    <div class="table-responsive">
    <table class="table table-dark-custom">
        <thead><tr><th>ID</th><th>Username</th><th>Full Name</th><th>Role</th><th>Email</th><th>Country</th><th>Registered</th><th>Actions</th></tr></thead>
        <tbody>
        <?php foreach($users as $u): $dt=$u['created_at'] instanceof DateTime?$u['created_at']->format('d M Y'):date('d M Y',strtotime($u['created_at']??''));?>
        <tr>
            <td class="text-muted"><?=$u['user_id']?></td>
            <td class="fw-bold">@<?=htmlspecialchars($u['username'])?></td>
            <td><?=htmlspecialchars($u['full_name'])?></td>
            <td><span class="badge role-<?=$u['role']?> badge-role"><?=$u['role']?></span></td>
            <td><small><?=htmlspecialchars($u['email']??'-')?></small></td>
            <td><?=htmlspecialchars($u['country']??'-')?></td>
            <td><small class="text-muted"><?=$dt?></small></td>
            <td>
                <?php if($u['user_id']!=$_SESSION['user_id']): ?>
                <a href="users.php?delete=<?=$u['user_id']?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete user and all their data?')"><i class="fa-solid fa-trash"></i></a>
                <?php else: ?><span class="text-muted small">You</span><?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    </div>
</div>
</div></div>
<?php include '../includes/footer.php'; ?>
