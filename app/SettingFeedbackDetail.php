<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SettingFeedbackDetail extends Model
{
    protected $fillable = [
        'feedback_id', 'image', 'status'
    ];
}
