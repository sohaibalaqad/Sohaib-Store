<?php

namespace Database\Seeders;

use App\Models\Admins;
use App\Models\Category;
use App\Models\Product;
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
        /**
         * used to implement factories
         */
//         \App\Models\User::factory(10)->create();
        // Category::factory(10)->create();
//        Product::factory(50)->create();
        Admins::factory(2)->create();

        /**
         * used to implement Seeders
         */
        $this->call([
            // CategoriesTableSeeder::class,
            // UsersTableSeeder::class,
        ]);
    }
}
