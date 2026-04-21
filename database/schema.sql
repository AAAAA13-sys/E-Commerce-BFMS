CREATE DATABASE IF NOT EXISTS ecommerce_db;
USE ecommerce_db;

-- Categories Table
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Products Table
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    stock INT NOT NULL DEFAULT 0,
    image_url VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
);

-- Users Table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'customer') DEFAULT 'customer',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Cart Table
CREATE TABLE IF NOT EXISTS cart (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- Orders Table
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    total_price DECIMAL(10, 2) NOT NULL,
    status ENUM('Order Placed', 'Processing', 'Out for Delivery', 'Delivered') DEFAULT 'Order Placed',
    shipping_address TEXT NOT NULL,
    payment_method VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Order Items Table
CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    product_name VARCHAR(255) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    quantity INT NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
);

-- Disputes Table
CREATE TABLE IF NOT EXISTS disputes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    user_id INT NOT NULL,
    type ENUM('wrong item', 'never received', 'damaged product', 'other') NOT NULL,
    description TEXT NOT NULL,
    status ENUM('pending', 'resolved', 'closed') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Initial Data
INSERT INTO categories (name) VALUES ('Classics'), ('Supercars'), ('Treasure Hunts'), ('Muscle Cars'), ('Fantasy');

INSERT INTO products (category_id, name, description, price, stock, image_url) VALUES 
(3, '2026 Hot Wheels RLC x NARUTO Nissan Silvia (S15)', 'Ultra-exclusive Red Line Club collaboration featuring the legendary Naruto Shippuden theme on a Nissan Silvia S15.', 74.99, 50, 'assets/images/2026 Hot Wheels RLC x NARUTO Nissan Silvia (S15).webp'),
(2, '2026 Hot Wheels RLC Elite 64 Porsche 911 GT2 EVO 993', 'High-detail Elite 64 series Porsche 911 GT2 EVO with opening parts and premium paint finish.', 64.99, 100, 'assets/images/2026 Hot Wheels RLC Elite 64 Porsche 911 GT2 EVO 993.webp'),
(1, '''95 Mazda RX-7', 'A pristine collector piece of the iconic FD3S Mazda RX-7 in a classic street racing setup.', 70.00, 75, 'assets/images/''95 Mazda rx-7.webp'),
(4, 'Hot Wheels 2026 Legends Tour 1969 Ford Mustang Boss 302', 'Limited edition Legends Tour exclusive 1969 Ford Mustang Boss 302 with authentic racing deco.', 59.99, 200, 'assets/images/Hot Wheels 2026 Legends Tour 1969 Ford Mustang Boss 302.webp');

-- Default Admin (Password: admin123)
INSERT INTO users (name, email, password, role) VALUES 
('Admin User', 'admin@example.com', '$2y$10$kdM8PQ6Vev9xN/6Ps7Y8Iu5Ix8.C6kJ39mgJNiiWie8qKB3SywiTa', 'admin');
