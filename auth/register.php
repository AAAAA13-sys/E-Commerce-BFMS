<?php
include '../includes/header.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    
    // Check if email already exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        $error = "Email already registered.";
    } else {
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        if ($stmt->execute([$name, $email, $password])) {
            header("Location: login.php?msg=Registration successful");
            exit;
        } else {
            $error = "Something went wrong.";
        }
    }
}
?>

<div class="auth-container glass fade-in">
    <div class="auth-header">
        <h2 class="auth-title">Join the <span>Team</span></h2>
        <p class="auth-subtitle">Apply for your collector's license</p>
    </div>

    <?php if($error): ?>
        <div class="auth-alert auth-alert-danger">
            <i class="fas fa-exclamation-triangle"></i> <?php echo $error; ?>
        </div>
    <?php endif; ?>

    <form method="POST">
        <div class="form-group">
            <label>Full Name</label>
            <input type="text" name="name" class="form-control" placeholder="John Speed" required>
        </div>
        <div class="form-group">
            <label>Email Address</label>
            <input type="email" name="email" class="form-control" placeholder="driver@speedshop.com" required>
        </div>
        <div class="form-group">
            <label>Create Secret Key</label>
            <input type="password" name="password" class="form-control" placeholder="••••••••" required minlength="6">
        </div>
        <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 1rem; padding: 1.25rem; font-size: 1.1rem;">Submit Application</button>
    </form>
    <p style="text-align: center; margin-top: 2.5rem; color: var(--bg-darker); font-size: 0.9rem; font-weight: 700;">
        Already have a license? <a href="login.php" style="color: var(--primary); text-decoration: none;">Login to Garage</a>
    </p>
</div>

<?php include '../includes/footer.php'; ?>
