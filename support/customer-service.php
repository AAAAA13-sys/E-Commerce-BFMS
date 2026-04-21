<?php
include '../includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$order_id = $_GET['order_id'] ?? '';
$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = $_POST['order_id'];
    $type = $_POST['type'];
    $description = $_POST['description'];
    
    $stmt = $pdo->prepare("INSERT INTO disputes (order_id, user_id, type, description) VALUES (?, ?, ?, ?)");
    if ($stmt->execute([$order_id, $user_id, $type, $description])) {
        $msg = "Your request has been submitted. Our team will review it shortly.";
    }
}

// Fetch user's orders for the dropdown
$stmt = $pdo->prepare("SELECT id FROM orders WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$user_id]);
$orders = $stmt->fetchAll();
?>

<div style="padding: 5rem 5%;">
    <div class="auth-container glass fade-in" style="max-width: 600px;">
        <h2 style="text-align: center; margin-bottom: 2rem;">Customer Support</h2>
        
        <?php if($msg): ?>
            <div style="color: var(--accent); margin-bottom: 1.5rem; text-align: center; background: var(--glass); padding: 1rem; border-radius: 8px;">
                <?php echo $msg; ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label>Related Order</label>
                <select name="order_id" class="form-control" required>
                    <option value="">Select an order</option>
                    <?php foreach ($orders as $order): ?>
                        <option value="<?php echo $order['id']; ?>" <?php echo ($order_id == $order['id']) ? 'selected' : ''; ?>>
                            Order #<?php echo $order['id']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Issue Type</label>
                <select name="type" class="form-control" required>
                    <option value="wrong item">Wrong item received</option>
                    <option value="never received">Never received</option>
                    <option value="damaged product">Damaged product</option>
                    <option value="other">Other</option>
                </select>
            </div>
            <div class="form-group">
                <label>Description of Issue</label>
                <textarea name="description" class="form-control" rows="5" required placeholder="Please provide details about your issue..."></textarea>
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 1rem;">Submit Request</button>
        </form>
    </div>

    <?php
    // Fetch user's existing disputes
    $stmt = $pdo->prepare("SELECT * FROM disputes WHERE user_id = ? ORDER BY created_at DESC");
    $stmt->execute([$user_id]);
    $disputes = $stmt->fetchAll();
    
    if ($disputes):
    ?>
    <div style="max-width: 800px; margin: 4rem auto 0;">
        <h3 style="margin-bottom: 2rem;">Your Support Requests</h3>
        <div style="display: flex; flex-direction: column; gap: 1rem;">
            <?php foreach ($disputes as $dispute): ?>
                <div class="glass" style="padding: 1.5rem; display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <p style="font-weight: 600;">#<?php echo $dispute['order_id']; ?> - <?php echo ucfirst($dispute['type']); ?></p>
                        <p style="color: var(--text-muted); font-size: 0.9rem;"><?php echo date('M d, Y', strtotime($dispute['created_at'])); ?></p>
                    </div>
                    <span style="padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.8rem; background: var(--glass); border: 1px solid <?php echo $dispute['status'] === 'pending' ? 'var(--primary)' : 'var(--accent)'; ?>;">
                        <?php echo ucfirst($dispute['status']); ?>
                    </span>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>
