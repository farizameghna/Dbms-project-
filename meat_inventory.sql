-- Meat Inventory Management Database
-- Author: Sabber Hasan
-- Date: 2025-08-29

CREATE DATABASE IF NOT EXISTS meat_inventory_db;
USE meat_inventory_db;

-- ==============================
-- 1. Products Table
-- ==============================
CREATE TABLE products (
    product_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    category VARCHAR(50) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ==============================
-- 2. Inventory Table
-- ==============================
CREATE TABLE inventory (
    inventory_id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    batch_no VARCHAR(50) NOT NULL,
    quantity INT NOT NULL,
    unit VARCHAR(20) DEFAULT 'kg',
    received_date DATE NOT NULL,
    expiry_date DATE NOT NULL,
    method ENUM('FIFO','FEFO') DEFAULT 'FIFO',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(product_id) ON DELETE CASCADE
);

-- ==============================
-- 3. Stock Levels Table
-- ==============================
CREATE TABLE stock_levels (
    stock_id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    current_stock INT NOT NULL,
    minimum_stock INT DEFAULT 10,
    last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(product_id) ON DELETE CASCADE
);

-- ==============================
-- 4. Storage Conditions Table
-- ==============================
CREATE TABLE storage (
    storage_id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    location VARCHAR(100),
    temperature DECIMAL(5,2),
    humidity DECIMAL(5,2),
    condition_notes TEXT,
    last_checked TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(product_id) ON DELETE CASCADE
);

-- ==============================
-- 5. Yields Table
-- ==============================
CREATE TABLE yields (
    yield_id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    input_weight DECIMAL(10,2),
    output_weight DECIMAL(10,2),
    yield_percentage DECIMAL(5,2) GENERATED ALWAYS AS ((output_weight/input_weight)*100) STORED,
    processed_date DATE NOT NULL,
    FOREIGN KEY (product_id) REFERENCES products(product_id) ON DELETE CASCADE
);

-- ==============================
-- 6. Recalls Table
-- ==============================
CREATE TABLE recalls (
    recall_id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    batch_no VARCHAR(50) NOT NULL,
    reason TEXT NOT NULL,
    recall_date DATE NOT NULL,
    status ENUM('Pending','Resolved') DEFAULT 'Pending',
    FOREIGN KEY (product_id) REFERENCES products(product_id) ON DELETE CASCADE
);

-- ==============================
-- Insert Sample Data
-- ==============================
INSERT INTO products (name, category, description) VALUES
('Beef Steak', 'Beef', 'High quality beef steak'),
('Chicken Breast', 'Poultry', 'Boneless chicken breast'),
('Mutton Leg', 'Mutton', 'Fresh mutton leg');

INSERT INTO inventory (product_id, batch_no, quantity, unit, received_date, expiry_date, method)
VALUES
(1, 'BATCH1001', 50, 'kg', '2025-08-01', '2025-09-10', 'FIFO'),
(2, 'BATCH2001', 100, 'kg', '2025-08-05', '2025-09-01', 'FEFO');

INSERT INTO stock_levels (product_id, current_stock, minimum_stock) VALUES
(1, 50, 20),
(2, 100, 30);

INSERT INTO storage (product_id, location, temperature, humidity, condition_notes) VALUES
(1, 'Cold Room A', -2.5, 70.0, 'Keep sealed and monitored'),
(2, 'Cold Room B', -1.0, 65.0, 'Humidity controlled');

INSERT INTO yields (product_id, input_weight, output_weight, processed_date) VALUES
(1, 100.00, 80.00, '2025-08-15'),
(2, 120.00, 95.00, '2025-08-18');

INSERT INTO recalls (product_id, batch_no, reason, recall_date, status) VALUES
(2, 'BATCH2001', 'Detected contamination in poultry batch', '2025-08-20', 'Pending');
