CREATE DATABASE IF NOT EXISTS notebook_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE notebook_db;

CREATE TABLE IF NOT EXISTS contacts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    surname VARCHAR(255) NOT NULL,
    name VARCHAR(255) NOT NULL,
    lastname VARCHAR(255),
    gender VARCHAR(20),
    date_birth DATE,
    phone VARCHAR(50),
    location VARCHAR(500),
    email VARCHAR(255),
    comment TEXT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;