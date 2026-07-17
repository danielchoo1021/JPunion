<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SettingColour extends Model
{
    protected $table = 'setting_colours';

    protected $fillable = [
        'button_colour', 'hover_colour','text_colour', 'header_text_colour', 'header_text_hover_colour', 'footer_text_colour', 'footer_text_hover_colour'
    ];
}
