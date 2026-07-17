<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PartnerLevel extends Model
{
    protected $fillable = [
        'partner_lvl', 'partner_lvl_cn', 'requirement', 'allowance', 'promotion_requirement', 'status'
    ];
}
