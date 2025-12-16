CREATE DATABASE IF NOT EXISTS ctf_db;
USE ctf_db;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    is_admin BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Создаем тестового пользователя (не админа)
INSERT INTO users (username, password, is_admin) 
VALUES ('testuser', MD5('test123'), FALSE);

-- Создаем админа (но флаг через куки)
INSERT INTO users (username, password, is_admin) 
VALUES ('admin', MD5('supersecret'), TRUE);