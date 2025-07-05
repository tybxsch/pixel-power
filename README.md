# 🎮 Pixel Power

Um site completo para catalogar e criar rankings pessoais de jogos retrô clássicos dos anos 80, 90 e início dos 2000!

## ✨ Características

- **Visual Retrô Nostálgico**: Interface inspirada nos anos 80/90 com cores neon, efeitos especiais e tipografia pixelada
- **Sistema Completo de CRUD**: Adicione, edite, visualize e exclua jogos da sua coleção
- **Design Responsivo**: Desenvolvido com Bootstrap 5 para funcionar perfeitamente em qualquer dispositivo
- **Segurança**: Sistema de autenticação seguro com hash de senhas
- **Ranking Pessoal**: Avalie seus jogos de 0 a 10 e organize por nota
- **Multiplataforma**: Suporte para Super Nintendo, Mega Drive, PlayStation, Arcade e muito mais
- **Efeitos Interativos**: Animações, partículas, efeitos de glitch e até um Easter Egg (Konami Code!)

## 🚀 Tecnologias Utilizadas

- **Backend**: PHP 8+ (puro, sem frameworks)
- **Frontend**: HTML5, CSS3, JavaScript (ES6+)
- **Framework CSS**: Bootstrap 5.3
- **Banco de Dados**: MySQL/MariaDB
- **Ícones**: Font Awesome 6
- **Fontes**: Google Fonts (Orbitron + Press Start 2P)

## 📁 Estrutura do Projeto

```
pixel-power/
├── assets/
│   ├── css/
│   │   └── retro-style.css      # Estilos retrô customizados
│   ├── js/
│   │   └── retro-effects.js     # Efeitos JavaScript
│   └── images/                  # Imagens do projeto
├── pages/
│   ├── sobre.php               # Página sobre
│   ├── login.php               # Tela de login
│   ├── register.php            # Tela de cadastro
│   ├── dashboard.php           # Dashboard do usuário
│   ├── add-game.php            # Formulário para adicionar jogos
│   └── edit-game.php           # Formulário para editar jogos
├── includes/
│   ├── header.php              # Header reutilizável
│   ├── navbar.php              # Navegação
│   └── footer.php              # Footer reutilizável
├── db/
│   ├── connection.php          # Conexão com banco de dados
│   └── database.sql            # Estrutura do banco
├── actions/
│   ├── logout.php              # Ação de logout
│   └── delete-game.php         # Ação para deletar jogos
├── config.php                  # Configurações gerais
├── index.php                   # Página inicial
└── README.md                   # Este arquivo
```

## 🛠️ Instalação

### Pré-requisitos

- PHP 8.0+ com extensões PDO e MySQL
- MySQL 8.0+ ou MariaDB 10.4+
- Servidor web (Apache/Nginx) ou XAMPP/WAMP para desenvolvimento

### Passo a Passo

1. **Clone ou baixe o projeto**

   ```bash
   git clone [url-do-repositório]
   # ou baixe e extraia o ZIP
   ```

2. **Configure o banco de dados**
   - Crie um banco de dados MySQL/MariaDB
   - Importe o arquivo `db/database.sql`

   ```sql
   CREATE DATABASE retro_games;
   USE retro_games;
   SOURCE db/database.sql;
   ```

3. **Configure as credenciais**
   - Edite o arquivo `config.php`
   - Atualize as constantes de conexão com o banco:

   ```php
   define('DB_HOST', 'localhost');
   define('DB_NAME', 'retro_games');
   define('DB_USER', 'seu_usuario');
   define('DB_PASS', 'sua_senha');
   ```

4. **Configure o servidor web**
   - Coloque os arquivos na pasta do servidor web (htdocs, www, etc.)
   - Certifique-se de que o PHP está configurado corretamente
   - Acesse via navegador: `http://localhost:xxxx/`

## 👤 Conta de Demonstração

O sistema já vem com uma conta de teste pré-configurada:

- **Usuário**: `retrogamer`
- **Senha**: `password`

Esta conta contém jogos de exemplo para você testar todas as funcionalidades!

## 🎯 Funcionalidades

### 🔐 Sistema de Autenticação

- Cadastro de novos usuários com validação
- Login seguro com hash de senhas
- Sessões protegidas
- Logout seguro

