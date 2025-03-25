<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Обработка ответа
    $name = $_POST['name'] ?? '';
    $userAnswer = (int)($_POST['answer'] ?? 0);
    $num1 = (int)($_POST['num1'] ?? 0);
    $num2 = (int)($_POST['num2'] ?? 0);

    // Функция вычисления НОД
    function gcd($a, $b) {
        while ($b != 0) {
            $temp = $a % $b;
            $a = $b;
            $b = $temp;
        }
        return $a;
    }
    $correctGcd = gcd($num1, $num2);
    $result = ($userAnswer === $correctGcd);

    // Сохранение в БД
    $dbPath = '../db/game.db';
    $pdo = new PDO('sqlite:' . $dbPath);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Добавляем игрока
    $stmt = $pdo->prepare("INSERT INTO players (name) VALUES (?)");
    $stmt->execute([$name]);
    $playerId = $pdo->lastInsertId();

    // Добавляем игру
    $stmt = $pdo->prepare("
        INSERT INTO games 
        (player_id, num1, num2, user_answer, correct_gcd, result)
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    $stmt->execute([$playerId, $num1, $num2, $userAnswer, $correctGcd, $result ? 1 : 0]);

    // Вывод результата
    echo "<h1>Результат</h1>";
    echo $result 
        ? "<p>Правильно! НОД чисел $num1 и $num2 равен $correctGcd.</p>"
        : "<p>Неверно. Правильный НОД: $correctGcd. Ваш ответ: $userAnswer.</p>";
    echo '<p><a href="/">Играть снова</a> | <a href="history.php">История</a></p>';
    exit;
}

// Генерация случайных чисел
$num1 = rand(1, 100);
$num2 = rand(1, 100);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Наибольший общий делитель</title>
</head>
<body>
    <h1>Найдите НОД чисел <?= $num1 ?> и <?= $num2 ?></h1>
    <form method="post">
        <input type="hidden" name="num1" value="<?= $num1 ?>">
        <input type="hidden" name="num2" value="<?= $num2 ?>">
        <div>
            <label>Ваше имя: <input type="text" name="name" required></label>
        </div>
        <div>
            <label>Ваш ответ: <input type="number" name="answer" required></label>
        </div>
        <button type="submit">Проверить</button>
    </form>
    <p><a href="history.php">История игр</a></p>
</body>
</html>