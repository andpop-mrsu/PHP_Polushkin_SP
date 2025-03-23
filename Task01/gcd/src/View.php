<?php
namespace Erightt\Gcd;
class View
{
    public static function showWelcome()
    {
        echo "Найдите наибольший общий делитель двух чисел!\n\n";
    }

    public static function showQuestion($a, $b)
    {
        echo "Числа: {$a} и {$b}\n";
    }

    public static function showResult($isCorrect, $correct)
    {
        if ($isCorrect) {
            echo "✓ Правильно!\n";
        } else {
            echo "✗ Неверно. Правильный ответ: {$correct}\n";
        }
    }
}