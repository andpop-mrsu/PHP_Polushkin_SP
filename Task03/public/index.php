<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

// Инициализация приложения
$app = AppFactory::create();
$app->addErrorMiddleware(true, true, true);

// Настройка CORS
$app->add(function ($request, $handler) {
    $response = $handler->handle($request);
    return $response
        ->withHeader('Access-Control-Allow-Origin', '*')
        ->withHeader('Access-Control-Allow-Headers', 'Content-Type')
        ->withHeader('Access-Control-Allow-Methods', 'GET, POST');
});

// Подключение к БД
$dbPath = __DIR__ . '/../db/game.db';
$pdo = new PDO('sqlite:' . $dbPath);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Главная страница
$app->get('/', function (Request $request, Response $response) {
    $html = file_get_contents(__DIR__ . '/index.html');
    $response->getBody()->write($html);
    return $response;
});

// Получение истории игр
$app->get('/games', function (Request $request, Response $response) use ($pdo) {
    $stmt = $pdo->query("
        SELECT players.name, games.* 
        FROM games 
        JOIN players ON games.player_id = players.id
        ORDER BY games.played_at DESC
    ");
    $games = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $response->getBody()->write(json_encode($games));
    return $response->withHeader('Content-Type', 'application/json');
});

// Создание новой игры
$app->post('/games', function (Request $request, Response $response) use ($pdo) {
    $data = json_decode($request->getBody()->getContents(), true);
    
    // Валидация данных
    if (empty($data['name']) || !isset($data['num1']) || !isset($data['num2'])) {
        $response->getBody()->write(json_encode(['error' => 'Invalid data']));
        return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
    }
    
    // Сохранение игрока
    $stmt = $pdo->prepare("INSERT INTO players (name) VALUES (?)");
    $stmt->execute([$data['name']]);
    $playerId = $pdo->lastInsertId();
    
    // Расчет НОД
    $num1 = (int)$data['num1'];
    $num2 = (int)$data['num2'];
    $correctGcd = gcd($num1, $num2);
    
    // Сохранение игры
    $stmt = $pdo->prepare("
        INSERT INTO games 
        (player_id, num1, num2, correct_gcd)
        VALUES (?, ?, ?, ?)
    ");
    $stmt->execute([$playerId, $num1, $num2, $correctGcd]);
    
    $gameId = $pdo->lastInsertId();
    $response->getBody()->write(json_encode([
        'game_id' => $gameId,
        'num1' => $num1,
        'num2' => $num2
    ]));
    return $response->withHeader('Content-Type', 'application/json');
});

// Проверка ответа
$app->post('/check', function (Request $request, Response $response) use ($pdo) {
    $data = json_decode($request->getBody()->getContents(), true);
    
    // Валидация
    if (empty($data['game_id']) || !isset($data['answer'])) {
        $response->getBody()->write(json_encode(['error' => 'Invalid data']));
        return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
    }
    
    // Получение игры
    $stmt = $pdo->prepare("
        SELECT games.*, players.name 
        FROM games 
        JOIN players ON games.player_id = players.id
        WHERE games.id = ?
    ");
    $stmt->execute([$data['game_id']]);
    $game = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$game) {
        $response->getBody()->write(json_encode(['error' => 'Game not found']));
        return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
    }
    
    // Обновление записи
    $userAnswer = (int)$data['answer'];
    $result = ($userAnswer == $game['correct_gcd']);
    
    $stmt = $pdo->prepare("
        UPDATE games 
        SET user_answer = ?, result = ?
        WHERE id = ?
    ");
    $stmt->execute([$userAnswer, $result ? 1 : 0, $data['game_id']]);
    
    $response->getBody()->write(json_encode([
        'result' => $result,
        'correct' => $game['correct_gcd']
    ]));
    return $response->withHeader('Content-Type', 'application/json');
});

// Функция вычисления НОД
function gcd($a, $b) {
    while ($b != 0) {
        $temp = $a % $b;
        $a = $b;
        $b = $temp;
    }
    return $a;
}

$app->run();