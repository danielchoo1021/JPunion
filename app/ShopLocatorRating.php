<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShopLocatorRating extends Model
{
    protected $fillable = [
    ];

    public function get_shop()
    {
        return $this->hasOne(Corporate::class, 'id', 'shop_id');
    }

    public function get_reviewer_user()
    {
        return $this->hasOne(User::class, 'code', 'user_id');
    }

    public function get_reviewer_agent()
    {
        return $this->hasOne(Merchant::class, 'code', 'user_id');
    }
}
