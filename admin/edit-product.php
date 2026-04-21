<?php
include '../includes/auth-check.php';
include '../config/db.php';

if (!isset($_GET['id'])) {
    header("Location: products.php");
    exit;
}

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch();

if (!$product) {
    header("Location: products.php");
    exit;
}

$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("UPDATE products SET name = ?, description = ?, price = ?, stock = ?, category_id = ?, image_url = ? WHERE id = ?");
    if ($stmt->execute([$_POST['name'], $_POST['description'], $_POST['price'], $_POST['stock'], $_POST['category_id'], $_POST['image_url'], $id])) {
        $msg = "Product updated successfully!";
        // Refresh product data
        $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->execute([$id]);
        $product = $stmt->fetch();
    } else {
        $msg = "Failed to update product.";
    }
}

$categories = $pdo->query("SELECT * FROM categories")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Product - EliteCommerce</title>
    <link rel="stylesheet" href="../assets/css/index.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body style="display: flex;">

<?php include '../includes/admin-sidebar.php'; ?>

<main style="margin-left: 280px; padding: 3rem; width: calc(100% - 280px);">
    <div style="max-width: 700px;">
        <h1 style="margin-bottom: 2rem;">Edit Product: <?php echo htmlspecialchars($product['name']); ?></h1>

        <?php if($msg): ?>
            <div style="color: var(--accent); margin-bottom: 1.5rem; background: var(--glass); padding: 1rem; border-radius: 8px; border: 1px solid var(--accent);">
                <?php echo $msg; ?>
            </div>
        <?php endif; ?>

        <div class="glass" style="padding: 3rem;">
            <form method="POST">
                <div class="form-group">
                    <label>Product Name</label>
                    <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($product['name']); ?>" required>
                </div>
                <div class="form-group">
                    <label>Category</label>
                    <select name="category_id" class="form-control" required>
                        <?php foreach($categories as $c): ?>
                            <option value="<?php echo $c['id']; ?>" <?php echo ($c['id'] == $product['category_id']) ? 'selected' : ''; ?>>
                                <?php echo $c['name']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label>Price</label>
                        <input type="number" step="0.01" name="price" class="form-control" value="<?php echo $product['price']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Stock</label>
                        <input type="number" name="stock" class="form-control" value="<?php echo $product['stock']; ?>" required>
                    </div>
                </div>
                <div class="form-group">
                    <label>Image URL</label>
                    <input type="text" name="image_url" class="form-control" value="<?php echo htmlspecialchars($product['image_url']); ?>" required>
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" class="form-control" rows="5"><?php echo htmlspecialchars($product['description']); ?></textarea>
                </div>
                <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                    <button type="submit" class="btn btn-primary" style="flex: 1;">Update Product</button>
                    <a href="products.php" class="btn" style="background: var(--glass); color: white; text-decoration: none; text-align: center; flex: 1;">Back to List</a>
                </div>
            </form>
        </div>
    </div>
</main>

</body>
</html>
