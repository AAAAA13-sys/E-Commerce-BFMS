<?php
include '../includes/auth-check.php';
include '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->execute([$_POST['status'], $_POST['order_id']]);
}

$orders = $pdo->query("SELECT o.*, u.name as customer_name FROM orders o JOIN users u ON o.user_id = u.id ORDER BY created_at DESC")->fetchAll();
$statuses = ['Order Placed', 'Processing', 'Out for Delivery', 'Delivered'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Orders - EliteCommerce</title>
    <link rel="stylesheet" href="../assets/css/index.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body style="display: flex;">

<?php include '../includes/admin-sidebar.php'; ?>

<main style="margin-left: 280px; padding: 3rem; width: calc(100% - 280px);">
    <h1 style="margin-bottom: 2rem;">Manage Orders</h1>

    <div class="glass" style="padding: 1.5rem;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="text-align: left; border-bottom: 1px solid var(--glass-border);">
                    <th style="padding: 1rem;">ID</th>
                    <th style="padding: 1rem;">Customer</th>
                    <th style="padding: 1rem;">Total</th>
                    <th style="padding: 1rem;">Payment</th>
                    <th style="padding: 1rem;">Status</th>
                    <th style="padding: 1rem;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($orders as $o): ?>
                <tr style="border-bottom: 1px solid var(--glass-border);">
                    <td style="padding: 1rem;">#<?php echo $o['id']; ?></td>
                    <td style="padding: 1rem;"><?php echo $o['customer_name']; ?></td>
                    <td style="padding: 1rem; color: var(--accent);">$<?php echo number_format($o['total_price'], 2); ?></td>
                    <td style="padding: 1rem;"><?php echo $o['payment_method']; ?></td>
                    <td style="padding: 1rem;">
                        <span style="background: var(--glass); padding: 0.2rem 0.6rem; border-radius: 12px; font-size: 0.8rem; border: 1px solid var(--primary);">
                            <?php echo $o['status']; ?>
                        </span>
                    </td>
                    <td style="padding: 1rem;">
                        <form method="POST" style="display: flex; gap: 0.5rem;">
                            <input type="hidden" name="order_id" value="<?php echo $o['id']; ?>">
                            <select name="status" class="form-control" style="padding: 0.3rem; font-size: 0.8rem; width: auto;">
                                <?php foreach($statuses as $s): ?>
                                    <option value="<?php echo $s; ?>" <?php echo ($o['status'] === $s) ? 'selected' : ''; ?>><?php echo $s; ?></option>
                                <?php endforeach; ?>
                            </select>
                            <button type="submit" name="update_status" class="btn btn-primary" style="padding: 0.3rem 0.8rem; font-size: 0.8rem;">Update</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</main>

</body>
</html>
