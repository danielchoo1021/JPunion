<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use DB;

class Product extends Model
{
    protected $fillable = [
        'featured', 'packages', 'item_code', 'product_code',  'product_name', 'category_id', 'sub_category_id', 'brand_id', 
        'price', 'special_price', 'agent_price', 'agent_special_price', 'variation_enable', 'free_gift', 'quantity', 'description', 
        'short_description', 'mall', 'status', 'product_type', 'f_banner', 's_banner', 'weight', 'product_comm_type', 
        'product_comm_amount', 'in_product_comm_type', 'in_product_comm_amount', 'own_product_comm_type', 'own_product_comm_amount', 
        'special_and_usage', 'variation_title', 'second_variation_title', 'second_variation_enable', 'agent_get_point',
        'upgrade_agent', 'costing_price', 'product_name_en', 'free_shipping', 'free_east_shipping', 'eng_description', 'sorting',
        'west_price', 'west_special_price',
        'corporate_price', 'corporate_special_price',
        'corporate_moq',
        'level_up',
        'dow',
        'agent_only',
        'customer_only',
        'corporate_only',
        'label',
        'register_product',
        'voucher_id',
        'recruiting_product',
        'merchant_id',
        'product_name_cn',
        'description_cn',
        'short_description_cn',
        'sort_level',
        'birthday_promotion',
        'birthday_price',
        'birthday_special_price',
        'member_birthday_price	'
    ];

    //Product Related Details
    public function first_image()
    {
        return $this->hasOne(ProductImage::class, 'product_id', 'id')->orderBy('sort_level', 'asc');
    }

    public function get_images()
    {
        return $this->hasMany(ProductImage::class, 'product_id', 'id')->orderBy('sort_level', 'asc');
    }

    public function get_variations()
    {
        return $this->hasMany(ProductVariation::class, 'product_id', 'id');
    }

    public function get_second_variations()
    {
        return $this->hasMany(ProductSecondVariation::class, 'product_id', 'id')->groupBy('variation_name');
    }

    public function product_categories()
    {
        return $this->hasMany(Category::class, 'product_id', 'id');
    }

    public function one_product_category()
    {
        return $this->hasOne(Category::class, 'id', 'category_id');
    }

    public function one_product_subcategory()
    {
        return $this->hasOne(SubCategory::class, 'id', 'sub_category_id');
    }

    public function one_product_brand()
    {
        return $this->hasOne(Brand::class, 'id', 'brand_id');
    }

    public function one_product_oum()
    {
        return $this->hasOne(SettingUom::class, 'id', 'product_type');
    }

    public function get_carts()
    {
        return $this->hasMany(Cart::class, 'product_id', 'id');
    }

    public function get_product_packages()
    {
        return $this->hasMany(PackageItem::class, 'product_id', 'id');
    }


    //Pricing
    public function get_variations_price()
    {
        return $this->belongsTo(ProductVariation::class, 'id', 'product_id');
    }

    public function get_second_variations_price()
    {
        return $this->belongsTo(ProductSecondVariation::class, 'id', 'product_id');
    }

    public function get_agent_price_product()
    {
        return $this->belongsTo(AgentPrice::class, 'id', 'product_id');
    }

    public function get_variations_min_max_birthday_price()
    {
        return $this->hasOne(ProductVariation::class, 'product_id', 'id')
                    ->select(DB::raw('MIN(IF(variation_birthday_special_price > 0, variation_birthday_special_price, variation_birthday_price)) as min_price'), 
                             DB::raw('MAX(IF(variation_birthday_special_price > 0, variation_birthday_special_price, variation_birthday_price)) as max_price'),

                             DB::raw('MAX(variation_birthday_special_price) as max_variation_special_price'),
                             DB::raw('MIN(variation_birthday_special_price) as min_special_price'),

                             DB::raw('MAX(variation_birthday_price) as max_normal_price'),
                             DB::raw('MIN(variation_birthday_price) as min_normal_price'));
    }

