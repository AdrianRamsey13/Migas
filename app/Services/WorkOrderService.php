<?php

namespace App\Services;

use App\Models\User;
use App\Models\WorkOrder;
use Illuminate\Support\Facades\DB;

class WorkOrderService
{
    // Transisi yang diizinkan per status
    const TRANSITIONS = [
        'draft'       => ['submitted'],
        'submitted'   => ['approved', 'rejected'],
        'approved'    => ['in_progress', 'rejected'],
        'in_progress' => ['completed'],
        'completed'   => ['closed'],
        'closed'      => [],
        'rejected'    => [],
    ];

    // Role yang boleh melakukan transisi
    const ROLE_TRANSITIONS = [
        'submitted'   => ['admin', 'supervisor'],
        'approved'    => ['admin', 'supervisor'],
        'rejected'    => ['admin', 'supervisor'],
        'in_progress' => ['admin', 'technician'],
        'completed'   => ['admin', 'technician'],
        'closed'      => ['admin', 'supervisor'],
    ];

    public function canTransition(WorkOrder $workOrder, string $toStatus): bool
    {
        $allowed = self::TRANSITIONS[$workOrder->status] ?? [];
        return in_array($toStatus, $allowed);
    }

    public function canActorTransition(User $user, string $toStatus): bool
    {
        $allowedRoles = self::ROLE_TRANSITIONS[$toStatus] ?? [];
        return $user->hasAnyRole($allowedRoles);
    }

    public function transition(WorkOrder $workOrder, string $toStatus, User $actor, array $data = []): array
    {
        // Validasi transisi status
        if (!$this->canTransition($workOrder, $toStatus)) {
            return [
                'success' => false,
                'message' => "Transisi dari '{$workOrder->status}' ke '{$toStatus}' tidak diizinkan.",
            ];
        }

        // Validasi role actor
        if (!$this->canActorTransition($actor, $toStatus)) {
            return [
                'success' => false,
                'message' => "Role Anda tidak memiliki akses untuk melakukan aksi ini.",
            ];
        }

        // Validasi business rules per status
        $validation = $this->validateBusinessRules($workOrder, $toStatus, $actor, $data);
        if (!$validation['success']) {
            return $validation;
        }

        // Jalankan transisi
        DB::transaction(function () use ($workOrder, $toStatus, $data) {
            $updates = ['status' => $toStatus];

            match ($toStatus) {
                'in_progress' => $updates['started_at']   = now(),
                'completed'   => $updates['completed_at'] = now(),
                'closed'      => $updates['closed_at']    = now(),
                'approved'    => $updates['assigned_to']  = $data['assigned_to'] ?? $workOrder->assigned_to,
                'rejected'    => $updates['rejection_reason'] = $data['rejection_reason'],
                default       => null,
            };

            $workOrder->update($updates);
        });

        return ['success' => true, 'message' => "Work order berhasil diupdate ke '{$toStatus}'."];
    }

    private function validateBusinessRules(WorkOrder $workOrder, string $toStatus, User $actor, array $data): array
    {
        // Saat approved: wajib ada assigned_to
        if ($toStatus === 'approved') {
            $assignedTo = $data['assigned_to'] ?? $workOrder->assigned_to;
            if (!$assignedTo) {
                return ['success' => false, 'message' => 'Technician wajib di-assign sebelum approve.'];
            }
        }

        // Saat rejected: wajib ada rejection_reason
        if ($toStatus === 'rejected') {
            if (empty($data['rejection_reason'])) {
                return ['success' => false, 'message' => 'Rejection reason wajib diisi.'];
            }
        }

        // Saat completed: wajib ada minimal 1 maintenance log
        if ($toStatus === 'completed') {
            if ($workOrder->maintenanceLogs()->count() === 0) {
                return ['success' => false, 'message' => 'Minimal 1 maintenance log wajib ada sebelum complete.'];
            }
        }

        // Saat in_progress: hanya technician yang di-assign
        if ($toStatus === 'in_progress') {
            if ($workOrder->assigned_to !== $actor->id && !$actor->hasRole('admin')) {
                return ['success' => false, 'message' => 'Hanya technician yang di-assign yang bisa memulai pekerjaan.'];
            }
        }

        // Saat completed: hanya technician yang di-assign
        if ($toStatus === 'completed') {
            if ($workOrder->assigned_to !== $actor->id && !$actor->hasRole('admin')) {
                return ['success' => false, 'message' => 'Hanya technician yang di-assign yang bisa complete pekerjaan.'];
            }
        }

        return ['success' => true, 'message' => ''];
    }
}
