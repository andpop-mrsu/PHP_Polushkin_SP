<?php
namespace Erightt\Gcd;
use function Cli\prompt;
use function Cli\line;
class Controller
{
    public static function startGame()
    {
        View::showWelcome();
        
        $num1 = rand(1, 100);
        $num2 = rand(1, 100);
        $correct = self::calculateGCD($num1, $num2);
        
        View::showQuestion($num1, $num2);
        $answer = (int)prompt('Ваш ответ');
        
        View::showResult($answer === $correct, $correct);
    }
    private static function calculateGCD($a, $b)
    {
        while ($b != 0) {
            $temp = $a % $b;
            $a = $b;
            $b = $temp;
        }
        return $a;
    }
}