-- Criar e usar o banco de dados pixel_power
CREATE DATABASE IF NOT EXISTS pixel_power;
USE pixel_power;

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

-- Inserir usuários de exemplo
INSERT INTO users (username, email, password) VALUES 
('retrogamer', 'retro@games.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('gamerpro', 'pro@games.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('nostalgia', 'nostalgia@retro.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('pixelmaster', 'pixel@power.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- Inserir jogos de exemplo para o usuário 1 (retrogamer)
INSERT INTO games (user_id, title, platform, release_year, genre, personal_rating, personal_comment) VALUES
(1, 'Super Mario World', 'Super Nintendo', 1990, 'Plataforma', 9.5, 'Um dos melhores jogos de plataforma já criados!'),
(1, 'Sonic the Hedgehog', 'Mega Drive', 1991, 'Plataforma', 9.0, 'Velocidade pura e soundtrack incrível!'),
(1, 'Final Fantasy VII', 'PlayStation', 1997, 'RPG', 10.0, 'Obra prima absoluta dos RPGs!'),
(1, 'Street Fighter II', 'Arcade', 1991, 'Luta', 9.2, 'Definiu o gênero de jogos de luta!'),
(1, 'The Legend of Zelda: A Link to the Past', 'Super Nintendo', 1991, 'Aventura', 9.8, 'Aventura épica que define o gênero!'),
(1, 'Chrono Trigger', 'Super Nintendo', 1995, 'RPG', 9.9, 'RPG perfeito com múltiplos finais!');

-- Inserir jogos de exemplo para o usuário 2 (gamerpro)
INSERT INTO games (user_id, title, platform, release_year, genre, personal_rating, personal_comment) VALUES
(2, 'Metal Gear Solid', 'PlayStation', 1998, 'Ação', 9.7, 'Revolucionou os jogos de stealth!'),
(2, 'Resident Evil 2', 'PlayStation', 1998, 'Survival Horror', 9.3, 'Terror clássico que marcou época!'),
(2, 'Gran Turismo', 'PlayStation', 1997, 'Corrida', 9.1, 'Simulador de corrida revolucionário!'),
(2, 'Crash Bandicoot', 'PlayStation', 1996, 'Plataforma', 8.8, 'Mascote da Sony em sua melhor forma!'),
(2, 'Spyro the Dragon', 'PlayStation', 1998, 'Plataforma', 8.9, 'Aventura colorida e divertida!');

-- Inserir jogos de exemplo para o usuário 3 (nostalgia)
INSERT INTO games (user_id, title, platform, release_year, genre, personal_rating, personal_comment) VALUES
(3, 'Pac-Man', 'Arcade', 1980, 'Labirinto', 9.0, 'O clássico que começou tudo!'),
(3, 'Donkey Kong', 'Arcade', 1981, 'Plataforma', 8.5, 'Mario em sua primeira aparição!'),
(3, 'Space Invaders', 'Arcade', 1978, 'Tiro', 8.7, 'Definiu os shooters espaciais!'),
(3, 'Tetris', 'Game Boy', 1989, 'Puzzle', 9.6, 'Puzzle perfeito que nunca envelhece!'),
(3, 'Super Mario Bros.', 'Nintendo Entertainment System', 1985, 'Plataforma', 9.4, 'O jogo que salvou a indústria!');

-- Inserir jogos de exemplo para o usuário 4 (pixelmaster)
INSERT INTO games (user_id, title, platform, release_year, genre, personal_rating, personal_comment) VALUES
(4, 'Castlevania: Symphony of the Night', 'PlayStation', 1997, 'Metroidvania', 9.8, 'Obra prima dos metroidvanias!'),
(4, 'Mega Man X', 'Super Nintendo', 1993, 'Plataforma', 9.2, 'Evolução perfeita do Mega Man!'),
(4, 'EarthBound', 'Super Nintendo', 1994, 'RPG', 9.5, 'RPG único e inovador!'),
(4, 'Super Metroid', 'Super Nintendo', 1994, 'Metroidvania', 9.7, 'Exploração espacial perfeita!'),
(4, 'Secret of Mana', 'Super Nintendo', 1993, 'RPG', 9.1, 'RPG de ação cooperativo incrível!'),
(4, 'Donkey Kong Country', 'Super Nintendo', 1994, 'Plataforma', 9.3, 'Gráficos pré-renderizados impressionantes!'); 