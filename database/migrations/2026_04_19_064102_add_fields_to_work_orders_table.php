<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('work_orders', function (Blueprint $table) {
            $table->timestamp('started_at')->nullable()->after('notes');
            $table->timestamp('completed_at')->nullable()->after('started_at');
            $table->timestamp('closed_at')->nullable()->after('completed_at');
            $table->text('rejection_reason')->nullable()->after('closed_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('work_orders', function (Blueprint $table) {
            $table->dropColumn(['started_at', 'completed_at', 'closed_at', 'rejection_reason']);
        });
    }
};
