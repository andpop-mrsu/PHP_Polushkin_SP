<?php
try {
    $dbPath = 'db/game.db';
    
    // Проверка и создание каталога
    if (!file_exists('db')) {
        mkdir('db', 0755, true);
    }
    
    // Создание подключения
    $pdo = new PDO('sqlite:' . $dbPath);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Создание таблиц
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS players (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )
    ");
    
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS games (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            player_id INTEGER,
            num1 INTEGER,
            num2 INTEGER,
            user_answer INTEGER,
            correct_gcd INTEGER,
            result BOOLEAN,
            played_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY(player_id) REFERENCES players(id)
        )
    ");
    
    echo "База данных успешно инициализирована!\n";
    
} catch(PDOException $e) {
    die("Ошибка: " . $e->getMessage() . "\n");
}