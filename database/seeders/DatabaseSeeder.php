<?php

namespace Database\Seeders;

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
        $this->call([
            // LocationSeeder::class,
            // UserSeeder::class,
            // StockRoomSeeder::class,
            // MaterialSeeder::class,
            // ProductSeeder::class,
            // TransactionSeeder::class,
            // RequestSeeder::class,
            ReportSeeder::class
        ]);
    }
}
