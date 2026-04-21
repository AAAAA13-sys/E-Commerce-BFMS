<?php
include '../includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Handle Actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];
    $product_id = $_POST['product_id'];

    if ($action === 'add') {
        $quantity = $_POST['quantity'];
        // Check if already in cart
        $stmt = $pdo->prepare("SELECT id, quantity FROM cart WHERE user_id = ? AND product_id = ?");
        $stmt->execute([$user_id, $product_id]);
        $existing = $stmt->fetch();

        if ($existing) {
            $new_qty = $existing['quantity'] + $quantity;
            $pdo->prepare("UPDATE cart SET quantity = ? WHERE id = ?")->execute([$new_qty, $existing['id']]);
        } else {
            $pdo->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)")->execute([$user_id, $product_id, $quantity]);
        }
    } elseif ($action === 'update') {
        $quantity = $_POST['quantity'];
        if ($quantity <= 0) {
            $pdo->prepare("DELETE FROM cart WHERE user_id = ? AND product_id = ?")->execute([$user_id, $product_id]);
        } else {
            $pdo->prepare("UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ?")->execute([$quantity, $user_id, $product_id]);
        }
    } elseif ($action === 'remove') {
        $pdo->prepare("DELETE FROM cart WHERE user_id = ? AND product_id = ?")->execute([$user_id, $product_id]);
    }
    
    header("Location: cart.php");
    exit;
}

// Fetch Cart Items
$stmt = $pdo->prepare("SELECT c.*, p.name, p.price, p.image_url FROM cart c JOIN products p ON c.product_id = p.id WHERE c.user_id = ?");
$stmt->execute([$user_id]);
$cart_items = $stmt->fetchAll();
?>

<div style="padding: 5rem 5%;">
    <h2 style="font-size: 2.5rem; margin-bottom: 3rem;">Shopping Bag</h2>

    <?php if ($cart_items): ?>
        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 4rem; align-items: start;">
            <div class="glass" style="padding: 2rem;">
                <?php 
                $subtotal = 0;
                foreach ($cart_items as $item): 
                    $total = $item['price'] * $item['quantity'];
                    $subtotal += $total;
                ?>
                <div style="display: flex; gap: 2rem; padding-bottom: 2rem; margin-bottom: 2rem; border-bottom: 1px solid var(--glass-border); align-items: center;">
                    <img src="<?php echo $item['image_url']; ?>" alt="<?php echo $item['name']; ?>" style="width: 100px; height: 100px; border-radius: 12px; object-fit: cover;">
                    <div style="flex-grow: 1;">
                        <h3><?php echo $item['name']; ?></h3>
                        <p style="color: var(--accent); font-weight: 600;">$<?php echo number_format($item['price'], 2); ?></p>
                    </div>
                    <form method="POST" style="display: flex; align-items: center; gap: 1rem;">
                        <input type="hidden" name="product_id" value="<?php echo $item['product_id']; ?>">
                        <input type="hidden" name="action" value="update">
                        <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" min="1" class="form-control" style="width: 70px;" onchange="this.form.submit()">
                    </form>
                    <div style="width: 100px; text-align: right; font-weight: 700;">$<?php echo number_format($total, 2); ?></div>
                    <form method="POST">
                        <input type="hidden" name="product_id" value="<?php echo $item['product_id']; ?>">
                        <input type="hidden" name="action" value="remove">
                        <button type="submit" style="background: none; border: none; color: var(--danger); cursor: pointer;"><i class="fas fa-trash"></i></button>
                    </form>
                </div>
                <?php endforeach; ?>
            </div>

            <div class="glass" style="padding: 2rem;">
                <h3 style="margin-bottom: 2rem;">Order Summary</h3>
                <div style="display: flex; justify-content: space-between; margin-bottom: 1rem;">
                    <span>Subtotal</span>
                    <span>$<?php echo number_format($subtotal, 2); ?></span>
                </div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 1rem;">
                    <span>Shipping</span>
                    <span style="color: var(--accent);">Free</span>
                </div>
                <div style="display: flex; justify-content: space-between; margin-top: 2rem; padding-top: 1rem; border-top: 1px solid var(--glass-border); font-size: 1.25rem; font-weight: 800;">
                    <span>Total</span>
                    <span>$<?php echo number_format($subtotal, 2); ?></span>
                </div>
                <a href="checkout.php" class="btn btn-primary" style="width: 100%; margin-top: 2rem; text-align: center;">Proceed to Checkout</a>
            </div>
        </div>
    <?php else: ?>
        <div class="glass" style="padding: 5rem; text-align: center;">
            <p style="color: var(--text-muted); font-size: 1.25rem; margin-bottom: 2rem;">Your cart is empty.</p>
            <a href="../products/products.php" class="btn btn-primary">Start Shopping</a>
        </div>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>
