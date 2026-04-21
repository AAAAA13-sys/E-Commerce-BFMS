<nav>
    <a href="/E-Commerce/index.php" class="logo">SPEED SHOP</a>
    <ul class="nav-links">
        <li><a href="/E-Commerce/products/products.php">Shop</a></li>
        <?php if(isset($_SESSION['user_id'])): ?>
            <li><a href="/E-Commerce/cart/cart.php">Cart</a></li>
            <li><a href="/E-Commerce/orders/order-history.php">Orders</a></li>
            <?php if($_SESSION['role'] === 'admin'): ?>
                <li><a href="/E-Commerce/admin/dashboard.php">Admin</a></li>
            <?php endif; ?>
            <li><a href="/E-Commerce/auth/logout.php">Logout</a></li>
        <?php else: ?>
            <li><a href="/E-Commerce/auth/login.php">Login</a></li>
            <li><a href="/E-Commerce/auth/register.php">Register</a></li>
        <?php endif; ?>
    </ul>
</nav>
