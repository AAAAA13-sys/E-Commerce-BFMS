<?php
include '../includes/auth-check.php';
include '../config/db.php';

$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add'])) {
        $stmt = $pdo->prepare("INSERT INTO products (name, description, price, stock, category_id, image_url) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$_POST['name'], $_POST['description'], $_POST['price'], $_POST['stock'], $_POST['category_id'], $_POST['image_url']]);
        $msg = "Product added successfully!";
    } elseif (isset($_POST['delete'])) {
        $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
        $stmt->execute([$_POST['id']]);
        $msg = "Product deleted!";
    }
}

$products = $pdo->query("SELECT p.*, c.name as cat_name FROM products p JOIN categories c ON p.category_id = c.id")->fetchAll();
$categories = $pdo->query("SELECT * FROM categories")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Products - EliteCommerce</title>
    <link rel="stylesheet" href="../assets/css/index.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body style="display: flex;">

<?php include '../includes/admin-sidebar.php'; ?>

<main style="margin-left: 280px; padding: 3rem; width: calc(100% - 280px);">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h1>Manage Products</h1>
        <button onclick="document.getElementById('addModal').style.display='block'" class="btn btn-primary">+ Add Product</button>
    </div>

    <?php if($msg): ?>
        <div style="color: var(--accent); margin-bottom: 1.5rem;"><?php echo $msg; ?></div>
    <?php endif; ?>

    <div class="glass" style="padding: 1.5rem;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="text-align: left; border-bottom: 1px solid var(--glass-border);">
                    <th style="padding: 1rem;">Image</th>
                    <th style="padding: 1rem;">Name</th>
                    <th style="padding: 1rem;">Category</th>
                    <th style="padding: 1rem;">Price</th>
                    <th style="padding: 1rem;">Stock</th>
                    <th style="padding: 1rem;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($products as $p): ?>
                <tr style="border-bottom: 1px solid var(--glass-border);">
                    <td style="padding: 1rem;">
                        <?php 
                            $img_path = $p['image_url'];
                            // If it's a relative path starting with assets/, prepend /E-Commerce/
                            if (strpos($img_path, 'http') !== 0 && strpos($img_path, '/') !== 0) {
                                $img_path = '/E-Commerce/' . $img_path;
                            }
                        ?>
                        <img src="<?php echo htmlspecialchars($img_path, ENT_QUOTES); ?>" style="width: 50px; height: 50px; object-fit: contain; background: white; border-radius: 4px; padding: 2px;">
                    </td>
                    <td style="padding: 1rem; font-weight: 600;"><?php echo $p['name']; ?></td>
                    <td style="padding: 1rem;"><?php echo $p['cat_name']; ?></td>
                    <td style="padding: 1rem; color: var(--secondary); font-weight: 800;">$<?php echo $p['price']; ?></td>
                    <td style="padding: 1rem;"><?php echo $p['stock']; ?></td>
                    <td style="padding: 1rem;">
                        <a href="edit-product.php?id=<?php echo $p['id']; ?>" class="btn btn-primary" style="padding: 0.4rem 0.8rem; font-size: 0.8rem; margin-right: 0.5rem; text-decoration: none;">Edit</a>
                        <form method="POST" style="display: inline;" onsubmit="return confirm('Delete this product?')">
                            <input type="hidden" name="id" value="<?php echo $p['id']; ?>">
                            <button type="submit" name="delete" class="btn" style="padding: 0.4rem 0.8rem; font-size: 0.8rem; background: var(--danger); color: white; border: none;">Delete</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Simple Add Modal -->
    <div id="addModal" class="glass" style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 500px; padding: 3rem; z-index: 1001; background: #1e293b;">
        <h2 style="margin-bottom: 1.5rem;">Add New Product</h2>
        <form method="POST">
            <div class="form-group">
                <label>Product Name</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Category</label>
                <select name="category_id" class="form-control" required>
                    <?php foreach($categories as $c): ?>
                        <option value="<?php echo $c['id']; ?>"><?php echo $c['name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label>Price</label>
                    <input type="number" step="0.01" name="price" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Stock</label>
                    <input type="number" name="stock" class="form-control" required>
                </div>
            </div>
            <div class="form-group">
                <label>Image URL (e.g., assets/images/phone.jpg)</label>
                <input type="text" name="image_url" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" class="form-control" rows="3"></textarea>
            </div>
            <div style="display: flex; gap: 1rem;">
                <button type="submit" name="add" class="btn btn-primary" style="flex: 1;">Save Product</button>
                <button type="button" onclick="document.getElementById('addModal').style.display='none'" class="btn" style="background: var(--glass); color: white;">Cancel</button>
            </div>
        </form>
    </div>
</main>

</body>
</html>
