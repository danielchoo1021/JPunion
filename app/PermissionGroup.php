<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PermissionGroup extends Model
{
    protected $fillable = [];

    public function get_created_by_admin()
    {
        return $this->hasOne(Admin::class, 'code', 'created_by');
    }

    public function get_created_by_agent()
    {
        return $this->hasOne(Merchant::class, 'code', 'created_by');
    }

    public function get_updated_by_admin()
    {
        return $this->hasOne(Admin::class, 'code', 'updated_by');
    }

    public function get_updated_by_agent()
    {
        return $this->hasOne(Merchant::class, 'code', 'updated_by');
    }    
}
