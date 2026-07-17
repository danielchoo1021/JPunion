<?php

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $products = [
            [
                'product_name'=>'Razer mamba 5G Tournament Edition RZ01-01370100 wired Gaming Mouse 16000DPI',
                'category_id'=>'1',
                'brand_id'=>'Razer',
                'price'=>'299.00',
                'special_price'=>'179.00',
                'description'=>'&lt;p&gt;Shipping:&lt;/p&gt;

&lt;p&gt;$0.99&amp;nbsp;Standard Shipping from China/Hong Kong/Taiwan to worldwide&amp;nbsp;|&amp;nbsp;&lt;a href=&quot;https://www.ebay.com/itm/Razer-mamba-5G-Tournament-Edition-RZ01-01370100-wired-Gaming-Mouse-16000DPI-/282966367112#shpCntId&quot; id=&quot;e2&quot;&gt;See details&lt;/a&gt;&lt;/p&gt;

&lt;p&gt;See details about international shipping here.&amp;nbsp;&lt;a href=&quot;javascript:;&quot; id=&quot;cbthlp&quot;&gt;&amp;nbsp;&lt;/a&gt;&lt;/p&gt;

&lt;p&gt;&amp;nbsp;&lt;/p&gt;

&lt;p&gt;Item location:&lt;/p&gt;

&lt;p&gt;China, China&lt;/p&gt;

&lt;p&gt;&amp;nbsp;&lt;/p&gt;

&lt;p&gt;Ships to:&lt;/p&gt;

&lt;p&gt;Worldwide&amp;nbsp;&lt;a href=&quot;https://www.ebay.com/itm/Razer-mamba-5G-Tournament-Edition-RZ01-01370100-wired-Gaming-Mouse-16000DPI-/282966367112#shpCntId&quot; id=&quot;e5&quot;&gt;See exclusions&lt;/a&gt;&lt;/p&gt;

&lt;p&gt;Delivery:&lt;/p&gt;

&lt;p&gt;Estimated between&amp;nbsp;&lt;strong&gt;Tue. Nov. 19 and Thu. Dec. 5&lt;/strong&gt;&lt;/p&gt;

&lt;p&gt;Seller ships within 1 day after&amp;nbsp;&lt;a href=&quot;https://pages.ebay.com/help/buy/contextual/domestic-handling-time.html&quot; target=&quot;_blank&quot;&gt;receiving cleared payment&lt;strong&gt;- opens in a new window or tab&lt;/strong&gt;&lt;/a&gt;.&amp;nbsp;&lt;a href=&quot;javascript:;&quot; id=&quot;hldhlp&quot;&gt;&amp;nbsp;&lt;/a&gt;&lt;/p&gt;

&lt;p&gt;Please note the delivery estimate is&amp;nbsp;greater than 7 business days.&lt;/p&gt;

&lt;p&gt;Payments:&lt;/p&gt;

&lt;p&gt;&lt;img alt=&quot;PayPal&quot; src=&quot;https://ir.ebaystatic.com/cr/v/c1/s_1x2.png&quot; title=&quot;PayPal&quot; /&gt;&amp;nbsp;&lt;img alt=&quot;Visa&quot; src=&quot;https://ir.ebaystatic.com/cr/v/c1/s_1x2.png&quot; title=&quot;Visa&quot; /&gt;&amp;nbsp;&lt;img alt=&quot;Master Card&quot; src=&quot;https://ir.ebaystatic.com/cr/v/c1/s_1x2.png&quot; title=&quot;Master Card&quot; /&gt;&amp;nbsp;&lt;img alt=&quot;Amex&quot; src=&quot;https://ir.ebaystatic.com/cr/v/c1/s_1x2.png&quot; title=&quot;Amex&quot; /&gt;&amp;nbsp;&lt;img alt=&quot;Discover&quot; src=&quot;https://ir.ebaystatic.com/cr/v/c1/s_1x2.png&quot; title=&quot;Discover&quot; /&gt;&lt;/p&gt;

&lt;p&gt;&lt;img alt=&quot;PayPal Credit&quot; src=&quot;https://ir.ebaystatic.com/pictures/aw/pics/logos/logoPaypalCredit_104x16.png&quot; title=&quot;PayPal Credit&quot; /&gt;&lt;/p&gt;

&lt;p&gt;Special financing available.&amp;nbsp;&amp;nbsp;&lt;a href=&quot;https://creditapply.paypal.com/apply?guid=VO3U2L3R&amp;amp;assetId=coreofferA&quot; target=&quot;_blank&quot;&gt;Apply Now&lt;/a&gt;&amp;nbsp;&amp;nbsp;|&amp;nbsp;&amp;nbsp;&lt;a href=&quot;https://creditapply.paypal.com/apply?guid=VO3U2L3R&amp;amp;assetId=coreofferA&quot; target=&quot;_blank&quot;&gt;See terms&lt;/a&gt;&lt;/p&gt;

&lt;p&gt;Returns:&lt;/p&gt;

&lt;table style=&quot;width:100%&quot;&gt;
    &lt;tbody&gt;
        &lt;tr&gt;
            &lt;td&gt;Seller does not accept returns&amp;nbsp;|&amp;nbsp;&amp;nbsp;&lt;a href=&quot;https://www.ebay.com/itm/Razer-mamba-5G-Tournament-Edition-RZ01-01370100-wired-Gaming-Mouse-16000DPI-/282966367112#rpdCntId&quot; id=&quot;e6&quot;&gt;See details&lt;/a&gt;&lt;/td&gt;
        &lt;/tr&gt;
    &lt;/tbody&gt;
&lt;/table&gt;',
                'status'=>'1',
                'created_at'=>\Carbon\Carbon::now(),
                'updated_at'=> \Carbon\Carbon::now()
            ],
            [
                'product_name'=>'Black Widow Mechanical keyboard (Black)',
                'category_id'=>'2',
                'brand_id'=>'Razer',
                'price'=>'495.00',
                'special_price'=>'359.00',
                'description'=>'&lt;p&gt;1111111111&lt;/p&gt;',
                'status'=>'1',
                'created_at'=>\Carbon\Carbon::now(),
                'updated_at'=> \Carbon\Carbon::now()
            ],
            [
                'product_name'=>'Iphone 6,7,8 Cartoon Casing',
                'category_id'=>'4',
                'brand_id'=>'No Brand',
                'price'=>'15.00',
                'special_price'=>'10.00',
                'description'=>'&lt;p&gt;2222&lt;/p&gt;

&lt;p&gt;333&lt;/p&gt;

&lt;p&gt;4&lt;/p&gt;

&lt;p&gt;54&lt;/p&gt;

&lt;p&gt;&amp;nbsp;&lt;/p&gt;',
                'status'=>'1',
                'created_at'=>\Carbon\Carbon::now(),
                'updated_at'=> \Carbon\Carbon::now()
            ]

        ];
        DB::table('products')->insert($products);
    }
}