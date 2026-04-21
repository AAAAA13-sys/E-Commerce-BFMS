<?php include '../includes/header.php'; ?>

<section style="padding: 3rem 5%;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 3rem; flex-wrap: wrap; gap: 1rem;">
        <h2 style="font-size: 2rem;">Our Collection</h2>
        <form style="display: flex; gap: 1rem; flex-grow: 1; max-width: 600px;">
            <input type="text" name="search" placeholder="Search products..." class="form-control" value="<?php echo $_GET['search'] ?? ''; ?>">
            <select name="category" class="form-control" style="width: auto;">
                <option value="">All Categories</option>
                <?php
                $catStmt = $pdo->query("SELECT * FROM categories");
                while ($cat = $catStmt->fetch()):
                ?>
                    <option value="<?php echo $cat['id']; ?>" <?php echo (isset($_GET['category']) && $_GET['category'] == $cat['id']) ? 'selected' : ''; ?>>
                        <?php echo $cat['name']; ?>
                    </option>
                <?php endwhile; ?>
            </select>
            <button type="submit" class="btn btn-primary">Filter</button>
        </form>
    </div>

    <div class="product-grid">
        <?php
        $query = "SELECT * FROM products WHERE 1=1";
        $params = [];

        if (!empty($_GET['search'])) {
            $query .= " AND (name LIKE ? OR description LIKE ?)";
            $params[] = "%" . $_GET['search'] . "%";
            $params[] = "%" . $_GET['search'] . "%";
        }

        if (!empty($_GET['category'])) {
            $query .= " AND category_id = ?";
            $params[] = $_GET['category'];
        }

        $stmt = $pdo->prepare($query);
        $stmt->execute($params);
        $products = $stmt->fetchAll();

        if ($products):
            foreach ($products as $product):
        ?>
            <div class="product-card">
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
                    <a href="product-detail.php?id=<?php echo $product['id']; ?>" class="btn btn-primary" style="width: 100%; text-align: center;">View Details</a>
                </div>
            </div>
        <?php 
            endforeach;
        else:
        ?>
            <p style="text-align: center; grid-column: 1/-1; color: var(--text-muted);">No products found matching your criteria.</p>
        <?php endif; ?>
    </div>
</section>

<?php include '../includes/footer.php'; ?>
