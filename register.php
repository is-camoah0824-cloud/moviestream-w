<?php
require_once 'config/session.php';
require_once 'config/db.php';
if (is_logged_in()) { header('Location: customer/dashboard.php'); exit; }
$error = ''; $success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username  = trim($_POST['username']  ?? '');
    $full_name = trim($_POST['full_name'] ?? '');
    $email     = trim($_POST['email']     ?? '');
    $country   = trim($_POST['country']   ?? '');
    $plan      = trim($_POST['plan_name'] ?? 'Basic');
    if (!$username || !$full_name || !$email) { $error = 'Username, full name, and email are required.'; }
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) { $error = 'Please enter a valid email address.'; }
    else {
        $sql = "EXEC RegisterCustomer_19 @username=?, @full_name=?, @plan_name=?, @email=?, @country=?";
        $params = [[$username,SQLSRV_PARAM_IN],[$full_name,SQLSRV_PARAM_IN],[$plan,SQLSRV_PARAM_IN],[$email,SQLSRV_PARAM_IN],[$country,SQLSRV_PARAM_IN]];
        $stmt = sqlsrv_query($conn, $sql, $params);
        if ($stmt === false) { $errs=$errs=sqlsrv_errors(); $error='Registration failed: '.($errs[0]['message']??'Unknown error'); }
        else { $row=sqlsrv_fetch_array($stmt,SQLSRV_FETCH_ASSOC); $success="Account created! User ID: #".($row['new_user_id']??'N/A').". You can now log in."; $_POST=[]; }
    }
}
$plans = ['Basic','Premium','Family'];
$countries = ['Ghana','Nigeria','Kenya','USA','UK','Canada','South Africa','Other'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account - MovieStream Platform</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/main.css">
</head>
<body class="auth-page">
<div class="auth-card" style="max-width:500px;">
    <div class="d-flex align-items-center gap-2 mb-4">
        <i class="fa-solid fa-clapperboard text-warning fs-4"></i>
        <div class="auth-logo">MovieStream<span>19</span></div>
    </div>
    <h2 class="mb-1">Create Account</h2>
    <p class="text-muted small mb-4">Join MovieStream and start streaming today.</p>
    <?php if ($error): ?><div class="alert alert-danger"><i class="fa-solid fa-circle-exclamation me-2"></i><?= htmlspecialchars($error) ?></div><?php endif; ?>
    <?php if ($success): ?>
        <div class="alert alert-success"><i class="fa-solid fa-circle-check me-2"></i><?= htmlspecialchars($success) ?></div>
        <a href="login.php" class="btn btn-primary w-100"><i class="fa-solid fa-right-to-bracket me-2"></i>Go to Login</a>
    <?php else: ?>
    <form method="POST" action="register.php" novalidate>
        <div class="row g-3">
            <div class="col-12">
                <label class="form-label" for="reg_username">Username *</label>
                <div class="input-group"><span class="input-group-text"><i class="fa-solid fa-at"></i></span>
                <input type="text" id="reg_username" name="username" class="form-control" placeholder="Choose a username" value="<?= htmlspecialchars($_POST['username']??'') ?>" required></div>
            </div>
            <div class="col-12">
                <label class="form-label" for="reg_fullname">Full Name *</label>
                <div class="input-group"><span class="input-group-text"><i class="fa-solid fa-user"></i></span>
                <input type="text" id="reg_fullname" name="full_name" class="form-control" placeholder="Your full name" value="<?= htmlspecialchars($_POST['full_name']??'') ?>" required></div>
            </div>
            <div class="col-12">
                <label class="form-label" for="reg_email">Email *</label>
                <div class="input-group"><span class="input-group-text"><i class="fa-solid fa-envelope"></i></span>
                <input type="email" id="reg_email" name="email" class="form-control" placeholder="your@email.com" value="<?= htmlspecialchars($_POST['email']??'') ?>" required></div>
            </div>
            <div class="col-sm-6">
                <label class="form-label" for="reg_country">Country</label>
                <select id="reg_country" name="country" class="form-select">
                    <option value="">-- Select --</option>
                    <?php foreach($countries as $c): ?><option value="<?=$c?>" <?=($_POST['country']??'')===$c?'selected':''?>><?=$c?></option><?php endforeach; ?>
                </select>
            </div>
            <div class="col-sm-6">
                <label class="form-label" for="reg_plan">Subscription Plan</label>
                <select id="reg_plan" name="plan_name" class="form-select">
                    <?php foreach($plans as $p): ?><option value="<?=$p?>" <?=($_POST['plan_name']??'Basic')===$p?'selected':''?>><?=$p?></option><?php endforeach; ?>
                </select>
            </div>
            <div class="col-12 pt-1">
                <button type="submit" class="btn btn-primary w-100 py-2"><i class="fa-solid fa-user-plus me-2"></i>Create Account</button>
            </div>
        </div>
    </form>
    <?php endif; ?>
    <hr class="mt-4 mb-3" style="border-color:var(--clr-border)">
    <p class="text-center text-muted small mb-0">Already have an account? <a href="login.php" class="text-warning fw-bold">Sign in</a></p>
    <p class="text-center small mt-2"><a href="index.php" class="text-muted"><i class="fa-solid fa-arrow-left me-1"></i>Back to home</a></p>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
