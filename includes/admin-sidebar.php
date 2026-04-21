<aside style="width: 280px; height: 100vh; background: #0b1120; border-right: 1px solid var(--glass-border); padding: 2rem; position: fixed; display: flex; flex-direction: column;">
    <h2 class="logo" style="margin-bottom: 3rem;">SPEED ADMIN</h2>
    <ul style="list-style: none; flex-grow: 1;">
        <li style="margin-bottom: 1.5rem;"><a href="dashboard.php" style="color: <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'var(--primary)' : 'var(--text-main)'; ?>; text-decoration: none; font-weight: <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? '700' : '400'; ?>;">Dashboard</a></li>
        <li style="margin-bottom: 1.5rem;"><a href="products.php" style="color: <?php echo basename($_SERVER['PHP_SELF']) == 'products.php' ? 'var(--primary)' : 'var(--text-main)'; ?>; text-decoration: none; font-weight: <?php echo basename($_SERVER['PHP_SELF']) == 'products.php' ? '700' : '400'; ?>;">Products</a></li>
        <li style="margin-bottom: 1.5rem;"><a href="orders.php" style="color: <?php echo basename($_SERVER['PHP_SELF']) == 'orders.php' ? 'var(--primary)' : 'var(--text-main)'; ?>; text-decoration: none; font-weight: <?php echo basename($_SERVER['PHP_SELF']) == 'orders.php' ? '700' : '400'; ?>;">Orders</a></li>
        <li style="margin-bottom: 1.5rem;"><a href="customer-service.php" style="color: <?php echo basename($_SERVER['PHP_SELF']) == 'customer-service.php' ? 'var(--primary)' : 'var(--text-main)'; ?>; text-decoration: none; font-weight: <?php echo basename($_SERVER['PHP_SELF']) == 'customer-service.php' ? '700' : '400'; ?>;">Support Requests</a></li>
        <li style="margin-top: 3rem;"><a href="<?php echo BASE_URL; ?>index.php" style="color: var(--text-muted); text-decoration: none; font-size: 0.9rem;">← View Site</a></li>
    </ul>
    
    <a href="<?php echo BASE_URL; ?>auth/logout.php" class="btn" style="background: rgba(239, 68, 68, 0.1); color: var(--danger); border: 1px solid var(--danger); width: 100%; padding: 0.8rem; text-decoration: none; font-weight: 700; text-transform: uppercase; font-size: 0.8rem; letter-spacing: 1px; transition: all 0.3s;">
        <i class="fas fa-sign-out-alt" style="margin-right: 0.5rem;"></i> Logout
    </a>
</aside>
