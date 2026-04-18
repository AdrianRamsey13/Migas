<?php

namespace Database\Seeders;

use App\Models\Asset;
use App\Models\User;
use App\Models\WorkOrder;
use Illuminate\Database\Seeder;

class WorkOrderSeeder extends Seeder
{
    public function run(): void
    {
        $admin      = User::where('email', 'admin@migas.com')->first();
        $supervisor = User::where('email', 'supervisor@migas.com')->first();
        $technician = User::where('email', 'technician@migas.com')->first();
        $assets     = Asset::all();

        $workOrders = [
            [
                'wo_number'      => 'WO-2026-001',
                'asset_id'       => $assets[1]->id,
                'title'          => 'Penggantian Filter Kompresor',
                'description'    => 'Filter kompresor sudah melewati jam operasi, perlu diganti.',
                'type'           => 'preventive',
                'priority'       => 'high',
                'status'         => 'approved',
                'requested_by'   => $supervisor->id,
                'assigned_to'    => $technician->id,
                'scheduled_date' => '2026-04-20',
                'completed_date' => null,
                'notes'          => 'Pastikan spare part tersedia sebelum pengerjaan.',
            ],
            [
                'wo_number'      => 'WO-2026-002',
                'asset_id'       => $assets[2]->id,
                'title'          => 'Inspeksi Rutin Separator',
                'description'    => 'Inspeksi bulanan kondisi separator 3 fasa.',
                'type'           => 'inspection',
                'priority'       => 'medium',
                'status'         => 'in_progress',
                'requested_by'   => $supervisor->id,
                'assigned_to'    => $technician->id,
                'scheduled_date' => '2026-04-18',
                'completed_date' => null,
                'notes'          => null,
            ],
            [
                'wo_number'      => 'WO-2026-003',
                'asset_id'       => $assets[0]->id,
                'title'          => 'Perbaikan Pompa Transfer A-01',
                'description'    => 'Pompa mengalami kebocoran pada seal, perlu penggantian.',
                'type'           => 'corrective',
                'priority'       => 'critical',
                'status'         => 'draft',
                'requested_by'   => $admin->id,
                'assigned_to'    => null,
                'scheduled_date' => null,
                'completed_date' => null,
                'notes'          => 'Menunggu approval supervisor.',
            ],
        ];

        foreach ($workOrders as $wo) {
            WorkOrder::firstOrCreate(['wo_number' => $wo['wo_number']], $wo);
        }
    }
}
