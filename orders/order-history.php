<?php
include '../includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$user_id]);
$orders = $stmt->fetchAll();
?>

<div class="section-padding">
    <h2 class="section-title" style="margin-bottom: 3rem;">Your <span>Orders</span></h2>

    <?php if ($orders): ?>
        <div class="orders-list">
            <?php foreach ($orders as $order): ?>
                <div class="glass order-item-card">
                    <div>
                        <p class="order-info-label">Order Date</p>
                        <p class="order-info-value"><?php echo date('M d, Y', strtotime($order['created_at'])); ?></p>
                    </div>
                    <div>
                        <p class="order-info-label">Total Amount</p>
                        <p class="order-info-value" style="color: var(--accent);">$<?php echo number_format($order['total_price'], 2); ?></p>
                    </div>
                    <div>
                        <p class="order-info-label">Status</p>
                        <span class="status-badge">
                            <?php echo $order['status']; ?>
                        </span>
                    </div>
                    <div style="display: flex; gap: 1rem;">
                        <a href="order-tracking.php?id=<?php echo $order['id']; ?>" class="btn btn-primary" style="font-size: 0.9rem; padding: 0.5rem 1rem;">Track Order</a>
                        <a href="../support/customer-service.php?order_id=<?php echo $order['id']; ?>" class="btn glass" style="font-size: 0.9rem; padding: 0.5rem 1rem; color: white; text-decoration: none;">Help</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
<?php else: ?>
        <div class="glass" style="padding: 5rem; text-align: center;">
            <p style="color: var(--text-muted); font-size: 1.25rem;">You haven't placed any orders yet.</p>
        </div>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>
