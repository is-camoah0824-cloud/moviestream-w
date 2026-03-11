<?php
require_once 'config/session.php';
require_once 'config/db.php';

if (is_logged_in()) {
    $r = current_role();
    header("Location: " . ($r === 'admin' ? 'admin/dashboard.php' : ($r === 'staff' ? 'staff/dashboard.php' : 'customer/dashboard.php')));
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    if ($username === '' || $password === '') {
        $error = 'Please enter both username and password.';
    } else {
        $stmt = sqlsrv_query($conn, "SELECT user_id, username, full_name, role FROM Users_19 WHERE username = ?", [[$username, SQLSRV_PARAM_IN]]);
        if ($stmt === false) {
            $error = 'Database error. Please try again.';
        } else {
            $user = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
            if (!$user) {
                $error = 'Invalid username or password.';
            } else {
                $_SESSION['user_id']   = $user['user_id'];
                $_SESSION['username']  = $user['username'];
                $_SESSION['full_name'] = $user['full_name'];
                $_SESSION['role']      = $user['role'];
                session_regenerate_id(true);
                $dest = match($user['role']) { 'admin' => 'admin/dashboard.php', 'staff' => 'staff/dashboard.php', default => 'customer/dashboard.php' };
                header("Location: $dest");
                exit;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In - MovieStream Platform</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/main.css">
</head>
<body class="auth-page">
<div class="auth-card">
    <div class="d-flex align-items-center gap-2 mb-4">
        <i class="fa-solid fa-clapperboard text-warning fs-4"></i>
        <div class="auth-logo">MovieStream<span>19</span></div>
    </div>
    <h2 class="mb-1">Welcome back</h2>
    <p class="text-muted small mb-4">Sign in to access your dashboard.</p>
    <?php if ($error): ?>
    <div class="alert alert-danger"><i class="fa-solid fa-circle-exclamation me-2"></i><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="POST" action="login.php" novalidate>
        <div class="mb-3">
            <label class="form-label" for="username">Username</label>
            <div class="input-group">
                <span class="input-group-text"><i class="fa-solid fa-user"></i></span>
                <input type="text" id="username" name="username" class="form-control" placeholder="Enter username" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" required>
            </div>
        </div>
        <div class="mb-4">
            <label class="form-label" for="password">Password</label>
            <div class="input-group">
                <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                <input type="password" id="password" name="password" class="form-control" placeholder="Enter password" required>
                <button class="input-group-text" type="button" onclick="togglePwd()"><i class="fa-regular fa-eye" id="eyeIcon"></i></button>
            </div>
        </div>
        <button type="submit" class="btn btn-primary w-100 py-2"><i class="fa-solid fa-right-to-bracket me-2"></i>Sign In</button>
    </form>
    <hr class="my-4" style="border-color:var(--clr-border)">
    <p class="text-center text-muted small">Don't have an account? <a href="register.php" class="text-warning fw-bold">Create one</a></p>
    <p class="text-center small"><a href="index.php" class="text-muted"><i class="fa-solid fa-arrow-left me-1"></i>Back to home</a></p>
    <div class="mt-3 p-3 rounded" style="background:rgba(255,255,255,0.04);border:1px solid var(--clr-border);">
        <p class="small fw-bold text-muted mb-2"><i class="fa-solid fa-circle-info me-1 text-info"></i>Demo Accounts</p>
        <table class="table table-sm text-muted mb-1" style="font-size:0.78rem;">
            <thead><tr><th>Role</th><th>Username</th><th>Password</th></tr></thead>
            <tbody>
                <tr><td><span class="badge bg-warning text-dark">Admin</span></td><td>admin19</td><td><em>any</em></td></tr>
                <tr><td><span class="badge bg-info text-dark">Staff</span></td><td>staff19</td><td><em>any</em></td></tr>
                <tr><td><span class="badge bg-success">Customer</span></td><td>cust_anna</td><td><em>any</em></td></tr>
            </tbody>
        </table>
        <p class="text-muted mb-0" style="font-size:0.7rem;">No password_hash column in DB - any password accepted (demo mode).</p>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>function togglePwd(){const p=document.getElementById('password'),i=document.getElementById('eyeIcon');if(p.type==='password'){p.type='text';i.classList.replace('fa-eye','fa-eye-slash');}else{p.type='password';i.classList.replace('fa-eye-slash','fa-eye');}}</script>
</body>
</html>
