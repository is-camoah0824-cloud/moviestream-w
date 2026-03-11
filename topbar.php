<?php /* Shared topbar */ ?>
<div class="topbar">
    <div class="d-flex align-items-center gap-3">
        <button class="sidebar-toggle" id="sidebarToggle"><i class="fa-solid fa-bars"></i></button>
        <span class="topbar-title"><?= htmlspecialchars($title ?? '') ?></span>
    </div>
    <div class="topbar-right">
        <span class="d-none d-sm-inline text-muted small"><i class="fa-regular fa-clock me-1"></i><?= date('D, d M Y') ?></span>
        <a href="<?= $root ?? '../' ?>logout.php" class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-right-from-bracket me-1"></i>Logout</a>
    </div>
</div>
