document.addEventListener('DOMContentLoaded', function () {
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

    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function (e) {
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
                showRetroAlert('Por favor, preencha todos os campos obrigatórios!', 'error');
            }
        });
    });

    window.showRetroAlert = function (message, type = 'success') {
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

        alertDiv.querySelector('.btn-close').addEventListener('click', () => {
            alertDiv.style.animation = 'slideOutRight 0.5s ease-in';
            setTimeout(() => {
                if (alertDiv.parentNode) {
                    alertDiv.parentNode.removeChild(alertDiv);
                }
            }, 500);
        });
    };

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

    let konamiCode = [];
    const sequence = [38, 38, 40, 40, 37, 39, 37, 39, 66, 65];

    document.addEventListener('keydown', function (e) {
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

    function initPasswordToggle() {
        const passwordInputs = document.querySelectorAll('input[type="password"]');

        passwordInputs.forEach(input => {
            const wrapper = document.createElement('div');
            wrapper.style.position = 'relative';
            wrapper.style.display = 'inline-block';
            wrapper.style.width = '100%';

            input.parentNode.insertBefore(wrapper, input);
            wrapper.appendChild(input);

            const toggleBtn = document.createElement('button');
            toggleBtn.type = 'button';
            toggleBtn.className = 'password-toggle-btn';
            toggleBtn.innerHTML = '<i class="fas fa-eye"></i>';
            toggleBtn.style.cssText = `
                position: absolute;
                right: 12px;
                top: 50%;
                transform: translateY(-50%);
                background: transparent;
                border: none;
                color: var(--neon-blue);
                cursor: pointer;
                font-size: 1.1rem;
                padding: 5px;
                border-radius: 4px;
                transition: all 0.3s ease;
                z-index: 10;
            `;

            toggleBtn.addEventListener('mouseenter', function () {
                const isVisible = input.type === 'text';
                this.title = isVisible ? 'Ocultar senha' : 'Mostrar senha';
            });

            toggleBtn.addEventListener('click', function () {
                const isPassword = input.type === 'password';

                this.classList.add('clicked');
                setTimeout(() => {
                    this.classList.remove('clicked');
                }, 300);

                if (isPassword) {
                    input.type = 'text';
                    this.innerHTML = '<i class="fas fa-eye-slash"></i>';
                    this.classList.add('password-visible');
                    this.title = 'Ocultar senha';

                    input.style.borderColor = 'var(--neon-pink)';
                    input.style.boxShadow = '0 0 15px rgba(255, 0, 128, 0.3)';
                } else {
                    input.type = 'password';
                    this.innerHTML = '<i class="fas fa-eye"></i>';
                    this.classList.remove('password-visible');
                    this.title = 'Mostrar senha';

                    input.style.borderColor = 'var(--neon-green)';
                    input.style.boxShadow = '0 0 15px rgba(0, 255, 65, 0.3)';
                }

                setTimeout(() => {
                    input.style.borderColor = '';
                    input.style.boxShadow = '';
                }, 1000);
            });

            toggleBtn.title = 'Mostrar senha';

            wrapper.appendChild(toggleBtn);

            input.style.paddingRight = '45px';
        });
    }

    initPasswordToggle();

    console.log('🎮 Pixel Power carregado! Digite ↑↑↓↓←→←→BA para ativar o Easter Egg! 🎮');
});

window.showLoading = function (element) {
    const originalContent = element.innerHTML;
    element.innerHTML = '<span class="loading-retro"></span> Carregando...';
    element.disabled = true;

    return function () {
        element.innerHTML = originalContent;
        element.disabled = false;
    };
}; 