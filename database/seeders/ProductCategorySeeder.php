<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ProductCategory;

class ProductCategorySeeder extends Seeder
{

    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $categories = [
            ["el" => "Νηστίσιμο menu", "en" => "Fasting menu"],
            ["el" => "Ορεκτικά", "en" => "Appetizers"],
            ["el" => "Πατάτα γεμιστή", "en" => "Stuffed potato"],
            ["el" => "Αλοιφές", "en" => "Dips"],
            ["el" => "Σαλάτες", "en" => "Salads"],
            ["el" => "Τεμάχια", "en" => "Pieces"],
            ["el" => "Σάντουιτς", "en" => "Sandwiches"],
            ["el" => "Pocket", "en" => "Pocket"],
            ["el" => "Δίπορτα σάντουιτς", "en" => "Double sandwiches"],
            ["el" => "Burgers", "en" => "Burgers"],
            ["el" => "Μερίδες", "en" => "Portions"],
            ["el" => "Κοτόπουλο σούβλας", "en" => "Rotisserie chicken"],
            ["el" => "Club sandwiches", "en" => "Club sandwiches"],
            ["el" => "Αναψυκτικά", "en" => "Soft drinks"],
            ["el" => "Μπύρες - Ποτά", "en" => "Beers - Drinks"]
        ];

        foreach ($categories as $category) {
            ProductCategory::create([
                'name' => $category,
                'store_id' => 1
            ]);
        }
    }
}
