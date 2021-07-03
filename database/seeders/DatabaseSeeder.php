<?php

namespace Database\Seeders;

use App\Models\Category;
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
        // \App\Models\User::factory(10)->create();
        Category::factory(10)->create();


        /**
         * used to implement Seeders
         */
        $this->call([
            // CategoriesTableSeeder::class,
            // UsersTableSeeder::class,
        ]);
    }
}
