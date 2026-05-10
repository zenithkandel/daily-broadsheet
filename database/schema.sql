-- The Daily Broadsheet Database Schema
-- Run this script to create the database

CREATE DATABASE IF NOT EXISTS daily_broadsheet CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE daily_broadsheet;

-- Users (authors, admins)
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('admin', 'editor', 'author') DEFAULT 'author',
    avatar VARCHAR(255),
    bio TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Categories
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    slug VARCHAR(100) UNIQUE NOT NULL,
    name_en VARCHAR(100) NOT NULL,
    name_ne VARCHAR(100),
    color VARCHAR(7),
    sort_order INT DEFAULT 0
);

-- Articles
CREATE TABLE IF NOT EXISTS articles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    slug VARCHAR(200) UNIQUE NOT NULL,
    author_id INT NOT NULL,
    category_id INT NOT NULL,
    status ENUM('draft', 'published', 'scheduled', 'archived') DEFAULT 'draft',
    featured BOOLEAN DEFAULT FALSE,
    view_count INT DEFAULT 0,
    featured_image VARCHAR(255),
    video_url VARCHAR(500),
    audio_url VARCHAR(500),
    attachments JSON,
    scheduled_at DATETIME,
    published_at DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (author_id) REFERENCES users(id),
    FOREIGN KEY (category_id) REFERENCES categories(id)
);

-- Article Content (per language)
CREATE TABLE IF NOT EXISTS article_content (
    id INT AUTO_INCREMENT PRIMARY KEY,
    article_id INT NOT NULL,
    lang VARCHAR(5) DEFAULT 'en',
    title VARCHAR(300) NOT NULL,
    excerpt TEXT,
    body LONGTEXT NOT NULL,
    meta_title VARCHAR(300),
    meta_desc VARCHAR(160),
    FOREIGN KEY (article_id) REFERENCES articles(id) ON DELETE CASCADE,
    UNIQUE KEY unique_article_lang (article_id, lang)
);

-- Article Media
CREATE TABLE IF NOT EXISTS article_media (
    id INT AUTO_INCREMENT PRIMARY KEY,
    article_id INT NOT NULL,
    type ENUM('image', 'video', 'audio', 'document') NOT NULL,
    filename VARCHAR(255) NOT NULL,
    thumbnail VARCHAR(255),
    caption VARCHAR(500),
    sort_order INT DEFAULT 0,
    FOREIGN KEY (article_id) REFERENCES articles(id) ON DELETE CASCADE
);

-- Tags
CREATE TABLE IF NOT EXISTS tags (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name_en VARCHAR(100) NOT NULL,
    name_ne VARCHAR(100),
    slug VARCHAR(100) UNIQUE NOT NULL
);

-- Article Tags (pivot)
CREATE TABLE IF NOT EXISTS article_tags (
    article_id INT NOT NULL,
    tag_id INT NOT NULL,
    PRIMARY KEY (article_id, tag_id),
    FOREIGN KEY (article_id) REFERENCES articles(id) ON DELETE CASCADE,
    FOREIGN KEY (tag_id) REFERENCES tags(id) ON DELETE CASCADE
);

-- Comments
CREATE TABLE IF NOT EXISTS comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    article_id INT NOT NULL,
    user_name VARCHAR(100),
    email VARCHAR(100),
    content TEXT NOT NULL,
    status ENUM('pending', 'approved', 'spam') DEFAULT 'pending',
    parent_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (article_id) REFERENCES articles(id) ON DELETE CASCADE,
    FOREIGN KEY (parent_id) REFERENCES comments(id) ON DELETE CASCADE
);

-- Site Settings
CREATE TABLE IF NOT EXISTS site_settings (
    setting_key VARCHAR(100) PRIMARY KEY,
    setting_value TEXT
);

-- Insert default admin
-- Password: 8038@Zenith (bcrypt hash)
INSERT INTO users (name, email, password_hash, role) VALUES
('Admin', 'admin@news.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');

-- Insert default categories
INSERT INTO categories (slug, name_en, name_ne, color, sort_order) VALUES
('news', 'News', 'समाचार', '#C84B31', 1),
('politics', 'Politics', 'राजनीति', '#2A2520', 2),
('sports', 'Sports', 'खेलकुद', '#4A7C59', 3),
('business', 'Business', 'व्यापार', '#1E3A5F', 4),
('entertainment', 'Entertainment', 'मनोरञ्जन', '#9A3623', 5),
('technology', 'Technology', 'प्रविधि', '#5B4B8A', 6);

-- Insert default site settings
INSERT INTO site_settings (setting_key, setting_value) VALUES
('site_name', 'The Daily Broadsheet'),
('site_tagline', 'A nod to classic print newspapers reimagined for the web'),
('site_email', 'editor@dailybroadsheet.com'),
('default_language', 'en');