<?php
include '../includes/header.php';

if (!isset($_GET['id']) || !isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ? AND user_id = ?");
$stmt->execute([$_GET['id'], $_SESSION['user_id']]);
$order = $stmt->fetch();

if (!$order) {
    header("Location: order-history.php");
    exit;
}

$statuses = ['Order Placed', 'Processing', 'Out for Delivery', 'Delivered'];
$current_index = array_search($order['status'], $statuses);
?>

<div class="section-padding">
    <div class="glass tracking-card">
        <h2 class="tracking-header">Tracking Order #<?php echo $order['id']; ?></h2>
        <p class="tracking-subtitle">Expected delivery: 3-5 business days from order date.</p>

        <div class="tracking-progress-container">
            <!-- Progress Line -->
            <div class="tracking-line"></div>
            <div class="tracking-line-active" style="width: <?php echo ($current_index / (count($statuses)-1)) * 100; ?>%;"></div>

            <?php foreach ($statuses as $index => $status): ?>
                <div class="tracking-step">
                    <div class="tracking-dot" style="background: <?php echo $index <= $current_index ? 'var(--primary)' : 'var(--bg-dark)'; ?>; border: 4px solid <?php echo $index <= $current_index ? 'var(--primary)' : 'var(--glass-border)'; ?>;">
                        <?php if($index < $current_index): ?>
                            <i class="fas fa-check" style="font-size: 0.8rem;"></i>
                        <?php endif; ?>
                    </div>
                    <p class="tracking-label" style="color: <?php echo $index <= $current_index ? 'var(--text-main)' : 'var(--text-muted)'; ?>;">
                        <?php echo $status; ?>
                    </p>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="tracking-info-grid">
            <div>
                <h4 style="margin-bottom: 1rem;">Shipping Address</h4>
                <p style="color: var(--text-muted); line-height: 1.6;"><?php echo nl2br(htmlspecialchars($order['shipping_address'])); ?></p>
            </div>
            <div>
                <h4 style="margin-bottom: 1rem;">Payment Method</h4>
                <p style="color: var(--text-muted);"><?php echo htmlspecialchars($order['payment_method']); ?></p>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
