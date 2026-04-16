<?php

namespace Database\Seeders;

use App\Models\Asset;
use App\Models\AssetType;
use Illuminate\Database\Seeder;

class AssetSeeder extends Seeder
{
    public function run(): void
    {
        $assets = [
            [
                'name'         => 'Pompa Transfer A-01',
                'code'         => 'PMP-A-001',
                'type'         => 'Pompa Sentrifugal',
                'location'     => 'Well Pad A',
                'status'       => 'active',
                'install_date' => '2021-03-15',
            ],
            [
                'name'         => 'Kompresor Injeksi K-01',
                'code'         => 'CMP-K-001',
                'type'         => 'Kompresor Gas',
                'location'     => 'Stasiun Kompresi B',
                'status'       => 'active',
                'install_date' => '2020-07-10',
            ],
            [
                'name'         => 'Separator 3 Fasa S-01',
                'code'         => 'SEP-S-001',
                'type'         => 'Separator',
                'location'     => 'Fasilitas Produksi C',
                'status'       => 'maintenance',
                'install_date' => '2019-11-20',
            ],
            [
                'name'         => 'Heat Exchanger HE-01',
                'code'         => 'HEX-001',
                'type'         => 'Heat Exchanger',
                'location'     => 'Fasilitas Produksi C',
                'status'       => 'active',
                'install_date' => '2022-01-05',
            ],
            [
                'name'         => 'Generator Darurat GEN-01',
                'code'         => 'GEN-001',
                'type'         => 'Generator',
                'location'     => 'Well Pad A',
                'status'       => 'retired',
                'install_date' => '2015-06-01',
            ],
        ];

        foreach ($assets as $item) {
            $type = AssetType::where('name', $item['type'])->first();
            Asset::firstOrCreate(
                ['code' => $item['code']],
                [
                    'name'          => $item['name'],
                    'asset_type_id' => $type->id,
                    'location'      => $item['location'],
                    'status'        => $item['status'],
                    'install_date'  => $item['install_date'],
                ]
            );
        }
    }
}
