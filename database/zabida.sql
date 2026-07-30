-- ZABIDA database schema
-- Import with: mysql -u root -p zabida < database/zabida.sql

CREATE DATABASE IF NOT EXISTS zabida CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE zabida;

CREATE TABLE IF NOT EXISTS users (
    id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username      VARCHAR(64) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS ngos (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    short_name  VARCHAR(32) NOT NULL,
    full_name   VARCHAR(255) NOT NULL,
    description TEXT,
    logo_path   VARCHAR(255),
    sort_order  TINYINT UNSIGNED DEFAULT 0
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS posts (
    id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title         VARCHAR(255) NOT NULL,
    excerpt       VARCHAR(500),
    body          TEXT,
    image         VARCHAR(255) DEFAULT 'assets/images/zabida_logo.png',
    source        ENUM('manual','facebook') DEFAULT 'manual',
    fb_post_id    VARCHAR(64) NULL,
    published_at  DATE NOT NULL,
    created_at    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uniq_fb_post (fb_post_id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS activities (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    category    VARCHAR(128) NOT NULL,
    title       VARCHAR(255) NOT NULL,
    description TEXT,
    sort_order  TINYINT UNSIGNED DEFAULT 0
) ENGINE=InnoDB;

-- Seed: the four founding member NGOs
INSERT INTO ngos (short_name, full_name, description, logo_path, sort_order) VALUES
('KKI', 'Katilingban sa Kalambuan, Inc.', 'Promotes women and children''s rights and socialized housing.', 'assets/images/Katilingban.png', 1),
('PAZ', 'Peace Advocates Zamboanga', 'Non-profit engaged in the promotion of peace, interreligious dialogue and advocacy.', 'assets/images/paz_logo.jpg', 2),
('ROOF', 'Reach Out to Others Foundation', 'Promotes sustainable agriculture and the welfare of marginalized sectors.', 'assets/images/roof_logo.png', 3),
('Nagdilaab', 'Nagdilaab Foundation Inc.', 'Capability building, conflict transformation and dialogue, cultural contextualization, peacebuilding and human rights in Basilan.', 'assets/images/nagdilaab_logo.png', 4);

-- Seed: a default admin user — CHANGE THIS PASSWORD before deploying.
-- Username: admin  Password: changeme
INSERT INTO users (username, password_hash) VALUES
('admin', '$2y$10$Hr5WnRUVHb9Tytaxx4ea7eElUrvPEUiqoPYJdzCmJBEULFydvb6e2');
