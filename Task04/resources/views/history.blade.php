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
            <th>Числа</th>
            <th>Ответ</th>
            <th>Результат</th>
            <th>Дата</th>
        </tr>
        @foreach($games as $game)
        <tr>
            <td>{{ $game->player->name }}</td>
            <td>{{ $game->num1 }} и {{ $game->num2 }}</td>
            <td>{{ $game->user_answer ?? '-' }}</td>
            <td>{{ $game->result ? '✅' : '❌' }}</td>
            <td>{{ $game->created_at }}</td>
        </tr>
        @endforeach
    </table>
    <a href="/">На главную</a>
</body>
</html>