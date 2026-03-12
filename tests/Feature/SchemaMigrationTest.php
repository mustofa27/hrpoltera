<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class SchemaMigrationTest extends TestCase
{
    public function test_critical_tables_and_foreign_keys_exist(): void
    {
        Artisan::call('migrate:fresh');

        $this->assertTrue(Schema::hasTable('users'));
        $this->assertTrue(Schema::hasTable('pegawais'));
        $this->assertTrue(Schema::hasTable('absensis'));
        $this->assertTrue(Schema::hasTable('cutis'));
        $this->assertTrue(Schema::hasTable('surats'));
        $this->assertTrue(Schema::hasTable('disposisis'));
        $this->assertTrue(Schema::hasTable('aktivitas_harians'));
        $this->assertTrue(Schema::hasTable('lupa_absens'));

        $this->assertTrue(Schema::hasColumns('users', ['nama', 'email', 'tipe_user_id', 'unit_kerja_id']));
        $this->assertTrue(Schema::hasColumns('pegawais', ['user_id', 'shift_id', 'atasan_langsung_id']));
        $this->assertTrue(Schema::hasColumns('absensis', ['user_id', 'tanggal']));
        $this->assertTrue(Schema::hasColumns('cutis', ['user_id', 'jenis_cuti_id']));

        $userForeignTables = collect(DB::select('PRAGMA foreign_key_list(users)'))->pluck('table')->all();
        $this->assertContains('tipe_users', $userForeignTables);
        $this->assertContains('unit_kerjas', $userForeignTables);

        $pegawaiForeignTables = collect(DB::select('PRAGMA foreign_key_list(pegawais)'))->pluck('table')->all();
        $this->assertContains('users', $pegawaiForeignTables);
        $this->assertContains('shifts', $pegawaiForeignTables);

        $cutiForeignTables = collect(DB::select('PRAGMA foreign_key_list(cutis)'))->pluck('table')->all();
        $this->assertContains('users', $cutiForeignTables);
        $this->assertContains('jenis_cutis', $cutiForeignTables);
    }
}
