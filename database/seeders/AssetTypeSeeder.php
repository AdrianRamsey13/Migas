<?php

namespace Database\Seeders;

use App\Models\AssetType;
use Illuminate\Database\Seeder;

class AssetTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            ['name' => 'Pompa Sentrifugal',    'maintenance_strategy' => 'preventive'],
            ['name' => 'Kompresor Gas',         'maintenance_strategy' => 'preventive'],
            ['name' => 'Separator',             'maintenance_strategy' => 'inspection'],
            ['name' => 'Heat Exchanger',        'maintenance_strategy' => 'inspection'],
            ['name' => 'Pipeline',              'maintenance_strategy' => 'inspection'],
            ['name' => 'Generator',             'maintenance_strategy' => 'preventive'],
        ];

        foreach ($types as $type) {
            AssetType::firstOrCreate(['name' => $type['name']], $type);
        }
    }
}
