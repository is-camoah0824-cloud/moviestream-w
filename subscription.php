<?php
require_once '../config/session.php';
require_once '../config/db.php';
require_role(['customer']);
$root='../'; $active='subscription'; $pageTitle='My Subscription'; $title='My Subscription';
$uid=$_SESSION['user_id'];
// CheckSubscriptionStatus_19 stored procedure
$schkStmt=sqlsrv_query($conn,"EXEC CheckSubscriptionStatus_19 @user_id=?",[[$uid,SQLSRV_PARAM_IN]]);
$subCheck=sqlsrv_fetch_array($schkStmt,SQLSRV_FETCH_ASSOC);
$subStatus=$subCheck['Status']??'No active subscription';
// All subscriptions for this user
$stmt=sqlsrv_query($conn,"SELECT plan_name,start_date,end_date,is_active FROM Subscriptions_19 WHERE user_id=? ORDER BY subscription_id DESC",[[$uid,SQLSRV_PARAM_IN]]);
$subs=db_fetch_all($stmt);
include '../includes/head.php'; include '../includes/sidebar.php';
?>
<div class="main-content">
<?php include '../includes/topbar.php'; ?>
<div class="page-section">
<div class="page-card mb-3">
    <div class="page-card-header"><span class="page-card-title"><i class="fa-solid fa-credit-card me-2 text-success"></i>Subscription Status</span></div>
    <?php if($subStatus==='Active'): ?>
    <div class="alert alert-success mb-0 d-flex align-items-center gap-3"><i class="fa-solid fa-circle-check fs-5"></i><div><strong>Active!</strong> You have <?=$subCheck['Active_Count']?> active subscription(s). Enjoy unlimited streaming.</div></div>
    <?php else: ?>
    <div class="alert alert-warning mb-0"><i class="fa-solid fa-triangle-exclamation me-2"></i><strong>No Active Subscription.</strong> Your subscription may have expired.</div>
    <?php endif; ?>
</div>
<div class="page-card">
    <div class="page-card-header"><span class="page-card-title"><i class="fa-solid fa-list me-2"></i>Subscription History</span></div>
    <?php if(empty($subs)): ?><p class="text-muted">No subscription records found.</p><?php else: ?>
    <div class="row g-3">
        <?php foreach($subs as $s):
            $start=$s['start_date'] instanceof DateTime?$s['start_date']->format('d M Y'):date('d M Y',strtotime($s['start_date']??''));
            $end=$s['end_date'] instanceof DateTime?$s['end_date']->format('d M Y'):date('d M Y',strtotime($s['end_date']??''));
        ?>
        <div class="col-md-4">
            <div class="plan-card <?=$s['is_active']?'active-plan':''?>">
                <div class="plan-name mb-1"><?=htmlspecialchars($s['plan_name'])?></div>
                <div class="plan-price mb-3"><?=$start?> &mdash; <?=$end?></div>
                <?=$s['is_active']?'<span class="badge bg-success px-3 py-2">Active</span>':'<span class="badge bg-secondary px-3 py-2">Expired</span>'?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>
</div></div>
<?php include '../includes/footer.php'; ?>