    public function get_variations_min_max_price()
    {
        return $this->hasOne(ProductVariation::class, 'product_id', 'id')
                    ->select(DB::raw('MIN(IF(variation_special_price > 0, variation_special_price, variation_price)) as min_price'), 
                             DB::raw('MAX(IF(variation_special_price > 0, variation_special_price, variation_price)) as max_price'),

                             DB::raw('MAX(variation_special_price) as max_variation_special_price'),
                             DB::raw('MIN(variation_special_price) as min_special_price'),

                             DB::raw('MAX(variation_price) as max_normal_price'),
                             DB::raw('MIN(variation_price) as min_normal_price'));
    }

    public function get_second_variations_min_max_price()
    {
        return $this->hasOne(ProductSecondVariation::class, 'product_id', 'id')
                    ->select(DB::raw('MIN(IF(variation_special_price > 0, variation_special_price, variation_price)) as min_price'), 
                             DB::raw('MAX(IF(variation_special_price > 0, variation_special_price, variation_price)) as max_price'),

                             DB::raw('MAX(variation_special_price) as max_variation_special_price'),
                             DB::raw('MIN(variation_special_price) as min_special_price'),

                             DB::raw('MAX(variation_price) as max_normal_price'),
                             DB::raw('MIN(variation_price) as min_normal_price'));
    }

    public function get_variations_min_max_retail_price()
    {
        return $this->hasOne(ProductVariation::class, 'product_id', 'id')
                    ->select(DB::raw('MIN(IF(variation_retail_special_price > 0, variation_retail_special_price, variation_retail_price)) as min_price'), 
                             DB::raw('MAX(IF(variation_retail_special_price > 0, variation_retail_special_price, variation_retail_price)) as max_price'),

                             DB::raw('MAX(variation_retail_special_price) as max_variation_special_price'),
                             DB::raw('MIN(variation_retail_special_price) as min_special_price'),

                             DB::raw('MAX(variation_retail_price) as max_normal_price'),
                             DB::raw('MIN(variation_retail_price) as min_normal_price'));
    }

    public function get_second_variations_min_max_retail_price()
    {
        return $this->hasOne(ProductSecondVariation::class, 'product_id', 'id')
                    ->select(DB::raw('MIN(IF(variation_retail_special_price > 0, variation_retail_special_price, variation_retail_price)) as min_price'), 
                             DB::raw('MAX(IF(variation_retail_special_price > 0, variation_retail_special_price, variation_retail_price)) as max_price'),

                             DB::raw('MAX(variation_retail_special_price) as max_variation_special_price'),
                             DB::raw('MIN(variation_retail_special_price) as min_special_price'),

                             DB::raw('MAX(variation_retail_price) as max_normal_price'),
                             DB::raw('MIN(variation_retail_price) as min_normal_price'));
    }

    public function get_second_variations_min_max_birthday_price()
    {
        return $this->hasOne(ProductSecondVariation::class, 'product_id', 'id')
                    ->select(DB::raw('MIN(IF(variation_birthday_special_price > 0, variation_birthday_special_price, variation_birthday_price)) as min_price'), 
                             DB::raw('MAX(IF(variation_birthday_special_price > 0, variation_birthday_special_price, variation_birthday_price)) as max_price'),

                             DB::raw('MAX(variation_birthday_special_price) as max_variation_special_price'),
                             DB::raw('MIN(variation_birthday_special_price) as min_special_price'),

                             DB::raw('MAX(variation_birthday_price) as max_normal_price'),
                             DB::raw('MIN(variation_birthday_price) as min_normal_price'));
    }

    public function get_agent_min_max_price_product()
    {
        return $this->belongsTo(AgentPrice::class, 'id', 'product_id')
                    ->select(DB::raw('MIN(IF(special_price > 0, special_price, price)) as min_price'), 
                             DB::raw('MAX(IF(special_price > 0, special_price, price)) as max_price'),

                             DB::raw('MAX(special_price) as max_special_price'),
                             DB::raw('MIN(special_price) as min_special_price'),

                             DB::raw('MAX(price) as max_normal_price'),
                             DB::raw('MIN(price) as min_normal_price'),

                             'product_id',
                             'agent_lvl_id')
                    ->groupBy('product_id');
    }

