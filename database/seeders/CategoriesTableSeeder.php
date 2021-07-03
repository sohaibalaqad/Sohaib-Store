<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /**
         * The developer should only use one of the three methods for the same goal
         */
        
        // ORM : Eloquent Model (implicitly used Query Builder)
        Category::create([
            'name' => 'Category model',
            'slug' => 'category-model',
            'status' => 'draft', 
        ]);

        // Query Builder (is converted to sql commands)
        /*
        DB::table('categories')->insert([
            'name' => 'My First Category',
            'slug' => 'my-first-category',
        ]);

        DB::connection('mysql')->table('categories')->insert([
            'name' => 'Sub Category',
            'parent_id' => 1,
            'slug' => 'sub-category',
        ]);
        */
        // SQL commands
        # INSERT INTO categories (name , slug)
        # VALUES ('My First Category', 'my-first-category');
        /*
        DB::statement("INSERT INTO categories (name , slug)
        VALUES ('My First Category', 'my-first-category')");
        */
    }
}
