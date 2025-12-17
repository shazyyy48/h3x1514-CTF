// script.js - обновленная версия
document.addEventListener('DOMContentLoaded', function() {
    // Элементы DOM
    const loginBtn = document.getElementById('loginBtn');
    const loginInput = document.getElementById('login');
    const tokenDisplay = document.getElementById('tokenDisplay');
    const resultBox = document.getElementById('resultBox');
    const showTokenBtn = document.getElementById('showTokenBtn');
    const copyTokenBtn = document.getElementById('copyTokenBtn');
    const decodeTokenBtn = document.getElementById('decodeTokenBtn');
    const modifyTokenBtn = document.getElementById('modifyTokenBtn');
    const flagStatus = document.getElementById('flagStatus');
    const modal = document.getElementById('tokenModal');
    const closeBtn = document.querySelector('.close');
    const applyTokenBtn = document.getElementById('applyTokenBtn');
    const editHeader = document.getElementById('editHeader');
    const editPayload = document.getElementById('editPayload');
    const editSignature = document.getElementById('editSignature');
    
    let currentToken = null;

    // Логин
    loginBtn.addEventListener('click', async function() {
        const login = loginInput.value.trim();
        
        if (!login) {
            showResult('Введите логин', 'error');
            return;
        }
        
        try {
            const response = await fetch('/login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ login })
            });
            
            const data = await response.json();
            
            if (response.ok) {
                currentToken = data.token;
                updateTokenDisplay(currentToken);
                showResult(data.message, 'success');
                checkFlagAccess();
            } else {
                showResult(data.error, 'error');
            }
        } catch (error) {
            showResult('Ошибка подключения', 'error');
        }
    });

    // Показать токен
    showTokenBtn.addEventListener('click', function() {
        if (!currentToken) {
            showResult('Сначала войдите в систему', 'error');
            return;
        }
        
        updateTokenDisplay(currentToken);
        tokenDisplay.style.display = 'block';
    });

    // Копировать токен
    copyTokenBtn.addEventListener('click', function() {
        if (!currentToken) {
            showResult('Нет токена', 'error');
            return;
        }
        
        navigator.clipboard.writeText(currentToken)
            .then(() => {
                showResult('Токен скопирован', 'success');
                copyTokenBtn.innerHTML = '<i class="fas fa-check"></i> Скопировано';
                setTimeout(() => {
                    copyTokenBtn.innerHTML = '<i class="fas fa-copy"></i> Копировать';
                }, 2000);
            })
            .catch(err => {
                showResult('Ошибка', 'error');
            });
    });

    // Декодировать токен
    decodeTokenBtn.addEventListener('click', async function() {
        if (!currentToken) {
            showResult('Нет токена', 'error');
            return;
        }
        
        try {
            const response = await fetch('/check-token');
            const data = await response.json();
            
            if (response.ok) {
                showResult(`
                    <strong>Токен расшифрован:</strong><br><br>
                    <strong>Заголовок:</strong><br><code>${JSON.stringify(data.header, null, 2)}</code><br><br>
                    <strong>Данные:</strong><br><code>${JSON.stringify(data.payload, null, 2)}</code>
                `, 'info');
            } else {
                showResult('Ошибка', 'error');
            }
        } catch (error) {
            showResult('Ошибка подключения', 'error');
        }
    });

    // Модифицировать токен
    modifyTokenBtn.addEventListener('click', function() {
        if (!currentToken) {
            showResult('Сначала войдите в систему', 'error');
            return;
        }
        
        // Декодируем текущий токен
        const parts = currentToken.split('.');
        if (parts.length === 3) {
            try {
                editHeader.value = JSON.stringify(JSON.parse(atob(parts[0])), null, 2);
                editPayload.value = JSON.stringify(JSON.parse(atob(parts[1])), null, 2);
                editSignature.value = parts[2];
            } catch (e) {
                editHeader.value = parts[0];
                editPayload.value = parts[1];
                editSignature.value = parts[2];
            }
        }
        
        modal.style.display = 'block';
    });

    // Применить измененный токен
    applyTokenBtn.addEventListener('click', function() {
        let header = editHeader.value.trim();
        let payload = editPayload.value.trim();
        let signature = editSignature.value.trim();
        
        // Пробуем распарсить JSON
        try {
            const payloadObj = JSON.parse(payload);
            payload = btoa(JSON.stringify(payloadObj));
        } catch (e) {
            // Если не JSON, используем как есть
            if (payload && !payload.includes('.')) {
                payload = btoa(payload);
            }
        }
        
        try {
            const headerObj = JSON.parse(header);
            header = btoa(JSON.stringify(headerObj));
        } catch (e) {
            if (header && !header.includes('.')) {
                header = btoa(header);
            }
        }
        
        // Собираем новый токен
        const newToken = `${header}.${payload}.${signature}`;
        
        // Устанавливаем новый токен в куки
        document.cookie = `auth_token=${newToken}; path=/; max-age=3600`;
        currentToken = newToken;
        
        updateTokenDisplay(newToken);
        modal.style.display = 'none';
        
        showResult('Токен обновлен. Проверьте доступ к /flag', 'info');
        checkFlagAccess();
    });

    // Закрыть модальное окно
    closeBtn.addEventListener('click', function() {
        modal.style.display = 'none';
    });

    window.addEventListener('click', function(event) {
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    });

    // Навигация
    window.navigateTo = function(path) {
        if (path === '/flag') {
            fetchFlag();
        } else if (path === '/docs') {
            window.open('/info', '_blank');
        }
    };

    // Проверить доступ к флагу
    async function checkFlagAccess() {
        if (!currentToken) {
            flagStatus.innerHTML = '🔒 Заблокировано';
            flagStatus.style.color = '#ff6b6b';
            return;
        }
        
        try {
            const response = await fetch('/flag');
            const text = await response.text();
            
            // Ищем секретный комментарий
            const secretMatch = text.match(/<!--\s*([^>]+)\s*-->/);
            if (secretMatch) {
                console.log('Найден секретный комментарий:', secretMatch[1]);
            }
            
            if (response.ok) {
                const data = JSON.parse(text.replace(/<!--.*?-->/g, ''));
                if (data.message && data.message.includes("Only user 'admin'")) {
                    flagStatus.innerHTML = '🔒 Требуется admin';
                    flagStatus.style.color = '#ffa502';
                } else if (data.flag) {
                    flagStatus.innerHTML = '✅ Доступ разрешен';
                    flagStatus.style.color = '#2ed573';
                }
            } else {
                flagStatus.innerHTML = '🔒 Ошибка доступа';
                flagStatus.style.color = '#ff6b6b';
            }
        } catch (error) {
            flagStatus.innerHTML = '⚠️ Ошибка проверки';
            flagStatus.style.color = '#ffa502';
        }
    }

    // Получить флаг
    async function fetchFlag() {
        try {
            const response = await fetch('/flag');
            const text = await response.text();
            
            // Удаляем комментарии для отображения
            const displayText = text.replace(/<!--.*?-->/g, '');
            
            try {
                const data = JSON.parse(displayText);
                showResult(`
                    <strong>Ответ от /flag:</strong><br><br>
                    <pre><code>${JSON.stringify(data, null, 2)}</code></pre>
                `, 'info');
            } catch (e) {
                showResult(`
                    <strong>Ответ от /flag:</strong><br><br>
                    <pre><code>${displayText}</code></pre>
                `, 'info');
            }
            
            checkFlagAccess();
        } catch (error) {
            showResult('Ошибка при запросе флага', 'error');
        }
    }

    // Обновить отображение токена
    function updateTokenDisplay(token) {
        if (!token) return;
        
        const parts = token.split('.');
        if (parts.length === 3) {
            document.getElementById('headerPart').textContent = parts[0];
            document.getElementById('payloadPart').textContent = parts[1];
            document.getElementById('signaturePart').textContent = parts[2];
        }
    }

    // Показать результат
    function showResult(message, type = 'info') {
        resultBox.innerHTML = `
            <div class="result-message ${type}">
                <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'}"></i>
                <div>${message}</div>
            </div>
        `;
        
        resultBox.classList.add('success');
        setTimeout(() => {
            resultBox.classList.remove('success');
        }, 500);
    }

    // Проверить текущий токен
    window.checkCurrentToken = async function() {
        if (!currentToken) {
            showResult('Вы не авторизованы', 'error');
            return;
        }
        
        try {
            const response = await fetch('/check-token');
            const data = await response.json();
            
            if (response.ok) {
                showResult(`
                    <strong>Текущий пользователь:</strong> ${data.decoded?.login || 'Неизвестно'}<br><br>
                    <strong>Время создания:</strong> ${new Date(data.decoded?.iat * 1000).toLocaleString()}<br>
                    <strong>Истекает:</strong> ${new Date(data.decoded?.exp * 1000).toLocaleString()}
                `, 'info');
            }
        } catch (error) {
            showResult('Ошибка проверки', 'error');
        }
    };

    // Инициализация
    function init() {
        // Проверяем существующий токен из куки
        const cookies = document.cookie.split(';');
        for (let cookie of cookies) {
            const [name, value] = cookie.trim().split('=');
            if (name === 'auth_token' && value) {
                currentToken = value;
                updateTokenDisplay(value);
                checkFlagAccess();
                showResult('Обнаружен существующий токен', 'info');
                break;
            }
        }
    }

    init();
});