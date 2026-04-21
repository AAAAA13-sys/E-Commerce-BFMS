<?php 
include '../includes/header.php'; 

if (!isset($_GET['id'])) {
    header("Location: products.php");
    exit;
}

$stmt = $pdo->prepare("SELECT p.*, c.name as category_name FROM products p JOIN categories c ON p.category_id = c.id WHERE p.id = ?");
$stmt->execute([$_GET['id']]);
$product = $stmt->fetch();

if (!$product) {
    header("Location: products.php");
    exit;
}
?>

<div class="detail-container">
    <div class="glass detail-image-box">
        <img src="<?php echo $product['image_url']; ?>" alt="<?php echo $product['name']; ?>">
    </div>
    <div class="fade-in">
        <span class="detail-category"><?php echo $product['category_name']; ?></span>
        <h1 class="detail-title"><?php echo $product['name']; ?></h1>
        <div class="product-price detail-price-badge">$<?php echo number_format($product['price'], 2); ?></div>
        <p class="detail-desc">
            <?php echo $product['description']; ?>
        </p>
        
        <div class="detail-stock">
            <p class="detail-stock-status">Availability: <span class="detail-stock-count"><?php echo $product['stock'] > 0 ? 'In Stock ('.$product['stock'].' units)' : 'Out of Stock'; ?></span></p>
        </div>

        <?php if(isset($_SESSION['user_id'])): ?>
            <form action="../cart/cart.php" method="POST" style="display: flex; gap: 1rem;">
                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                <input type="hidden" name="action" value="add">
                <input type="number" name="quantity" value="1" min="1" max="<?php echo $product['stock']; ?>" class="form-control" style="width: 80px;">
                <button type="submit" class="btn btn-primary" style="flex-grow: 1;">Add to Cart</button>
            </form>
        <?php else: ?>
            <a href="../auth/login.php" class="btn btn-primary" style="display: block; text-align: center;">Login to Purchase</a>
        <?php endif; ?>
    </div>
</div>
<?php include '../includes/footer.php'; ?>
