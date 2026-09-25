CREATE DATABASE IF NOT EXISTS inventaris_db;

USE inventaris_db;

CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL
);

CREATE TABLE suppliers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    phone VARCHAR(20)
);

CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    category_id INT NOT NULL,
    supplier_id INT NOT NULL,
    price DECIMAL(12,2) NOT NULL,
    stock INT NOT NULL DEFAULT 0,

    FOREIGN KEY (category_id)
        REFERENCES categories(id),

    FOREIGN KEY (supplier_id)
        REFERENCES suppliers(id)
);

INSERT INTO categories (name) VALUES
('Aksesoris'),
('Display'),
('Komputer'),
('Jaringan'),
('Penyimpanan');

INSERT INTO suppliers (name, phone) VALUES
('PT Teknologi Nusantara', '081234567801'),
('CV Digital Jaya', '081234567802'),
('PT Komputer Medan', '081234567803'),
('CV Network Solution', '081234567804'),
('PT Data Storage Indonesia', '081234567805');

INSERT INTO products
(name, category_id, supplier_id, price, stock)
VALUES
('Mouse Wireless Logitech', 1, 1, 150000, 20),
('Keyboard Mechanical', 1, 2, 450000, 15),
('Monitor LG 24 Inch', 2, 3, 2200000, 10),
('Router TP-Link', 4, 4, 650000, 12),
('SSD Kingston 1TB', 5, 5, 1200000, 8);