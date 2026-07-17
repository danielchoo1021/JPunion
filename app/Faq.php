<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    protected $fillable = [
        'user_id', 'product_id', 'question', 'question_cn', 'answer', 'answer_cn'
    ];
}
