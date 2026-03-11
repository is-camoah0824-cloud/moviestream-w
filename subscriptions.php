<?php
require_once '../config/session.php';
require_once '../config/db.php';
require_role(['admin']);
$root='../'; $active='subscriptions'; $pageTitle='Subscriptions'; $title='Manage Subscriptions';
$stmt=sqlsrv_query($conn,"SELECT s.subscription_id,u.username,u.full_name,s.plan_name,s.start_date,s.end_date,s.is_active FROM Subscriptions_19 s JOIN Users_19 u ON s.user_id=u.user_id ORDER BY s.subscription_id DESC");
$subs=db_fetch_all($stmt);
include '../includes/head.php'; include '../includes/sidebar.php';
?>
<div class="main-content">
<?php include '../includes/topbar.php'; ?>
<div class="page-section">
<div class="page-card">
    <div class="page-card-header"><span class="page-card-title"><i class="fa-solid fa-credit-card me-2 text-success"></i>All Subscriptions (<?=count($subs)?>)</span></div>
    <div class="table-responsive">
    <table class="table table-dark-custom">
        <thead><tr><th>ID</th><th>Username</th><th>Full Name</th><th>Plan</th><th>Start</th><th>End</th><th>Status</th></tr></thead>
        <tbody>
        <?php foreach($subs as $s):
            $start=$s['start_date'] instanceof DateTime?$s['start_date']->format('d M Y'):date('d M Y',strtotime($s['start_date']??''));
            $end=$s['end_date'] instanceof DateTime?$s['end_date']->format('d M Y'):date('d M Y',strtotime($s['end_date']??''));
        ?>
        <tr>
            <td class="text-muted"><?=$s['subscription_id']?></td>
            <td class="fw-bold">@<?=htmlspecialchars($s['username'])?></td>
            <td><?=htmlspecialchars($s['full_name'])?></td>
            <td>
                <?php $plans=['Premium'=>'bg-warning text-dark','Family'=>'bg-info text-dark','Basic'=>'bg-secondary']; $pc=$plans[$s['plan_name']]??'bg-secondary'; ?>
                <span class="badge <?=$pc?>"><?=htmlspecialchars($s['plan_name'])?></span>
            </td>
            <td><small><?=$start?></small></td>
            <td><small><?=$end?></small></td>
            <td><?=$s['is_active']?'<span class="badge bg-success">Active</span>':'<span class="badge bg-danger">Inactive</span>'?></td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    </div>
</div>
</div></div>
<?php include '../includes/footer.php'; ?>
