<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Store;

class StoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Seeding stores...');

        $stores = [
            [
                'name' => ['el' => 'Το Παραδοσιακό', 'en' => 'To Paradosiako'],
                'address' => ['el' => 'Αριστοτέλους 15, Θεσσαλονίκη', 'en' => 'Aristotelous 15, Thessaloniki'],
                'phone' => '2310 123456',
                'minimum_cart_value' => 10.00,
                'latitude' => 40.6320,
                'longitude' => 22.9420,
                'working_hours' => [
                    0 => ['start' => '09:00', 'end' => '22:00'], // Κυριακή
                    1 => ['start' => '08:00', 'end' => '22:00'], // Δευτέρα
                    2 => ['start' => '08:00', 'end' => '22:00'], // Τρίτη
                    3 => ['start' => '08:00', 'end' => '22:00'], // Τετάρτη
                    4 => ['start' => '08:00', 'end' => '22:00'], // Πέμπτη
                    5 => ['start' => '08:00', 'end' => '23:00'], // Παρασκευή
                    6 => ['start' => '09:00', 'end' => '23:00'], // Σάββατο
                ],
                'active' => true,
            ],
            [
                'name' => ['el' => 'Γεύσεις Ανατολής', 'en' => 'Gefseis Anatolis'],
                'address' => ['el' => 'Τσιμισκή 22, Θεσσαλονίκη', 'en' => 'Tsimiski 22, Thessaloniki'],
                'phone' => '2310 654321',
                'minimum_cart_value' => 15.00,
                'latitude' => 40.6295,
                'longitude' => 22.9444,
                'working_hours' => [
                    0 => ['start' => '11:00', 'end' => '21:00'],
                    1 => ['start' => '10:00', 'end' => '22:00'],
                    2 => ['start' => '10:00', 'end' => '22:00'],
                    3 => ['start' => '10:00', 'end' => '22:00'],
                    4 => ['start' => '10:00', 'end' => '22:00'],
                    5 => ['start' => '10:00', 'end' => '23:00'],
                    6 => ['start' => '11:00', 'end' => '23:00'],
                ],
                'active' => true,
            ],
            [
                'name' => ['el' => 'Θαλασσινές Γεύσεις', 'en' => 'Thalassines Gefseis'],
                'address' => ['el' => 'Νίκης 30, Θεσσαλονίκη', 'en' => 'Nikis 30, Thessaloniki'],
                'phone' => '2310 789012',
                'minimum_cart_value' => 20.00,
                'latitude' => 40.6260,
                'longitude' => 22.9485,
                'working_hours' => [
                    0 => ['start' => '12:00', 'end' => '22:00'],
                    1 => ['start' => '12:00', 'end' => '23:00'],
                    2 => ['start' => '12:00', 'end' => '23:00'],
                    3 => ['start' => '12:00', 'end' => '23:00'],
                    4 => ['start' => '12:00', 'end' => '23:00'],
                    5 => ['start' => '12:00', 'end' => '00:00'],
                    6 => ['start' => '12:00', 'end' => '00:00'],
                ],
                'active' => true,
            ],
            [
                'name' => ['el' => 'Ο Μπακλαβάς', 'en' => 'O Baklavas'],
                'address' => ['el' => 'Εγνατία 45, Θεσσαλονίκη', 'en' => 'Egnatia 45, Thessaloniki'],
                'phone' => '2310 345678',
                'minimum_cart_value' => 8.00,
                'latitude' => 40.6300,
                'longitude' => 22.9500,
                'working_hours' => [
                    0 => ['start' => '10:00', 'end' => '20:00'],
                    1 => ['start' => '09:00', 'end' => '21:00'],
                    2 => ['start' => '09:00', 'end' => '21:00'],
                    3 => ['start' => '09:00', 'end' => '21:00'],
                    4 => ['start' => '09:00', 'end' => '21:00'],
                    5 => ['start' => '09:00', 'end' => '22:00'],
                    6 => ['start' => '10:00', 'end' => '22:00'],
                ],
                'active' => true,
            ],
            [
                'name' => ['el' => 'Το Ψητοπωλείο', 'en' => 'To Psitopoleio'],
                'address' => ['el' => 'Βασιλέως Γεωργίου 12, Θεσσαλονίκη', 'en' => 'Vasileos Georgiou 12, Thessaloniki'],
                'phone' => '2310 567890',
                'minimum_cart_value' => 12.00,
                'latitude' => 40.6255,
                'longitude' => 22.9512,
                'working_hours' => [
                    0 => ['start' => '11:00', 'end' => '22:00'],
                    1 => ['start' => '11:00', 'end' => '23:00'],
                    2 => ['start' => '11:00', 'end' => '23:00'],
                    3 => ['start' => '11:00', 'end' => '23:00'],
                    4 => ['start' => '11:00', 'end' => '23:00'],
                    5 => ['start' => '11:00', 'end' => '00:00'],
                    6 => ['start' => '11:00', 'end' => '00:00'],
                ],
                'active' => true,
            ],
            [
                'name' => ['el' => 'Η Κουζίνα της Μαμάς', 'en' => 'I Kouzina tis Mamas'],
                'address' => ['el' => 'Αγίας Σοφίας 18, Θεσσαλονίκη', 'en' => 'Agias Sofias 18, Thessaloniki'],
                'phone' => '2310 234567',
                'latitude' => 40.6280,
                'longitude' => 22.9525,
                'working_hours' => [
                    0 => ['start' => '10:00', 'end' => '21:00'],
                    1 => ['start' => '10:00', 'end' => '22:00'],
                    2 => ['start' => '10:00', 'end' => '22:00'],
                    3 => ['start' => '10:00', 'end' => '22:00'],
                    4 => ['start' => '10:00', 'end' => '22:00'],
                    5 => ['start' => '10:00', 'end' => '23:00'],
                    6 => ['start' => '10:00', 'end' => '23:00'],
                ],
                'active' => true,
            ],
        ];

        foreach ($stores as $store) {
            $this->command->info('Seeding store: ' . $store['name']['el']);
            Store::create($store);
        }

        $this->command->info('Stores seeded!');

    }
}
