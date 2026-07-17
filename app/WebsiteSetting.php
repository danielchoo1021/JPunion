<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WebsiteSetting extends Model
{
    protected $fillable = [
        'company_registration_no', 'sst_registration_no', 'about_us', 'faqs', 'address', 'about_us_image','contact_us', 
        'contact_us_image', 'contact_whatsapp', 'privacy_policy_description', 'privacy_policy_description_cn', 'return_policy_description', 'return_policy_description_cn',
        'shipping_policy_description', 'shipping_policy_description_cn', 'top_scrolling_message', 'point_to_rm', 'website_short_description', 'ws_picture',
        'menu', 'about_us_en',
        'file_member_order_amount',
        'file_member_discount_type',
        'file_member_discount_amount',
        'setting_sold_display_product',
        'agent_discount_type',
        'agent_discount_amount',
        'fouding_member_order_amount',
        'high_fouding_member_order_amount',
        'founding_member_month_period',
        'high_founding_member_month_period',
        'founding_member_month_period_comm',
        'high_founding_member_month_period_comm',
        'tnc_description',
        'tnc_description_cn',
        'minimum_retail_amount',
        'minimum_loo_amount',
        'kvip_member_order_amount',
        'rm_to_point',
        'point_period',
        'minimum_lol_amount',
        'withdrawal_charges',
        'prize_pool',
        'birthday_popup',
        'bank_name',
        'bank_account_number',
        'bank_holder_name',
        'type_set_shipping_fee',
        'setting_featured_product_title',
        'free_shipping_threshold'
    ];
}
