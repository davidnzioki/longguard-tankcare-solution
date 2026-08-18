-- ============================================
-- Longguard Tankcare Solutions - Database Setup
-- Run this in phpMyAdmin or MySQL CLI
-- ============================================

CREATE DATABASE IF NOT EXISTS longguard_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE longguard_db;

-- Bookings / Quote Requests
CREATE TABLE IF NOT EXISTS bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    location VARCHAR(150) NOT NULL,
    tank_size VARCHAR(50) NOT NULL,
    service_type VARCHAR(100) DEFAULT 'Cleaning',
    preferred_date DATE,
    message TEXT,
    status ENUM('pending', 'confirmed', 'completed', 'cancelled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Contact Form Messages
CREATE TABLE IF NOT EXISTS contact_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    email VARCHAR(150),
    location VARCHAR(150),
    tank_size VARCHAR(50),
    message TEXT NOT NULL,
    is_read TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Testimonials
CREATE TABLE IF NOT EXISTS testimonials (
    id INT AUTO_INCREMENT PRIMARY KEY,
    client_name VARCHAR(100) NOT NULL,
    client_role VARCHAR(100),
    location VARCHAR(100),
    message TEXT NOT NULL,
    rating TINYINT DEFAULT 5,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert sample testimonials
INSERT INTO testimonials (client_name, client_role, location, message, rating) VALUES
('James Mwangi', 'Property Manager', 'Nairobi', 'Longguard cleaned our apartment tanks professionally and on time. Highly recommend their services to anyone looking for reliable tank cleaning.', 5),
('Grace Wanjiku', 'Homeowner', 'Thika', 'Excellent service and very affordable. My water now smells and tastes clean. The team was courteous and thorough.', 5),
('Pastor David Kariuki', 'Church Administrator', 'Juja', 'We hired Longguard for our church water tanks. They did a fantastic job and explained everything clearly. Trustworthy company.', 5),
('Sarah Njoroge', 'Hotel Manager', 'Kiambu', 'Professional team, modern equipment, and results speak for themselves. Our guests have noticed the difference in water quality.', 5),
('Dr. Peter Omondi', 'Clinic Owner', 'Ruiru', 'For a medical facility, water quality is critical. Longguard exceeded our expectations with their disinfection service.', 5);

-- Pricing Table
CREATE TABLE IF NOT EXISTS pricing (
    id INT AUTO_INCREMENT PRIMARY KEY,
    size_range VARCHAR(50) NOT NULL,
    starting_price INT,
    is_quote TINYINT(1) DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1
);

INSERT INTO pricing (size_range, starting_price, is_quote) VALUES
('500L – 1,000L', 1500, 0),
('1,500L – 3,000L', 2500, 0),
('5,000L – 10,000L', 4500, 0),
('15,000L – 24,000L', 8000, 0),
('25,000L+', NULL, 1);

-- ============================================
-- Admin login brute-force log (optional table)
-- Used by auth.php file-based fallback; this
-- table is an alternative if you prefer DB logging.
-- ============================================
-- CREATE TABLE IF NOT EXISTS admin_login_attempts (
--     id INT AUTO_INCREMENT PRIMARY KEY,
--     ip_address VARCHAR(45) NOT NULL,
--     attempted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
--     INDEX (ip_address)
-- );
