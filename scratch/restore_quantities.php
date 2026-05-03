<?php

use App\Models\Item;
use Illuminate\Support\Facades\DB;

$data = [
    1 => 56, 2 => 2, 3 => 1, 4 => 1, 5 => 3, 6 => 2, 7 => 3, 8 => 1, 9 => 3, 10 => 1,
    11 => 1, 12 => 1, 13 => 2, 14 => 10, 15 => 25, 16 => 4, 17 => 2, 18 => 4, 19 => 1, 20 => 2,
    21 => 1, 22 => 1, 23 => 1, 24 => 12, 25 => 2, 26 => 1, 27 => 1, 28 => 1, 29 => 1, 30 => 1,
    31 => 2, 32 => 2, 33 => 1, 34 => 1, 35 => 23, 36 => 12, 37 => 1, 38 => 1, 39 => 3, 40 => 8,
    41 => 1, 42 => 1, 43 => 15, 44 => 1, 45 => 1, 46 => 2, 47 => 1, 48 => 1, 49 => 1, 50 => 1,
    51 => 2, 52 => 5, 53 => 2, 54 => 1, 55 => 35, 56 => 8, 57 => 93, 58 => 12, 59 => 38, 60 => 16,
    61 => 95, 62 => 1, 63 => 3, 64 => 1, 65 => 1, 66 => 2, 67 => 1, 68 => 3, 69 => 2, 70 => 4,
    71 => 1, 72 => 1, 73 => 7, 74 => 5
];

echo "Updating " . count($data) . " items...\n";

foreach ($data as $id => $qty) {
    Item::where('id', $id)->update([
        'quantity' => $qty,
        'qty_baik' => $qty,
        'qty_tersedia' => $qty,
        'qty_rusak_ringan' => 0,
        'qty_rusak_berat' => 0,
        'qty_dipinjam' => 0,
        'qty_diperbaiki' => 0,
        'qty_hilang' => 0,
        'qty_tidak_digunakan' => 0,
        'qty_pengadaan' => 0,
        'condition' => 'Baik',
        'status' => 'Tersedia'
    ]);
}

echo "Done.\n";
