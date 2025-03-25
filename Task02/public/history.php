<?php
$dbPath = '../db/game.db';
$pdo = new PDO('sqlite:' . $dbPath);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$stmt = $pdo->query("
    SELECT players.name, games.played_at, games.num1, games.num2, 
           games.user_answer, games.correct_gcd, games.result
    FROM games
    JOIN players ON games.player_id = players.id
    ORDER BY games.played_at DESC
");
$games = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <title>История игр</title>
</head>
<body>
    <h1>История игр</h1>
    <table border="1">
        <tr>
            <th>Имя</th>
            <th>Дата</th>
            <th>Число 1</th>
            <th>Число 2</th>
            <th>Ответ</th>
            <th>Правильный НОД</th>
            <th>Результат</th>
        </tr>
        <?php foreach ($games as $game): ?>
        <tr>
            <td><?= htmlspecialchars($game['name']) ?></td>
            <td><?= htmlspecialchars($game['played_at']) ?></td>
            <td><?= $game['num1'] ?></td>
            <td><?= $game['num2'] ?></td>
            <td><?= $game['user_answer'] ?></td>
            <td><?= $game['correct_gcd'] ?></td>
            <td><?= $game['result'] ? '✅' : '❌' ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
    <p><a href="/">Назад к игре</a></p>
</body>
</html>