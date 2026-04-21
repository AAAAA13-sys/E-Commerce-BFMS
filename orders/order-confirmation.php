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
    header("Location: index.php");
    exit;
}
?>

<div style="padding: 8rem 5%; text-align: center;" class="fade-in">
    <div style="font-size: 5rem; color: var(--accent); margin-bottom: 2rem;">
        <i class="fas fa-check-circle"></i>
    </div>
    <h1 style="font-size: 3.5rem; margin-bottom: 1rem;">Order Confirmed!</h1>
    <p style="color: var(--text-muted); font-size: 1.25rem; margin-bottom: 3rem;">
        Thank you for your purchase. Your order #<?php echo htmlspecialchars($_GET['id']); ?> has been placed successfully.
    </p>
    <div style="display: flex; gap: 2rem; justify-content: center;">
        <a href="order-history.php" class="btn btn-primary">View My Orders</a>
        <a href="products.php" class="btn glass" style="text-decoration: none; color: white;">Continue Shopping</a>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
