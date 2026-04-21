<?php
include '../includes/auth-check.php';
include '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_dispute'])) {
    $stmt = $pdo->prepare("UPDATE disputes SET status = ? WHERE id = ?");
    $stmt->execute([$_POST['status'], $_POST['dispute_id']]);
}

$disputes = $pdo->query("SELECT d.*, u.name as customer_name FROM disputes d JOIN users u ON d.user_id = u.id ORDER BY created_at DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Customer Support Management - EliteCommerce</title>
    <link rel="stylesheet" href="../assets/css/index.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body style="display: flex;">

<?php include '../includes/admin-sidebar.php'; ?>

<main style="margin-left: 280px; padding: 3rem; width: calc(100% - 280px);">
    <h1 style="margin-bottom: 2rem;">Customer Support Requests</h1>

    <div style="display: flex; flex-direction: column; gap: 2rem;">
        <?php foreach ($disputes as $d): ?>
            <div class="glass" style="padding: 2rem;">
                <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1.5rem;">
                    <div>
                        <h3 style="margin-bottom: 0.5rem;"><?php echo ucfirst($d['type']); ?> - Order #<?php echo $d['order_id']; ?></h3>
                        <p style="color: var(--text-muted);">From: <strong><?php echo $d['customer_name']; ?></strong> on <?php echo date('M d, Y', strtotime($d['created_at'])); ?></p>
                    </div>
                    <form method="POST" style="display: flex; gap: 1rem;">
                        <input type="hidden" name="dispute_id" value="<?php echo $d['id']; ?>">
                        <select name="status" class="form-control" style="width: auto; padding: 0.5rem;">
                            <option value="pending" <?php echo ($d['status'] === 'pending') ? 'selected' : ''; ?>>Pending</option>
                            <option value="resolved" <?php echo ($d['status'] === 'resolved') ? 'selected' : ''; ?>>Resolved</option>
                            <option value="closed" <?php echo ($d['status'] === 'closed') ? 'selected' : ''; ?>>Closed</option>
                        </select>
                        <button type="submit" name="update_dispute" class="btn btn-primary" style="padding: 0.5rem 1rem;">Update</button>
                    </form>
                </div>
                <div style="background: rgba(0,0,0,0.2); padding: 1.5rem; border-radius: 12px; border-left: 4px solid var(--primary);">
                    <p style="white-space: pre-line;"><?php echo htmlspecialchars($d['description']); ?></p>
                </div>
            </div>
        <?php endforeach; ?>
        <?php if(!$disputes): ?>
            <p style="text-align: center; color: var(--text-muted);">No support requests at the moment.</p>
        <?php endif; ?>
    </div>
</main>

</body>
</html>
