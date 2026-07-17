<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SettingEinvoice extends Model
{
    protected $fillable = ['client_id','client_secret','supplier_name','supplier_nric','supplier_tin','supplier_telephone','supplier_email','industry_classification_code','industry_classification_desc','address_1','address_2','address_3','country_code','state_code','postal_code','city_name','status'];
}
