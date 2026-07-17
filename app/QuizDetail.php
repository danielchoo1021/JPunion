<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuizDetail extends Model
{
    protected $fillable = [
        'quiz_id', 'answer', 'suggestion', 'answer_cn', 'suggestion_cn', 'status'
    ];
}
