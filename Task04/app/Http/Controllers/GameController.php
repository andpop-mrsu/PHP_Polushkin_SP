<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Player;
use Illuminate\Http\Request;

class GameController extends Controller
{
    public function index()
    {
        return view('game');
    }

    public function start(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255'
        ]);
        $player = Player::create(['name' => $request->name]);
        
        $num1 = rand(1, 100);
        $num2 = rand(1, 100);
        
        $game = Game::create([
            'player_id' => $player->id,
            'num1' => $num1,
            'num2' => $num2,
            'correct_gcd' => $this->gcd($num1, $num2)
        ]);
        
        return view('game', compact('game'));
    }

    public function check(Request $request, Game $game)
    {
        $result = $request->answer == $game->correct_gcd;
        
        $game->update([
            'user_answer' => $request->answer,
            'result' => $result
        ]);
        
        return view('result', compact('game', 'result'));
    }

    public function history()
    {
        $games = Game::with('player')->latest()->get();
        return view('history', compact('games'));
    }

    private function gcd($a, $b)
    {
        while ($b != 0) {
            $temp = $a % $b;
            $a = $b;
            $b = $temp;
        }
        return $a;
    }
}