<!DOCTYPE html>
<html>
<head>
    <title>Наибольший общий делитель</title>
</head>
<body>
    @isset($game)
    <h1>Найдите НОД чисел {{ $game->num1 }} и {{ $game->num2 }}</h1>
    <form method="POST" action="{{ route('check', $game) }}">
        @csrf
        <input type="number" name="answer" required>
        <button type="submit">Проверить</button>
    </form>
    @else
    <h1>Новая игра</h1>
    <form method="POST" action="{{ route('start') }}">
        @csrf
        <input type="text" name="name" placeholder="Ваше имя" required>
        <button type="submit">Начать игру</button>
    </form>
    @endisset
    
    <a href="{{ route('history') }}">История игр</a>
</body>
</html>