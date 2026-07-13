<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * LamaranModel & PencariKerjaModel sudah declare useTimestamps + useSoftDeletes
 * (created_at/updated_at/deleted_at), tapi tabel live `lamaran` dan
 * `pencari_kerja` tidak punya kolom-kolom itu sama sekali — bikin save()
 * gagal "Unknown column". Migration ini menyelaraskan skema live dengan
 * yang diasumsikan Model, mengikuti pola yang sama seperti AlignPerusahaanTable.
 *
 * Tidak ada FK baru di migration ini, jadi processIndexes() tidak diperlukan.
 */
class AddTimestampsToLamaranAndPencariKerja extends Migration
{
    public function up()
    {
        $timestampColumns = [
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
            'deleted_at' => ['type' => 'DATETIME', 'null' => true],
        ];

        $this->forge->addColumn('lamaran', $timestampColumns);
        $this->forge->addColumn('pencari_kerja', $timestampColumns);
    }

    public function down()
    {
        $this->forge->dropColumn('lamaran', ['created_at', 'updated_at', 'deleted_at']);
        $this->forge->dropColumn('pencari_kerja', ['created_at', 'updated_at', 'deleted_at']);
    }
}
