<?php
include '../includes/auth-check.php';
include '../config/db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - EliteCommerce</title>
    <link rel="stylesheet" href="../assets/css/index.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body style="display: flex;">

<?php include '../includes/admin-sidebar.php'; ?>

<main style="margin-left: 280px; padding: 3rem; width: calc(100% - 280px);">
    <h1 style="margin-bottom: 2rem;">Dashboard Overview</h1>

    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 2rem; margin-bottom: 4rem;">
        <?php
        $stats = [
            'Total Sales' => ['$'.number_format($pdo->query("SELECT SUM(total_price) FROM orders")->fetchColumn(), 2), 'var(--accent)'],
            'Total Orders' => [$pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn(), 'var(--primary)'],
            'Products' => [$pdo->query("SELECT COUNT(*) FROM products")->fetchColumn(), '#f59e0b'],
            'Customers' => [$pdo->query("SELECT COUNT(*) FROM users WHERE role='customer'")->fetchColumn(), '#ec4899']
        ];

        foreach($stats as $label => $val):
        ?>
        <div class="glass" style="padding: 1.5rem;">
            <p style="color: var(--text-muted); font-size: 0.9rem;"><?php echo $label; ?></p>
            <h2 style="color: <?php echo $val[1]; ?>; font-size: 2rem; margin-top: 0.5rem;"><?php echo $val[0]; ?></h2>
        </div>
        <?php endforeach; ?>
    </div>

    <h2 style="margin-bottom: 1.5rem;">Recent Orders</h2>
    <div class="glass" style="padding: 1.5rem; overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="text-align: left; border-bottom: 1px solid var(--glass-border);">
                    <th style="padding: 1rem;">Order ID</th>
                    <th style="padding: 1rem;">Customer</th>
                    <th style="padding: 1rem;">Date</th>
                    <th style="padding: 1rem;">Total</th>
                    <th style="padding: 1rem;">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $recentOrders = $pdo->query("SELECT o.*, u.name FROM orders o JOIN users u ON o.user_id = u.id ORDER BY created_at DESC LIMIT 5")->fetchAll();
                foreach($recentOrders as $order):
                ?>
                <tr style="border-bottom: 1px solid var(--glass-border);">
                    <td style="padding: 1rem;">#<?php echo $order['id']; ?></td>
                    <td style="padding: 1rem;"><?php echo $order['name']; ?></td>
                    <td style="padding: 1rem;"><?php echo date('M d, Y', strtotime($order['created_at'])); ?></td>
                    <td style="padding: 1rem; color: var(--accent);">$<?php echo number_format($order['total_price'], 2); ?></td>
                    <td style="padding: 1rem;">
                        <span style="background: var(--glass); padding: 0.2rem 0.6rem; border-radius: 12px; font-size: 0.8rem; border: 1px solid var(--primary);"><?php echo $order['status']; ?></span>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</main>

</body>
</html>
