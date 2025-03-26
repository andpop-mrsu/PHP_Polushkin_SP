<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    protected $fillable = [
        'player_id', 
        'num1',
        'num2',
        'user_answer',
        'correct_gcd',
        'result'
    ];
    public function player()
    {
        return $this->belongsTo(Player::class);
    }
}