<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuizRecord extends Model
{
    protected $fillable = [
        'code', 'f_name', 'email', 'status'
    ];

    public function get_quiz_details()
    {
        return $this->hasMany(QuizRecordDetail::class, 'record_id', 'id');
    }
}

?>