<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $menu_items = [
            [
                "name" => ["el" => "Μπιφτέκι λαχανικών μερίδα", "en" => "Vegetable burger portion"],
                "description" => ["el" => "3 τεμάχια. Συνοδεύεται από γαρνιτούρα της επιλογής σας", "en" => "3 pieces. Served with a garnish of your choice"],
                "price" => 8.50
            ],
            [
                "name" => ["el" => "Κολοκυθοκεφτέδες μερίδα", "en" => "Zucchini fritters portion"],
                "description" => ["el" => "8 τεμάχια. Συνοδεύεται από γαρνιτούρα της επιλογής σας", "en" => "8 pieces. Served with a garnish of your choice"],
                "price" => 8.30
            ],
            [
                "name" => ["el" => "Φαλάφελ μερίδα", "en" => "Falafel portion"],
                "description" => ["el" => "8 τεμάχια. Συνοδεύεται από γαρνιτούρα της επιλογής σας", "en" => "8 pieces. Served with a garnish of your choice"],
                "price" => 8.30
            ],
            [
                "name" => ["el" => "Μπιφτέκι λαχανικών σε σάντουιτς", "en" => "Vegetable burger in sandwich"],
                "description" => ["el" => "Σάντουιτς μπιφτέκι λαχανικών με τα υλικά της επιλογής σας", "en" => "Vegetable burger sandwich with ingredients of your choice"],
                "price" => 4.20
            ],
            [
                "name" => ["el" => "Κολοκυθοκεφτέδες σε σάντουιτς", "en" => "Zucchini fritters in sandwich"],
                "description" => ["el" => "Σάντουιτς κολοκυθοκεφτέδες με τα υλικά της επιλογής σας", "en" => "Zucchini fritters sandwich with ingredients of your choice"],
                "price" => 4.10
            ]
        ];

        $this->command->info('Creating products...');

        foreach ($menu_items as $item) {
            Product::create([
                'name' => $item['name'],
                'description' => $item['description'],
                'price' => $item['price'],
                'store_id' => 1,
                'product_category_id' => 1
            ]);
        }
    }
}