    public function get_agent_min_max_birthday_price_product()
    {
        return $this->belongsTo(AgentPrice::class, 'id', 'product_id')
                    ->select(DB::raw('MIN(IF(birthday_special_price > 0, birthday_special_price, birthday_price)) as min_price'), 
                             DB::raw('MAX(IF(birthday_special_price > 0, birthday_special_price, birthday_price)) as max_price'),

                             DB::raw('MAX(birthday_special_price) as max_special_price'),
                             DB::raw('MIN(birthday_special_price) as min_special_price'),

                             DB::raw('MAX(birthday_price) as max_normal_price'),
                             DB::raw('MIN(birthday_price) as min_normal_price'),

                             'product_id',
                             'agent_lvl_id')
                    ->groupBy('product_id');
    }

    public function get_retail_variation_min_max_price_product()
    {
        return $this->belongsTo(ProductVariation::class, 'id', 'product_id')
                    ->select(DB::raw('MIN(IF(variation_retail_special_price > 0, variation_retail_special_price, variation_retail_price)) as min_price'), 
                             DB::raw('MAX(IF(variation_retail_special_price > 0, variation_retail_special_price, variation_retail_price)) as max_price'),

                             DB::raw('MAX(variation_retail_special_price) as max_variation_retail_special_price'),
                             DB::raw('MIN(variation_retail_special_price) as min_variation_retail_special_price'),

                             DB::raw('MAX(variation_retail_price) as max_normal_price'),
                             DB::raw('MIN(variation_retail_price) as min_normal_price'),

                             'product_id',
                             'variation_retail_price')
                    ->groupBy('product_id');
    }

    public function get_retail_second_variation_min_max_price_product()
    {
        return $this->belongsTo(ProductSecondVariation::class, 'id', 'product_id')
                    ->select(DB::raw('MIN(IF(variation_retail_special_price > 0, variation_retail_special_price, variation_retail_price)) as min_price'), 
                             DB::raw('MAX(IF(variation_retail_special_price > 0, variation_retail_special_price, variation_retail_price)) as max_price'),

                             DB::raw('MAX(variation_retail_special_price) as max_variation_retail_special_price'),
                             DB::raw('MIN(variation_retail_special_price) as min_variation_retail_special_price'),

                             DB::raw('MAX(variation_retail_price) as max_normal_price'),
                             DB::raw('MIN(variation_retail_price) as min_normal_price'),

                             'product_id',
                             'variation_retail_price')
                    ->groupBy('product_id');
    }

    public function get_variation_min_max_price_product()
    {
        return $this->belongsTo(ProductVariation::class, 'id', 'product_id')
                    ->select(DB::raw('MIN(IF(variation_retail_special_price > 0, variation_retail_special_price, variation_retail_price)) as min_price'), 
                             DB::raw('MAX(IF(variation_retail_special_price > 0, variation_retail_special_price, variation_retail_price)) as max_price'),

                             DB::raw('MAX(variation_retail_special_price) as max_variation_retail_special_price'),
                             DB::raw('MIN(variation_retail_special_price) as min_variation_retail_special_price'),

                             DB::raw('MAX(variation_retail_price) as max_normal_price'),
                             DB::raw('MIN(variation_retail_price) as min_normal_price'),

                             'product_id',
                             'variation_price')
                    ->groupBy('product_id');
    }

    public function get_second_variation_min_max_price_product()
    {
        return $this->belongsTo(ProductSecondVariation::class, 'id', 'product_id')
                    ->select(DB::raw('MIN(IF(variation_retail_special_price > 0, variation_retail_special_price, variation_retail_price)) as min_price'), 
                             DB::raw('MAX(IF(variation_retail_special_price > 0, variation_retail_special_price, variation_retail_price)) as max_price'),

                             DB::raw('MAX(variation_retail_special_price) as max_variation_retail_special_price'),
                             DB::raw('MIN(variation_retail_special_price) as min_variation_retail_special_price'),

                             DB::raw('MAX(variation_retail_price) as max_normal_price'),
                             DB::raw('MIN(variation_retail_price) as min_normal_price'),

                             'product_id',
                             'variation_price')
                    ->groupBy('product_id');
    }
}
