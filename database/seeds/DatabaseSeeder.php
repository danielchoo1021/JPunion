<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersTableSeeder::class);
        $this->call(AdminTableSeeder::class);
        $this->call(CategoryTableSeeder::class);
        $this->call(ProductImageTableSeeder::class);
        $this->call(ProductTableSeeder::class);
        $this->call(StocksTableSeeder::class);
        $this->call(StatesTableSeeder::class);
        $this->call(MerchantTableSeeder::class);
        $this->call(BanksTableSeeder::class);
        $this->call(AffiliateTableSeeder::class);
    }
}
