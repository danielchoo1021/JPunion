<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuizRecordDetail extends Model
{
    protected $fillable = [
        'record_id', 'quiz_id', 'answer_id', 'status'
    ];

    public function get_quiz_title()
    {
        return $this->hasOne(Quiz::class, 'id', 'quiz_id');
    }

    public function get_quiz_det()
    {
        return $this->hasOne(QuizDetail::class, 'id', 'answer_id');
    }
}

?>