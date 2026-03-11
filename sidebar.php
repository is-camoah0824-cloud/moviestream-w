<?php
$role     = $_SESSION['role']      ?? 'customer';
$fullname = $_SESSION['full_name'] ?? ($_SESSION['username'] ?? 'User');
$initial  = strtoupper(substr($fullname,0,1));
$root     = $root ?? '../';
$active   = $active ?? '';
?>
<aside class="sidebar" id="sidebar">
    <a href="<?=$root?>index.php" class="sidebar-brand">
        <i class="fa-solid fa-clapperboard"></i> MovieStream<span>19</span>
    </a>
    <div class="sidebar-user d-flex align-items-center gap-3">
        <div class="avatar"><?= htmlspecialchars($initial) ?></div>
        <div class="user-info">
            <div class="user-name"><?= htmlspecialchars($fullname) ?></div>
            <div class="user-role role-<?=$role?>"><?=$role?></div>
        </div>
    </div>
    <nav class="sidebar-nav">
        <?php if ($role === 'admin'): ?>
        <div class="sidebar-section-label">Overview</div>
        <a href="<?=$root?>admin/dashboard.php"    class="sidebar-link <?=$active==='dashboard'?'active':''?>"><i class="fa-solid fa-gauge-high"></i> Dashboard</a>
        <div class="sidebar-section-label">Content</div>
        <a href="<?=$root?>admin/movies.php"       class="sidebar-link <?=$active==='movies'?'active':''?>"><i class="fa-solid fa-film"></i> Movies</a>
        <a href="<?=$root?>admin/movie_add.php"    class="sidebar-link <?=$active==='movie_add'?'active':''?>"><i class="fa-solid fa-plus-circle"></i> Add Movie</a>
        <div class="sidebar-section-label">Users</div>
        <a href="<?=$root?>admin/users.php"        class="sidebar-link <?=$active==='users'?'active':''?>"><i class="fa-solid fa-users"></i> All Users</a>
        <a href="<?=$root?>admin/subscriptions.php" class="sidebar-link <?=$active==='subscriptions'?'active':''?>"><i class="fa-solid fa-credit-card"></i> Subscriptions</a>
        <div class="sidebar-section-label">Analytics</div>
        <a href="<?=$root?>admin/watch_history.php" class="sidebar-link <?=$active==='watch_history'?'active':''?>"><i class="fa-solid fa-clock-rotate-left"></i> Watch History</a>
        <a href="<?=$root?>admin/ratings.php"       class="sidebar-link <?=$active==='ratings'?'active':''?>"><i class="fa-solid fa-star"></i> Ratings</a>
        <a href="<?=$root?>admin/audit_log.php"     class="sidebar-link <?=$active==='audit_log'?'active':''?>"><i class="fa-solid fa-scroll"></i> Audit Log</a>
        <?php elseif ($role === 'staff'): ?>
        <div class="sidebar-section-label">Overview</div>
        <a href="<?=$root?>staff/dashboard.php"    class="sidebar-link <?=$active==='dashboard'?'active':''?>"><i class="fa-solid fa-gauge-high"></i> Dashboard</a>
        <div class="sidebar-section-label">Content</div>
        <a href="<?=$root?>staff/movies.php"       class="sidebar-link <?=$active==='movies'?'active':''?>"><i class="fa-solid fa-film"></i> Movies</a>
        <a href="<?=$root?>staff/movie_add.php"    class="sidebar-link <?=$active==='movie_add'?'active':''?>"><i class="fa-solid fa-plus-circle"></i> Add Movie</a>
        <div class="sidebar-section-label">Analytics</div>
        <a href="<?=$root?>staff/watch_history.php" class="sidebar-link <?=$active==='watch_history'?'active':''?>"><i class="fa-solid fa-clock-rotate-left"></i> Watch History</a>
        <a href="<?=$root?>staff/ratings.php"       class="sidebar-link <?=$active==='ratings'?'active':''?>"><i class="fa-solid fa-star"></i> Ratings</a>
        <?php else: ?>
        <div class="sidebar-section-label">Overview</div>
        <a href="<?=$root?>customer/dashboard.php"     class="sidebar-link <?=$active==='dashboard'?'active':''?>"><i class="fa-solid fa-gauge-high"></i> Dashboard</a>
        <div class="sidebar-section-label">Browse</div>
        <a href="<?=$root?>customer/movies.php"        class="sidebar-link <?=$active==='movies'?'active':''?>"><i class="fa-solid fa-film"></i> Browse Movies</a>
        <div class="sidebar-section-label">My Account</div>
        <a href="<?=$root?>customer/subscription.php"  class="sidebar-link <?=$active==='subscription'?'active':''?>"><i class="fa-solid fa-credit-card"></i> Subscription</a>
        <a href="<?=$root?>customer/watch_history.php" class="sidebar-link <?=$active==='watch_history'?'active':''?>"><i class="fa-solid fa-clock-rotate-left"></i> Watch History</a>
        <a href="<?=$root?>customer/ratings.php"       class="sidebar-link <?=$active==='ratings'?'active':''?>"><i class="fa-solid fa-star"></i> My Ratings</a>
        <?php endif; ?>
    </nav>
    <div class="sidebar-footer">
        <a href="<?=$root?>logout.php" class="sidebar-link" style="color:#e74c3c"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
    </div>
</aside>
<div class="sidebar-overlay" id="sidebarOverlay"></div>
