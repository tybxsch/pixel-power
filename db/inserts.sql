INSERT INTO users (username, email, password) VALUES
('retrogamer', 'retro@games.com', 'senha123'),
('pixelmaster', 'pixel@power.com', 'senha456');

INSERT INTO games (user_id, title, platform, release_year, genre, personal_rating, image_url, personal_comment) VALUES
(1, 'Super Mario World', 'Super Nintendo', 1990, 'Plataforma', 9.5, 'https://link-da-imagem.com/mario.png', 'Clássico dos clássicos!'),
(1, 'Sonic the Hedgehog', 'Mega Drive', 1991, 'Plataforma', 9.0, 'https://link-da-imagem.com/sonic.png', 'Melhor rival do Mario!');
