<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserShippingAddress extends Model
{
    protected $fillable = [
        'user_id', 'default','f_name', 'l_name', 'email', 'phone', 'address', 'country', 'state', 'city', 'postcode', 'status', 'created_at', 'updated_at', 'country_code'
    ];

    public function get_states()
    {
        return $this->hasOne(State::class, 'id', 'state');
    }

    public function get_country()
    {
        return $this->hasOne(TblCountry::class, 'country_id', 'country');
    }
}
