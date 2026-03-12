<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->string('sso_sub')->nullable()->unique()->after('remember_token');
            $table->string('sso_department')->nullable()->after('sso_sub');
            $table->string('sso_program')->nullable()->after('sso_department');
            $table->string('sso_support_unit')->nullable()->after('sso_program');
            $table->timestamp('sso_synced_at')->nullable()->after('sso_support_unit');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->dropColumn([
                'sso_sub',
                'sso_department',
                'sso_program',
                'sso_support_unit',
                'sso_synced_at',
            ]);
        });
    }
};
