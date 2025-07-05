CREATE DATABASE IF NOT EXISTS pixel_power;
USE pixel_power;

CREATE TABLE games (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    title VARCHAR(100) NOT NULL,
    platform VARCHAR(50) NOT NULL,
    release_year YEAR NOT NULL,
    genre VARCHAR(50) NOT NULL,
    personal_rating DECIMAL(3,1) CHECK (personal_rating >= 0 AND personal_rating <= 10),
    image_url VARCHAR(255),
    personal_comment TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
