<?php
include '../includes/header.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['role'] = $user['role'];
        
        if ($user['role'] === 'admin') {
            header("Location: " . BASE_URL . "admin/dashboard.php");
        } else {
            header("Location: " . BASE_URL . "index.php");
        }
        exit;
    } else {
        $error = "Invalid email or password.";
    }
}
?>

<div class="auth-container glass fade-in">
    <div class="auth-header">
        <h2 class="auth-title">Welcome <span>Back</span></h2>
        <p class="auth-subtitle">Enter your pit stop credentials</p>
    </div>
    
    <?php if(isset($_GET['msg'])): ?>
        <div class="auth-alert auth-alert-info">
            <i class="fas fa-info-circle"></i> <?php echo htmlspecialchars($_GET['msg']); ?>
        </div>
    <?php endif; ?>

    <?php if($error): ?>
        <div class="auth-alert auth-alert-danger">
            <i class="fas fa-exclamation-triangle"></i> <?php echo $error; ?>
        </div>
    <?php endif; ?>

    <form method="POST">
        <div class="form-group">
            <label>Driver Email</label>
            <input type="email" name="email" class="form-control" placeholder="race@speedshop.com" required>
        </div>
        <div class="form-group">
            <label>Secret Key</label>
            <input type="password" name="password" class="form-control" placeholder="••••••••" required>
        </div>
        <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 1rem; padding: 1.25rem; font-size: 1.1rem;">Ignition Start</button>
    </form>
    <p style="text-align: center; margin-top: 2.5rem; color: var(--bg-darker); font-size: 0.9rem; font-weight: 700;">
        Don't have a license? <a href="register.php" style="color: var(--primary); text-decoration: none;">Apply Here</a>
    </p>
</div>

<?php include '../includes/footer.php'; ?>
