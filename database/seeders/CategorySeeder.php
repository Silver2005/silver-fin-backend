<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Vente de Marchandises', 'type' => 'revenu'],
            ['name' => 'Prestation de Service', 'type' => 'revenu'],
            ['name' => 'Achat de Stock', 'type' => 'depense'],
            ['name' => 'Loyer / Boutique', 'type' => 'depense'],
            ['name' => 'Transport / Carburant', 'type' => 'depense'],
            ['name' => 'Électricité / Eau', 'type' => 'depense'],
            ['name' => 'Divers', 'type' => 'depense'],
        ];

        foreach ($categories as $category) {
            DB::table('categories')->updateOrInsert(
                ['name' => $category['name']], // Évite les doublons
                ['type' => $category['type'], 'created_at' => now(), 'updated_at' => now()]
            );
        }
    }
}