<?php
include '../includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch Cart
$stmt = $pdo->prepare("SELECT c.*, p.name, p.price FROM cart c JOIN products p ON c.product_id = p.id WHERE c.user_id = ?");
$stmt->execute([$user_id]);
$cart_items = $stmt->fetchAll();

if (!$cart_items) {
    header("Location: products.php");
    exit;
}

$subtotal = 0;
foreach($cart_items as $item) {
    $subtotal += $item['price'] * $item['quantity'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $address = $_POST['address'];
    $payment = $_POST['payment'];
    
    try {
        $pdo->beginTransaction();
        
        // Create Order
        $stmt = $pdo->prepare("INSERT INTO orders (user_id, total_price, shipping_address, payment_method) VALUES (?, ?, ?, ?)");
        $stmt->execute([$user_id, $subtotal, $address, $payment]);
        $order_id = $pdo->lastInsertId();
        
        // Create Order Items
        $itemStmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, product_name, price, quantity) VALUES (?, ?, ?, ?, ?)");
        $stockStmt = $pdo->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");
        
        foreach ($cart_items as $item) {
            $itemStmt->execute([$order_id, $item['product_id'], $item['name'], $item['price'], $item['quantity']]);
            $stockStmt->execute([$item['quantity'], $item['product_id']]);
        }
        
        // Clear Cart
        $pdo->prepare("DELETE FROM cart WHERE user_id = ?")->execute([$user_id]);
        
        $pdo->commit();
        header("Location: order-confirmation.php?id=" . $order_id);
        exit;
    } catch (Exception $e) {
        $pdo->rollBack();
        $error = "Failed to place order: " . $e->getMessage();
    }
}
?>

<div style="padding: 5rem 5%;">
    <h2 style="font-size: 2.5rem; margin-bottom: 3rem;">Checkout</h2>

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 4rem; align-items: start;">
        <div class="glass" style="padding: 3rem;">
            <form method="POST">
                <h3 style="margin-bottom: 2rem;">Shipping Information</h3>
                <div class="form-group">
                    <label>Shipping Address</label>
                    <textarea name="address" class="form-control" rows="4" required placeholder="Enter your full address here..."></textarea>
                </div>
                
                <h3 style="margin: 3rem 0 2rem;">Payment Method</h3>
                <div class="form-group">
                    <label>Select Payment</label>
                    <select name="payment" class="form-control" required>
                        <option value="Credit Card">Credit Card</option>
                        <option value="PayPal">PayPal</option>
                        <option value="Cash on Delivery">Cash on Delivery</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 2rem; font-size: 1.1rem; padding: 1.25rem;">Complete Purchase</button>
            </form>
        </div>

        <div class="glass" style="padding: 2rem;">
            <h3 style="margin-bottom: 2rem;">Order Summary</h3>
            <?php foreach($cart_items as $item): ?>
                <div style="display: flex; justify-content: space-between; margin-bottom: 1rem; color: var(--text-muted);">
                    <span><?php echo $item['name']; ?> x <?php echo $item['quantity']; ?></span>
                    <span>$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></span>
                </div>
            <?php endforeach; ?>
            <div style="display: flex; justify-content: space-between; margin-top: 2rem; padding-top: 1rem; border-top: 1px solid var(--glass-border); font-size: 1.25rem; font-weight: 800;">
                <span>Total</span>
                <span>$<?php echo number_format($subtotal, 2); ?></span>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
