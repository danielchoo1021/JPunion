<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
	protected $fillable = [
        'quiz_title', 'status'
    ];

	public function get_quiz_details()
	{
	    return $this->hasMany(QuizDetail::class, 'quiz_id', 'id');
	}
}

