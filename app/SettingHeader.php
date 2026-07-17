<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SettingHeader extends Model
{
	protected $fillable = [
        'shop_image','about_us_image','faqs_image','contact_us_image','blog_image','privacy_policy_bg_image','return_policy_bg_image','shipping_policy_bg_image','terms_bg_image','quiz_bg_image', 'status'
    ];
}

