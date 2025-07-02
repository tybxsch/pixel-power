CREATE DATABASE IF NOT EXISTS retro_games;
USE retro_games;

-- Tabela de usuários
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabela de jogos
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

-- Inserir usuário de teste
INSERT INTO users (username, email, password) VALUES 
('retrogamer', 'retro@games.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- Inserir alguns jogos de exemplo
INSERT INTO games (user_id, title, platform, release_year, genre, personal_rating, personal_comment) VALUES
(1, 'Super Mario World', 'Super Nintendo', 1990, 'Plataforma', 9.5, 'Um dos melhores jogos de plataforma já criados!'),
(1, 'Sonic the Hedgehog', 'Mega Drive', 1991, 'Plataforma', 9.0, 'Velocidade pura e soundtrack incrível!'),
(1, 'Final Fantasy VII', 'PlayStation', 1997, 'RPG', 10.0, 'Obra prima absoluta dos RPGs!'),
(1, 'Street Fighter II', 'Arcade', 1991, 'Luta', 9.2, 'Definiu o gênero de jogos de luta!'); 