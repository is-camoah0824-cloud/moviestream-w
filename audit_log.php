<?php
require_once '../config/session.php';
require_once '../config/db.php';
require_role(['admin']);
$root='../'; $active='audit_log'; $pageTitle='Audit Log'; $title='Audit Log';
$stmt=sqlsrv_query($conn,"SELECT TOP 200 log_id,action,username,logged_at FROM AuditLog_19 ORDER BY logged_at DESC");
$logs=db_fetch_all($stmt);
include '../includes/head.php'; include '../includes/sidebar.php';
?>
<div class="main-content">
<?php include '../includes/topbar.php'; ?>
<div class="page-section">
<div class="page-card">
    <div class="page-card-header"><span class="page-card-title"><i class="fa-solid fa-scroll me-2 text-danger"></i>Audit Log <span class="text-muted small fw-normal">(last 200 entries)</span></span></div>
    <?php if(empty($logs)): ?><p class="text-muted">No audit entries yet. Entries appear when users watch movies (trigger).</p>
    <?php else: ?>
    <?php foreach($logs as $l):
        $dt=$l['logged_at'] instanceof DateTime?$l['logged_at']->format('d M Y H:i:s'):date('d M Y H:i:s',strtotime($l['logged_at']??''));
    ?>
    <div class="audit-entry">
        <div class="d-flex justify-content-between align-items-start">
            <div><?=htmlspecialchars($l['action'])?></div>
            <small class="audit-time ms-3 text-nowrap"><?=$dt?></small>
        </div>
        <div class="audit-time mt-1"><i class="fa-solid fa-user me-1"></i><?=htmlspecialchars($l['username'])?></div>
    </div>
    <?php endforeach; ?>
    <?php endif; ?>
</div>
</div></div>
<?php include '../includes/footer.php'; ?>
