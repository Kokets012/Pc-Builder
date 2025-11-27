-- PC Builder Database for InfinityFree
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- Components table
CREATE TABLE components (
    id INT PRIMARY KEY AUTO_INCREMENT,
    category VARCHAR(50) NOT NULL,
    name VARCHAR(255) NOT NULL,
    brand VARCHAR(100),
    model VARCHAR(100),
    price DECIMAL(10,2),
    specs TEXT,
    socket_type VARCHAR(50),
    ram_type VARCHAR(50),
    form_factor VARCHAR(50),
    power_requirements INT,
    image_url VARCHAR(255)
);

-- Compatibility rules table
CREATE TABLE compatibility_rules (
    id INT PRIMARY KEY AUTO_INCREMENT,
    component1_category VARCHAR(50),
    component1_value VARCHAR(100),
    component2_category VARCHAR(50),
    component2_value VARCHAR(100),
    is_compatible TINYINT(1)
);

-- User builds table
CREATE TABLE user_builds (
    id INT PRIMARY KEY AUTO_INCREMENT,
    build_name VARCHAR(255),
    cpu_id INT,
    motherboard_id INT,
    ram_id INT,
    gpu_id INT,
    total_price DECIMAL(10,2),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Sample data for components
INSERT INTO components (category, name, brand, model, price, specs, socket_type, ram_type, form_factor, power_requirements) VALUES
('CPU', 'Intel Core i7-13700K', 'Intel', 'i7-13700K', 419.99, '16 Cores, 5.4 GHz', 'LGA1700', 'DDR5', NULL, 125),
('CPU', 'AMD Ryzen 7 7800X3D', 'AMD', 'Ryzen 7 7800X3D', 449.99, '8 Cores, 5.0 GHz', 'AM5', 'DDR5', NULL, 120),
('Motherboard', 'ASUS ROG STRIX B650-A', 'ASUS', 'ROG STRIX B650-A', 299.99, 'WiFi 6, PCIe 5.0', 'AM5', 'DDR5', 'ATX', 50),
('Motherboard', 'MSI MAG B760 TOMAHAWK', 'MSI', 'MAG B760 TOMAHAWK', 189.99, 'DDR5 Support', 'LGA1700', 'DDR5', 'ATX', 45),
('RAM', 'Corsair Vengeance 32GB DDR5', 'Corsair', 'Vengeance', 129.99, '32GB 5600MHz', NULL, 'DDR5', NULL, 5),
('RAM', 'G.Skill Trident Z 16GB DDR4', 'G.Skill', 'Trident Z', 79.99, '16GB 3200MHz', NULL, 'DDR4', NULL, 5),
('GPU', 'NVIDIA RTX 4070 Ti', 'NVIDIA', 'RTX 4070 Ti', 799.99, '12GB GDDR6X', NULL, NULL, NULL, 285),
('GPU', 'AMD Radeon RX 7800 XT', 'AMD', 'RX 7800 XT', 499.99, '16GB GDDR6', NULL, NULL, NULL, 263);

-- Sample compatibility rules
INSERT INTO compatibility_rules (component1_category, component1_value, component2_category, component2_value, is_compatible) VALUES
('CPU', 'LGA1700', 'Motherboard', 'LGA1700', 1),
('CPU', 'AM5', 'Motherboard', 'AM5', 1),
('Motherboard', 'DDR4', 'RAM', 'DDR4', 1),
('Motherboard', 'DDR5', 'RAM', 'DDR5', 1);

COMMIT;