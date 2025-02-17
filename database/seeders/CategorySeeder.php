<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Creating categories...');

        $categories = [
            ['el' => 'Καφέδες', 'en' => 'Coffee'],
            ['el' => 'Σουβλάκια', 'en' => 'Souvlaki'],
            ['el' => 'Pizza', 'en' => 'Pizza'],
            ['el' => 'Κινέζικη', 'en' => 'Chinese'],
            ['el' => 'Κρέπες', 'en' => 'Crepes'],
            ['el' => 'Burgers', 'en' => 'Burgers'],
            ['el' => 'Sushi', 'en' => 'Sushi'],
            ['el' => 'Γλυκά', 'en' => 'Sweets'],
            ['el' => 'Μαγειρευτά', 'en' => 'Home-cooked meals'],
            ['el' => 'Ζυμαρικά', 'en' => 'Pasta'],
            ['el' => 'Μεξικάνικη', 'en' => 'Mexican'],
            ['el' => 'Νηστίσιμα', 'en' => 'Fasting meals'],
            ['el' => 'Βάφλες', 'en' => 'Waffles'],
            ['el' => 'Ινδική', 'en' => 'Indian'],
            ['el' => 'Vegan', 'en' => 'Vegan'],
            ['el' => 'Brunch', 'en' => 'Brunch'],
            ['el' => 'Vegetarian', 'en' => 'Vegetarian'],
            ['el' => 'Hot Dog', 'en' => 'Hot Dog'],
            ['el' => 'Ασιατική', 'en' => 'Asian'],
            ['el' => 'Σφολιάτες', 'en' => 'Pastries'],
            ['el' => 'Θαλασσινά', 'en' => 'Seafood'],
            ['el' => 'Σαλάτες', 'en' => 'Salads'],
            ['el' => 'Κουλούρια', 'en' => 'Bagels'],
            ['el' => 'Ζαχαροπλαστείο', 'en' => 'Patisserie'],
            ['el' => 'Cocktails', 'en' => 'Cocktails'],
            ['el' => 'Ψητά - Grill', 'en' => 'Grilled meats'],
            ['el' => 'Sandwich', 'en' => 'Sandwich'],
            ['el' => 'Snacks', 'en' => 'Snacks'],
            ['el' => 'Παγωτό', 'en' => 'Ice Cream'],
            ['el' => 'Ελληνική', 'en' => 'Greek'],
            ['el' => 'Ιταλική', 'en' => 'Italian'],
            ['el' => 'Φρέσκοι χυμοί', 'en' => 'Fresh Juices'],
            ['el' => 'Ξηροί καρποί', 'en' => 'Nuts'],
            ['el' => 'Κοτόπουλα', 'en' => 'Chicken'],
            ['el' => 'Μεζεδοπωλείο', 'en' => 'Meze Restaurant'],
            ['el' => 'Ποτά', 'en' => 'Drinks'],
            ['el' => 'Αρτοποιήματα', 'en' => 'Bakery Goods'],
            ['el' => 'Μπουγάτσα', 'en' => 'Bougatsa'],
            ['el' => 'Μεσογειακή', 'en' => 'Mediterranean'],
            ['el' => 'Ethnic', 'en' => 'Ethnic'],
            ['el' => 'Πίτες', 'en' => 'Pies'],
            ['el' => 'Ανατολίτικη', 'en' => 'Middle Eastern'],
            ['el' => 'Πεϊνιρλί', 'en' => 'Peinirli'],
            ['el' => 'Smoothies', 'en' => 'Smoothies'],
            ['el' => 'Donuts', 'en' => 'Donuts'],
            ['el' => 'Φαλάφελ', 'en' => 'Falafel'],
            ['el' => 'Λουκουμάδες', 'en' => 'Loukoumades'],
            ['el' => 'Cool food', 'en' => 'Cool Food'],
            ['el' => 'Superfoods', 'en' => 'Superfoods'],
            ['el' => 'Kebab', 'en' => 'Kebab'],
            ['el' => 'Κεντροευρωπαϊκή', 'en' => 'Central European'],
            ['el' => 'Churros', 'en' => 'Churros'],
            ['el' => 'Πατσάς', 'en' => 'Tripe Soup'],
            ['el' => 'Πρωτεϊνικά γεύματα', 'en' => 'Protein Meals'],
            ['el' => 'Τσάι', 'en' => 'Tea'],
        ];

        foreach ($categories as $category) {
            Category::create([
                'name' => $category
            ]);
            $this->command->info('Category ' . $category['el'] . ' created');
        }

        $this->command->info('Categories created!');
    }
}