### 🎮 Gerenciamento de Jogos

- **Adicionar**: Cadastre jogos com título, plataforma, ano, gênero, nota e comentários
- **Visualizar**: Dashboard com todos os seus jogos organizados
- **Editar**: Atualize informações de qualquer jogo
- **Excluir**: Remove jogos com confirmação de segurança
- **Imagens**: Suporte a capas via URL

### 📊 Dashboard e Estatísticas

- Total de jogos cadastrados
- Nota média da sua coleção
- Quantidade de plataformas
- Resumo por plataforma
- Ordenação por nota (ranking automático)

### 🎨 Visual e UX

- Tema retrô com cores neon vibrantes
- Animações suaves e efeitos visuais
- Grid animado no fundo
- Efeitos de glitch aleatórios
- Partículas flutuantes
- Scrollbar personalizada
- Alerts estilizados
- Responsivo para mobile

### 🥚 Easter Eggs

- **Konami Code**: Digite `↑↑↓↓←→←→BA` para ativar um efeito especial!
- Efeitos sonoros visuais
- Mensagens especiais no console

## 🎮 Plataformas Suportadas

- Super Nintendo (SNES)
- Mega Drive (Genesis)
- PlayStation 1
- PlayStation 2
- Nintendo 64
- Game Boy/Color/Advance
- Nintendo DS
- Arcade (Fliperamas)
- PC Retro
- Atari 2600
- Master System
- Dreamcast
- Saturn
- PC Engine
- Neo Geo
- E muito mais!

## 🎲 Gêneros Inclusos

Ação, Aventura, RPG, Plataforma, Luta, Tiro, Corrida, Esporte, Estratégia, Puzzle, Simulação, Terror, Musical, Arcade, Beat em Up, e outros.

## 🔧 Personalização

### Cores do Tema

As cores neon podem ser facilmente personalizadas no arquivo `assets/css/retro-style.css`:

```css
:root {
    --neon-purple: #b300ff;
    --neon-blue: #00bfff;
    --neon-green: #00ff41;
    --neon-pink: #ff0080;
    --neon-yellow: #ffff00;
}
```

### Adicionando Novas Plataformas/Gêneros

Edite os arrays nos arquivos `add-game.php` e `edit-game.php`:

```php
$platforms = [
    'Nova Plataforma',
    // ... outras plataformas
];
```

## 🐛 Solução de Problemas

### Erro de Conexão com Banco

- Verifique as credenciais em `config.php`
- Certifique-se de que o MySQL está rodando
- Confirme se o banco foi criado corretamente

### Estilos não Carregam

- Verifique se o caminho em `SITE_URL` está correto
- Confirme se os arquivos CSS estão no lugar certo
- Teste se o servidor está servindo arquivos estáticos

### Sessões não Funcionam

- Certifique-se de que `session_start()` está sendo chamado
- Verifique permissões da pasta de sessões do PHP
- Confirme se os cookies estão habilitados no navegador

## 📱 Responsividade

O site é totalmente responsivo e se adapta perfeitamente a:

- 📱 Smartphones (320px+)
- 📱 Tablets (768px+)
- 💻 Desktops (1024px+)
- 🖥️ Telas grandes (1440px+)

## 🚀 Melhorias Futuras

- [ ] Sistema de favoritos
- [ ] Busca e filtros avançados
- [ ] Export/import de coleções
- [ ] Integração com APIs de jogos
- [ ] Sistema de tags personalizadas
- [ ] Gráficos e relatórios avançados
- [ ] Modo escuro/claro
- [ ] PWA (Progressive Web App)

## 📄 Licença

Este projeto é livre para uso pessoal e educacional. Sinta-se à vontade para modificar e adaptar conforme suas necessidades!

## 🤝 Contribuindo

Contribuições são bem-vindas! Sinta-se livre para:

- Reportar bugs
- Sugerir melhorias
- Enviar pull requests
- Compartilhar ideias

## 🎉 Créditos

Desenvolvido com 💜 para os amantes dos jogos retrô!

**"Preserve suas memórias, reviva a nostalgia!"** 🎮✨

---

*Que a força (e a nostalgia) estejam com você!* 🚀
