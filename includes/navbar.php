<nav>
    <a href="<?php echo BASE_URL; ?>index.php" class="logo">SPEED SHOP</a>
    <ul class="nav-links">
        <li><a href="<?php echo BASE_URL; ?>products/products.php">Shop</a></li>
        <?php if(isset($_SESSION['user_id'])): ?>
            <li><a href="<?php echo BASE_URL; ?>cart/cart.php">Cart</a></li>
            <li><a href="<?php echo BASE_URL; ?>orders/order-history.php">Orders</a></li>
            <?php if($_SESSION['role'] === 'admin'): ?>
                <li><a href="<?php echo BASE_URL; ?>admin/dashboard.php">Admin</a></li>
            <?php endif; ?>
            <li><a href="<?php echo BASE_URL; ?>auth/logout.php">Logout</a></li>
        <?php else: ?>
            <li><a href="<?php echo BASE_URL; ?>auth/login.php">Login</a></li>
            <li><a href="<?php echo BASE_URL; ?>auth/register.php">Register</a></li>
        <?php endif; ?>
    </ul>
</nav>
