<!DOCTYPE html>
<html>
<head>
    <title>Результат</title>
</head>
<body>
    <h1>{{ $result ? 'Правильно!' : 'Неверно!' }}</h1>
    <p>Ваш ответ: {{ $game->user_answer }}</p>
    <p>Правильный ответ: {{ $game->correct_gcd }}</p>
    <a href="/">Играть снова</a>
</body>
</html>