<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('partisipasis', 'vendor_pendamping')) {
            return;
        }

        // Move legacy free-text pendamping into keterangan; keep column as string storing JSON arrays of vendor IDs.
        $rows = DB::table('partisipasis')
            ->whereNotNull('vendor_pendamping')
            ->select('id', 'vendor_pendamping', 'keterangan')
            ->get();

        foreach ($rows as $row) {
            $raw = $row->vendor_pendamping;
            $decoded = json_decode((string) $raw, true);

            if (is_array($decoded)) {
                // Ensure consistent JSON encoding
                DB::table('partisipasis')->where('id', $row->id)->update([
                    'vendor_pendamping' => json_encode(array_values($decoded)),
                ]);

                continue;
            }

            $notes = trim((string) ($row->keterangan ?? ''));
            $legacy = trim((string) $raw);
            if ($legacy !== '') {
                $extra = 'Pendamping (legacy): '.$legacy;
                $notes = $notes === '' ? $extra : $notes.' | '.$extra;
            }

            DB::table('partisipasis')->where('id', $row->id)->update([
                'vendor_pendamping' => null,
                'keterangan' => $notes !== '' ? $notes : null,
            ]);
        }
    }

    public function down(): void
    {
        // Irreversible data normalization
    }
};
