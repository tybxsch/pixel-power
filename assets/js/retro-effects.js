// Efeitos Retrô JavaScript
document.addEventListener('DOMContentLoaded', function() {
    
    // Efeito de digitação para títulos
    function typeWriter(element, text, speed = 100) {
        let i = 0;
        element.innerHTML = '';
        function type() {
            if (i < text.length) {
                element.innerHTML += text.charAt(i);
                i++;
                setTimeout(type, speed);
            }
        }
        type();
    }

    // Aplicar efeito de digitação nos títulos principais
    const mainTitle = document.querySelector('.hero-title');
    if (mainTitle) {
        const originalText = mainTitle.textContent;
        typeWriter(mainTitle, originalText, 150);
    }

    // Efeito de brilho nos cards ao passar o mouse
    const cards = document.querySelectorAll('.card-retro, .game-card');
    cards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-10px) scale(1.02)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
        });
    });

    // Efeito de glitch aleatório
    function randomGlitch() {
        const glitchElements = document.querySelectorAll('.glitch');
        glitchElements.forEach(element => {
            if (Math.random() < 0.1) { // 10% de chance
                element.style.animation = 'glitch 0.3s ease-in-out';
                setTimeout(() => {
                    element.style.animation = '';
                }, 300);
            }
        });
    }

    // Executar glitch aleatório a cada 3 segundos
    setInterval(randomGlitch, 3000);

    // Efeito de scroll suave para links âncora
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Animação de entrada para elementos
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('fade-in');
            }
        });
    }, observerOptions);

    // Observar todos os cards e elementos animáveis
    document.querySelectorAll('.card-retro, .game-card, .hero-retro').forEach(el => {
        observer.observe(el);
    });

    // Efeito de partículas no fundo (simplificado)
    function createParticle() {
        const particle = document.createElement('div');
        particle.style.cssText = `
            position: fixed;
            width: 2px;
            height: 2px;
            background: var(--neon-green);
            border-radius: 50%;
            pointer-events: none;
            z-index: -1;
            top: ${Math.random() * 100}vh;
            left: ${Math.random() * 100}vw;
            animation: particle-float 8s linear infinite;
            opacity: 0.3;
        `;
        document.body.appendChild(particle);

        setTimeout(() => {
            if (particle.parentNode) {
                particle.parentNode.removeChild(particle);
            }
        }, 8000);
    }

    // Criar partículas ocasionalmente
    setInterval(createParticle, 2000);

    // CSS para animação das partículas
    if (!document.querySelector('#particle-style')) {
        const style = document.createElement('style');
        style.id = 'particle-style';
        style.textContent = `
            @keyframes particle-float {
                0% {
                    transform: translateY(0) rotate(0deg);
                    opacity: 0;
                }
                10% {
                    opacity: 0.3;
                }
                90% {
                    opacity: 0.3;
                }
                100% {
                    transform: translateY(-100vh) rotate(360deg);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(style);
    }

    // Validação de formulários com estilo retrô
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const requiredFields = form.querySelectorAll('[required]');
            let hasErrors = false;

            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    field.style.borderColor = 'var(--neon-pink)';
                    field.style.boxShadow = '0 0 15px rgba(255, 0, 128, 0.5)';
                    hasErrors = true;
                } else {
                    field.style.borderColor = 'var(--neon-green)';
                    field.style.boxShadow = '0 0 15px rgba(0, 255, 65, 0.3)';
                }
            });

            if (hasErrors) {
                e.preventDefault();
                // Mostrar mensagem de erro estilizada
                showRetroAlert('Por favor, preencha todos os campos obrigatórios!', 'error');
            }
        });
    });

    // Função para mostrar alertas estilizados
    window.showRetroAlert = function(message, type = 'success') {
        const alertDiv = document.createElement('div');
        const alertClass = type === 'error' ? 'alert-danger-retro' : 'alert-retro';
        
        alertDiv.className = `alert ${alertClass} position-fixed`;
        alertDiv.style.cssText = `
            top: 100px;
            right: 20px;
            z-index: 9999;
            min-width: 300px;
            animation: slideInRight 0.5s ease-out;
        `;
        alertDiv.innerHTML = `
            <div class="d-flex align-items-center">
                <i class="fas fa-${type === 'error' ? 'exclamation-triangle' : 'check-circle'} me-2"></i>
                <span>${message}</span>
                <button type="button" class="btn-close ms-auto" style="color: white;"></button>
            </div>
        `;

        document.body.appendChild(alertDiv);

        // Auto-remover após 5 segundos
        setTimeout(() => {
            if (alertDiv.parentNode) {
                alertDiv.style.animation = 'slideOutRight 0.5s ease-in';
                setTimeout(() => {
                    if (alertDiv.parentNode) {
                        alertDiv.parentNode.removeChild(alertDiv);
                    }
                }, 500);
            }
        }, 5000);

        // Botão de fechar
        alertDiv.querySelector('.btn-close').addEventListener('click', () => {
            alertDiv.style.animation = 'slideOutRight 0.5s ease-in';
            setTimeout(() => {
                if (alertDiv.parentNode) {
                    alertDiv.parentNode.removeChild(alertDiv);
                }
            }, 500);
        });
    };

    // CSS para animações de alerta
    if (!document.querySelector('#alert-animations')) {
        const style = document.createElement('style');
        style.id = 'alert-animations';
        style.textContent = `
            @keyframes slideInRight {
                from {
                    transform: translateX(100%);
                    opacity: 0;
                }
                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }
            @keyframes slideOutRight {
                from {
                    transform: translateX(0);
                    opacity: 1;
                }
                to {
                    transform: translateX(100%);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(style);
    }

    // Konami Code easter egg! 🎮
    let konamiCode = [];
    const sequence = [38, 38, 40, 40, 37, 39, 37, 39, 66, 65];
    
    document.addEventListener('keydown', function(e) {
        konamiCode.push(e.keyCode);
        if (konamiCode.length > sequence.length) {
            konamiCode.shift();
        }
        
        if (JSON.stringify(konamiCode) === JSON.stringify(sequence)) {
            document.body.style.animation = 'rainbow 2s linear infinite';
            showRetroAlert('🎮 KONAMI CODE ATIVADO! Você é um verdadeiro gamer retrô! 🎮', 'success');
            
            setTimeout(() => {
                document.body.style.animation = '';
            }, 5000);
            
            konamiCode = [];
        }
    });

    // CSS para efeito rainbow do Konami Code
    if (!document.querySelector('#konami-style')) {
        const style = document.createElement('style');
        style.id = 'konami-style';
        style.textContent = `
            @keyframes rainbow {
                0% { filter: hue-rotate(0deg); }
                100% { filter: hue-rotate(360deg); }
            }
        `;
        document.head.appendChild(style);
    }

    console.log('🎮 Retro Games Vault carregado! Digite ↑↑↓↓←→←→BA para ativar o Easter Egg! 🎮');
});

// Função global para loading
window.showLoading = function(element) {
    const originalContent = element.innerHTML;
    element.innerHTML = '<span class="loading-retro"></span> Carregando...';
    element.disabled = true;
    
    return function() {
        element.innerHTML = originalContent;
        element.disabled = false;
    };
}; 