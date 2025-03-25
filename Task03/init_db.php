<?php
$dbPath = __DIR__ . '/db/game.db';
if (!file_exists('db')) mkdir('db', 0755);

$pdo = new PDO('sqlite:' . $dbPath);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Игроки
$pdo->exec("CREATE TABLE IF NOT EXISTS players (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
)");

// Игры
$pdo->exec("CREATE TABLE IF NOT EXISTS games (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    player_id INTEGER,
    num1 INTEGER,
    num2 INTEGER,
    user_answer INTEGER,
    correct_gcd INTEGER,
    result BOOLEAN,
    played_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY(player_id) REFERENCES players(id)
)");

echo "База данных создана!\n";