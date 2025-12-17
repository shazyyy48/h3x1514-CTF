const express = require('express');
const jwt = require('jsonwebtoken');
const cookieParser = require('cookie-parser');
const path = require('path');
const crypto = require('crypto');

const app = express();
const PORT = 4545;

// Используем декодированный ключ
const SECRET_KEY_RAW = 'aXRzLW5vdC1hLWZsYWctYnV0LWl0cy1pbXBvcnRhbnQ=='; // Base64
const SECRET_KEY = Buffer.from(SECRET_KEY_RAW, 'base64').toString(); // Декодируем: "its-not-a-flag-but-its-important"

app.use(express.json());
app.use(cookieParser());
app.use(express.static('public'));
app.use(express.urlencoded({ extended: true }));

// Храним пользователей
const users = {
    'guest': { password: 'guest', role: 'user' },
    'admin': { password: crypto.randomBytes(16).toString('hex'), role: 'admin' }
};

// Генерация JWT токена
const generateToken = (username) => {
    const payload = { 
        login: username
    };
    return jwt.sign(payload, SECRET_KEY, { algorithm: 'HS256' });
};

// Проверка JWT токена
const verifyToken = (token) => {
    try {
        return jwt.verify(token, SECRET_KEY);
    } catch (err) {
        return null;
    }
};

// Главная страница (логин)
app.get('/', (req, res) => {
    res.sendFile(path.join(__dirname, 'public', 'index.html'));
});

// Страница после успешного логина
app.get('/dashboard', (req, res) => {
    const token = req.cookies.auth_token;
    
    if (!token) {
        return res.redirect('/');
    }
    
    const decoded = verifyToken(token);
    if (!decoded) {
        return res.redirect('/');
    }
    
    res.sendFile(path.join(__dirname, 'public', 'dashboard.html'));
});

// Обработка логина
app.post('/login', (req, res) => {
    const { username, password } = req.body;
    
    if (!username || !password) {
        return res.status(400).json({ error: 'Заполните все поля' });
    }
    
    const user = users[username];
    
    if (!user || user.password !== password) {
        return res.status(401).json({ error: 'Неверные учетные данные' });
    }
    
    // Генерируем токен
    const token = generateToken(username);
    
    // Устанавливаем куку
    res.cookie('auth_token', token, {
        httpOnly: true,
        maxAge: 3600000,
        sameSite: 'strict'
    });
    
    res.json({ 
        success: true, 
        message: `Добро пожаловать, ${username}!`,
        redirect: '/dashboard'
    });
});

// Выход
app.get('/logout', (req, res) => {
    res.clearCookie('auth_token');
    res.redirect('/');
});

// Страница флага
app.get('/flag', (req, res) => {
    const token = req.cookies.auth_token;
    
    if (!token) {
        return res.status(401).json({ 
            error: 'Требуется авторизация' 
        });
    }
    
    const decoded = verifyToken(token);
    
    if (!decoded) {
        return res.status(401).json({ 
            error: 'Неверный или просроченный токен' 
        });
    }
    
    console.log('Token decoded:', decoded);
    console.log('User:', decoded.login);
    
    if (decoded.login === 'admin') {
        return res.json({ 
            flag: process.env.FLAG || 'CTF{jwt_admin_flag_' + crypto.randomBytes(8).toString('hex') + '}',
            message: 'Поздравляем! Вы получили доступ как администратор!',
            role: 'admin'
        });
    }
    
    return res.json({ 
        message: "Only user 'admin' have access to this page!",
        current_user: decoded.login,
        hint: "Your current role doesn't have sufficient privileges."
    });
});

// Информация о пользователе
app.get('/api/user', (req, res) => {
    const token = req.cookies.auth_token;
    
    if (!token) {
        return res.status(401).json({ error: 'Not authenticated' });
    }
    
    const decoded = verifyToken(token);
    
    if (!decoded) {
        return res.status(401).json({ error: 'Invalid token' });
    }
    
    res.json({
        username: decoded.login,
        iat: decoded.iat,
        exp: decoded.exp
    });
});

// Проверка токена (для отладки)
app.get('/api/debug-token', (req, res) => {
    const token = req.cookies.auth_token;
    
    if (!token) {
        return res.status(400).json({ error: 'No token provided' });
    }
    
    try {
        const decoded = jwt.decode(token, { complete: true });
        const verified = verifyToken(token);
        
        res.json({
            token: token,
            header: decoded?.header || null,
            payload: decoded?.payload || null,
            verified: verified ? true : false,
            secret_key: SECRET_KEY_RAW,
            secret_key_decoded: SECRET_KEY
        });
    } catch (err) {
        res.status(400).json({ error: 'Invalid token format' });
    }
});

// Отладка - показать секретный ключ (только для разработки)
app.get('/debug/secret', (req, res) => {
    res.json({
        secret_base64: SECRET_KEY_RAW,
        secret_decoded: SECRET_KEY,
        hint: 'Use this key to sign your JWT tokens'
    });
});

// Старт сервера
app.listen(PORT, () => {
    console.log(`🌐 Сервер запущен на http://localhost:${PORT}`);
    console.log(`🔑 Тестовый аккаунт: guest:guest`);
    console.log(`🎯 Цель: Получить доступ к /flag как admin`);
    console.log(`🔐 Секретный ключ (base64): ${SECRET_KEY_RAW}`);
    console.log(`🔐 Секретный ключ (decoded): ${SECRET_KEY}`);
});