<?php include 'includes/header.php'; ?>

<section class="hero fade-in">
    <div class="hero-content">
        <h1>PREMIUM DIE-CAST <span>COLLECTOR SERIES</span></h1>
        <p style="font-size: 1.25rem; color: white; margin-bottom: 2.5rem; text-transform: uppercase; letter-spacing: 3px; font-weight: 800;">
            THE ULTIMATE DESTINATION FOR HOT WHEELS CAR COLLECTORS
        </p>
        <a href="products/products.php" class="btn btn-primary" style="font-size: 1.5rem; padding: 1rem 3rem;">Shop Now</a>
    </div>
</section>

<section class="section-padding">
    <div class="section-header">
        <h2 class="section-title">Top <span>Speed</span> Picks</h2>
        <a href="products/products.php" class="btn btn-primary">View All</a>
    </div>
    <div class="product-grid">
        <?php
        $stmt = $pdo->query("SELECT * FROM products LIMIT 3");
        while ($product = $stmt->fetch()):
        ?>
        <div class="product-card fade-in">
            <?php 
                $img_path = $product['image_url'];
                if (strpos($img_path, 'http') !== 0 && strpos($img_path, '/') !== 0) {
                    $img_path = BASE_URL . $img_path;
                }
            ?>
            <img src="<?php echo htmlspecialchars($img_path, ENT_QUOTES); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="product-image">
            <div class="product-info">
                <h3><?php echo $product['name']; ?></h3>
                <p>
                    <?php echo $product['description']; ?>
                </p>
                <div class="product-price">$<?php echo number_format($product['price'], 2); ?></div>
                <a href="products/product-detail.php?id=<?php echo $product['id']; ?>" class="btn btn-primary" style="width: 100%; text-align: center;">View Details</a>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
